<?php
session_start();

require_once(__DIR__."/../bdd/creation_bdd.php");
if(!isset($_SESSION["connecte"]) || $_SESSION["connecte"]!== true){
        header('Location: ../index.php');
        exit();
    }
$user_id = $_SESSION['user_id'];

$result = $bdd->prepare("SELECT 
    d.id, 
    d.titre, 
    d.description, 
    d.categorie, 
    d.statut,
    d.date_soumission, 
    d.date_attribution,
    f.user_id,
    u.nomDUtilisateur AS freelance_username,
    u.nom AS freelance_nom,
    u.prenom AS freelance_prenom
FROM demande d
LEFT JOIN freelancers f ON d.freelancer_id = f.id
LEFT JOIN inscription u ON f.user_id = u.id
WHERE d.user_id = :user_id
ORDER BY 
    CASE WHEN d.statut = 'en attente' THEN 0 ELSE 1 END,
    d.date_soumission DESC");

$result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$result->execute();
$demandes = $result->fetchAll(PDO::FETCH_ASSOC);

?> 

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Demandes - Freelance Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
   
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="../assets/style.css">


    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>
<?php require_once(__DIR__."/header.php")?>
<body>
    <div class="container py-5">

        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (empty($demandes)): ?>
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
                <h4 class="mt-3">Vous n'avez aucune demande en cours</h4>
                <p class="mb-0">Créez votre première demande pour trouver le freelanceur idéal</p>
                <a href="Demande.php" class="btn btn-outline-primary mt-3">Créer une demande</a>
            </div>
        <?php else: ?>
            <div class="row g-4 justify-content-center">
              <?php foreach($demandes as $demande): ?>
<div class="col-lg-12 col-md-12 mb-4">
    <div class="card h-100 p-2 shadow border-primary-subtle border-3 rounded-4">
        <div class="card-body">
            <!-- En-tête avec statut -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge <?= $demande['statut'] === 'attribué' ? 'bg-success' : 'bg-warning' ?>">
                    <?= htmlspecialchars($demande['statut']) ?>
                </span>
                <small class="text-muted">
                    <?= date('d/m/Y', strtotime($demande['date_soumission'])) ?>
                </small>
            </div>
            
            <!-- Contenu de la demande -->
            <h3 class="card-title text-secondary"><?= htmlspecialchars($demande['categorie']) ?></h3>
            <h5 class="card-title text-secondary"><?= htmlspecialchars($demande['titre']) ?></h5>
            <p class="card-text text-muted"><?= htmlspecialchars($demande['description']) ?></p>
            
            <!-- Section attribution -->
            <?php if ($demande['statut'] === 'en attente'): ?>
                <!-- Remplacer le formulaire  par : -->
                    <form action="../PHP/attribuer_demande.php" method="post" class="mt-4">
                        <input type="hidden" name="demande_id" value="<?= $demande['id'] ?>">

                        <div class="input-group">
                        <input type="text" 
                                class="form-control" 
                                name="freelance_username"
                                placeholder="Nom d'utilisateur du freelanceur"
                                required
                                list="freelancersList"
                                autocomplete="off">

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-check"></i> Attribuer
                        </button>
                        </div>

                        <!-- Liste des freelancers -->
                        <datalist id="freelancersList">
                        <?php
                        // Récupération de la liste des freelancers
                        $reqFreelancers = $bdd->prepare("SELECT nomDUtilisateur 
                                                        FROM inscription 
                                                        WHERE role = 'freelance'");
                        $reqFreelancers->execute();
                        $freelancers = $reqFreelancers->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($freelancers as $freelancer): ?>
                            <option value="<?= htmlspecialchars($freelancer['nomDUtilisateur']) ?>">
                        <?php endforeach; ?>
                        </datalist>
                    </form>

            <?php else: ?>
                <div class="alert alert-success mt-3 p-2">
                    <i class="bi bi-check-circle"></i> Attribué à: 
                    <strong><a class="text-dark text-decoration-none" href="info_profile.php?id=<?= htmlspecialchars($demande['user_id'])?>"><?= htmlspecialchars($demande['freelance_nom'].'  '.$demande['freelance_prenom']  ?? 'Inconnu') ?></a></strong>
                    (le <?= date('d/m/Y', strtotime($demande['date_attribution'])) ?>)
                </div>
                <a href="suivi_notation_projet_client.php?id=<?= $demande['id'] ?>" class="btn btn-sm btn-info mt-2">
                        <i class="bi bi-eye"></i> Voir le suivi
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php require_once(__DIR__."/../front_projet_EDL/footer.php")?>
<script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>
