<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\User;





class ContractController extends Controller
{

    public function index() {
        $contracts = Contract::where('seeker_id', Auth::id())->orWhere('freelancer_id', Auth::id())->get();
        return view('contracts.view', compact('contracts'));
    }

    public function create() {

        $freelancers = User::whereHas('roles', function($query) {
            $query->where('name', 'provider');
        })->get();

        return view('contracts.create', compact('freelancers'));
    }



    public function store(Request $request) {
        $contract = new Contract();
        $contract->freelancer_id = $request->freelancer_id;
        $contract->seeker_id = Auth::id();
        $contract->contract_content = $request->contract_content;
        $contract->save();

        return redirect()->route('contracts.index');
    }

    private function encryptData($data) {
        return Crypt::encryptString($data);
    }

    private function decryptData($encryptedData) {
        return Crypt::decryptString($encryptedData);
    }

    public function sign(Request $request, Contract $contract) {

        $role = Auth::id() == $contract->freelancer_id ? 'freelancer' : (Auth::id() == $contract->seeker_id ? 'seeker' : 'admin');


        $privateKeyPath = config('keys.' . $role . '.private_key');
            $publicKeyPath = config('keys.' . $role . '.public_key');
            dd($role, $privateKeyPath, $publicKeyPath);
            // dd(config('keys.freelancer.private_key'), config('keys.seeker.private_key'), config('keys.admin.private_key'));


            if ($role == 'freelancer') {
                $signature = $this->signContent($contract->contract_content, $privateKeyPath);
                $contract->freelancer_signature = $this->encryptData($signature);
                $contract->freelancer_public_key = $this->encryptData(file_get_contents($publicKeyPath));
            } else if ($role == 'seeker') {
                $signature = $this->signContent($contract->contract_content, $privateKeyPath);
                $contract->seeker_signature = $this->encryptData($signature);
                $contract->seeker_public_key = $this->encryptData(file_get_contents($publicKeyPath));
            } else if ($role == 'admin') {
                $signature = $this->signContent($contract->contract_content, $privateKeyPath);
                $contract->admin_signature = $this->encryptData($signature);
                $contract->admin_public_key = $this->encryptData(file_get_contents($publicKeyPath));
            }

            $contract->save();

            return redirect()->route('contracts.show', $contract);

    }

    public function show(Contract $contract) {
        $freelancer_signature_valid = $this->verifySignature(
            $contract->contract_content,
            $this->decryptData($contract->freelancer_signature),
            $this->decryptData($contract->freelancer_public_key)
        );
        $seeker_signature_valid = $this->verifySignature(
            $contract->contract_content,
            $this->decryptData($contract->seeker_signature),
            $this->decryptData($contract->seeker_public_key)
        );
        $admin_signature_valid = $this->verifySignature(
            $contract->contract_content,
            $this->decryptData($contract->admin_signature),
            $this->decryptData($contract->admin_public_key)
        );

        return view('contracts.show', compact('contract', 'freelancer_signature_valid', 'seeker_signature_valid', 'admin_signature_valid'));
    }



    private function signContent($content, $privateKeyPath) {
        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));
        openssl_sign($content, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    private function verifySignature($content, $signature, $publicKey) {
        $publicKey = openssl_pkey_get_public($publicKey);
        $result = openssl_verify($content, base64_decode($signature), $publicKey, OPENSSL_ALGO_SHA256);
        return $result === 1;
    }
}

