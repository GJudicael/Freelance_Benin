<?php session_start();?>

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
</html>