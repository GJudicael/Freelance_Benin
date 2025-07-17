<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de Mission - Plateforme Freelance</title>
    <link href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">

   
</head>
<body>
    <?php require_once(__DIR__."/header.php")?>
    <div class="container py-5">
        <!-- Affichage des messages -->
        <?php if ($viewData['error']): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $viewData['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($viewData['success']): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $viewData['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-briefcase"></i> 
                Suivi de mission: <?= htmlspecialchars($viewData['mission']['titre']) ?>
            </h2>
            <a href="../front_projet_EDL/Mesmissions.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Client -->
        <div class="card mb-4">
            <div class="card-body">
                <h5><i class="bi bi-person"></i> Client</h5>
                <p class="mb-0">
                    <?= htmlspecialchars($viewData['mission']['client_prenom'].' '.$viewData['mission']['client_nom']) ?>
                </p>
            </div>
        </div>

        <!-- Progression globale -->
        <div class="card mb-4">
            <div class="card-body">
                <h5><i class="bi bi-speedometer2"></i> Avancement global</h5>
                <div class="progress mb-2">
                    <div class="progress-bar progress-bar-striped bg-success" 
                         role="progressbar" 
                         style="width: <?= $viewData['mission']['avancement'] ?? 0 ?>%" 
                         aria-valuenow="<?= $viewData['mission']['avancement'] ?? 0 ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        <?= $viewData['mission']['avancement'] ?? 0 ?>%
                    </div>
                </div>
                <small class="text-muted">
                    Statut: <?= htmlspecialchars($viewData['mission']['statut']) ?>
                </small>
            </div>
        </div>

        <!-- Formulaire nouvelle étape -->
        <div class="card mb-4">
            <div class="card-body">
                <h5><i class="bi bi-plus-circle"></i> Ajouter une étape</h5>
                <form method="post" action="traitement_suivi_projet.php?id=<?= $viewData['mission']['id'] ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom de l'étape</label>
                            <input type="text" name="etape" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avancement (%)</label>
                            <input type="range" name="pourcentage" class="form-range" 
                                   min="<?= ($viewData['mission']['avancement'] ?? 0) + 5 ?>" 
                                   max="100" step="5" 
                                   value="<?= min(($viewData['mission']['avancement'] ?? 0) + 5, 100) ?>"
                                   oninput="this.nextElementSibling.value = this.value + '%'">
                            <output><?= min(($viewData['mission']['avancement'] ?? 0) + 5, 100) ?>%</output>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Commentaire (optionnel)</label>
                            <textarea name="commentaire" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer l'étape
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Historique des étapes -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-list-check"></i> Historique des étapes</h5>
                
                <?php if (empty($viewData['etapes'])): ?>
                    <div class="alert alert-info">
                        Aucune étape enregistrée pour le moment
                    </div>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($viewData['etapes'] as $etape): ?>
                        <div class="timeline-item mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5><?= htmlspecialchars($etape['etape']) ?></h5>
                                        <span class="badge bg-primary"><?= $etape['pourcentage'] ?>%</span>
                                    </div>
                                    <?php if (!empty($etape['commentaire'])): ?>
                                    <p class="mb-2"><?= htmlspecialchars($etape['commentaire']) ?></p>
                                    <?php endif; ?>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($etape['date_mise_a_jour'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

   <?php require_once(__DIR__."/footer.php")?>
<script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>