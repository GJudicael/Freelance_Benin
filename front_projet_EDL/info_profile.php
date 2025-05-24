<?php

require_once(__DIR__."/../bdd/creation_bdd.php");
require_once(__DIR__."/../PHP/upload_photo.php");
require_once(__DIR__."/../PHP/update_profile.php");

// Simulation d'utilisateur connecté (à remplacer par session et requête réelle)
$user_id = $_SESSION['user_id']; 

$stmt = $bdd->prepare("SELECT nom, prenom, email, numero, nomDUtilisateur, photo, role FROM inscription WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$check = $bdd->prepare("SELECT * FROM freelancers WHERE user_id = ?");
$check->execute([$user_id]);
$freelancer = $check->fetch();

$_SESSION['photo'] = $user['photo'];
    
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profile</title>
   
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="../assets/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
  
</head>
<body>

<?php require_once(__DIR__."/header.php")?>

<div class="container py-5">
  <h2 class="mb-4">Mon profil</h2>

  <div class="row">
    <!-- PHOTO -->
    <div class="col-md-4 text-center">
      <img src="../photo_profile/<?= htmlspecialchars($_SESSION['photo']) ?>" class=" mb-3 rounded-circle" width="150px" height="150px" alt="Photo de profil">
      <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="photo" class="form-control mb-2" accept="image/*">
        <p> <small class="text-danger"> <?php echo isset($message)? htmlspecialchars($message): "" ; 
            echo isset($erreur["format"]) ? htmlspecialchars($erreur["format"]):"" ; 
            echo isset($erreur["fichier"])? htmlspecialchars($erreur["fichier"]):"" ; ?>
        </small></p>
        <button type="submit" name ="changer" class="btn btn-primary btn-sm">Changer la photo</button>
      </form>
    </div>

    <!-- INFOS -->
    <div class="col-md-8">
      <!-- Affichage simple -->
      <div id="infos-affichage">
        <?php if(isset($succes)){ echo '<div class="alert alert-success">'. htmlspecialchars($succes) .' </div>';} ?>
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
        <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['nomDUtilisateur']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Numéro de téléphone :</strong> <?= htmlspecialchars($user['numero']) ?></p>
        <?php if ($freelancer) : ?>

            <p> <strong> Biographie</strong></p>
            <p><?= nl2br(htmlspecialchars($freelancer['bio'])) ?></p>

            <p> <strong> Compétences </strong></p>
            <p><?= htmlspecialchars($freelancer['competences']) ?></p>
          
        
        <?php endif; ?>
        <button class="btn btn-outline-primary" onclick="afficherFormulaire()">Modifier mes informations</button>
        <?php if ($user['role'] === 'client') : ?>
          <div class="alert alert-info mt-4">
            Vous êtes actuellement en mode <strong>Client</strong>.

            <form method="POST" action="profile_freelance.php" class="d-inline">
              <input type="hidden" name="switch_role" value="freelancer">
              <button type="submit" class="btn btn-sm btn-primary ms-3">Passer en mode Freelance</button>
            </form>
          </div>
        <?php else : ?>
          <div class="alert alert-success mt-4">
            Vous êtes en mode <strong>Freelancer</strong>
          </div>
          <p><a href="profile_freelance.php" class="btn btn-info"> Complèter mon profile </a></p>
        <?php endif; ?>

       
        
      </div>

      <!-- Formulaire caché -->
      <div id="infos-formulaire" style="display: none;">
        <form action="" method="post">
          <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>">
            
          </div>
          <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Nom d'utilisateur</label>
            <input type="text" name="nomDUtilisateur" class="form-control" value="<?= htmlspecialchars($user['nomDUtilisateur']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Numéro</label>
            <input type="tel" name="numero" class="form-control" value="<?= htmlspecialchars($user['numero']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Mot de passe <small>(laisser vide pour ne pas changer)</small></label>
            <input type="password" name="password" class="form-control" placeholder="Nouveau mot de passe">
          </div>
          <button type="submit" name="enregistrer" class="btn btn-success">Enregistrer</button>
          <button type="button"  class="btn btn-secondary" onclick="annulerFormulaire()">Annuler</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function afficherFormulaire() {
    document.getElementById('infos-affichage').style.display = 'none';
    document.getElementById('infos-formulaire').style.display = 'block';
  }

  function annulerFormulaire() {
    document.getElementById('infos-formulaire').style.display = 'none';
    document.getElementById('infos-affichage').style.display = 'block';
  }
</script>
<script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>


</body>
</html>
