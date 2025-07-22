<?php
@session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../index.php");
//     exit();
// }

// 📊 Statistiques entreprises
$stats = [
    'total' => 0,
    'par_pays' => [],
    'par_annee' => [],
    'entreprises' => []
];

// Nombre total
$stats['total'] = $bdd->query("SELECT COUNT(*) FROM inscription WHERE role='entreprise'")->fetchColumn();

// Par pays
$paysQuery = $bdd->query("SELECT pays, COUNT(*) AS total FROM inscription WHERE role='entreprise' GROUP BY pays");
while ($row = $paysQuery->fetch(PDO::FETCH_ASSOC)) {
    $stats['par_pays'][$row['pays']] = $row['total'];
}

// Par année
$anneeQuery = $bdd->query("SELECT YEAR(annee) AS annee, COUNT(*) AS total FROM inscription WHERE role='entreprise' AND annee IS NOT NULL GROUP BY YEAR(annee)");
while ($row = $anneeQuery->fetch(PDO::FETCH_ASSOC)) {
    $stats['par_annee'][$row['annee']] = $row['total'];
}

// Liste des entreprises
$listeQuery = $bdd->query("SELECT nom, prenom, email, ville, pays, nomDUtilisateur, activity_sector FROM inscription WHERE role='entreprise' ORDER BY nom ASC");
$stats['entreprises'] = $listeQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>📊 Statistiques Entreprises • FreeBenin</title>
  <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
  <?php require_once(__DIR__."/../front_projet_EDL/header.php"); ?>

  <div class="container my-5">
    <h3 class="mb-4 text-center text-secondary">🏢 Statistiques des entreprises inscrites</h3>

    <div class="row g-4 text-center mb-4">
      <div class="col-md-4">
        <div class="bg-white p-3 rounded shadow-sm">
          <h5>Nombre d'entreprises</h5>
          <p class="fs-4 text-primary"><?= $stats['total'] ?></p>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-6"><canvas id="entreprisesParPays"></canvas></div>
      <div class="col-md-6"><canvas id="entreprisesParAnnee"></canvas></div>
    </div>

    <div class="mt-5">
      <h5>📋 Liste complète des entreprises</h5>
      <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
          <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Ville</th>
            <th>Pays</th>
            <th>Nom d'utilisateur</th>
            <th>Secteur d'activité</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stats['entreprises'] as $e): ?>
            <tr>
              <td><?= htmlspecialchars($e['nom']) ?></td>
              <td><?= htmlspecialchars($e['prenom']) ?></td>
              <td><?= htmlspecialchars($e['email']) ?></td>
              <td><?= htmlspecialchars($e['ville']) ?></td>
              <td><?= htmlspecialchars($e['pays']) ?></td>
              <td><?= htmlspecialchars($e['nomDUtilisateur']) ?></td>
              <td><?= htmlspecialchars($e['activity_sector']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php require_once(__DIR__."/../front_projet_EDL/footer.php"); ?>

  <script>
    new Chart(document.getElementById('entreprisesParPays'), {
      type: 'pie',
      data: {
        labels: <?= json_encode(array_keys($stats['par_pays'])) ?>,
        datasets: [{
          data: <?= json_encode(array_values($stats['par_pays'])) ?>,
          backgroundColor: ['#0d6efd', '#198754', '#fd7e14', '#dc3545', '#ffc107']
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: "Répartition géographique"
          }
        }
      }
    });

    new Chart(document.getElementById('entreprisesParAnnee'), {
      type: 'bar',
      data: {
        labels: <?= json_encode(array_keys($stats['par_annee'])) ?>,
        datasets: [{
          label: "Inscriptions par année",
          data: <?= json_encode(array_values($stats['par_annee'])) ?>,
          backgroundColor: '#20c997'
        }]
      }
    });
  </script>
</body>
</html>
