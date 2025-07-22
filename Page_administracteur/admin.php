<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Panneau d'administration • FreeBenin</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }
    .admin-card:hover {
      transform: scale(1.02);
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      transition: 0.3s ease-in-out;
    }
  </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">
  <?php require_once(__DIR__."/../front_projet_EDL/header.php"); ?>

  <main class="container my-5 flex-fill">
    <div class="text-center mb-4">
      <h3 class="text-secondary fw-bold">🎛️ Panneau d'administration</h3>
      <p class="text-muted">Bienvenue, vous pouvez gérer les signalements et contrôler les activités de la plateforme.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <a href="signalements_demandes.php" class="text-decoration-none">
          <div class="card admin-card shadow-sm border-0 p-4 h-100">
            <div class="d-flex align-items-center">
              <i class="bi bi-flag-fill fs-2 text-primary me-3"></i>
              <div>
                <h5 class="mb-1 text-dark">Signalements de demandes</h5>
                <p class="text-muted mb-0">Afficher et traiter les contenus signalés par les utilisateurs.</p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-6">
        <a href="signalements_profils.php" class="text-decoration-none">
          <div class="card admin-card shadow-sm border-0 p-4 h-100">
            <div class="d-flex align-items-center">
              <i class="bi bi-person-x-fill fs-2 text-danger me-3"></i>
              <div>
                <h5 class="mb-1 text-dark">Profils signalés</h5>
                <p class="text-muted mb-0">Inspecter les utilisateurs suspects ou inactifs.</p>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row g-4 mt-3">
      <div class="col-md-6">
        <a href="recherche.php" class="text-decoration-none">
          <div class="card admin-card shadow-sm border-0 p-4 h-100">
            <div class="d-flex align-items-center">
              <i class="bi bi-people-fill fs-2 text-secondary me-3"></i>
              <div>
                <h5 class="mb-1 text-dark">Utilisateurs</h5>
                <p class="text-muted mb-0">Accéder aux comptes, rôles et statuts des membres.</p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-6">
        <a href="statistiques.php" class="text-decoration-none">
          <div class="card admin-card shadow-sm border-0 p-4 h-100">
            <div class="d-flex align-items-center">
              <i class="bi bi-bar-chart-line-fill fs-2 text-success me-3"></i>
              <div>
                <h5 class="mb-1 text-dark">Statistiques</h5>
                <p class="text-muted mb-0">Consulter l’activité de la plateforme en temps réel.</p>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </main>

  <?php require_once(__DIR__."/../front_projet_EDL/footer.php"); ?>
  <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
