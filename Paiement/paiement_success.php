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
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Paiement confirmé</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #00c6ff, #0072ff);
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
    }
    .confirmation-card {
      max-width: 600px;
      margin: 100px auto;
      background-color: #ffffff10;
      backdrop-filter: blur(8px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .confirmation-icon {
      font-size: 4rem;
      margin-bottom: 20px;
    }
    .btn-return {
      margin-top: 30px;
      padding: 10px 30px;
      font-size: 1.1rem;
      border-radius: 50px;
      background-color: #fff;
      color: #0072ff;
      font-weight: bold;
      transition: 0.3s ease;
    }
    .btn-return:hover {
      background-color: #0072ff;
      color: #fff;
      transform: scale(1.05);
    }
    code {
      background-color: #ffffff30;
      padding: 5px 10px;
      border-radius: 10px;
    }
  </style>
</head>
<body>

  <div class="confirmation-card text-center">
    <div class="confirmation-icon">🎉</div>
    <h2 class="fw-bold">Paiement confirmé avec succès !</h2>
    <p class="mb-3 fs-5">Merci pour votre confiance 🙏</p>
    <p>📦 Abonnement activé pour : <strong><?= htmlspecialchars($mois) ?> mois</strong></p>
    <p>⏳ Expiration prévue : <strong><?= date('d/m/Y', strtotime($exp)) ?></strong></p>
    <p>🧾 ID de transaction : <code><?= htmlspecialchars($transactionId) ?></code></p>

    <a href="../front_projet_EDL/accueil.php" class="btn btn-return">🏠 Retour à l’accueil</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
