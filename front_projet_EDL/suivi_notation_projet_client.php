<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

// Vérification que l'utilisateur est bien client et propriétaire de la demande
$stmt = $bdd->prepare("SELECT d.*, i.role 
                      FROM demande d
                      JOIN inscription i ON d.user_id = i.id
                      WHERE d.id = :demande_id AND d.user_id = :user_id");
$stmt->execute([
    ':demande_id' => $_GET['id'],
    ':user_id' => $_SESSION['user_id']
]);
$demande = $stmt->fetch();

if (!$demande) {
    $_SESSION['error'] = "Accès non autorisé ou demande introuvable";
    header("Location: Mesdemandes.php");
    exit();
}

// Récupération des infos du freelance
$stmtFreelance = $bdd->prepare("SELECT i.* 
                           FROM freelancers f
                           JOIN inscription i ON f.user_id = i.id
                           WHERE f.id = :freelancer_id");
$stmtFreelance->bindParam(':freelancer_id', $demande['freelancer_id'], PDO::PARAM_INT);
$stmtFreelance->execute();
$freelance = $stmtFreelance->fetch(PDO::FETCH_ASSOC);

// Récupération du suivi
$stmtSuivi = $bdd->prepare("SELECT * FROM suivi_projet 
                       WHERE demande_id = :demande_id
                       ORDER BY date_mise_a_jour DESC");
$stmtSuivi->bindParam(':demande_id', $demande['id'], PDO::PARAM_INT);
$stmtSuivi->execute();
$suivi = $stmtSuivi->fetchAll(PDO::FETCH_ASSOC);

// Vérification si notation déjà effectuée
$stmtNotation = $bdd->prepare("SELECT * FROM notation 
                          WHERE order_id = :demande_id AND user_id = :user_id");
$stmtNotation->bindParam(':demande_id', $demande['id'], PDO::PARAM_INT);
$stmtNotation->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmtNotation->execute();
$notation = $stmtNotation->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suivi du projet</title>
    <link href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    
</head>
<body>
    <?php require_once(__DIR__."/header.php") ?>
    
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-info-subtle text-secondary">
                        <h4>Suivi du projet : <?= htmlspecialchars($demande['titre']) ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-4">
                            <div>
                                <h5>Freelanceur : <?= htmlspecialchars($freelance['prenom'].' '.$freelance['nom']) ?> </h5>
                                
                            </div>
                            <div class="text-end">
                                <h5>Avancement:</h5>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?= $demande['avancement'] ?>%">
                                        <?= $demande['avancement'] ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h4 class="mb-3">Historique des mises à jour</h4>
                        <div class="timeline">
                            <?php foreach ($suivi as $etape): ?>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <strong><?= htmlspecialchars($etape['etape']) ?></strong>
                                        <span class="float-end">
                                            <?= date('d/m/Y H:i', strtotime($etape['date_mise_a_jour'])) ?>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <p><?= htmlspecialchars($etape['commentaire']) ?></p>
                                        <div class="text-end">
                                            <span class="badge bg-primary">
                                                Avancement: <?= $etape['pourcentage'] ?>%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if ($demande['statut'] === 'terminé' && !$notation): ?>
                        <hr>
                        <h4 class="mb-3">Noter le freelanceur</h4>
                        <form action="../PHP/noter_freelance.php" method="post">
                            <input type="hidden" name="demande_id" value="<?= $demande['id'] ?>">
                            <input type="hidden" name="freelancer_id" value="<?= $demande['freelancer_id'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Note (1-5 étoiles)</label>
                                <div class="rating">
                                    <i class="bi bi-star" data-value="1" onclick="setRating(1)"></i>
                                    <i class="bi bi-star" data-value="2" onclick="setRating(2)"></i>
                                    <i class="bi bi-star" data-value="3" onclick="setRating(3)"></i>
                                    <i class="bi bi-star" data-value="4" onclick="setRating(4)"></i>
                                    <i class="bi bi-star" data-value="5" onclick="setRating(5)"></i>
                                    <input type="hidden" name="stars" id="rating-value" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Commentaire (facultatif)</label>
                                <textarea class="form-control" name="comment" rows="3"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Envoyer la notation</button>
                        </form>
                        <?php elseif ($notation): ?>
                        <div class="alert alert-info mt-4">
                            <h5>Votre notation:</h5>
                            <div class="mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star<?php echo $i <= $notation['stars'] ? '-fill text-warning' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p><?php echo $notation['comment'] ? htmlspecialchars($notation['comment']) : 'Aucun commentaire'; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setRating(value) {
            const stars = document.querySelectorAll('.rating i');
            const ratingInput = document.getElementById('rating-value');
            
            ratingInput.value = value;
            
            stars.forEach(star => {
                const starValue = parseInt(star.getAttribute('data-value'));
                if (starValue <= value) {
                    star.classList.remove('bi-star');
                    star.classList.add('bi-star-fill', 'text-warning');
                } else {
                    star.classList.remove('bi-star-fill', 'text-warning');
                    star.classList.add('bi-star');
                }
            });
        }
    </script>
    <?php require_once(__DIR__."/footer.php")?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>