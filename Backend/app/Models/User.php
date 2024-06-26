<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Constants\GlobalConstants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Events\Registered;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\HasRolesAndPermissions;
use Laratrust\Contracts\LaratrustUser;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable implements LaratrustUser
{
    use HasRolesAndPermissions, HasApiTokens, HasFactory, Notifiable;



    /**
     * Get the phone associated with the user.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function inTheSameCategoriy()
    {
        return $this->belongsTo(Profile::class, 'category_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts()
    {
        return $this->hasMany(Posts::class, 'user_id', 'user_id');
    }


    // get all skills of the user
    public function skills()
    {
        return $this->hasMany(UserSkills::class, 'user_id');
    }

    public function seeker()
    {
        return $this->hasMany(Role::class, 'id');
    }

    public function provider()
    {
        return $this->hasMany(Role::class, 'id');
    }
    /**
     * A user can have many messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Messages::class);
    }

    public static function getProviders($search_keyword, $cates, $rateing)
    {
        $users = DB::table('profiles')->join('role_user', 'role_user.user_id', '=', 'profiles.user_id')
            ->where('role_user.role_id', 4);


        if ($search_keyword && !empty($search_keyword)) {
            $users->where(function ($q) use ($search_keyword) {
                $q->where('name', 'like', "%{$search_keyword}%");
            });
        }

        // Filter By categories
        if ($cates && $cates != GlobalConstants::ALL) {
            $checkIfAllChecked = array_search(-1, $cates, true);
            if (!$checkIfAllChecked)
                // print_r($checkIfAllChecked);
                $users = $users->whereIn('profiles.category_id', $cates);
        }
        //Filter By Skills
        // if ($skills && $skills != GlobalConstants::ALL) {
        //     $checkIfAllChecked = array_search(-1, $skills, true);
        //     if (!$checkIfAllChecked)
        //         // print_r($checkIfAllChecked);
        //         $users = $users->whereIn('profiles.category_id', $cates);
        // }
        // Filter By rateing
        if ($rateing && $rateing != GlobalConstants::ALL) {
            $users = $users->where('profiles.rating', $rateing);
        }

        // Filter By Type
        // if ($sort_by) {
        //     $sort_by = lcfirst($sort_by);
        //     if ($sort_by == GlobalConstants::USER_TYPE_FRONTEND) {
        //         $users = $users->where('profiles.type', $sort_by);
        //     } else if ($sort_by == GlobalConstants::USER_TYPE_BACKEND) {
        //         $users = $users->where('profiles.type', $sort_by);
        //     }
        // }

        // // Filter By Salaries
        // if ($range && $range != GlobalConstants::ALL) {
        //     $users = $users->where('users.salary', $range);
        // }

        return $users->paginate(10);
    }

    public static function handle(Registered $event)
    {
        if ($event->user instanceof MustVerifyEmail && !$event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('تأكيد البريد الاكتروني'))
            ->line(Lang::get('رجاء قم بالضغط على الزر في الاسفل لتفعيل البريد الاكتروني'))
            ->action(Lang::get('تأكيد البريد الاكتروني'), $url)
            ->line(Lang::get('اذا واجهتك مشاكل في الضغط على الزرار يمكنك الضغط على الرابط ادناه'));
    }
    public function message()
    {
        return $this->hasMany("App\Models\Messages");
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'holder_id')->where('holder_type', self::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'payable');
    }

    public function transfers(): MorphMany
    {
        return $this->morphMany(Transfer::class, 'from');
    }

    public function transfer(User $recipient, float $amount, array $meta = []): Transfer
    {

        $senderWallet = Wallet::where('holder_id',$this->id)->first();
        $recipientWallet = Wallet::where('holder_id', $recipient->id)->first();

        if (!$senderWallet || !$recipientWallet) {
            throw new \Exception('One or both users do not have wallets.');
        }

        if ($senderWallet->balance < $amount) {
            throw new \Exception('Insufficient balance.');
        }
        $senderWallet->balance -= $amount;
        $senderWallet->save();

        $recipientWallet->balance += $amount;
        $recipientWallet->save();

        $withdraw = $this->transactions()->create([
            'wallet_id' => $senderWallet->id,
            'type' => 'withdraw',
            'amount' => $amount,
            'confirmed' => true,
            'meta' => $meta,
            'uuid' =>Uuid::uuid4()->toString(),
        ]);

        $deposit = $recipient->transactions()->create([
            'wallet_id' => $recipientWallet->id,
            'type' => 'deposit',
            'amount' => $amount,
            'confirmed' => true,
            'meta' => $meta,
            'uuid' => Uuid::uuid4()->toString(),
        ]);

        return Transfer::create([
            'from_type' => self::class,
            'from_id' => $this->id,
            'to_type' => User::class,
            'to_id' => $recipient->id,
            'status' => 'transfer',
            'deposit_id' => $deposit->id,
            'withdraw_id' => $withdraw->id,
            'uuid' => Uuid::uuid4()->toString(),
        ]);
    }
}
