<?php
// Connexion à la base de données
require_once(__DIR__ . "/../../bdd/creation_bdd.php");

// Requête SQL pour récupérer tous les signalements de demandes
$requete = $bdd->prepare("
    SELECT 
        s.id AS signalement_id,
        s.raison,
        s.date_signalement,
        d.titre AS titre_demande,
        d.description AS description_demande,
        cible.nom AS nom_auteur,
        cible.prenom AS prenom_auteur,
        cible.nomDUtilisateur AS pseudo_auteur,
        signaleur.nom AS nom_signaleur,
        signaleur.prenom AS prenom_signaleur,
        signaleur.nomDUtilisateur AS pseudo_signaleur
    FROM signalements s
    INNER JOIN demande d ON s.demande_id = d.id
    INNER JOIN inscription cible ON d.user_id = cible.id
    INNER JOIN inscription signaleur ON s.signale_par = signaleur.id
    ORDER BY s.date_signalement DESC
");
$requete->execute();
$signalements = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>📝 Signalements de Demandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h2 class="text-center mb-5">📝 Signalements de Demandes</h2>

        <?php if ($signalements): ?>
            <?php foreach ($signalements as $signalement): ?>
                <div class="card shadow-sm mb-4 border-start border-4 border-danger-subtle">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?= htmlspecialchars($signalement['titre_demande']) ?></h5>
                        <p class="mb-2">
                            <strong>Description :</strong> <?= nl2br(htmlspecialchars($signalement['description_demande'])) ?>
                        </p>
                        <p class="mb-2">
                            <strong>Posté par :</strong> 
                            <?= htmlspecialchars($signalement['nom_auteur'] . ' ' . $signalement['prenom_auteur']) ?>
                            <span class="text-muted">(<?= htmlspecialchars($signalement['pseudo_auteur']) ?>)</span>
                        </p>
                        <p class="mb-2">
                            <strong>Signalé par :</strong> 
                            <?= htmlspecialchars($signalement['nom_signaleur'] . ' ' . $signalement['prenom_signaleur']) ?>
                            <span class="text-muted">(<?= htmlspecialchars($signalement['pseudo_signaleur']) ?>)</span>
                        </p>
                        <p class="mb-2">
                            <strong>Raison du signalement :</strong> <?= htmlspecialchars($signalement['raison']) ?>
                        </p>
                        <p class="text-muted">
                            <small>Signalé le <?= date('d/m/Y à H:i', strtotime($signalement['date_signalement'])) ?></small>
                        </p>
                        <form action="traiter_signalement.php" method="POST" class="d-flex gap-2 align-items-center">
                        <input type="hidden" name="demande_id" value="<?= $signalement['signalement_id'] ?>">
                        <input type="hidden" name="action_type" id="action-type-<?= $signalement['signalement_id'] ?>">

                        <select class="form-select form-select-sm w-auto" onchange="
                            document.getElementById('action-type-<?= $signalement['signalement_id'] ?>').value = this.value;
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
            <div class="alert alert-info text-center">
                Aucun signalement n’a été enregistré pour le moment.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
