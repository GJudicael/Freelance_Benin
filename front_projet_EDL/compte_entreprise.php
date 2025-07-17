
<?php require_once(__DIR__."/../PHP/traitement_entreprise.php")?>
<!-- Ensuite, place ton <html> ... </html> ici avec le même formulaire que tu as déjà configuré. Les variables $message et $error fonctionneront automatiquement -->
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

    <form method="post" action="" enctype="multipart/form-data" novalidate>
         <?php 
            if(isset($message)){
                echo '<div class="alert alert-danger" role="alert">'. htmlspecialchars($message) .'</div>';
                unset($message);
            }
        ?>
        <?php if (isset($message)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Informations générales -->
        <div class="row g-3">
            <div class="p-md-6">
                <label for="nom_de_l_entreprise" class="form-label">Nom de l'entreprise *</label>
                <input type="text" id="nom_de_l_entreprise" name="nom_de_l_entreprise" class="form-control" placeholder="Ex: FreeBenin SARL" value="<?php echo isset($error)? htmlspecialchars($nom): '' ?>" 
            </div>
            <div class="p-2">
                <label for="nom_d_utilisateur" class="form-text  fs-6 p-1">Nom d'utilisateur</label>
                <input id="nom_d_utilisateur" type="text" name="nom_d_utilisateur" class="form-control" placeholder="Entrer un nom d'utilisateur" value="<?php echo isset($error)? htmlspecialchars($nomUtilisateur): '' ?>">
                <p> <small class="text-danger"> <?php if(isset($error["nomDutilisateur"])) { echo htmlspecialchars($error["nom_d_utilisateur"]); 
                    unset($error["nom_d_utilisateur"]) ; } ?> </small></p>
            </div>
        </div>

        <!-- Description -->
       <div class="p-2">
                    <label for="description" class="form-text p-1 fs-6">Description</label>
                    <textarea name="description" cols="50" class="form-control" placeholder="Decrivez votre demande ici..." value="<?php echo isset($error)? htmlspecialchars($description): '' ?>"></textarea>
                    <p> <small class = "text-danger"> <?php echo isset($erreur['description'])? htmlspecialchars($erreur['description']): ''?></small></p>
                </div>

        <!-- Secteur -->
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label for="secteur" class="form-label">Secteur d'activité </label>
                <input type="text" id="secteur" name="secteur" class="form-control"
                       placeholder="Ex: Technologie, Conseil" value="<?php echo isset($error)? htmlspecialchars($secteur): '' ?>" required>
            </div>

            <div class="col-md-6">
                <label for="employes" class="form-label">Nombre d'employés</label>
                <input type="number" id="employes" name="employes" min="0"
                       class="form-control" value="<?php echo isset($error)? htmlspecialchars($nombre_d_employe): '' ?>">
            </div>
        </div>

        <!-- Contacts -->
        <div class="row g-3 mt-2">
           <div class="p-2">
                <label for="telephone" class="form-text  fs-6 p-1">Numéro de téléphone</label>
                <input id="telephone" type="tel" name="telephone" class="form-control" placeholder="Entrer votre numero" value="<?php echo isset($error)? htmlspecialchars($numero): '' ?> ">
            </div>
            <div class="p-2 form-group form-password-toggle ">
                <label for="email" class="form-text  fs-6 p-1">Email</label>
                <input id="email" type="email" name="email" class="form-control" placeholder="Entrer votre adresse email" value="<?php echo isset($error)? htmlspecialchars($email): '' ?>" data-sb-validations="required">
                <div class="invalid-feedback text-red" data-sb-feedback="emailAddressBelow:required">Email Address is required.</div>
                <p> <small class="text-danger"> <?php if(isset($error["email"])) { echo htmlspecialchars($error["email"]); 
                        unset($error["email"]) ; } ?> </small></p>
            </div>
        </div>

        <!-- Réseaux & IFU -->
        <div class="row g-3 mt-2">
            <div class="col-md-4">
                <label for="site" class="form-label">Site web </label>
                <input type="url" id="site" name="site" class="form-control"
                       pattern="https?://.+" placeholder="https://www.freebenin.bj"
                       value="<?= htmlspecialchars($_POST['site'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label for="facebook" class="form-label">Facebook </label>
                <input type="url" id="facebook" name="facebook" class="form-control"
                       pattern="https?://(www\.)?facebook\.com/.+"
                       placeholder="https://facebook.com/votrepage"
                       value="<?= htmlspecialchars($_POST['facebook'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label for="linkdin" class="form-label">LinkedIn </label>
                <input type="url" id="linkdin" name="linkdin" class="form-control"
                       pattern="https?://(www\.)?linkedin\.com/.+"
                       placeholder="https://linkedin.com/in/entreprise"
                       value="<?= htmlspecialchars($_POST['linkedin'] ?? '') ?>" required>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-4">
                <label for="numero" class="form-label">Numéro IFU </label>
                <input type="text" id="numero" name="numero"
                    class="form-control" pattern="[0-9]{1,20}" maxlength="20"
                    placeholder="312011234567"  value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>
            <div class="p-2">
                <label for="adresse" class="form-text  fs-6 p-1">Adresse</label>
                <input id="adresse" type="text" name="adresse" class="form-control" placeholder="Adresse de l'entreprise " value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>

            <div class="p-2">
                <label for="annee" class="form-text  fs-6 p-1">Année de création </label>
                <input id="annee" type="date" name="annee" class="form-control" placeholder="Année de création de l'entreprise" value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>
        </div>

        <!-- Logo -->
        <div class="p-2">
                <label for="logo" class="form-text  fs-6 p-1">Logo l'entreprise</label>
                <input id="logo" type="file" name="logo" class="form-control" placeholder="Insérer une image " value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
        </div>

        <!-- Mot de passe -->
        <div class="row g-3 mt-3">
            <div class="p-2 form-group form-password-toggle ">
                <label for="mot_de_passe" class="form-text  fs-6 p-1">Mot de passe</label>
                <div class="input-group input-group-merge">
                    <input id="mot_de_passe" type="password" name="mot_de_passe" class="form-control  
                    <?php if (isset($error["password"])) {
                        echo "is-invalid";
                    } ?>" placeholder="Entrer votre mot de passe" aria-describedby="passwordHelp" value="<?php echo isset($error)? htmlspecialchars($motDepasse): '' ?>">
                    <span class="input-group-text cursor-pointer"><i class="bi bi-eye" id="eyeToggle1"></i></span>
                </div>
                <p> <small class="text-danger"> <?php if(isset($error["password"])) { echo htmlspecialchars($error["password"]); 
                        unset($error["password"]) ; } ?> </small></p>
                
            </div>
            
            <div class="p-2">
                <label for="mot_de_passe_confirmation" class="form-text  fs-6 p-1"> Confirmer votre mot de passe</label>
                <div class="input-group input-group-merge">
                    <input id="mot_de_passe_confirmation" type="password" name="mot_de_passe_confirmation" value="<?php echo isset($error)? htmlspecialchars($motDepasseConfirmation): '' ?>" class="form-control
                    <?php if (isset($error["pass_confirm"])) {
                        echo "is-invalid";
                    } ?>" placeholder="Entrer votre mot de passe" aria-describedby="passwordHelp1">
                    <span class="input-group-text cursor-pointer"><i class="bi bi-eye" id="eyeToggle2"></i></span>
                </div>
                <p> <small class="text-danger"> <?php if(isset($error["pass_confirm"])) { echo htmlspecialchars($error["pass_confirm"]); 
                        unset($error["pass_confirm"]) ; } ?> </small></p>
                
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
// Toggle mot de passe
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', () => {
        const target = document.getElementById(icon.dataset.target);
        const type = target.type === 'password' ? 'text' : 'password';
        target.type = type;
        icon.querySelector('i').classList.toggle('bi-eye');
        icon.querySelector('i').classList.toggle('bi-eye-slash');
    });
});
</script>
</body>
</html>