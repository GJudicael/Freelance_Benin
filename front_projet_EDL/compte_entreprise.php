<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$error = $_SESSION["error"] ?? [];
$message = $_SESSION["message"] ?? null;
unset($_SESSION["error"], $_SESSION["message"]);
require_once(__DIR__."/../PHP/traitement_entreprise.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création compte entreprise</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>
<body>

<main class="container w-75 shadow my-5 p-5">
    <h3 class="text-center text-primary mb-4">Créez votre compte entreprise</h3>

    <?php if ($message): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" action="../PHP/traitement_entreprise.php" enctype="multipart/form-data" novalidate>
        <!-- Informations générales -->
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nom" class="form-label">Nom de l'entreprise *</label>
                <input type="text" id="nom" name="nom" class="form-control <?= isset($error['nom']) ? 'is-invalid' : '' ?>"
                       placeholder="Ex: FreeBenin SARL" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                <?php if (isset($error['nom'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($error['nom']) ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="nom_d_utilisateur" class="form-label">Nom d'utilisateur *</label>
                <input type="text" id="nom_d_utilisateur" name="nom_d_utilisateur"
                       class="form-control <?= isset($error['nomDutilisateur']) ? 'is-invalid' : '' ?>"
                       placeholder="Ex: freebenin_admin" value="<?= htmlspecialchars($_POST['nom_d_utilisateur'] ?? '') ?>" required>
                <?php if (isset($error['nomDutilisateur'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($error['nomDutilisateur']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Description -->
        <div class="mt-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" rows="3" class="form-control"
                      placeholder="Présentez brièvement votre entreprise"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <!-- Secteur -->
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label for="secteur" class="form-label">Secteur d'activité *</label>
                <input type="text" id="secteur" name="secteur" class="form-control"
                       placeholder="Ex: Technologie, Conseil" value="<?= htmlspecialchars($_POST['secteur'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label for="employes" class="form-label">Nombre d'employés</label>
                <input type="number" id="employes" name="employes" min="0"
                       class="form-control" value="<?= htmlspecialchars($_POST['employes'] ?? '') ?>">
            </div>
        </div>

        <!-- Contacts -->
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label for="telephone" class="form-label">Téléphone *</label>
                <input type="tel" id="telephone" name="telephone"
                       class="form-control <?= isset($error['telephone']) ? 'is-invalid' : '' ?>"
                       pattern="^\+?[0-9\s\-]{8,20}$"
                       placeholder="+229 97 00 00 00" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>" required>
                <?php if (isset($error['telephone'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($error['telephone']) ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email professionnel *</label>
                <input type="email" id="email" name="email"
                       class="form-control <?= isset($error['email']) ? 'is-invalid' : '' ?>"
                       placeholder="contact@freebenin.bj" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                <?php if (isset($error['email'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($error['email']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Réseaux & IFU -->
        <div class="row g-3 mt-2">
            <div class="col-md-4">
                <label for="site" class="form-label">Site web *</label>
                <input type="url" id="site" name="site" class="form-control"
                       pattern="https?://.+" placeholder="https://www.freebenin.bj"
                       value="<?= htmlspecialchars($_POST['site'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label for="facebook" class="form-label">Facebook *</label>
                <input type="url" id="facebook" name="facebook" class="form-control"
                       pattern="https?://(www\.)?facebook\.com/.+"
                       placeholder="https://facebook.com/votrepage"
                       value="<?= htmlspecialchars($_POST['facebook'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label for="linkedin" class="form-label">LinkedIn *</label>
                <input type="url" id="linkedin" name="linkedin" class="form-control"
                       pattern="https?://(www\.)?linkedin\.com/.+"
                       placeholder="https://linkedin.com/in/entreprise"
                       value="<?= htmlspecialchars($_POST['linkedin'] ?? '') ?>" required>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-4">
                <label for="numero" class="form-label">Numéro IFU *</label>
                <input type="text" id="numero" name="numero"
                       class="form-control <?= isset($error['numero']) ? 'is-invalid' : '' ?>"
                       pattern="[0-9]{1,20}" maxlength="20"
                       placeholder="312011234567" value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>" required>
                <?php if (isset($error['numero'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($error['numero']) ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" id="adresse" name="adresse" class="form-control"
                       value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label for="annee" class="form-label">Année de création</label>
                <input type="date" id="annee" name="annee" class="form-control"
                       value="<?= htmlspecialchars($_POST['annee'] ?? '') ?>">
            </div>
        </div>

        <!-- Logo -->
        <div class="mt-3">
            <label for="logo" class="form-label">Logo de l'entreprise</label>
            <input type="file" id="logo" name="logo" class="form-control" accept="image/png, image/jpeg, image/webp">
        </div>

        <!-- Mot de passe -->
        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <label for="mot_de_passe" class="form-label">Mot de passe *</label>
                <div class="input-group">
                    <input type="password" id="mot_de_passe" name="mot_de_passe"
                           class="form-control <?= isset($error['password']) ? 'is-invalid' : '' ?>" required>
                    <span class="input-group-text toggle-password" data-target="mot_de_passe">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
                <?php if (isset($error['password'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($error['password']) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label for="mot_de_passe_confirmation" class="form-label">Confirmer le mot de passe *</label>
                <div class="input-group">
                    <input type="password" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation"
                           class="form-control <?= isset($error['pass_confirm']) ? 'is-invalid' : '' ?>" required>
                    <span class="input-group-text toggle-password" data-target="mot_de_passe_confirmation">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
                <?php if (isset($error['pass_confirm'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($error['pass_confirm']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bouton -->
        <div class="text-center mt-4">
            <button type="submit" name="envoyer" class="btn btn-primary px-5">
                <i class="bi bi-check-circle me-2"></i> Créer le compte
            </button>
        </div>
    </form>

    <div class="text-center mt-3">
        Vous avez déjà un compte ? <a href="Connexion.php" class="text-decoration-none text-primary">Connectez-vous</a>
    </div>
</main>

<script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', () => {
        const target = document.getElementById(icon.dataset.target);
        target.type = target.type === 'password' ? 'text' : 'password';
        icon.querySelector('i').classList.toggle('bi-eye');
        icon.querySelector('i').classList.toggle('bi-eye-slash');
    });
});
</script>
</body>
</html>
