<?php
session_start();
require_once(__DIR__ . "/../bdd/creation_bdd.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion/connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $categorie = $_POST['categorie'] ?? '';
    $image = null;

    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/offres/';
        $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image = $image_name;
        }
    }

    $stmt = $bdd->prepare("INSERT INTO offres_entreprise (entreprise_id, titre, description, categorie, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $titre, $description, $categorie, $image]);

    header("Location: info_profile_entreprise.php?id=" . $_SESSION['user_id']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une offre</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . "/header.php"); ?>
    <main class="container bg-white my-4 flex-fill p-4">
        <h2>Ajouter une nouvelle offre pour les clients</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre de l’offre</label>
                <input type="text" name="titre" id="titre" class="form-control" required placeholder="Ex: Construction de bâtiments modernes">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" required placeholder="Décrivez ce que votre entreprise propose..."></textarea>
            </div>
            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <select name="categorie" id="categorie" class="form-control" required>
                    <option value="" disabled selected>Choisissez une catégorie</option>
                    <option value="Génie civil">Génie civil</option>
                    <option value="Architecture">Architecture</option>
                    <option value="Design intérieur">Design intérieur</option>
                    <option value="Développement logiciel">Développement logiciel</option>
                    <!-- Ajoutez d'autres catégories selon vos besoins -->
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image (optionnel)</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-success">Enregistrer l’offre</button>
            <a href="profile_entreprise.php?id=<?= $_SESSION['user_id'] ?>" class="btn btn-secondary">Annuler</a>
        </form>
    </main>
    <?php require_once(__DIR__ . "/footer.php"); ?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>