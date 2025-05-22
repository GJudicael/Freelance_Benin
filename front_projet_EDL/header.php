 
<header>
<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-primary-subtle px-5 shadow static-top">
  <a class="navbar-brand site text-secondary fs-4 fw-bolder" href="#">FreeBenin</a>

  <!-- Bouton responsive -->
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
    <!-- Liens -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="accueil.php">Accueil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Demande.php">Effectuer une demande</a>
      </li>
    </ul>

    <!-- Barre de recherche -->
    <form class="d-flex" role="search">
      <input class="form-control me-2" type="search" placeholder="Recherche" aria-label="Search">
      <button class="btn btn-outline-secondary" type="submit">
        <i class="bi bi-search"></i>
      </button>
    </form>

    <!-- Profil avec dropdown -->
    <div class="dropdown ms-3">
      <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="../PHP/<?= htmlspecialchars($_SESSION['photo']) ?>"  alt="Profil" class="rounded-circle me-2 mt-2" width="40px" height="40px">
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

