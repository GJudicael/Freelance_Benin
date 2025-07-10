<!-- 
<?php
require_once(__DIR__."/../bdd/creation_bdd.php");

if (isset($_GET['token'])) {
    $token = $_GET['token']; // Récupère le token depuis l'URL

    // Recherche l'utilisateur avec ce token
    $stmt = $bdd->prepare("SELECT * FROM inscription WHERE token = :token AND est_confirme = FALSE");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Si l'utilisateur existe et n'est pas encore confirmé, on confirme le compte
        $update = $bdd->prepare("UPDATE inscription SET est_confirme = TRUE, token = NULL WHERE id = :id");
        $update->execute(['id' => $user['id']]);

        echo "✅ Votre compte a été confirmé avec succès. <p><a href='http://localhost/Freelance_Benin-master/front_projet_EDL/Connexion.php'>Vous pouvez maintenant vous connecter</a></p>.";
    } else {
        echo "❌ Lien invalide ou compte déjà confirmé.";
    }
} else {
    echo "❌ Aucun token fourni dans le lien.";
}
?>
=======
<?php session_start();
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
    <title>Info</title>

    
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="../assets/style.css">


    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>
<body>
    
    <div class="d-flex justify-content-center m-auto align-items-center">
      <?php 
                
            if(isset($_SESSION["mail_envoye"] )){ ?>

            <div class="container alert alert-info p-5 text-center" role="alert">
                 <i class="bi bi-info-circle" style="font-size: 2rem;"> </i>
                <p class="py-3"> <?php echo htmlspecialchars($_SESSION["mail_envoye"]);
                    unset($_SESSION["mail_envoye"] );?> 
                </p>
            </div>

        <?php
            }
        ?>  
    </div>

    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html> -->
