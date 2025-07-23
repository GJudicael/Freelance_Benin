<?php
session_start();
require_once(__DIR__ . "/../bdd/creation_bdd.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion/connexion.php");
    exit();
}

$stm = $bdd->prepare("SELECT * FROM offres_entreprise WHERE entreprise_id = ?");
$stm->execute([$_SESSION['user_id']]);
$offres = $stm->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $bdd->prepare("UPDATE offres_entreprise SET statut = 'inactive' WHERE id = ? AND entreprise_id = ?");
    $stmt->execute([$_GET['delete'], $_SESSION['user_id']]);
    header("Location: gerer_offres.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer mes services</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . "/header.php"); ?>
    <main class="container bg-white my-4 flex-fill p-4">
        <h2>Gérer mes services</h2>
        <a href="ajouter_offre.php" class="btn btn-outline-primary mb-3">Ajouter un nouveau service</a>
        <?php if (empty($offres)): ?>
            <p>Vous n’avez aucun service pour le moment.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($offres as $offre): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card border-light shadow-sm">
                            <?php if ($offre['image']): ?>
                                <img src="../uploads/offres/<?= htmlspecialchars($offre['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($offre['titre']) ?>" style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($offre['titre']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($offre['description']) ?></p>
                                <p><strong>Catégorie :</strong> <?= htmlspecialchars($offre['categorie']) ?></p>
                                <p><strong>Statut :</strong> <?= $offre['statut'] === 'active' ? 'Actif' : 'Inactif' ?></p>
                                <a href="modifier_offre.php?id=<?= $offre['id'] ?>" class="btn btn-outline-primary">Modifier</a>
                                <a href="gerer_offres.php?delete=<?= $offre['id'] ?>" class="btn btn-outline-danger" onclick="return confirm('Désactiver ce service ?')">Désactiver</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    <?php require_once(__DIR__ . "/footer.php"); ?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>