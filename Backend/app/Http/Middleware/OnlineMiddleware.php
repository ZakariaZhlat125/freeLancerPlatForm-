<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response; // Make sure this is imported

class OnlineMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $currentTime = Carbon::now()->format('Y-m-d H:i:s');

            $usersToOffline = User::where('last_activity', '<', $currentTime)->get();
            $usersToOffline->each(function ($user) {
                $user->update(['is_online' => 0]);
            });

            $usersToOnline = User::where('last_activity', '>=', $currentTime)->get();
            $usersToOnline->each(function ($user) {
                $user->update(['is_online' => 1]);
            });

            if (auth()->check()) {
                $cacheValue = Cache::put('user-is-online', auth()->id(), Carbon::now()->addMinutes(1));
                $user = User::find(Cache::get('user-is-online'));

                if ($user) {
                    $user->last_activity = now()->addMinutes(1);
                    $user->is_online = true;
                    $user->save();
                }
            } elseif (!auth()->check() && filled(Cache::get('user-is-online'))) {
                $user = User::find(Cache::get('user-is-online'));

                if ($user) {
                    $user->is_online = false;
                    $user->save();
                }
            }

            return $next($request);
        } catch (\Throwable $th) {
            // Log the exception or handle it accordingly
            // To maintain the response type, you may need to return a default response here
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
