<?php
require_once(__DIR__."/../bdd/creation_bdd.php");
require_once(__DIR__."/../PHP/update_profile.php");
require_once(__DIR__."/signaler_demande.php");
    

$user_id = $_SESSION["user_id"];
$result = $bdd->prepare("SELECT i.id, i.nom ,i.prenom, i.admin, d.description, d.titre, d.date_soumission, d.budget , d.date_souhaitee, d.id as id_demande
FROM demande d
INNER JOIN inscription i ON i.id = d.user_id
WHERE d.statut = 'en attente' AND d.user_id != :id
ORDER BY date_soumission DESC");

$result->execute(['id' => $user_id]);

$demandes = $result->fetchAll(PDO::FETCH_ASSOC);

if ($demandes) {
    foreach ($demandes as $index => $demande) {
        
?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12">
            <div class="card h-100 p-2 shadow border-primary-subtle border-3 rounded-4">
                <div class="card-body overflow-auto">
                    <div class="user-info pb-3">
                        <i class="bi bi-person-fill"></i> Posté par :
                        <a href="info_profile.php?id=<?= htmlspecialchars($demande['id']) ?>" class="text-decoration-none text-tertiary">
                            <strong><?= htmlspecialchars($demande["nom"]) . ' ' . htmlspecialchars($demande["prenom"]) ?></strong>
                        </a>
                    </div>
                    <h5 class="card-title text-secondaryg"><?= ($demande["titre"]) ?></h5>
                    <p class="card-text text-muted"><?= ($demande["description"]) ?></p>
                    <p class="card-text text-muted"><strong> Budget :</strong><?= htmlspecialchars($demande["budget"]) ?></p>
                    <p class="card-text text-muted"><strong> Date de soumission au plus tard du projet :</strong><?= htmlspecialchars($demande["date_souhaitee"]) ?></p>

                    <!-- Bouton Signaler -->
                    <?php if($demande['admin'] !== "admin"): ?>

                        <button class="btn btn-outline-danger btn-sm" onclick="toggleSignalement(<?= $index ?>)"> Signaler</button>
                    <?php endif;?>

                    <!-- Formulaire de signalement -->
                    <form action="" method="POST" class="mt-3 d-none" id="form-signalement-<?= $index ?>">
                        <input type="hidden" name="demande_id" value="<?= htmlspecialchars($demande['id_demande']) ?>">
                        <textarea name="raison" class="form-control mb-2" rows="3" placeholder="Expliquez la raison du signalement..." required ></textarea>
                        <p> <small class="text-danger"> <?php echo isset($erreur["raison"])? htmlspecialchars($erreur["raison"]) : ""?> </small></p> 
                        <button type="submit" class="btn btn-danger btn-sm">Envoyer le signalement</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
<?php
    }
} else {
    echo '<p class="text-center mt-3">Aucune demande disponible</p>';
}
?>

<!-- Script pour afficher/masquer le champ de signalement -->
<script>
function toggleSignalement(index) {
    const form = document.getElementById('form-signalement-' + index);
    form.classList.toggle('d-none');
}
</script>
