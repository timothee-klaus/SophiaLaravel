<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReceiptController extends Controller
{
    /**
     * Génère une URL de visualisation temporaire (5 minutes).
     */
    public function getTemporaryUrl(Payment $payment)
    {
        if (!$payment->receipt_path || !Storage::exists($payment->receipt_path)) {
            return response()->json(['error' => 'Aucun reçu n\'est associé à ce paiement.'], 404);
        }

        $url = URL::temporarySignedRoute(
            'receipt.download', now()->addMinutes(5), ['payment' => $payment->id]
        );

        return response()->json(['url' => $url]);
    }

    /**
     * Télécharge ou affiche le reçu (Sécurisé par signature).
     */
    public function download(Request $request, Payment $payment)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Ce lien a expiré ou la signature est invalide.');
        }

        $path = $payment->receipt_path;

        if (!$path || !Storage::exists($path)) {
            abort(404, 'Fichier introuvable.');
        }

        // On peut renvoyer le fichier en téléchargement direct
        return Storage::download($path);
    }
}
