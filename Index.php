<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeBenin</title>
   
    <link rel="stylesheet" href="assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">  

    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
     <nav class="navbar navbar-light bg-light static-top">
            <div class="container">
                <a class="navbar-brand text-secondary site fs-4 fw-bolder" href="#!">FreeBenin</a>
                <a class="btn btn-primary" href="front_projet_EDL/Connexion.php">Connexion</a>
            </div>
    </nav>
    <header class="masthead">
    <div class="container position-relative">
                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="text-center text-white">
                                <h1 class="mb-5"> Bienvenus sur FreeBenin</h1>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="mb-5 text-light"> Choisissez votre type de compte : </p>
                        <div class="d-flex justify-content-center gap-4">
                            <a href="front_end_EDL/Creation_d_un_compte_freelancer.php" class="btn btn-primary btn-lg"> Créer un compte client </a>
                            <a href="front_projet_EDL/Creation_d_un_compte.php" class="btn btn-primary btn-lg"> Créer un compte Freelance</a>
                        </div>
                    </div>
        </div>
        </header>
    
   <?php require_once(__DIR__."/front_projet_EDL/footer.php")?>
</body>
</html>
