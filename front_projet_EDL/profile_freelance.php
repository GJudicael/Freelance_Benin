<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php"); // Connexion DB

$user_id = $_SESSION['user_id'];

// Vérifie si un profil existe
$check = $bdd->prepare("SELECT * FROM freelancers WHERE user_id = ?");
$check->execute([$user_id]);
$freelancer = $check->fetch();

$smtp = $bdd->prepare('SELECT * FROM projets WHERE freelancer_id = ? ORDER BY date_projet DESC');
$smtp->execute([$freelancer['id']]);
$projets = $smtp->fetchAll();

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
<body>

    <?php require_once(__DIR__."/header.php")?>
    <div class="container mt-5">

  <h3 class=" text-center text-dark-emphasis">Compléter votre profil Freelance</h3>

  <form method="POST">
    <div class="mb-3">
      <label for="bio" class="form-text text-black fs-6">Présentation</label>
      <textarea name="bio" class="form-control" rows="2" class="form-control"><?= htmlspecialchars($freelancer['bio'] ?? '') ?></textarea>
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

    <h4 class=" text-info fw-bolder">Aperçu du profil</h4>

    <div class="card mt-3 border-3 border-primary-subtle shadow">
      <div class="card-body">
        <h5 class="card-title">Biographie</h5>
        <p class="card-text"><?= nl2br(htmlspecialchars($freelancer['bio'])) ?></p>

        <h5 class="card-title">Compétences</h5>
        <p class="card-text"><?= htmlspecialchars($freelancer['competences']) ?></p>
      </div>
    </div>

    <?php if (!empty($projets)) : ?>
  <hr class="my-5">
  <h3>Projets réalisés</h3>

  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-3">
    <?php foreach ($projets as $projet) : ?>
      <div class="col">
        <div class="card h-100">
          <?php if (!empty($projet['image'])) : ?>
            <img src="<?= htmlspecialchars($projet['image']) ?>" class="card-img-top" height="275px" alt="Image du projet">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($projet['titre']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($projet['description'])) ?></p>
            <?php if (!empty($projet['lien'])) : ?>
              <a href="<?= htmlspecialchars($projet['lien']) ?>" target="_blank" class="btn btn-outline-primary">Voir le projet</a>
            <?php endif; ?>
          </div>
          <div class="card-footer text-muted">
            Réalisé le : <?= htmlspecialchars($projet['date_projet']) ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else : ?>
  <p class="mt-4 text-muted">Aucun projet ajouté pour le moment.</p>
<?php endif; ?>
  <?php endif; ?>
<a href="info_profile.php" class=" mt-3 btn btn-info"> Voir mon profil </a>

</div>



<?php require_once(__DIR__."/footer.php")?>
<script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>
