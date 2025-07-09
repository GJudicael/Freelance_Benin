<?php 
    
    require_once(__DIR__."/../PHP/traitement.php");
    if(!isset($_SESSION["connecte"]) || $_SESSION["connecte"]!== true){
        header('Location: ../index.php');
        exit();
    }
?>


<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de demande</title>
   
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
    <?php require_once(__DIR__."/header.php")?>

    <main class="container w-50 container-fluide my-5 p-5 shadow">

    <h4 class="text-center text-primary "> Faites vos demandes ici ! </h4> 

    
        <form method="post" action="">

        <?php 
            if(isset($message)){
                echo '<div class="alert alert-danger">' . $message .' </div>';
                unset($message);
            }
        ?>
            
                <div class="p-2">
                    <label class="form-text p-1 fs-6 " for="client"> Nom d'utilisateur </label>
                    <input id="client" type="text" name="client" class="form-control fs-6" placeholder="Entrer votre nom d'utilisateur " value ="<?= isset($erreur)? $_POST['client']: ''?>">
                    <p> <small class = "text-danger"> <?php echo isset($erreur['nomDUtilisateur'])? htmlspecialchars($erreur['nomDUtilisateur']): ''?></small></p>
                </div>
                <div class="p-2">
                    <label for="categorie" class="form-text p-1 fs-6 ">Catégotie</label>
                    <input id="categorie" type="text" name="categorie" class="form-control" placeholder="Entrer la catégorie de votre demande" value ="<?= isset($erreur)? $_POST['categorie']: ''?>">
                    <p> <small class = "text-danger"> <?php echo isset($erreur['categorie'])? htmlspecialchars($erreur['categorie']): ''?></small></p>
                </div>
                <div class="p-2">
                    <label for="titre" class="form-text p-1 fs-6 ">Titre</label>
                    <input id="titre" type="text" name="titre" class="form-control" placeholder="Entrer le titre de votre demande" value ="<?= isset($erreur)? $_POST['titre']: ''?>">
                    <p> <small class = "text-danger"> <?php echo isset($erreur['titre'])? htmlspecialchars($erreur['titre']): ''?></small></p>
                </div>
                <div class="p-2">
                    <label for="demande" class="form-text p-1 fs-6">Description</label>
                    <textarea name="demande" cols="50" class="form-control" placeholder="Decrivez votre demande ici..."> <?= isset($erreur)? $_POST['demande']: ''?></textarea>
                    <p> <small class = "text-danger"> <?php echo isset($erreur['description'])? htmlspecialchars($erreur['description']): ''?></small></p>
                </div>
                <div class="text-end">
                <button type="submit" name="envoyer" class="btn btn-outline-primary my-2"> Soumettre </button>
                </div>
                
          
        </form>
    
    </main>

    <?php require_once(__DIR__."/footer.php")?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>