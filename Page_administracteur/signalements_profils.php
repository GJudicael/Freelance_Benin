<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

$stmt = $bdd->prepare("
    SELECT s.id AS signalement_id, s.raison, s.date_signalement,
           cible.nom AS nom_cible, cible.prenom AS prenom_cible, cible.email, cible.nomDUtilisateur AS pseudo_cible,
           auteur.nom AS nom_auteur, auteur.prenom AS prenom_auteur, auteur.nomDUtilisateur AS pseudo_auteur
    FROM signalements_profil s
    INNER JOIN inscription cible ON s.utilisateur_id = cible.id
    INNER JOIN inscription auteur ON s.signale_par = auteur.id
    ORDER BY s.date_signalement DESC
");
$stmt->execute();
$signalements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>👤 Signalements de profils</title>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="../assets/style.css">


    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    <?php require_once(__DIR__."/../front_projet_EDL/header.php") ?>

    <main class="container py-4 flex-fill">
        <h3 class="my-5 text-danger text-center">👤 Profils signalés</h3>

        <?php if ($signalements): ?>
            <?php foreach ($signalements as $s): ?>
                <div class="card mb-3 border-start border-4 border-danger-subtle shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-dark">
                            Profil : <?= htmlspecialchars($s['nom_cible'] . ' ' . $s['prenom_cible']) ?>
                            <span class="text-muted">(<?= htmlspecialchars($s['pseudo_cible']) ?>)</span>
                        </h5>
                        <p class="mb-1"><strong>Email :</strong> <?= htmlspecialchars($s['email']) ?></p>
                        <p class="mb-1"><strong>Signalé par :</strong> <?= htmlspecialchars($s['nom_auteur'] . ' ' . $s['prenom_auteur']) ?>
                            <span class="text-muted">(<?= htmlspecialchars($s['pseudo_auteur']) ?>)</span>
                        </p>
                        <p class="mb-1"><strong>Raison :</strong> <?= htmlspecialchars($s['raison']) ?></p>
                        <p class="text-muted"><small>Signalé le <?= date('d/m/Y H:i', strtotime($s['date_signalement'])) ?></small></p>

                        <form action="traiter_signalement_profil.php" method="POST" class="mt-3 d-flex gap-2 align-items-center">
                            <input type="hidden" name="signalement_id" value="<?= $s['signalement_id'] ?>">
                            <input type="hidden" name="action_type" id="action-type-<?= $s['signalement_id'] ?>">

                            <select class="form-select form-select-sm w-auto" onchange="
                                document.getElementById('action-type-<?= $s['signalement_id'] ?>').value = this.value;
                                this.form.submit();
                            ">
                                <option selected disabled>⚙️ Action</option>
                                <option value="retablir">🟢 Rétablir</option>
                                <option value="supprimer">🗑 Supprimer</option>
                            </select>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-center p-4">Aucun signalement de profil pour le moment.</div>
        <?php endif; ?>
        </main>

    
    <?php require_once(__DIR__."/../front_projet_EDL/footer.php") ?>
   
    
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
