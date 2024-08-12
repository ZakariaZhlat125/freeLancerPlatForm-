<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\File;

use Barryvdh\DomPDF\Facade as PDF;





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
        $contract->status = 'pending';
        $contract->amount = $request->amount;
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
        // Determine the role of the user signing the contract
        $role = Auth::id() == $contract->freelancer_id ? 'freelancer' : (Auth::id() == $contract->seeker_id ? 'seeker' : 'admin');

        // Define the paths to the private and public keys
        $privateKeyPath = storage_path('keys/'. $role . '/private_key_' . $role . '.pem');
        $publicKeyPath = storage_path('keys/' . $role . '/public_key_' . $role . '.pem');

        // Verify the current content hash
        $currentContentHash = hash('sha256', $contract->contract_content);

        // Sign the contract based on the role
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

        // Save the updated contract details
        $contract->save();

        // Check if both freelancer and seeker have signed the contract
        if (!empty($contract->freelancer_signature) && !empty($contract->seeker_signature)) {
            $contract->status = 'signed';
            $this->generateAndEncryptPdf($contract);
        }

        // Redirect to the contract's show page with a success message
        return redirect()->route('contracts.show', $contract->id)
            ->with('success', 'Contract signed successfully.');
    }


    private function generateAndEncryptPdf(Contract $contract) {
        // Generate the PDF content from the contract
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('contracts.pdf', ['contract' => $contract]);
        $pdfContent = $pdf->output();

        // Encrypt the PDF content
        $encryptedPdf = $this->encryptData($pdfContent);

        // Add a timestamp to the filename
        $timestamp = now()->format('Y_m_d_H_i_s');
        $fileName = "contract_{$contract->id}_{$timestamp}.pdf.enc";
        $directory = storage_path('app/contracts');

        // Ensure the directory exists, create it if necessary
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Define the file path
        $filePath = $directory . '/' . $fileName;

        // Save the encrypted PDF to the storage directory
        file_put_contents($filePath, $encryptedPdf);

        // Store the encrypted PDF file path and timestamp in the contract
        $contract->encrypted_pdf_path = $filePath;
        $contract->pdf_timestamp = $timestamp;
        $contract->save();
    }


    // public function show(Contract $contract) {
    //     $freelancer_signature_valid = $this->verifySignature(
    //         $contract->contract_content,
    //         $this->decryptData($contract->freelancer_signature),
    //         $this->decryptData($contract->freelancer_public_key)
    //     );
    //     $seeker_signature_valid = $this->verifySignature(
    //         $contract->contract_content,
    //         $this->decryptData($contract->seeker_signature),
    //         $this->decryptData($contract->seeker_public_key)
    //     );
    //     $admin_signature_valid = $this->verifySignature(
    //         $contract->contract_content,
    //         $this->decryptData($contract->admin_signature),
    //         $this->decryptData($contract->admin_public_key)
    //     );

    //     return view('contracts.show', compact('contract', 'freelancer_signature_valid', 'seeker_signature_valid', 'admin_signature_valid'));
    // }

    // public function show(Contract $contract) {
    //     return view('contracts.show', [
    //         'contract' => $contract,
    //         'contract_content' => $contract->contract_content,
    //         'freelancer_signature' => $contract->freelancer_signature,
    //         'seeker_signature' => $contract->seeker_signature,
    //         'admin_signature' => $contract->admin_signature,
    //     ]);

    // }

    public function show(Contract $contract) {
        // Determine if each user has signed the contract
        $freelancer_signed = !empty($contract->freelancer_signature);
        $seeker_signed = !empty($contract->seeker_signature);
        // $admin_signed = !empty($contract->admin_signature);

        // Verify signatures if the user has signed
        $freelancer_signature_valid = $freelancer_signed ? $this->verifySignature(
            $contract->contract_content,
            $this->decryptData($contract->freelancer_signature),
            $this->decryptData($contract->freelancer_public_key)
        ) : null;

        $seeker_signature_valid = $seeker_signed ? $this->verifySignature(
            $contract->contract_content,
            $this->decryptData($contract->seeker_signature),
            $this->decryptData($contract->seeker_public_key)
        ) : null;

        // $admin_signature_valid = $admin_signed ? $this->verifySignature(
        //     $contract->contract_content,
        //     $this->decryptData($contract->admin_signature),
        //     $this->decryptData($contract->admin_public_key)
        // ) : null;

        // Check if the content has changed compared to the saved hash
        $content_hash_valid = hash('sha256', $contract->contract_content) === $contract->content_hash;


        return view('contracts.show', [
            'contract' => $contract,
            'freelancer_signed' => $freelancer_signed,
            'freelancer_signature_valid' => $freelancer_signature_valid,
            'seeker_signed' => $seeker_signed,
            'seeker_signature_valid' => $seeker_signature_valid,
            // 'admin_signed' => $admin_signed,
            // 'admin_signature_valid' => $admin_signature_valid,
        ]);
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


    // public function edit(Contract $contract) {

    //     return view('contracts.edit', compact('contract'));
    // }

    // public function update(Request $request, Contract $contract) {
    //     // Validate the request data
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'amount' => 'required|numeric|min:0',
    //         'contract_content' => 'required|string',
    //     ]);

    //     // Update the contract with the validated data
    //     $contract->update([
    //         'title' => $request->input('title'),
    //         'amount' => $request->input('amount'),
    //         'contract_content' => $request->input('contract_content'),
    //         'version' => $contract->version + 1,
    //         'content_hash' => hash('sha256', $request->input('contract_content')), // Update content hash
    //     ]);

    //     // Redirect to the sign page for the updated contract
    //     return redirect()->route('contracts.sign', $contract->id)
    //         ->with('success', 'Contract updated successfully. Please sign the updated contract.');
    // }



}

