<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de création de compte</title>
   
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
<body>

<main class="container w-50 shadow my-5 p-3">
   
    <h3 class="text-center text-primary">Créez votre compte</h3>
    <form method="post" action="../PHP/traitement_de_l_inscription.php">
       
            <div class="p-2">
                <label for="nom" class="form-text fs-6 p-1">Nom</label>
                <input id="nom" type="text" name="nom" class="form-control" placeholder="Entrer votre nom">
            </div>
            <div class="p-2">
                <label for="prenom" class="form-text  fs-6 p-1">Prénom(s)</label>
                <input id="prenom" type="text" name="prenom" class="form-control" placeholder="Entrer votre prenom">
            </div>
             <div class="p-2">
                <label for="nom_d_utilisateur" class="form-text  fs-6 p-1">Nom d'utilisateur</label>
                <input id="nom_d_utilisateur" type="text" name="nom_d_utilisateur" class="form-control" placeholder="Entrer un nom d'utilisateur">
            </div>
            <div class="p-2">
                <label for="numero" class="form-text  fs-6 p-1">Numéro de téléphone</label>
                <input id="numero" type="text" name="numero" class="form-control" placeholder="Entrer votre numero">
            </div>
            <div class="p-2">
                <label for="email" class="form-text  fs-6 p-1">Email</label>
                <input id="email" type="email" name="email" class="form-control" placeholder="Entrer votre adresse email">
            </div>
            <div class="p-2">
                <label for="mot_de_passe" class="form-text  fs-6 p-1">Mot de passe</label>
                <input id="mot_de_passe" type="password" name="mot_de_passe" class="form-control" placeholder="Entrer votre mot de passe">
            </div>
            <div class="p-2">
                <label for="mot_de_passe_confirmation" class="form-text  fs-6 p-1"> Confirmer votre mot de passe</label>
                <input id="mot_de_passe_confirmation" type="password" name="mot_de_passe_confirmation" class="form-control" placeholder="Entrer votre mot de passe">
            </div>
           
            <div class="text-center">
                <button type="submit" name="envoyer" class="btn btn-outline-primary mt-2"> Créer un compte </button>
            </div>
           
        
    </form>
</main>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>