<?php
@session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");


// 📊 Stats clients
$stats = [
    'total' => 0,
    'par_pays' => [],
    'par_annee' => [],
    'clients' => []
];

// 🔢 Total clients
$stats['total'] = $bdd->query("SELECT COUNT(*) FROM inscription WHERE role='client'")->fetchColumn();

// 🗺️ Par pays
$rqPays = $bdd->query("SELECT pays, COUNT(*) AS total FROM inscription WHERE role='client' GROUP BY pays");
while ($row = $rqPays->fetch(PDO::FETCH_ASSOC)) {
    $stats['par_pays'][$row['pays']] = $row['total'];
}

// 📅 Par année
$rqAnnee = $bdd->query("SELECT YEAR(annee) AS annee, COUNT(*) AS total FROM inscription WHERE role='client' AND annee IS NOT NULL GROUP BY YEAR(annee)");
while ($row = $rqAnnee->fetch(PDO::FETCH_ASSOC)) {
    $stats['par_annee'][$row['annee']] = $row['total'];
}

// 📑 Liste des clients
$rqListe = $bdd->query("SELECT id, nom, prenom, email, ville, pays FROM inscription WHERE role='client' ORDER BY nom ASC");
$stats['clients'] = $rqListe->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>📊 Statistiques Clients • FreeBenin</title>
  <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
  <?php require_once(__DIR__."/../front_projet_EDL/header.php"); ?>

  <div class="container my-5">
    <h3 class="mb-4 text-center text-secondary">📊 Statistiques des clients FreeBenin</h3>

    <div class="row g-4 mb-4 text-center">
      <div class="col-md-4">
        <div class="bg-white p-3 rounded shadow-sm">
          <h5>👥 Nombre total de clients</h5>
          <p class="fs-4 text-primary"><?= $stats['total'] ?></p>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-6"><canvas id="clientsByCountry"></canvas></div>
      <div class="col-md-6"><canvas id="clientsByYear"></canvas></div>
    </div>

    <div class="mt-5">
      <h5>📋 Liste des clients inscrits</h5>
      <table class="table table-bordered table-striped table-hover mt-3">
        <thead class="table-dark">
          <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Ville</th>
            <th>Pays</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stats['clients'] as $client): ?>
            <tr>
              <td><?= htmlspecialchars($client['nom']) ?></td>
              <td><?= htmlspecialchars($client['prenom']) ?></td>
              <td><?= htmlspecialchars($client['email']) ?></td>
              <td><?= htmlspecialchars($client['ville']) ?></td>
              <td><?= htmlspecialchars($client['pays']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php require_once(__DIR__."/../front_projet_EDL/footer.php"); ?>

  <script>
    new Chart(document.getElementById('clientsByCountry'), {
      type: 'doughnut',
      data: {
        labels: <?= json_encode(array_keys($stats['par_pays'])) ?>,
        datasets: [{
          data: <?= json_encode(array_values($stats['par_pays'])) ?>,
          backgroundColor: ['#198754', '#ffc107', '#fd7e14', '#0d6efd', '#dc3545']
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: "Répartition géographique des clients"
          }
        }
      }
    });

    new Chart(document.getElementById('clientsByYear'), {
      type: 'bar',
      data: {
        labels: <?= json_encode(array_keys($stats['par_annee'])) ?>,
        datasets: [{
          label: "Inscriptions par année",
          data: <?= json_encode(array_values($stats['par_annee'])) ?>,
          backgroundColor: '#0d6efd'
        }]
      }
    });
  </script>
</body>
</html>
