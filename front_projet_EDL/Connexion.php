<?php 
    require_once(__DIR__."/../PHP/traitement_de_la_connexion.php");?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>

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

<body >

    <!-- Profil utilisateur -->
        
    <main class="d-flex justify-content-center align-items-center vh-100">
        <section  class="container w-50 shadow p-5 my-5">
        <h3 class="text-center text-success mb-3">Content de vous revoir!</h3>
        <h6 class="text-center text-secondary mb-3"> Connectez-vous à votre compte pour accéder à la page d'accueil </h6>
        <form method="post" action="">
            
                <?php 
                
                if(isset($message_error)){
                    
                    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($message_error). '</div>';
                    unset($message_error);
        
                }
            ?>

            <?php 
                
                if(isset($_SESSION["succes"])){
                    
                    echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($_SESSION["succes"]). '</div>';
                    unset($_SESSION["succes"]);
            
                }
            ?>

            <div class="p-2">
                <label for="nom_d_utilisateur" class="form-text fs-6 p-1">Nom d'utilisateur </label>
                <input id="nom_d_utilisateur" type="text" name="nom_d_utilisateur" class="form-control
                <?php if (isset($error["user_name"])) {
                            echo "is-invalid";
                        } ?>" placeholder="Entrer votre nom d'utilisateur" value= <?php echo isset($error)? htmlspecialchars($nom_utilisateur) : ""?> >
                <p> <small class="text-danger"> <?php if(isset($error["user_name"])) { echo htmlspecialchars($error['user_name']); 
                    unset($error["user_name"]) ; } ?> </small></p>
            
            </div>
            <div class="p-2 form-group form-password-toggle ">
                <label for="mot_de_passe" class="form-text fs-6 p-1">Mot de passe </label>
                <div class="input-group input-group-merge ">
                    <input id="mot_de_passe" type="password" name="mot_de_passe" class="form-control 
                    <?php if (isset($error["password"])) {
                            echo "is-invalid";
                        } ?>" placeholder="Entrer votre mot de passe" value= <?php echo isset($error)? htmlspecialchars($mot_de_passe) : ""?>>
                        <span class="input-group-text cursor-pointer"><i class="bi bi-eye" id="eyeToggle1"></i></span>
                </div>
                <p> <small class="text-danger"> <?php if(isset($error["password"])) { echo htmlspecialchars($error["password"]); 
                        unset($error["password"]) ; } ?> </small></p>
                
            </div> 

            <div class="p-2 text-center">
                <button type="submit" name="envoyer" class="btn btn-outline-primary"> Se connecter </button>
            </div>   
                
        </form>
        <p class=" text-center"> Êtes-vous nouveau sur le site ?  
            <a href="Creation_d_un_compte.php" class=" text-decoration-none">Créez un compte</a>
        </p>
    
        </section>
    </main>

    <?php require_once(__DIR__."/footer.php")?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
