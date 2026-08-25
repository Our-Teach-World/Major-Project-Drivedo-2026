<?php

namespace App\Http\Controllers\Certchain;

use App\Http\Controllers\Controller;

use App\Models\Certificate;
use App\Services\BlockchainService;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function __construct(protected BlockchainService $blockchain)
    {
    }

    public function index()
    {
        return view('verify.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $q = trim($request->input('query'));

        // Try by Certificate ID first, then by enrollment number
        $certificate = Certificate::with(['event', 'issuer', 'template', 'blockchainBlock'])
            ->where('certificate_id', $q)
            ->orWhere('enrollment_number', $q)
            ->latest()
            ->first();

        if (!$certificate) {
            return back()->with('error', 'No certificate found for: ' . $q);
        }

        $verification = $this->blockchain->verifyCertificate($certificate);

        return view('verify.result', compact('certificate', 'verification'));
    }

    public function certificate(string $id)
    {
        $certificate = Certificate::with(['event', 'issuer', 'template', 'blockchainBlock'])
            ->where('certificate_id', $id)->firstOrFail();

        $verification = $this->blockchain->verifyCertificate($certificate);

        return view('verify.result', compact('certificate', 'verification'));
    }
}
