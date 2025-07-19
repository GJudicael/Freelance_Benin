<?php require_once(__DIR__ . "/../PHP/submit_entreprise.php") ?>

<!-- Ensuite, place ton <html> ... </html> ici avec le même formulaire que tu as déjà configuré. Les variables $message et $error fonctionneront automatiquement -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de création de compte entreprise</title>

    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="../assets/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>

<body>

    <main class="container w-75 shadow my-5 p-5">
        <h3 class="text-center text-primary"> Créez votre compte</h3>
        <?php
        // Display general database insertion errors if any
        if (isset($errors['db_insert_error'])) {
            echo '<p><small class="text-danger text-center">' . htmlspecialchars($errors['db_insert_error']) . '</small></p>';
            unset($errors['db_insert_error']);
        }
        // General error for debugging (optional, remove for production)
        // if (!empty($errors)) {
        //     echo '<div class="alert alert-danger">Veuillez corriger les erreurs dans le formulaire.</div>';
        //     echo '<pre>' . print_r($errors, true) . '</pre>'; // For debugging purposes
        // }
        ?>
        <form method="post" action="" enctype="multipart/form-data" novalidate>
            <div class="p-2">
                <label for="nom" class="form-text fs-6 p-1">Nom de l'entreprise </label>
                <input id="nom" type="text" name="nom"
                    class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>"
                    placeholder="Ex : Freelance Sarl" value="<?= htmlspecialchars($entreprise['nom']) ?>">
                <?php if (isset($errors["nom"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["nom"]) ?></small></p>
                <?php endif; ?>
            </div>

            <div class="p-2">
                <label for="nom_utilisateur" class="form-text fs-6 p-1">Nom d'itilisateur </label>
                <input id="nom_utilisateur" type="text" name="nom_utilisateur"
                    class="form-control <?= isset($errors['nom_utilisateur']) ? 'is-invalid' : '' ?>"
                    placeholder="Ex : Freelance Sarl" value="<?= htmlspecialchars($entreprise['nom_utilisateur']) ?>">
                <?php if (isset($errors["nom_utilisateur"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["nom_utilisateur"]) ?></small></p>
                <?php endif; ?>
            </div>

            <div class="p-2">
                <label for="description" class="form-text fs-6 p-1">Description de l'entreprise</label>
                <textarea id="description" name="description"
                    class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>"
                    placeholder="Décrivez votre entreprise"><?= htmlspecialchars($entreprise['description']) ?></textarea>
                <?php if (isset($errors["description"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["description"]) ?></small></p>
                <?php endif; ?>
            </div>

            <div class="p-2">
                <label for="secteur" class="form-text fs-6 p-1">Secteur d'activité</label>
                <input id="secteur" type="text" name="secteur"
                    class="form-control <?= isset($errors['secteur']) ? 'is-invalid' : '' ?>"
                    placeholder="Technologie,Réseau," value="<?= htmlspecialchars($entreprise['secteur']) ?>">
                <?php if (isset($errors["secteur"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["secteur"]) ?></small></p>
                <?php endif; ?>
            </div>



            <div class="p-2 form-group form-password-toggle">
                <label for="email" class="form-text fs-6 p-1">Email</label>
                <input id="email" type="email" name="email"
                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                    placeholder="Entrer le mail de l'entreprise" value="<?= htmlspecialchars($entreprise['email']) ?>">
                <?php if (isset($errors["email"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["email"]) ?></small></p>
                <?php endif; ?>
            </div>



            <div class="p-2 form-group form-password-toggle">
                <label for="site" class="form-text fs-6 p-1">Site web</label>
                <input id="site" type="url" name="site"
                    class="form-control <?= isset($errors['site']) ? 'is-invalid' : '' ?>" pattern="https?://.+"
                    placeholder="https://www.freebenin.bj" value="<?= htmlspecialchars($entreprise['site']) ?>">
                <?php if (isset($errors["site"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["site"]) ?></small></p>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="py-2 col-md-6">
                    <label for="facebook" class="form-text fs-6 p-1">Facebook</label>
                    <input id="facebook" type="url" name="facebook"
                        class="form-control <?= isset($errors['facebook']) ? 'is-invalid' : '' ?>"
                        pattern="https?://(www\.)?facebook\.com/.+" placeholder="https://facebook.com/votrepage"
                        value="<?= htmlspecialchars($entreprise['facebook']) ?>">
                    <?php if (isset($errors["facebook"])): ?>
                        <p><small class="text-danger"><?= htmlspecialchars($errors["facebook"]) ?></small></p>
                    <?php endif; ?>
                </div>

                <div class="py-2 col-md-6">
                    <label for="linkdin" class="form-text fs-6 p-1">Linkdin</label>
                    <input id="linkdin" type="url" name="linkdin"
                        class="form-control <?= isset($errors['linkdin']) ? 'is-invalid' : '' ?>"
                        pattern="https?://(www\.)?linkedin\.com/.+" placeholder="https://linkedin.com/in/entreprise"
                        value="<?= htmlspecialchars($entreprise['linkdin']) ?>">
                    <?php if (isset($errors["linkdin"])): ?>
                        <p><small class="text-danger"><?= htmlspecialchars($errors["linkdin"]) ?></small></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-2">
                <label for="employes" class="form-text fs-6 p-1">Nombre d'employés </label>
                <input id="employes" type="number" name="employes"
                    class="form-control <?= isset($errors['employes']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($entreprise['employes']) ?>">
                <?php if (isset($errors["employes"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["employes"]) ?></small></p>
                <?php endif; ?>
            </div>


            <div class="p-2 ">
                <label for="telephone" class="form-text fs-6 p-1">Téléphone</label>
                <input id="telephone" type="text" name="telephone"
                    class="form-control <?= isset($errors['telephone']) ? 'is-invalid' : '' ?>"
                    placeholder="EX: 00 00 00 00" value="<?= htmlspecialchars($entreprise['telephone'] ?? '') ?>">
                <?php if (isset($errors["telephone"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["telephone"]) ?></small></p>
                <?php endif; ?>
            </div>

            <div class="py-2 ">
                <label for="numero" class="form-text fs-6 p-1">Numéro IFU</label>
                <input id="numero" type="number" name="numero"
                    class="form-control <?= isset($errors['numero']) ? 'is-invalid' : '' ?>" pattern="[0-9]{1,20}"
                    maxlength="20" placeholder="312011234567" value="<?= htmlspecialchars($entreprise['numero']) ?>">
                <?php if (isset($errors["numero"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["numero"]) ?></small></p>
                <?php endif; ?>
            </div>


            <div class=" row ">
                <div class="py-2 col-md-6">
                    <label for="pays" class="form-text fs-6 p-1">Pays</label>
                    <input id="pays" type="text" name="pays"
                        class="form-control <?= isset($errors['pays']) ? 'is-invalid' : '' ?>"
                        placeholder="Entrer le pays de l'entreprise"
                        value="<?= htmlspecialchars($entreprise['pays']) ?>">
                    <?php if (isset($errors["pays"])): ?>
                        <p><small class="text-danger"><?= htmlspecialchars($errors["pays"]) ?></small></p>
                    <?php endif; ?>
                </div>

                <div class="py-2 col-md-6">
                    <label for="ville" class="form-text fs-6 p-1">Ville</label>
                    <input id="ville" type="text" name="ville"
                        class="form-control <?= isset($errors['ville']) ? 'is-invalid' : '' ?>"
                        placeholder="Entrer la ville de l'entreprise"
                        value="<?= htmlspecialchars($entreprise['ville']) ?>">
                    <?php if (isset($errors["ville"])): ?>
                        <p><small class="text-danger"><?= htmlspecialchars($errors["ville"]) ?></small></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="p-2">
                <label for="adresse" class="form-text fs-6 p-1">Adresse</label>
                <input id="adresse" type="text" name="adresse"
                    class="form-control <?= isset($errors['adresse']) ? 'is-invalid' : '' ?>"
                    placeholder="Adresse de l'entreprise" value="<?= htmlspecialchars($entreprise['adresse']) ?>">
                <?php if (isset($errors["adresse"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["adresse"]) ?></small></p>
                <?php endif; ?>
            </div>

            <div class="p-2">
                <label for="annee" class="form-text fs-6 p-1">Année de création </label>
                <input id="annee" type="date" name="annee"
                    class="form-control <?= isset($errors['annee']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($entreprise['annee']) ?>">
                <?php if (isset($errors["annee"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["annee"]) ?></small></p>
                <?php endif; ?>
            </div>

            <div class="p-2">
                <label for="logo" class="form-text fs-6 p-1">Logo de l'entreprise</label>
                <input id="logo" type="file" name="logo"
                    class="form-control <?= isset($errors['logo']) ? 'is-invalid' : '' ?>" accept="image/*">
                <?php if (isset($errors["logo"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["logo"]) ?></small></p>
                <?php endif; ?>
            </div>

            <div class="p-2 form-group form-password-toggle">
                <label for="mot_de_passe" class="form-text fs-6 p-1">Mot de passe</label>
                <div class="input-group input-group-merge">
                    <input id="mot_de_passe" type="password" name="mot_de_passe"
                        class="form-control <?= isset($errors['motDepasse']) ? 'is-invalid' : '' ?>"
                        placeholder="Entrer votre mot de passe" aria-describedby="passwordHelp">
                    <span class="input-group-text cursor-pointer"><i class="bi bi-eye" id="eyeToggle1"></i></span>
                </div>
                <?php if (isset($errors["motDepasse"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["motDepasse"]) ?></small></p>
                <?php endif; ?>
            </div>

            <div class="p-2">
                <label for="mot_de_passe_confirmation" class="form-text fs-6 p-1"> Confirmer votre mot de
                    passe</label>
                <div class="input-group input-group-merge">
                    <input id="mot_de_passe_confirmation" type="password" name="mot_de_passe_confirmation"
                        class="form-control <?= isset($errors['motDepasseConfirmer']) ? 'is-invalid' : '' ?>"
                        placeholder="Entrer votre mot de passe" aria-describedby="passwordHelp1">
                    <span class="input-group-text cursor-pointer"><i class="bi bi-eye" id="eyeToggle2"></i></span>
                </div>
                <?php if (isset($errors["motDepasseConfirmer"])): ?>
                    <p><small class="text-danger"><?= htmlspecialchars($errors["motDepasseConfirmer"]) ?></small></p>
                <?php endif; ?>
            </div>

            <div class="text-center">
                <button type="submit" name="creer" class="btn btn-primary mt-2"> <i class="bi bi-check-circle me-2"></i>
                    Créer un compte </button>
            </div>

        </form>
        <div class="text-center mt-3">
            Vous avez déjà un compte? <a href="Connexion.php" class="text-decoration-none text-primary">
                Connectez-vous</a>
        </div>
    </main>

    <?php require_once(__DIR__ . "/footer.php") ?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>