<?php
session_start();
if (!isset($_SESSION["connecte"]) || $_SESSION["connecte"] !== true) {
  header('Location: ../index.php');
  exit();
}
require_once(__DIR__ . "/../bdd/creation_bdd.php"); // Connexion DB

$user_id = $_SESSION['user_id'];

// Vérifie si un profil existe
$check = $bdd->prepare("SELECT * FROM freelancers WHERE user_id = ?");
$check->execute([$user_id]);
$freelancer = $check->fetch();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $bio = $_POST['bio'] ?? '';
  $competences = $_POST['competences'] ?? '';
  $gitHub = $_POST['gitHub'] ?? '';
  $linkdin = $_POST['linkdin']  ?? '';

  if ($freelancer) {
    $update = $bdd->prepare("UPDATE freelancers SET bio = ?, competences = ?, gitHub = ?, linkdin = ? WHERE user_id = ?");
    $update->execute([$bio, $competences, $gitHub, $linkdin, $user_id]);


  } else {


    if (!empty($gitHub) && !filter_var($gitHub, FILTER_VALIDATE_URL)) {
      $errors['gitHub'] = "L'URL fournie pour gitHub n'est pas valide.";
    } elseif (!empty($linkdin) && !filter_var($linkdin, FILTER_VALIDATE_URL)) {
      $errors['linkdin'] = "L'URL fournie pour linkdin n'est pas valide.";
    } else {
      $insert = $bdd->prepare("INSERT INTO freelancers (user_id, bio, competences, gitHub, linkdin) VALUES (?, ?, ?, ?, ?)");
      $insert->execute([$user_id, $bio, $competences, $gitHub, $linkdin]);

    }


  }

  if (isset($_POST['switch_role']) && $_POST['switch_role'] === 'freelancer') {
    $update = $bdd->prepare("UPDATE inscription SET role = 'freelance' WHERE id = ?");
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

<body class="d-flex flex-column min-vh-100">


  <?php require_once(__DIR__ . "/header.php") ?>

  <main class="flex-fill">
    <div class="container mt-5">


      <h3 class=" text-center text-success">Compléter votre profil Freelance</h3>

      <form method="POST">
        <div class="mb-3">
          <label for="bio" class="form-text text-black fs-6">Domaine(s) de spécialité</label>
          <textarea name="bio" class="form-control" rows="2"
            class="form-control"><?= htmlspecialchars($freelancer['bio'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label for="competences" class="form-text text-black fs-6">Compétences (séparées par des virgules)</label>
          <input type="text" name="competences" class="form-control"
            value="<?= htmlspecialchars($freelancer['competences'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label for="gitHub" class="form-text text-black fs-6">Lien gitHub</label>
          <input type="url" name="gitHub" class="form-control" pattern="https?://github\.com/.+"
            placeholder="https://github.com/nom_utilisateur"
            value=" <?= htmlspecialchars($freelancer['gitHub'] ?? '') ?>">
          <?php if (isset($errors["gitHub"])): ?>
            <p><small class="text-danger"><?= htmlspecialchars($errors["gitHub"]) ?></small></p>
          <?php endif; ?>

        </div>

        <div class="mb-3">
          <label for="linkdin" class="form-text text-black fs-6">Linkdin</label>
          <input type="url" name="linkdin" class="form-control" pattern="https?://www\.?linkdin\.com/in/.+"
            placeholder="https://www.linkdin.com/in/nom_utilisateur"
            value="<?= htmlspecialchars($freelancer['linkdin'] ?? '') ?>">
          <?php if (isset($errors["linkdin"])): ?>
            <p><small class="text-danger"><?= htmlspecialchars($errors["linkdin"]) ?></small></p>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
      </form>




      <?php if ($freelancer): ?>
        <hr class="my-5">

        <h4 class=" text-info fw-bolder">Aperçu du profil</h4>

        <div class="card mt-3 border-3 border-primary-subtle shadow">
          <div class="card-body">
            <h5 class="card-title">Domaine(s) de spécialité</h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($freelancer['bio'])) ?></p>

            <h5 class="card-title">Compétences</h5>
            <p class="card-text"><?= htmlspecialchars($freelancer['competences']) ?></p>

            <h5 class="card-title">GitHub</h5>
            <p class="card-text"><?= htmlspecialchars($freelancer['gitHub']) ?></p>

            <h5 class="card-title">Linkdin</h5>
            <p class="card-text"><?= htmlspecialchars($freelancer['linkdin']) ?></p>
          </div>
        </div>


      <?php endif; ?>
      <a href="info_profile.php" class=" mt-3 btn btn-info"> Voir mon profil </a>

    </div>
  </main>
  <?php require_once(__DIR__ . "/footer.php") ?>




  <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>