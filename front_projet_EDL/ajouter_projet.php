<?php session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

// Remplace par la vraie session
$user_id = $_SESSION['user_id'];

// Récupérer l'ID du freelance lié à l'utilisateur
$stmt = $bdd->prepare("SELECT id FROM freelancers WHERE user_id = ?");
$stmt->execute([$user_id]);
$freelancer = $stmt->fetch();

if (!$freelancer) {
  die("Profil freelance introuvable.");
}

$freelancer_id = $freelancer['id'];
$erreurs = [];

$titre = $_POST['titre'] ?? '';
$description = $_POST['description'] ?? '';
$lien = $_POST['lien'] ?? '';
$date_projet = $_POST['date_projet'] ?? '';

$image_name = null;

if (!empty($_FILES['image']['name'])) {
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Crée le dossier s'il n'existe pas
    }

    $file_tmp = $_FILES['image']['tmp_name'];
    $file_name = uniqid() . "_" . basename($_FILES['image']['name']);
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($file_tmp, $file_path)) {
        $image_name = $file_path;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($titre)) $erreurs['titre'] = "Titre du projet requis.";
    if (empty($description)) $erreurs['description'] = "Description du projet requis.";
    if (empty($lien)) $erreurs['lien'] = "Lien du projet requis.";
    if (empty($date_projet)) $erreurs['date'] = "Date du projet requis.";


    if (empty($erreurs)) {
        $stmt = $bdd->prepare("INSERT INTO projets (freelancer_id, titre, description, image, lien, date_projet)
                               VALUES (?, ?, ?, ?, ?,?)");
        $stmt->execute([$freelancer_id, $titre, $description, $image_name, $lien, $date_projet]);

        header("Location: profile_freelance.php"); // Retour au profil
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'ajout de projets</title>

    
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
   
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="../assets/style.css">


    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>
<body>

<?php require_once(__DIR__."/header.php")?>

<main class="container mt-5">

  <h3>Ajouter un projet</h3>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="titre" class="form-label">Titre du projet</label>
      <input type="text" name="titre" class="form-control" value="<?= isset($erreurs)? htmlspecialchars($titre):'' ?>">
      <p> <small class="text-danger"> <?= isset($erreurs['titre'])? htmlspecialchars($erreurs['titre']):'' ?> </small></p>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"><?= isset($erreurs)? htmlspecialchars($description):'' ?></textarea>
      <p> <small class="text-danger"> <?= isset($erreurs['description'])? htmlspecialchars($erreurs['description']):'' ?> </small></p>
    </div>

    <div class="mb-3">
      <label for="lien" class="form-label">Lien du projet (GitHub, site...)</label>
      <input type="url" name="lien" class="form-control" value="<?= isset($erreurs)? htmlspecialchars($lien):'' ?>">
      <p> <small class="text-danger"> <?= isset($erreurs['lien'])? htmlspecialchars($erreurs['lien']):'' ?> </small></p>
    </div>

    <div class="mb-3">
      <label for="date_projet" class="form-label">Date du projet</label>
      <input type="date" name="date_projet" class="form-control" value="<?= htmlspecialchars($date_projet) ?>">
      <p> <small class="text-danger"> <?= isset($erreurs['date'])? htmlspecialchars($erreurs['date']):'' ?> </small></p>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Image du projet</label>
        <input type="file" name="image" id="image" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="profile_freelance.php" class="btn btn-secondary">Retour</a>
  </form>

</main>

<?php require_once(__DIR__."/footer.php")?>
<script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>