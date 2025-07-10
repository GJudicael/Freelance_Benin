<?php
require_once(__DIR__."/../../bdd/creation_bdd.php");

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4 text-danger">👤 Signalements de Profils</h2>

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
        <div class="alert alert-info text-center">Aucun signalement de profil pour le moment.</div>
    <?php endif; ?>
</div>
</body>
</html>
