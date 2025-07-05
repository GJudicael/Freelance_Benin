<?php
    
require_once(__DIR__."/../bdd/creation_bdd.php");
require_once(__DIR__."/../PHP/update_profile.php");
if(!isset($_SESSION["connecte"]) || $_SESSION["connecte"]!== true){
        header('Location: ../index.php');
        exit();
    }
function freelancers($bdd, $rechercher) {
    
    $stmt = $bdd->prepare("SELECT i.id, i.nom, i.prenom, f.bio, f.competences
                        FROM freelancers f
                        INNER JOIN inscription i ON i.id = f.user_id
                        WHERE f.bio = ?");
    $stmt->execute([$rechercher]);
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($demandes as $demande) {
?>
            <div class="container py-4 ">
                <div class="row justify-content-center ">
                    <!-- Carte -->
                    <div class="col-lg-8 col-md-12">
                        <div class="card h-100 p-2 shadow border-primary-subtle border-3 rounded-4">
                            <div class="card-body overflow-auto">
                                <div class="user-info pb-3">

                                    <a href="info_profile.php?id=<?= htmlspecialchars($demande['id']) ?>" class="text-decoration-none text-tertiary">
                                        <strong><?= htmlspecialchars($demande["nom"]) . ' ' . htmlspecialchars($demande["prenom"]) ?></strong>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
}
?>
