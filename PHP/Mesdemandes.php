<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

$user_id = $_SESSION['user_id'];

$result = $bdd->query("SELECT d.id , d.description, d.categorie 
FROM demande d WHERE d.user_id = $user_id");

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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary"><i class="bi bi-list-task"></i> Mes Demandes</h1>
            <a href="creer_demande.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nouvelle Demande</a>
        </div>

        <?php if (empty($demandes)): ?>
            <div class="alert alert-info text-center py-4">
                <i class="bi bi-info-circle-fill fs-4"></i>
                <h4 class="mt-3">Vous n'avez aucune demande en cours</h4>
                <p class="mb-0">Créez votre première demande pour trouver le freelanceur idéal</p>
                <a href="creer_demande.php" class="btn btn-primary mt-3">Créer une demande</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
              <?php foreach($demandes as $demande): ?>
<div class="col-lg-8 col-md-12 mb-4">
    <div class="card h-100 p-2 shadow border-primary-subtle border-3 rounded-4">
        <div class="card-body">
            <!-- En-tête avec statut -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge <?= $demande['statut'] === 'attribué' ? 'bg-success' : 'bg-warning' ?>">
                    <?= htmlspecialchars($demande['statut']) ?>
                </span>
                <small class="text-muted">
                    <?= date('d/m/Y', strtotime($demande['date_creation'])) ?>
                </small>
            </div>
            
            <!-- Contenu de la demande -->
            <h5 class="card-title text-secondary"><?= htmlspecialchars($demande['categorie']) ?></h5>
            <p class="card-text text-muted"><?= htmlspecialchars($demande['description']) ?></p>
            
            <!-- Section attribution -->
            <?php if ($demande['statut'] === 'en attente'): ?>
                <form action="attribuer_demande.php" method="post" class="mt-4">
                    <input type="hidden" name="demande_id" value="<?= $demande['id'] ?>">
                    
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               name="freelance_username"
                               placeholder="Nom d'utilisateur du freelanceur"
                               required>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-check"></i> Attribuer
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-success mt-3 p-2">
                    <i class="bi bi-check-circle"></i> Attribué à: 
                    <strong><?= htmlspecialchars($demande['freelance_username'] ?? 'Inconnu') ?></strong>
                    (le <?= date('d/m/Y', strtotime($demande['date_attribution'])) ?>)
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php require_once(__DIR__."/footer.php")?>
<script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>