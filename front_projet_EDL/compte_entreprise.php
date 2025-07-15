<?php require_once(__DIR__."/../PHP/submit_entreprise.php")?>

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
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

     <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>
<body>

<main class="container w-75 shadow my-5 p-5">
   
    <h3 class="text-center text-primary"> Créez votre compte</h3>
    <form method="post" action="">
        <?php 
            if(isset($message)){
                echo '<div class="alert alert-danger" role="alert">'. htmlspecialchars($message) .'</div>';
                unset($message);
            }
        ?>
       
            <div class="p-2">
                <label for="nom" class="form-text fs-6 p-1">Nom de l'entreprise </label>
                <input id="nom" type="text" name="nom" class="form-control" placeholder="Entrerle nom de votre entreprise" value="<?php echo isset($error) || isset($message)? htmlspecialchars($nom): '' ?>">
            </div>
            
             <div class="p-2">
                <label for="nom_d_utilisateur" class="form-text  fs-6 p-1">Nom d'utilisateur</label>
                <input id="nom_d_utilisateur" type="text" name="nom_d_utilisateur" class="form-control" placeholder="Entrer un nom d'utilisateur" value="<?php echo isset($error) || isset($message)? htmlspecialchars($nomUtilisateur): '' ?>">
                <p> <small class="text-danger"> <?php if(isset($error["nomDutilisateur"])) { echo htmlspecialchars($error["nomDutilisateur"]); 
                    unset($error["nomDutilisateur"]) ; } ?> </small></p>
            </div>
            <div class="p-2">
                <label for="desccription" class="form-text  fs-6 p-1">Description de l'entreprise</label>
                <input id="description" type="text" name="description" class="form-control" placeholder="Brève description de l'entreprise" value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>

             <div class="p-2">
                <label for="secteurn" class="form-text  fs-6 p-1">Secteur d'activité</label>
                <input id="secteur" type="text" name="secteur" class="form-control" placeholder="Secteur d'activité de l'entreprise" value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>

            <div class="p-2 form-group form-password-toggle ">
                <label for="email" class="form-text  fs-6 p-1">Site web</label>
                <input id="site" type="url" name="site" class="form-control" placeholder="Entrer le lien du site web de l'entreprise" value="<?php echo isset($error) || isset($message)? htmlspecialchars($email): '' ?>" data-sb-validations="required">
                <p> <small class="text-danger"> <?php if(isset($error["email"])) { echo htmlspecialchars($error["email"]); 
                    unset($error["email"]) ; } ?> </small></p>
            </div>

             <div class="p-2">
                <label for="facebook" class="form-text  fs-6 p-1">Page facebook</label>
                <input id="facebook" type="url" name="facebook" class="form-control" placeholder="Lien de la page facebook de l'entreprise" value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>

            <div class="p-2">
                <label for="linkdin" class="form-text  fs-6 p-1">Linkdin</label>
                <input id="linkdin" type="url" name="linkdin" class="form-control" placeholder="Lien du profil linkdin de l'entreprise" value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>

             <div class="p-2">
                <label for="employes" class="form-text  fs-6 p-1">Nombre d'employés </label>
                <input id="employes" type="number" name="employes" class="form-control" placeholder="Nombre d'employés de l'entreprise" value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>

             <div class="p-2">
                <label for="numero" class="form-text  fs-6 p-1">ID légal</label>
                <input id="numero" type="number" name="numero" class="form-control" placeholder="Id légal de l'entreprise" value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>

             <div class="p-2">
                <label for="adresse" class="form-text  fs-6 p-1">Adresse</label>
                <input id="adresse" type="text" name="adresse" class="form-control" placeholder="Adresse de l'entreprise " value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>

             <div class="p-2">
                <label for="annee" class="form-text  fs-6 p-1">Année de création </label>
                <input id="annee" type="date" name="annee" class="form-control" placeholder="Année de création de l'entreprise" value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>

             <div class="p-2">
                <label for="logo" class="form-text  fs-6 p-1">Logo l'entreprise</label>
                <input id="logo" type="file" name="logo" class="form-control" placeholder="Insérer une image " value="<?php echo isset($error) || isset($message)? htmlspecialchars($numero): '' ?> ">
            </div>

            <div class="p-2 form-group form-password-toggle ">
                <label for="mot_de_passe" class="form-text  fs-6 p-1">Mot de passe</label>
                <div class="input-group input-group-merge">
                    <input id="mot_de_passe" type="password" name="mot_de_passe" class="form-control  
                    <?php if (isset($error["password"])) {
                        echo "is-invalid";
                    } ?>" placeholder="Entrer votre mot de passe" aria-describedby="passwordHelp" value="<?php echo isset($error) || isset($message)? htmlspecialchars($motDepasse): '' ?>">
                    <span class="input-group-text cursor-pointer"><i class="bi bi-eye" id="eyeToggle1"></i></span>
                </div>
                <p> <small class="text-danger"> <?php if(isset($error["password"])) { echo htmlspecialchars($error["password"]); 
                        unset($error["password"]) ; } ?> </small></p>
                
            </div>
            
            <div class="p-2">
                <label for="mot_de_passe_confirmation" class="form-text  fs-6 p-1"> Confirmer votre mot de passe</label>
                <div class="input-group input-group-merge">
                    <input id="mot_de_passe_confirmation" type="password" name="mot_de_passe_confirmation" value="<?php echo isset($error) || isset($message)? htmlspecialchars($motDepasseConfirmation): '' ?>" class="form-control
                    <?php if (isset($error["pass_confirm"])) {
                        echo "is-invalid";
                    } ?>" placeholder="Entrer votre mot de passe" aria-describedby="passwordHelp1">
                    <span class="input-group-text cursor-pointer"><i class="bi bi-eye" id="eyeToggle2"></i></span>
                </div>
                <p> <small class="text-danger"> <?php if(isset($error["pass_confirm"])) { echo htmlspecialchars($error["pass_confirm"]); 
                        unset($error["pass_confirm"]) ; } ?> </small></p>
                
            </div>
           
            <div class="text-center">
                <button type="submit" name="envoyer" class="btn btn-outline-primary mt-2"> Créer un compte </button>
            </div>
        
    </form>
    <div class="text-center mt-3">
        Vous avez déjà un compte? <a href="Connexion.php" class="text-decoration-none text-primary"> Connectez-vous</a>
    </div>
</main>

<?php require_once(__DIR__."/footer.php")?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>