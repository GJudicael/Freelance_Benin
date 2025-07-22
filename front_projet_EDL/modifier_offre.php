<?php
session_start();
require_once(__DIR__ . "/../bdd/creation_bdd.php");

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: ../connexion/connexion.php");
    exit();
}

$offre_id = (int)$_GET['id'];
$stm = $bdd->prepare("SELECT * FROM offres_entreprise WHERE id = ? AND entreprise_id = ?");
$stm->execute([$offre_id, $_SESSION['user_id']]);
$offre = $stm->fetch(PDO::FETCH_ASSOC);

if (!$offre) {
    header("Location: gerer_offres.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $categorie = $_POST['categorie'] ?? '';
    $image = $offre['image'];

    // Gestion de l'upload d'une nouvelle image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/offres/';
        $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Supprimer l'ancienne image si elle existe
            if ($image && file_exists($upload_dir . $image)) {
                unlink($upload_dir . $image);
            }
            $image = $image_name;
        }
    }

    $stmt = $bdd->prepare("UPDATE offres_entreprise SET titre = ?, description = ?, categorie = ?, image = ? WHERE id = ? AND entreprise_id = ?");
    $stmt->execute([$titre, $description, $categorie, $image, $offre_id, $_SESSION['user_id']]);

    header("Location: gerer_offres.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un service</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . "/header.php"); ?>
    <main class="container bg-white my-4 flex-fill p-4">
        <h2>Modifier un service</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre de l’offre</label>
                <input type="text" name="titre" id="titre" class="form-control" value="<?= htmlspecialchars($offre['titre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" required><?= htmlspecialchars($offre['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <select name="categorie" id="categorie" class="form-control" required>
                    <option value="Génie civil" <?= $offre['categorie'] === 'Génie civil' ? 'selected' : '' ?>>Génie civil</option>
                    <option value="Architecture" <?= $offre['categorie'] === 'Architecture' ? 'selected' : '' ?>>Architecture</option>
                    <option value="Design intérieur" <?= $offre['categorie'] === 'Design intérieur' ? 'selected' : '' ?>>Design intérieur</option>
                    <option value="Développement logiciel" <?= $offre['categorie'] === 'Développement logiciel' ? 'selected' : '' ?>>Développement logiciel</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image (optionnel)</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <?php if ($offre['image']): ?>
                    <p>Image actuelle : <img src="../uploads/offres/<?= htmlspecialchars($offre['image']) ?>" alt="Image actuelle" style="max-width: 100px; margin-top: 10px;"></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
            <a href="gerer_offres.php" class="btn btn-secondary">Annuler</a>
        </form>
    </main>
    <?php require_once(__DIR__ . "/footer.php"); ?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>