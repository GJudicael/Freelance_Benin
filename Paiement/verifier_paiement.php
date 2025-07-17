<?php
require __DIR__ . '/../vendor/autoload.php';

use FedaPay\FedaPay;

// Configuration de l’environnement
$stripe_api_key = getenv('STRIPE_API_KEY');

FedaPay::setEnvironment('sandbox'); // Utilise 'live' en production

// ID de transaction transmis par GET
$transaction_id = $_GET['transaction_id'] ?? null;

if (!$transaction_id) {
    echo "Aucun ID de transaction fourni.";
    exit;
}

try {
    // Récupération de la transaction
    $transaction = \FedaPay\Transaction::retrieve($transaction_id);

    if ($transaction->status === 'approved') {
        echo "✅ Paiement réussi : " . $transaction->amount . " " . $transaction->currency;
    } elseif ($transaction->status === 'declined') {
        echo "❌ Paiement refusé.";
    } else {
        echo "⏳ Paiement en attente ou inconnu : " . $transaction->status;
    }
} catch (\Exception $e) {
    echo "🚨 Erreur lors de la vérification : " . $e->getMessage();
}
