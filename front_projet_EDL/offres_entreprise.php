<?php
session_start();
require_once(__DIR__ . "/../bdd/creation_bdd.php");

$entreprise_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stm = $bdd->prepare("SELECT * FROM inscription WHERE id = ?");
$stm->execute([$entreprise_id]);
$comp = $stm->fetch(PDO::FETCH_ASSOC);

$stm_offres = $bdd->prepare("SELECT * FROM offres_entreprise WHERE entreprise_id = ? AND statut = 'active'");
$stm_offres->execute([$entreprise_id]);
$offres = $stm_offres->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos services - <?= htmlspecialchars($comp['nom']) ?></title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . "/header.php"); ?>
    <main class="container bg-white my-4 flex-fill p-4">
        <h2>Nos services - <?= htmlspecialchars($comp['nom']) ?></h2>
        <?php if (empty($offres)): ?>
            <p>Aucun service disponible pour le moment.</p>
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
                                <a href="../messagerie/discussions.php?user_id=<?= $entreprise_id ?>" class="btn btn-info">Nous contacter</a>
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