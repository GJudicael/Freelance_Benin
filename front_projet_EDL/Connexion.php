<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
   
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">

    <!-- Profil utilisateur -->
        
    <main class="container w-50 shadow p-5 my-5">
        <h3 class="text-center text-success">Connexion</h3>
        <form method="post" action="../PHP/traitement_de_la_connexion.php">
            
                <div class="p-2">
                    <label for="nom_d_utilisateur" class="form-text fs-6 p-1">Nom d'utilisateur </label>
                    <input id="nom_d_utilisateur" type="text" name="nom_d_utilisateur" class="form-control" placeholder="Entrer votre nom d'utilisateur">
                
                </div>
                <div class="p-2">
                    <label for="mot_de_passe" class="form-text fs-6 p-1">Mot de passe </label>
                    <input id="mot_de_passe" type="password" name="mot_de_passe" class="form-control" placeholder="Entrer votre mot de passe">
            
                </div>  
                <div class="p-2 text-center">
                    <button type="submit" name="envoyer" class="btn btn-outline-primary"> Se connecter </button>
                </div>   
                    
        </form>
        <p class="d-flex justify-content-center"> Vous êtes nouveau sur le site ?  
            <a href="Creation_d_un_compte.php" class="nav-link text-primary">Créer un compte</a>
        </p>
        
    </main>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>
 