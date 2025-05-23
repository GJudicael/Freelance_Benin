<?php session_start();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>

    
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
   
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="../assets/style.css">


    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>
<body>

<?php require_once(__DIR__."/header.php")?>


<main>
    <section class = "my-3 p-5 shadow">
        <p class="text-center"> <span class="fw-bold text-secondary site fs-4"> FreeBenin </span> est un site de freelance local qui met en relation les freelances béninois avec des clients à la recherche 
            de compétences spécifiques. Le site permettra aux freelances de créer un profil professionnel, de proposer leurs services 
            et de recevoir des offres de missions, tandis que les clients pourront publier des projets et entrer en contact avec des prestataires qualifiés. 
        </p>
    </section>
    
    <section class="my-3 p-5 shadow">

        <h4 class="mt-4 text-center text-primary historique"> HISTORIQUE DES DEMANDES </h4>
        <?php require_once(__DIR__."/historique.php"); ?> 
    </section>
</main>

<?php require_once(__DIR__."/footer.php")?>
<script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>