<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de demande</title>
   
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
    

    <main class="container w-50 container-fluide my-5 p-5 shadow">
    <h4 class="text-center text-secondary "> Faites votre demandes ici ! </h4> 
    
        <form method="post" action="../PHP/traitement_de_la_demande.php">
            
                <div class="p-2">
                    <label class="form-text p-1 fs-6 " for="client"> Nom d'utilisateur </label>
                    <input id="client" type="text" name="client" class="form-control fs-6" placeholder="Entrer votre nom d'utilisateur ">
                </div>
                <div class="p-2">
                    <label for="categorie" class="form-text p-1 fs-6 ">Categorie</label>
                    <input id="categorie" type="text" name="categorie" class="form-control" placeholder="Entrer une categorie pour votre description ">
                </div>
                <div class="p-2">
                    <label for="demande" class="form-text p-1 fs-6">Description</label>
                    <textarea name="demande" rows="5" cols="50" class="form-control" placeholder="Decrivez votre demande ici..."></textarea>
                </div>
                <div class="text-end">
                <button type="submit" name="envoyer" class="btn btn-outline-primary my-2"> Soumettre </button>
                </div>
                
          
        </form>
    
    </main>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>