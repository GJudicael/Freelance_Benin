 <?php 
  require_once(__DIR__."/../bdd/creation_bdd.php");
  require_once(__DIR__ . "/../PHP/update_profile.php");

  
  $user_id = $_SESSION['user_id'];

  $stmt = $bdd->prepare("SELECT photo FROM inscription WHERE id = ?");
  $stmt->execute([$user_id]);
  $users = $stmt->fetch(PDO::FETCH_ASSOC); 

  $photo = $users["photo"];
  
  $type = $_POST['type'] ?? '';

?>
<header>
<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-light px-5 shadow static-top bg-info-subtle">
  <a class="navbar-brand site text-secondary fs-4 fw-bolder" href="#">FreeBenin</a>

  <!-- Bouton responsive -->
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
    <!-- Liens -->
     <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="../front_projet_EDL/accueil.php">Accueil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../front_projet_EDL/Demande.php">Effectuer une demande</a>
      </li>
       <li class="nav-item">
        <a class="nav-link" href="../front_projet_EDL/Mesdemandes.php">Mes Demandes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../front_projet_EDL/Mesmissions.php">Mes Missions</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../messagerie/discussions.php">Messagerie</a>
      </li>
    </ul>

    <!-- Barre de recherche -->
    
      
      <a href="recherche.php" class="btn btn-outline-light border-0"><i class="bi bi-search" style="color:black"></i></a>
      
    <!-- Profil avec dropdown -->
    <div class="dropdown ms-3">
      <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="../photo_profile/<?= isset($photo)? htmlspecialchars($photo) : "photo_profile.jpg " ?>"  alt="Profil" class="rounded-circle me-2 mt-2" width="40px" height="40px">
        <span> <?php echo isset($_SESSION["user_name"])? htmlspecialchars($_SESSION["user_name"]): "Profile" ?></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
        <li><a class="dropdown-item" href="info_profile.php">Mon Profil</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="deconnexion.php">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>
</header>

