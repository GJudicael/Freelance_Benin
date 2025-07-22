<?php
session_start();
require_once __DIR__ . '/../bdd/creation_bdd.php';

$userId = $_SESSION['user_id'] ?? null;
$mois = $_POST['mois'] ?? $_SESSION['mois'] ?? null;
$transactionId = $_GET['transaction_id'] ?? null;

$exp = null;
if ($userId) {
  $stmt = $bdd->prepare("SELECT date_fin_abonnement FROM inscription WHERE id = ?");
  $stmt->execute([$userId]);
  $exp = $stmt->fetchColumn();
}
?>

<h2>✅ Paiement confirmé !</h2>
<p>Abonnement ajouté : <strong><?= htmlspecialchars($mois) ?> mois</strong></p>
<p>Date d’expiration : <strong><?= $exp ?></strong></p>
<p>Transaction ID : <code><?= $transactionId ?></code></p>
<a href="../front_projet_EDL/accueil.php">🏠 Retour à l’accueil</a>
