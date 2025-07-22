<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bdd/creation_bdd.php';

use Kkiapay\Kkiapay;

$publicKey  = "ea9534dc15c123315069269740eb2c74980ae74f";
$privateKey = "pk_60d9268ac6adf471691f93ca71ae6a61f2353c8f044967a257de97c7c7ce1270";
$secretKey  = "sk_3dd42cfbae5ec2e0bcf50fe5d966be0e581b9616ddba4343c622a0474c86808e";

// Récupérer l'ID de la transaction
$transactionId = $_GET['transaction_id'] ?? null;

if (!$transactionId) {
    die("Aucune transaction reçue.");
}

session_start();
$userId = $_SESSION['user_id'] ?? null;
$mois = $_SESSION['mois'] ?? 1; // Défaut 1 mois si rien n'est en session

if (!$userId) {
    die("Utilisateur non connecté.");
}

// Vérification du paiement
$kkiapay = new Kkiapay($publicKey, $privateKey, $secretKey, false); // false = production
try {
    $transaction = $kkiapay->verifyTransaction($transactionId);

    if ($transaction->status === "SUCCESS") {

        // 1. Mise à jour de la date de fin d’abonnement
        $update = $bdd->prepare("
            UPDATE inscription
            SET date_fin_abonnement = IF(
                date_fin_abonnement >= CURDATE(),
                DATE_ADD(date_fin_abonnement, INTERVAL :mois MONTH),
                DATE_ADD(CURDATE(), INTERVAL :mois MONTH)
            )
            WHERE id = :user_id
        ");
        $update->execute([
            'mois' => $mois,
            'user_id' => $userId
        ]);

        // 2. Insertion dans la table transaction
        $insert = $bdd->prepare("
            INSERT INTO transaction (utilisateur_id, mois, transaction_id)
            VALUES (:utilisateur_id, :mois, :transaction_id)
        ");
        $insert->execute([
            'utilisateur_id' => $userId,
            'mois' => $mois,
            'transaction_id' => $transactionId
        ]);

        
        header("Location: paiement_success.php");
        exit();
    } else {
        header("Location: paiement_echec.php");
        exit();
    }

} catch (Exception $e) {
    die("Erreur de vérification : " . $e->getMessage());
}
