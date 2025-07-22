<?php
@session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");


// Récupérer les statistiques
$stats = [
    'clients' => 0,
    'freelances' => 0,
    'entreprises' => 0,
    'demandes' => 0,
    'signalements' => 0,
    'profils_signales' => 0,
    'messages_total' => 0,
    'demandes_par_statut' => []
];

// Nombre par rôle
$rq = $bdd->query("SELECT role, COUNT(*) AS total FROM inscription GROUP BY role");
while ($row = $rq->fetch(PDO::FETCH_ASSOC)) {
    $stats[$row['role'].'s'] = $row['total'];
}

// Nombre de demandes
$stats['demandes'] = $bdd->query("SELECT COUNT(*) FROM demande")->fetchColumn();

// Signalements (demande + profil)
$stats['signalements'] = $bdd->query("SELECT COUNT(*) FROM signalements")->fetchColumn();
$stats['profils_signales'] = $bdd->query("SELECT COUNT(*) FROM signalements_profil")->fetchColumn();

// Messages
$stats['messages_total'] = $bdd->query("SELECT COUNT(*) FROM messages")->fetchColumn();

// Répartition des demandes par statut
$rqStatut = $bdd->query("SELECT statut, COUNT(*) AS total FROM demande GROUP BY statut");
while ($row = $rqStatut->fetch(PDO::FETCH_ASSOC)) {
    $stats['demandes_par_statut'][$row['statut']] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques • Admin FreeBenin</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <?php require_once(__DIR__."/../front_projet_EDL/header.php"); ?>
    
    <div class="container my-5">
        <h3 class="mb-4 text-secondary">📊 Statistiques de la plateforme</h3>

       <div class="row g-4">
  <div class="col-md-4">
    <div class="card p-3 shadow-sm text-center">
      <h5>👥 Clients</h5>
      <p><?= $stats['clients'] ?></p>
      <a href="statistiques_clients.php" class="btn btn-sm btn-outline-primary">Voir les profils</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card p-3 shadow-sm text-center">
      <h5>🧑‍💻 Freelances</h5>
      <p><?= $stats['freelances'] ?></p>
      <a href="statistiques_freelancers.php" class="btn btn-sm btn-outline-success">Voir les freelances</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card p-3 shadow-sm text-center">
      <h5>🏢 Entreprises</h5>
      <p><?= $stats['entreprises'] ?></p>
      <a href="statistiques_entreprises.php" class="btn btn-sm btn-outline-secondary">Voir les entreprises</a>
    </div>
  </div>

  

  <div class="col-md-4">
    <div class="card p-3 shadow-sm text-center">
      <h5>📝 Demandes</h5>
      <p><?= $stats['demandes'] ?></p>
      <a href="statistiques_demandes.php" class="btn btn-sm btn-outline-warning">Gérer les demandes</a>
    </div>
  </div>
  

  <div class="col-md-4">
    <div class="card p-3 shadow-sm text-center">
      <h5>🚨 Signalements</h5>
      <p><?= $stats['signalements'] + $stats['profils_signales'] ?></p>
      <a href="statistiques_signalements.php" class="btn btn-sm btn-outline-danger">Voir les signalements</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card p-3 shadow-sm text-center">
      <h5>📬 Messages</h5>
      <p><?= $stats['messages_total'] ?></p>
    </div>
  </div>
</div>

        <hr class="my-5">

        <div class="row">
            <div class="col-md-8 mx-auto">
                <canvas id="demandesStatutChart"></canvas>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__."/../front_projet_EDL/footer.php"); ?>

    <script>
        const ctx = document.getElementById('demandesStatutChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($stats['demandes_par_statut'])) ?>,
                datasets: [{
                    label: 'Demandes par statut',
                    data: <?= json_encode(array_values($stats['demandes_par_statut'])) ?>,
                    backgroundColor: [
                        '#0d6efd', '#ffc107', '#198754', '#6c757d', '#dc3545', '#fd7e14'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Répartition des demandes'
                    }
                }
            }
        });
    </script>
</body>
</html>
