<?php session_start();
    if(!isset($_SESSION["connecte"]) || $_SESSION["connecte"]!== true){
        header('Location: ../index.php');
        exit();
    }
require_once(__DIR__ . "/../bdd/creation_bdd.php");

$user_id = $_SESSION["user_id"];

$smt = $bdd->prepare("SELECT i.nom, i.prenom, i.photo, f.bio, f.user_id FROM inscription i 
INNER JOIN freelancers f 
ON i.id = f.user_id 
WHERE f.user_id != ?");

$smt->execute([$user_id]);
$freelancers = $smt->fetchALl(PDO::FETCH_ASSOC);

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

    <?php require_once(__DIR__ . "/header.php") ?>
    <main>
        <section class="my-3 p-5 shadow">
            <p class="text-center"> <span class="fw-bold text-secondary site fs-4"> FreeBenin </span> est un site de freelance local qui met en relation les freelances béninois avec des clients à la recherche
                de compétences spécifiques. Le site permettra aux freelances de créer un profil professionnel, de proposer leurs services
                et de recevoir des offres de missions, tandis que les clients pourront publier des projets et entrer en contact avec des prestataires qualifiés.
            </p>
        </section>
        <section class=" my-4 py-4 bg-body-secondary">
            <h4 class="mb-2 text-center text-warning p-2 historique"> NOS FREELANCEURS </h4>

            <div id="freelancerCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <!-- Slides ici -->
                    <?php
                    $count = count($freelancers);
                    $perSlide = 3;
                    $chunked = array_chunk($freelancers, $perSlide);
                    $active = true;
                    foreach ($chunked as $group): ?>
                        <div class="carousel-item <?= $active ? 'active' : '' ?>">
                            <div class="row justify-content-center px-3">
                                <?php foreach ($group as $freelancer): ?>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card text-center p-3 border-0 bg-body-secondary">
                                            <img src="../photo_profile/<?= htmlspecialchars($freelancer['photo']) ?>" class="rounded-circle mx-auto d-block" alt="Freelancer" height="100" width="100">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($freelancer['nom']) ?> <?= htmlspecialchars($freelancer['prenom']) ?></h5>
                                                <p class="card-text"><?= htmlspecialchars($freelancer['bio']) ?></p>
                                                <a href="info_profile.php?id=<?= $freelancer['user_id'] ?>" class="btn btn-outline-warning">Voir Profil</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php $active = false;
                    endforeach; ?>
                </div>

                <!-- Contrôles -->
                <button class="carousel-control-prev px-3" type="button" data-bs-target="#freelancerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-black opacity-50"></span>
                </button>
                <button class="carousel-control-next px-3" type="button" data-bs-target="#freelancerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-black opacity-50"></span>
                </button>
            </div>

        </section>


        <section class="mb-3 p-5 shadow">

            <h4 class="mt-4 text-center text-primary historique"> DEMANDES PUBLIÉES </h4>
            <?php require_once(__DIR__ . "/historique.php"); ?>
        </section>
    </main>

    <?php require_once(__DIR__ . "/footer.php") ?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>