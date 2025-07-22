<?php
session_start();
require_once __DIR__ . '/../bdd/creation_bdd.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Kkiapay\Kkiapay;

// 🔐 Reçoit le JSON envoyé depuis JS
$input = json_decode(file_get_contents("php://input"), true);
$transactionId = $input['transaction_id'] ?? null;
$mois = intval($input['mois'] ?? 0);
$userId = $_SESSION['user_id'] ?? null;

if (!$transactionId || !$userId || $mois <= 0) {
  http_response_code(400);
  echo "Données manquantes ou invalides.";
  exit;
}

// 🔍 Vérifier transaction avec Kkiapay
$kkiapay = new Kkiapay(
  "ea9534dc15c123315069269740eb2c74980ae74f",
  "pk_60d9268ac6adf471691f93ca71ae6a61f2353c8f044967a257de97c7c7ce1270",
  "sk_3dd42cfbae5ec2e0bcf50fe5d966be0e581b9616ddba4343c622a0474c86808e",
  false
);

try {
  $transaction = $kkiapay->verifyTransaction($transactionId);

  if ($transaction->status === "SUCCESS") {

    // 📅 Mise à jour abonnement
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

    // 🧾 Enregistrement transaction
    $insert = $bdd->prepare("
      INSERT INTO transaction (utilisateur_id, mois, transaction_id)
      VALUES (:user_id, :mois, :transaction_id)
    ");
    $insert->execute([
      'user_id' => $userId,
      'mois' => $mois,
      'transaction_id' => $transactionId
    ]);

    http_response_code(200);
    echo "Transaction enregistrée avec succès.";
  } else {
    http_response_code(402);
    echo "Transaction non valide.";
  }

} catch (Exception $e) {
  http_response_code(500);
  echo "Erreur de vérification : " . $e->getMessage();
}
