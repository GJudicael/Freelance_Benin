<?php
require_once(__DIR__ . "/../bdd/creation_bdd.php");
require_once(__DIR__ . "/../PHP/update_profile.php");

$user_id = $_SESSION['user_id'];



$stmt = $bdd->prepare("SELECT photo, role FROM inscription WHERE id = ?");
$stmt->execute([$user_id]);
$users = $stmt->fetch(PDO::FETCH_ASSOC);

if ($users) {

  $photo = $users['photo'];

}

$type = $_POST['type'] ?? '';

?>
<!-- Style mis par Espéro pour faire des tests -->
<link rel="stylesheet" href="../assets/style.css?v=<?= time() ?>">
<!-- Style mis par Espéro pour faire des tests -->

<header>
  <!-- Barre de navigation -->
  <nav class="navbar navbar-expand-xl navbar-light px-5 shadow bg-primary">
    <a class="navbar-brand site text-light fs-4 fw-bolder" href="#">FreeBenin</a>


    <!-- Bouton responsive -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
      <!-- Liens -->
      <ul class="navbar-nav">
        <li class="nav-item">

          <a class="nav-link text-light" href="../front_projet_EDL/accueil.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-light" href="../front_projet_EDL/Demande.php">Effectuer une demande</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-light" href="../front_projet_EDL/Mesdemandes.php">Mes Demandes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-light" href="../front_projet_EDL/Mesmissions.php">Mes Missions</a>
        </li>
        <li class="nav-item me-2">
          <a class="nav-link text-light d-flex align-items-center" href="../notifications/notifications.php">
            Notifications
            <div class="position-relative ms-1">
              <i class="bi bi-bell-fill text-white"></i>
              <?php
              $stmt = $bdd->query('SELECT * FROM notifications WHERE is_read=0 AND user_id=' . $_SESSION['user_id']);
              $nbr_notifications = $stmt->rowCount();
              ?>

              <?php if ($nbr_notifications != 0): ?>
                <span class="badge bg-danger badge-counter"><?= $nbr_notifications ?>+</span>
              <?php endif; ?>
            </div>
          </a>

        </li>
        <li class="nav-item">
          <a class="nav-link text-light d-flex align-items-center" href="../messagerie/discussions.php">
            <span>Messages</span>
            <?php
            $stmt = $bdd->query('SELECT sender_id FROM messages WHERE receiver_id =' . $user_id . ' AND lu = 0 GROUP BY sender_id');
            ?>
            <?php if ($stmt->rowCount() != 0): ?>
              <span class="badge badge-center h-px-20 w-px-20 bg-light ms-1 text-primary"><?= $stmt->rowCount() ?></span>
            <?php endif; ?>
          </a>
        </li>

              <li class="nav-item">
  <a class="nav-link fw-semibold text-white d-flex align-items-center gap-2" href="../Paiement/paiement.html">
    <i class="bi bi-star-fill fs-5 text-white"></i> <span>S’abonner</span>
  </a>
</li>


      </ul>

      <!-- Barre de recherche -->
      <div class=" group group-merge">
        <a class="btn btn-light border-0"><i class="bi bi-search" style="color:black"></i></a>
        <a href="../front_projet_EDL/recherche.php" class=" btn btn-light">Recherche</a>
      </div>


      <!-- Profil avec dropdown -->
      <div class="dropdown ms-3">
        <a class="d-flex align-items-center text-decoration-none dropdown-toggle text-light" href="#"
          id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <?php if ($users['role'] !== 'entreprise'): ?>
            <img src="../photo_profile/<?= isset($photo) ? htmlspecialchars($photo) : "photo_profile.jpg " ?>"
              alt="Profil" class="rounded-circle me-2 mt-2" width="40px" height="40px">
          <?php elseif ($users['role'] === 'entreprise'): ?>
            <img src="../logo/<?= htmlspecialchars($photo) ?>" alt="Logo" class="rounded-circle me-2 mt-2" width="40px"
              height="40px">
          <?php endif; ?>

          <span class="text-light">
            <?php echo isset($user_name) ? htmlspecialchars($user_name) : "Profile" ?></span>

        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li>
            <?php if ($users['role'] !== 'entreprise'): ?>
              <a class="dropdown-item" href="../front_projet_EDL/info_profile.php">Mon Profil</a>
            <?php elseif ($users['role'] === 'entreprise'): ?>
              <a class="dropdown-item" href="../front_projet_EDL/info_profile_entreprise.php">Mon Profil</a>
            <?php endif; ?>
          </li>

          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item text-danger" href="../front_projet_EDL/deconnexion.php">Déconnexion</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>