<?php session_start() ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panneau d'administration</title>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="../assets/style.css">


    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>
<body class=" bg-light d-flex flex-column min-vh-100">
    <?php require_once(__DIR__."/../front_projet_EDL/header.php") ?>

    <main class=" flex-fill container my-5 text-center">
        
        <h3 class="mb-4 text-secondary"> 🎛️ Interface d'administration</h3>

            <div class="shadow py-5 ">
                <div class="d-grid gap-4 col-6 mx-auto ">
                <a href="signalements_demandes.php" class="btn btn-outline-primary btn-lg">📝 Signalements de demandes</a>
                <a href="signalements_profils.php" class="btn btn-outline-danger btn-lg">👤 Signalements de profils</a>
                </div>
            </div>
                   
   
    </main>
    

    <?php require_once(__DIR__."/../front_projet_EDL/footer.php") ?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
