<?php
@session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../index.php");
//     exit();
// }

// Statistiques
$stats = [
    'par_statut' => [],
    'budgets' => [],
    'par_mois' => [],
    'par_categorie' => [],
    'attribution' => ['attribuees' => 0, 'total' => 0],
    'demandes' => []
];

// Statut
$rqStatut = $bdd->query("SELECT statut, COUNT(*) AS total FROM demande GROUP BY statut");
while ($row = $rqStatut->fetch(PDO::FETCH_ASSOC)) {
    $stats['par_statut'][$row['statut']] = $row['total'];
}

// Budget (tranches personnalisées)
$rqBudgets = $bdd->query("
    SELECT CASE
        WHEN budget < 500 THEN '< 500'
        WHEN budget BETWEEN 500 AND 999 THEN '500-999'
        WHEN budget BETWEEN 1000 AND 4999 THEN '1000-4999'
        ELSE '≥ 5000'
    END AS tranche,
    COUNT(*) AS total
    FROM demande
    GROUP BY tranche
");
while ($row = $rqBudgets->fetch(PDO::FETCH_ASSOC)) {
    $stats['budgets'][$row['tranche']] = $row['total'];
}

// Par mois
$rqMois = $bdd->query("
    SELECT DATE_FORMAT(date_soumission, '%Y-%m') AS mois, COUNT(*) AS total
    FROM demande
    GROUP BY mois ORDER BY mois
");
while ($row = $rqMois->fetch(PDO::FETCH_ASSOC)) {
    $stats['par_mois'][$row['mois']] = $row['total'];
}

// Par catégorie
$rqCat = $bdd->query("SELECT categorie, COUNT(*) AS total FROM demande GROUP BY categorie ORDER BY total DESC");
while ($row = $rqCat->fetch(PDO::FETCH_ASSOC)) {
    $stats['par_categorie'][$row['categorie']] = $row['total'];
}

// Taux d'attribution
$stats['attribution']['attribuees'] = $bdd->query("SELECT COUNT(*) FROM demande WHERE freelancer_id IS NOT NULL")->fetchColumn();
$stats['attribution']['total'] = $bdd->query("SELECT COUNT(*) FROM demande")->fetchColumn();

// Liste des demandes
$rqList = $bdd->query("SELECT titre, budget, statut, categorie, date_soumission FROM demande ORDER BY date_soumission DESC LIMIT 50");
$stats['demandes'] = $rqList->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>📊 Demandes • Statistiques Admin FreeBenin</title>
  <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<?php require_once(__DIR__."/../front_projet_EDL/header.php"); ?>

<div class="container my-5">
  <h3 class="text-center mb-4 text-secondary">📦 Statistiques des demandes</h3>

  <div class="row g-4 mb-4 text-center">
    <div class="col-md-6"><div class="bg-white p-3 rounded shadow-sm">
      <h5>Taux d'attribution</h5>
      <p class="fs-4 text-success">
        <?= round(($stats['attribution']['attribuees'] / max($stats['attribution']['total'], 1)) * 100, 1) ?> %
      </p>
    </div></div>
  </div>

  <div class="row g-4">
    <div class="col-md-6"><canvas id="statutChart"></canvas></div>
    <div class="col-md-6"><canvas id="budgetChart"></canvas></div>
  </div>

  <div class="row g-4 mt-4">
    <div class="col-md-6"><canvas id="moisChart"></canvas></div>
    <div class="col-md-6"><canvas id="categorieChart"></canvas></div>
  </div>

  <div class="mt-5">
    <h5>📋 Liste des dernières demandes</h5>
    <table class="table table-bordered table-striped">
      <thead class="table-dark"><tr>
        <th>Titre</th><th>Budget</th><th>Statut</th><th>Catégorie</th><th>Soumise le</th>
      </tr></thead>
      <tbody>
        <?php foreach ($stats['demandes'] as $d): ?>
          <tr>
            <td><?= htmlspecialchars($d['titre']) ?></td>
            <td><?= htmlspecialchars($d['budget']) ?> FCFA</td>
            <td><?= htmlspecialchars($d['statut']) ?></td>
            <td><?= htmlspecialchars($d['categorie']) ?></td>
            <td><?= htmlspecialchars($d['date_soumission']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once(__DIR__."/../front_projet_EDL/footer.php"); ?>

<script>
new Chart(document.getElementById('statutChart'), {
  type: 'pie',
  data: {
    labels: <?= json_encode(array_keys($stats['par_statut'])) ?>,
    datasets: [{
      data: <?= json_encode(array_values($stats['par_statut'])) ?>,
      backgroundColor: ['#ffc107','#198754','#0d6efd','#6c757d','#dc3545','#fd7e14']
    }]
  },
  options: { plugins: { title: { display: true, text: "Répartition par statut" } } }
});

new Chart(document.getElementById('budgetChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode(array_keys($stats['budgets'])) ?>,
    datasets: [{
      label: "Budget (tranches)",
      data: <?= json_encode(array_values($stats['budgets'])) ?>,
      backgroundColor: '#0d6efd'
    }]
  }
});

new Chart(document.getElementById('moisChart'), {
  type: 'line',
  data: {
    labels: <?= json_encode(array_keys($stats['par_mois'])) ?>,
    datasets: [{
      label: "Demandes par mois",
      data: <?= json_encode(array_values($stats['par_mois'])) ?>,
      borderColor: '#198754',
      tension: 0.3,
      fill: true
    }]
  }
});

new Chart(document.getElementById('categorieChart'), {
  type: 'doughnut',
  data: {
    labels: <?= json_encode(array_keys($stats['par_categorie'])) ?>,
    datasets: [{
      data: <?= json_encode(array_values($stats['par_categorie'])) ?>,
      backgroundColor: ['#fd7e14','#20c997','#6f42c1','#0d6efd','#198754','#ffc107']
    }]
  },
  options: { plugins: { title: { display: true, text: "Catégories les plus utilisées" } } }
});
</script>
</body>
</html>
