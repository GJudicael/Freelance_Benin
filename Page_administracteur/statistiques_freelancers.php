<?php
@session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");



// 📦 Stats freelances
$stats = [
    'par_annee' => [],
    'par_pays' => [],
    'notes_moyennes' => [],
    'projets_par_freelance' => [],
    'top_freelances' => []
];

// 📅 Inscriptions par année
$req = $bdd->query("SELECT YEAR(annee) AS annee, COUNT(*) AS total FROM inscription WHERE role='freelance' AND annee IS NOT NULL GROUP BY YEAR(annee)");
while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
    $stats['par_annee'][$row['annee']] = $row['total'];
}

// 🗺️ Répartition par pays
$req = $bdd->query("SELECT pays, COUNT(*) AS total FROM inscription WHERE role='freelance' GROUP BY pays");
while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
    $stats['par_pays'][$row['pays']] = $row['total'];
}

// ⭐ Note moyenne par freelance
$req = $bdd->query("SELECT f.user_id, i.nom, i.prenom, ROUND(AVG(n.stars),2) AS moyenne FROM notation n INNER JOIN freelancers f ON f.id=n.freelancer_id INNER JOIN inscription i ON i.id=f.user_id GROUP BY f.user_id");
while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
    $stats['notes_moyennes'][$row['nom'].' '.$row['prenom']] = $row['moyenne'];
}

// 🧑‍💻 Projets par freelance
$req = $bdd->query("SELECT i.nom, i.prenom, COUNT(p.id) AS total FROM projets p INNER JOIN freelancers f ON f.id=p.freelancer_id INNER JOIN inscription i ON i.id=f.user_id GROUP BY f.user_id");
while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
    $stats['projets_par_freelance'][$row['nom'].' '.$row['prenom']] = $row['total'];
}

// 🥇 Top 5 freelances
$req = $bdd->query("SELECT i.nom, i.prenom, ROUND(AVG(n.stars),2) AS moyenne FROM notation n INNER JOIN freelancers f ON f.id=n.freelancer_id INNER JOIN inscription i ON i.id=f.user_id GROUP BY f.user_id ORDER BY moyenne DESC LIMIT 5");
$stats['top_freelances'] = $req->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>📊 Freelances • Statistiques FreeBenin</title>
  <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
  <?php require_once(__DIR__."/../front_projet_EDL/header.php"); ?>

  <div class="container my-5">
    <h3 class="mb-4 text-center text-secondary">📊 Analyse des freelances sur la plateforme</h3>

    <div class="row g-4">
      <div class="col-md-6"><canvas id="freelancesByYear"></canvas></div>
      <div class="col-md-6"><canvas id="freelancesByCountry"></canvas></div>
    </div>

    <div class="row g-4 mt-4">
      <div class="col-md-6"><canvas id="averageRatings"></canvas></div>
      <div class="col-md-6"><canvas id="projectsPerFreelance"></canvas></div>
    </div>

    <div class="mt-5">
      <h5>🥇 Top 5 des freelances les mieux notés</h5>
      <ul class="list-group">
        <?php foreach ($stats['top_freelances'] as $f): ?>
          <li class="list-group-item d-flex justify-content-between">
            <?= htmlspecialchars($f['nom'].' '.$f['prenom']) ?>
            <span class="badge bg-success"><?= $f['moyenne'] ?>/5</span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

 <?php $requete = $bdd->query("
  SELECT i.id, i.nom, i.prenom, i.ville, i.pays, i.email, i.nomDUtilisateur, f.competences, f.gitHub, f.linkdin
  FROM inscription i
  INNER JOIN freelancers f ON f.user_id = i.id
  WHERE i.role = 'freelance'
  ORDER BY i.nom ASC
");
$freelances = $requete->fetchAll(PDO::FETCH_ASSOC);?>

<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Nom d'utilisateur</th>
      <th>Compétences</th>
      <th>GitHub</th>
      <th>LinkedIn</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($freelances as $f): ?>
      <tr>
        <td><?= htmlspecialchars($f['nom']) ?></td>
        <td><?= htmlspecialchars($f['prenom']) ?></td>
        <td><?= htmlspecialchars($f['nomDUtilisateur']) ?></td>
        <td><?= htmlspecialchars($f['competences']) ?></td>
        <td><a href="<?= htmlspecialchars($f['gitHub']) ?>" target="_blank">GitHub</a></td>
        <td><a href="<?= htmlspecialchars($f['linkdin']) ?>" target="_blank">LinkedIn</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>



  <?php require_once(__DIR__."/../front_projet_EDL/footer.php"); ?>

  <script>
    // 📅 Freelances par année
    new Chart(document.getElementById('freelancesByYear'), {
      type: 'bar',
      data: {
        labels: <?= json_encode(array_keys($stats['par_annee'])) ?>,
        datasets: [{
          label: 'Inscriptions par année',
          data: <?= json_encode(array_values($stats['par_annee'])) ?>,
          backgroundColor: '#0d6efd'
        }]
      }
    });

    // 🗺️ Répartition par pays
    new Chart(document.getElementById('freelancesByCountry'), {
      type: 'doughnut',
      data: {
        labels: <?= json_encode(array_keys($stats['par_pays'])) ?>,
        datasets: [{
          label: 'Pays',
          data: <?= json_encode(array_values($stats['par_pays'])) ?>,
          backgroundColor: ['#198754','#ffc107','#fd7e14','#dc3545','#20c997']
        }]
      }
    });

    // ⭐ Notes moyennes
    new Chart(document.getElementById('averageRatings'), {
      type: 'horizontalBar',
      data: {
        labels: <?= json_encode(array_keys($stats['notes_moyennes'])) ?>,
        datasets: [{
          label: 'Note moyenne',
          data: <?= json_encode(array_values($stats['notes_moyennes'])) ?>,
          backgroundColor: '#198754'
        }]
      },
      options: {
        indexAxis: 'y'
      }
    });

    // 📦 Projets par freelance
    new Chart(document.getElementById('projectsPerFreelance'), {
      type: 'bar',
      data: {
        labels: <?= json_encode(array_keys($stats['projets_par_freelance'])) ?>,
        datasets: [{
          label: 'Projets publiés',
          data: <?= json_encode(array_values($stats['projets_par_freelance'])) ?>,
          backgroundColor: '#fd7e14'
        }]
      }
    });
  </script>
</body>
</html>
