<?php
require_once(__DIR__."/../bdd/creation_bdd.php");
require_once(__DIR__."/../PHP/update_profile.php");

$result = $bdd->query("SELECT i.id, i.nom ,i.prenom, d.description, d.titre, d.date_soumission
FROM demande d
INNER JOIN  inscription i 
ON i.id = d.user_id WHERE d.statut = 'en attente'
ORDER BY date_soumission DESC");

$demandes = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($demandes as $demande){
       
?>

<div class="container py-4 ">
    <div class="row justify-content-center ">
      <!-- Carte 1 -->
        <div class="col-lg-8 col-md-12">
            <div class="card h-100 p-2 shadow border-primary-subtle border-3 rounded-4">
                <div class="card-body  overflow-auto">
                    <div class="user-info pb-3"><i class="bi bi-person-fill"></i> Posté par : <a href="info_profile.php?id=<?= htmlspecialchars($demande['id'])?>" class="text-decoration-none text-tertiary"> <strong> <?php echo htmlspecialchars($demande["nom"]) ; echo ' '. htmlspecialchars($demande["prenom"]) ?></strong> </a></div>
                    <h5 class="card-title text-secondaryg"> <?= htmlspecialchars($demande["titre"]) ?></h5>
                    <p class="card-text text-muted"> <?= htmlspecialchars($demande["description"]) ?></p>
            
                </div>
            </div>
        </div>
    </div>
</div>  
<?php
}
?>