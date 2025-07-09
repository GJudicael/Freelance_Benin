<?php
require __DIR__ . '/../vendor/autoload.php';

use Kkiapay\Kkiapay;

// Remplace par tes vraies clés KKiaPay
$kkiapay = new Kkiapay(
  'ea9534dc15c123315069269740eb2c74980ae74f',
  'pk_60d9268ac6adf471691f93ca71ae6a61f2353c8f044967a257de97c7c7ce1270',
  'sk_3dd42cfbae5ec2e0bcf50fe5d966be0e581b9616ddba4343c622a0474c86808e',
  false // true = sandbox
);

// Simule une transaction (remplace par un vrai ID après un test)
$transaction_id = $_GET['transaction_id'] ?? null;

if (!$transaction_id) {
    echo "Aucun ID de transaction fourni.";
    exit;
}

try {
    $transaction = $kkiapay->verifyTransaction($transaction_id);

    if (is_array($transaction) && isset($transaction['status'])) {
        if ($transaction['status'] === 'SUCCESS') {
            echo "✅ Paiement réussi : " . $transaction['amount'] . " FCFA";
        } else {
            echo "❌ Paiement échoué ou en attente.";
        }
    } else {
        echo "⚠️ Réponse inattendue de KKiaPay : ";
        var_dump($transaction);
    }
} catch (Exception $e) {
    echo "🚨 Erreur lors de la vérification : " . $e->getMessage();
}


