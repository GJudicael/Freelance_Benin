<?php
@session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../index.php");
//     exit();
// }

// 📦 Statistiques
$stats = [
    'total_demandes' => 0,
    'total_profils' => 0,
    'par_mois' => [],
    'signalements_recents' => []
];

// Nombre total
$stats['total_demandes'] = $bdd->query("SELECT COUNT(*) FROM signalements")->fetchColumn();
$stats['total_profils'] = $bdd->query("SELECT COUNT(*) FROM signalements_profil")->fetchColumn();

// Signalements par mois
$rqMois = $bdd->query("
  SELECT DATE_FORMAT(date_signalement, '%Y-%m') AS mois, COUNT(*) AS total
  FROM (
    SELECT date_signalement FROM signalements
    UNION ALL
    SELECT date_signalement FROM signalements_profil
  ) AS all_signals
  GROUP BY mois
  ORDER BY mois
");
while ($row = $rqMois->fetch(PDO::FETCH_ASSOC)) {
  $stats['par_mois'][$row['mois']] = $row['total'];
}

// Liste des 20 derniers signalements (demande + profil)
$rqRecents = $bdd->query("
  SELECT s.raison, s.date_signalement, i.nom, i.prenom, 'demande' AS type
  FROM signalements s
  JOIN inscription i ON i.id = s.signale_par
  UNION ALL
  SELECT sp.raison, sp.date_signalement, i.nom, i.prenom, 'profil' AS type
  FROM signalements_profil sp
  JOIN inscription i ON i.id = sp.signale_par
  ORDER BY date_signalement DESC
  LIMIT 20
");
$stats['signalements_recents'] = $rqRecents->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>🚨 Statistiques Signalements • FreeBenin</title>
  <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<?php require_once(__DIR__."/../front_projet_EDL/header.php"); ?>

<div class="container my-5">
  <h3 class="text-center mb-4 text-danger">🚨 Statistiques des signalements</h3>

  <div class="row text-center g-4 mb-4">
    <div class="col-md-6">
      <div class="bg-white p-3 shadow-sm rounded">
        <h5>Signalements sur demandes</h5>
        <p class="fs-4 text-warning"><?= $stats['total_demandes'] ?></p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="bg-white p-3 shadow-sm rounded">
        <h5>Signalements de profils</h5>
        <p class="fs-4 text-danger"><?= $stats['total_profils'] ?></p>
      </div>
    </div>
  </div>

  <div class="row mt-4 mb-5">
    <div class="col-md-8 mx-auto">
      <canvas id="signalementsMoisChart"></canvas>
    </div>
  </div>

  <h5 class="mt-4">📋 Signalements récents</h5>
  <table class="table table-bordered table-striped mt-3">
    <thead class="table-dark">
      <tr><th>Type</th><th>Par</th><th>Raison</th><th>Date</th></tr>
    </thead>
    <tbody>
      <?php foreach ($stats['signalements_recents'] as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['type']) ?></td>
          <td><?= htmlspecialchars($s['nom'].' '.$s['prenom']) ?></td>
          <td><?= htmlspecialchars($s['raison']) ?></td>
          <td><?= htmlspecialchars($s['date_signalement']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once(__DIR__."/../front_projet_EDL/footer.php"); ?>

<script>
new Chart(document.getElementById('signalementsMoisChart'), {
  type: 'line',
  data: {
    labels: <?= json_encode(array_keys($stats['par_mois'])) ?>,
    datasets: [{
      label: "Total signalements par mois",
      data: <?= json_encode(array_values($stats['par_mois'])) ?>,
      borderColor: '#dc3545',
      backgroundColor: 'rgba(220,53,69,0.2)',
      fill: true,
      tension: 0.3
    }]
  },
  options: {
    plugins: {
      title: { display: true, text: "Évolution mensuelle des signalements" }
    }
  }
});
</script>
</body>
</html>
