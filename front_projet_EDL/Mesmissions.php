<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

$user_id = $_SESSION["user_id"];

// Vérification du rôle
$stmt = $bdd->prepare("SELECT i.role FROM inscription i WHERE i.id=:user_id");
$stmt->bindParam(':user_id', $user_id,PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result['role'] !== 'freelance') {
    $_SESSION['error'] = "Accès réservé aux freelancers";
    header("Location: info_profile.php?id=".$user_id);
    exit();
}

// Récupération des missions
$stmt = $bdd->prepare("SELECT 
    d.id, d.titre, d.description, d.categorie, d.statut,
    d.date_soumission, d.date_attribution,
    d.freelancer_id,
    c.nomDUtilisateur AS client_username,
    c.nom AS client_nom,
    c.prenom AS client_prenom,
    (SELECT MAX(pourcentage) FROM suivi_projet WHERE demande_id = d.id) AS avancement
FROM demande d
JOIN inscription c ON d.user_id = c.id
WHERE d.freelancer_id = :freelancer_id
ORDER BY 
    CASE WHEN d.statut = 'en cours' THEN 0 
         WHEN d.statut = 'attribué' THEN 1
         ELSE 2 END,
    d.date_attribution DESC");

$stmt->bindParam(':freelancer_id', $user_id);
$stmt->execute();
$missions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Missions - Plateforme Freelance</title>
    <link href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">

    <style>
        .mission-card {
            transition: all 0.3s;
            border-left: 4px solid var(--bs-primary);
        }
        .mission-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .progress {
            height: 10px;
        }
    </style>
</head>
<body>
    <?php require_once(__DIR__."/header.php") ?>
    
    <div class="container py-5">
        <h1 class="mb-4"><i class="bi bi-briefcase"></i> Mes Missions</h1>

        <?php if (empty($missions)): ?>
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
                <h3 class="mt-3">Aucune mission pour le moment</h3>
                <p class="lead">Les missions qui vous sont attribuées apparaîtront ici</p>
                <a href="" class="btn btn-outline-primary mt-3">
                    <i class="bi bi-person-gear"></i> Compléter mon profil
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($missions as $mission): ?>
                <div class="col-lg-6">
                    <div class="card mission-card h-100 shadow-sm">
                        <div class="card-body">
                            <!-- En-tête -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-<?= 
                                    $mission['statut'] === 'en cours' ? 'success' : 
                                    ($mission['statut'] === 'attribué' ? 'warning' : 'secondary')
                                ?>">
                                    <?= htmlspecialchars($mission['statut']) ?>
                                </span>
                                <small class="text-muted">
                                    Attribuée le <?= date('d/m/Y', strtotime($mission['date_attribution'])) ?>
                                </small>
                            </div>
                            
                            <!-- Infos mission -->
                            <h4 class="card-title"><?= htmlspecialchars($mission['titre']) ?></h4>
                            <h6 class="text-muted mb-3"><?= htmlspecialchars($mission['categorie']) ?></h6>
                            <p class="card-text"><?= htmlspecialchars($mission['description']) ?></p>
                            
                            <!-- Client -->
                            <div class="client-info mb-3">
                                <i class="bi bi-person"></i> Client : 
                                <strong><?= htmlspecialchars($mission['client_prenom'] . ' ' . $mission['client_nom']) ?></strong>
                                (@<?= htmlspecialchars($mission['client_username']) ?>)
                            </div>
                            
                            <!-- Progression -->
                            <?php if ($mission['statut'] === 'en cours'): ?>
                                <div class="progress mb-3">
                                    <div class="progress-bar" 
                                         role="progressbar" 
                                         style="width: <?= $mission['avancement'] ?>%"
                                         aria-valuenow="<?= $mission['avancement'] ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small>Avancement : <?= $mission['avancement'] ?>%</small>
                            <?php endif; ?>
                            
                            <!-- Actions -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="../PHP/traitement_suivi_projet.php?id=<?= $mission['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                   <i class="bi bi-activity"></i> Suivi
                                </a>
                                
                                <?php if ($mission['statut'] === 'attribué'): ?>
                                    <form action="../PHP/demarrer_mission.php" method="post">
                                        <input type="hidden" name="mission_id" value="<?= $mission['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-play-circle"></i> Démarrer
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php require_once(__DIR__."/footer.php") ?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>