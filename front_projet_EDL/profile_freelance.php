<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php"); // Connexion DB

$user_id = $_SESSION['user_id'];

// Vérifie si un profil existe
$check = $bdd->prepare("SELECT * FROM freelancers WHERE user_id = ?");
$check->execute([$user_id]);
$freelancer = $check->fetch();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio'] ?? '';
    $competences = $_POST['competences'] ?? '';

    if ($freelancer) {
        $update = $bdd->prepare("UPDATE freelancers SET bio = ?, competences = ? WHERE user_id = ?");
        $update->execute([$bio, $competences, $user_id]);
    } else { 
        $insert = $bdd->prepare("INSERT INTO freelancers (user_id, bio, competences) VALUES (?, ?, ?)");
        $insert->execute([$user_id, $bio, $competences]);
    }

     if (isset($_POST['switch_role']) && $_POST['switch_role'] === 'freelancer') {
        $update = $bdd->prepare("UPDATE inscription SET role = 'freelancer' WHERE id = ?");
        $update->execute([$user_id]);
    
    }

    header("Location:profile_freelance.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Profil Freelance</title>

   <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="../assets/style.css">


    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>
<body>

    <?php require_once(__DIR__."/header.php")?>
    <div class="container mt-5">

  <h3 class=" text-center text-dark-emphasis">Compléter votre profil Freelance</h3>

  <form method="POST">
    <div class="mb-3">
      <label for="bio" class="form-text text-black fs-6">Présentation</label>
      <textarea name="bio" class="form-control" rows="4" class="form-control"><?= htmlspecialchars($freelancer['bio'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label for="competences" class="form-text text-black fs-6" >Compétences (séparées par des virgules)</label>
      <input type="text" name="competences" class="form-control" value="<?= htmlspecialchars($freelancer['competences'] ?? '') ?>">
    </div>

    <button type="submit" class="btn btn-success">Enregistrer</button>
  </form>


  <a href="ajouter_projet.php" class="btn btn-outline-primary mt-4">Ajouter un projet</a>

  <?php if ($freelancer) : ?>
    <hr class="my-5">

    <h4 class=" text-info">Aperçu du profil</h4>

    <div class="card mt-3 border-1 border-secondary">
      <div class="card-body">
        <h5 class="card-title">Biographie</h5>
        <p class="card-text"><?= nl2br(htmlspecialchars($freelancer['bio'])) ?></p>

        <h5 class="card-title">Compétences</h5>
        <p class="card-text"><?= htmlspecialchars($freelancer['competences']) ?></p>
      </div>
    </div>
  <?php endif; ?>

  <a href="info_profile.php" class=" mt-3 btn btn-info"> Voir mon profil </a>
</div>



<?php require_once(__DIR__."/footer.php")?>
<script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>
