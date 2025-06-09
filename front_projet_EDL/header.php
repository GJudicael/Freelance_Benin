<?php
require_once(__DIR__ . "/../bdd/creation_bdd.php");
require_once(__DIR__ . "/../PHP/update_profile.php");

$searchResults = [];
$type = $_GET['type'] ?? '';
$keywords = $_GET['keywords'] ?? '';
$keyword = "%$keywords%";

if (!empty($keywords)) {
    switch ($type) {
        case 'inscription':
            $stmt = $bdd->prepare("SELECT * FROM inscription WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? OR numero LIKE ? OR nomDUtilisateur LIKE ?");
            $stmt->execute([$keyword, $keyword, $keyword, $keyword, $keyword]);
            $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'freelancer':
            $stmt = $bdd->prepare("SELECT f.*, i.nom, i.prenom, i.id AS user_id FROM freelancers f JOIN inscription i ON f.user_id = i.id WHERE bio LIKE ? OR competences LIKE ?");
            $stmt->execute([$keyword, $keyword]);
            $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'demande':
            $stmt = $bdd->prepare("SELECT d.*, i.nom, i.prenom, i.id AS user_id FROM demande d JOIN inscription i ON d.user_id = i.id WHERE titre LIKE ? OR description LIKE ? OR categorie LIKE ?");
            $stmt->execute([$keyword, $keyword, $keyword]);
            $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        default:
            // Recherche globale sur les 3 tables
            $stmt1 = $bdd->prepare("SELECT * FROM inscription WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? OR numero LIKE ? OR nomDUtilisateur LIKE ?");
            $stmt1->execute([$keyword, $keyword, $keyword, $keyword, $keyword]);
            $res1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

            $stmt2 = $bdd->prepare("SELECT f.*, i.nom, i.prenom, i.id AS user_id FROM freelancers f JOIN inscription i ON f.user_id = i.id WHERE bio LIKE ? OR competences LIKE ?");
            $stmt2->execute([$keyword, $keyword]);
            $res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            $stmt3 = $bdd->prepare("SELECT d.*, i.nom, i.prenom, i.id AS user_id FROM demande d JOIN inscription i ON d.user_id = i.id WHERE titre LIKE ? OR description LIKE ? OR categorie LIKE ?");
            $stmt3->execute([$keyword, $keyword, $keyword]);
            $res3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

            $searchResults = array_merge($res1, $res2, $res3);
            break;
    }
}
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
    <form class="d-flex mb-4" method="get" action="">
    <input class="form-control me-2" type="search" name="keywords" value="<?= htmlspecialchars($keywords) ?>" placeholder="Recherche">
    <select name="type" class="form-select me-2">
        <option value="">Toutes catégories</option>
        <option value="inscription" <?= $type === 'inscription' ? 'selected' : '' ?>>Inscription</option>
        <option value="freelancer" <?= $type === 'freelancer' ? 'selected' : '' ?>>Freelancer</option>
        <option value="demande" <?= $type === 'demande' ? 'selected' : '' ?>>Demande</option>
    </select>
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
</form>
    <!-- Profil avec dropdown -->
    <div class="dropdown ms-3">
      <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="../photo_profile/<?= isset($_SESSION["photo"])? htmlspecialchars($_SESSION["photo"]) : "photo_profile.jpg " ?>"  alt="Profil" class="rounded-circle me-2 mt-2" width="40px" height="40px">
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

