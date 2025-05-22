<?php
require_once(__DIR__."/../bdd/creation_bdd.php");

$result = $bdd->query("SELECT i.nom ,i.prenom, d.description, d.categorie 
FROM demande d
INNER JOIN  inscription i 
ON i.id = d.user_id
ORDER BY description DESC");

$demandes = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($demandes as $demande){
       
?>

<div class="container py-4 ">
    <div class="row justify-content-center">
      <!-- Carte 1 -->
        <div class="col-lg-8 col-md-12 ">
            <div class="card h-100">
                <div class="card-body">
                    <div class="user-info"><i class="bi bi-person-fill"></i> Posté par : <a href="info_profile.php" class="text-decoration-none text-tertiary"> <strong> <?php echo htmlspecialchars($demande["nom"]) ; echo ' '. htmlspecialchars($demande["prenom"]) ?></strong> </a></div>
                    <h5 class="card-title text-secondaryg"> <?= htmlspecialchars($demande["categorie"]) ?></h5>
                    <p class="card-text text-muted"> <?= htmlspecialchars($demande["description"]) ?></p>
            
                </div>
            </div>
        </div>
    </div>
</div>  
<?php
}
?>