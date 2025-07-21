<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . "/../bdd/creation_bdd.php");

$user_id = $_SESSION["user_id"];

// Récupération des freelanceurs en bdd
$smt = $bdd->prepare("SELECT i.nom, i.prenom, i.nomDUtilisateur, i.photo, f.bio, f.user_id FROM inscription i 
INNER JOIN freelancers f 
ON i.id = f.user_id 
WHERE f.user_id != ?");

$smt->execute([$user_id]);
$freelancers = $smt->fetchALl(PDO::FETCH_ASSOC);
var_dump($freelancers);
echo '<br>';


// Récupération des entreprises en bdd
$smt = $bdd->prepare("SELECT i.nom, i.photo, i.description FROM inscription i 
WHERE i.id != ? AND i.role = 'entreprise' ");

$smt->execute([$user_id]);
$entreprise = $smt->fetchALl(PDO::FETCH_ASSOC);
var_dump($entreprise);
echo '<br>';




// Récupération des notes en bdd pour les freelanceurs
$stmtRatings = $bdd->prepare("SELECT 
                              sum(n.stars) AS total_note,
                              n.freelancer_id ,  COUNT(*) AS occurence
                              FROM notation n
                              JOIN demande d ON n.order_id = d.id
                              JOIN freelancers f ON f.id = n.freelancer_id
                              WHERE f.user_id != :user_id 
                              AND d.statut = 'terminé'
                              GROUP BY freelancer_id");

$stmtRatings->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtRatings->execute();
$ratings = $stmtRatings->fetchAll(PDO::FETCH_ASSOC);
var_dump($ratings);
echo '<br>';



// Récupération des notes en bdd pour les entreprises
$stmtRatingsentreprise = $bdd->prepare("SELECT n.stars,n.freelancer_id
                             FROM notation n
                             JOIN demande d ON n.order_id = d.id
                             JOIN inscription i ON i.id = n.freelancer_id
                             WHERE i.id != :user_id
                             AND d.statut = 'terminé' AND i.role = 'entreprise' ");
$stmtRatingsentreprise->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtRatingsentreprise->execute();
$ratingsentreprise = $stmtRatingsentreprise->fetchAll(PDO::FETCH_ASSOC);
var_dump($ratingsentreprise);
echo '<br>';



// Calcul de la moyenne des notes et du nombre de votants pour les freelanceurs
$total_ratings = count($ratings);
$average_rating = 0;
if ($total_ratings > 0) {
    $sum = array_sum(array_column($ratings, 'stars'));
    $average_rating = round($sum / $total_ratings, 1);
}

// Calcul de la moyenne des notes et du nombre de votants pour les entreprises
$total_ratings = count($ratingsentreprise);
$average_rating = 0;
if ($total_ratings > 0) {
    $sum = array_sum(array_column($ratingsentreprise, 'stars'));
    $average_rating = round($sum / $total_ratings, 1);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>


    <link
        href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="../assets/style.css">

    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>

<body class=" bg-light d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . "/header.php") ?>

    <main class=" flex-fill text">

        <section class="my-3 p-5 shadow">
            <p class="text-center"> <span class="fw-bold text-secondary site fs-4"> FreeBenin </span> est un site de
                freelance local qui met en relation les freelances béninois avec des clients à la recherche
                de compétences spécifiques. Le site permettra aux freelances de créer un profil professionnel, de
                proposer leurs services
                et de recevoir des offres de missions, tandis que les clients pourront publier des projets et entrer en
                contact avec des prestataires qualifiés.
            </p>
        </section>
        <section class=" my-4 py-4 bg-black bg-opacity-75 ">
            <h3 class="mb-2 text-center fw-bold text-warning p-2 historique"> NOS FREELANCEURS </h3>

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
                                    <div class="col-lg-3 col-md-6 ">
                                        <div class="card text-center py-3 my-2 border-0 " style="width: 18vw;">
                                            <img src="../photo_profile/<?= htmlspecialchars($freelancer['photo']) ?>"
                                                class="rounded-circle mx-auto d-block" alt="Freelancer" height="100"
                                                width="100">
                                            <div class="card-body">
                                                <h5 class="card-title fw-bold"><?= htmlspecialchars($freelancer['nom']) ?>
                                                    <?= htmlspecialchars($freelancer['prenom']) ?>
                                                </h5>

                                                <p class="card-text"><?= htmlspecialchars($freelancer['bio']) ?>
                                                <div class="rating card-text mb-2">
                                                    <?php
                                                    $full_stars = floor($average_rating);
                                                    $has_half_star = ($average_rating - $full_stars) >= 0.5;
                                                    for ($i = 1; $i <= 5; $i++):
                                                        ?>
                                                        <i class="bi <?php
                                                        if ($i <= $full_stars) {
                                                            echo 'bi-star-fill text-warning';
                                                        } elseif ($has_half_star && $i == $full_stars + 1) {
                                                            echo 'bi-star-half text-warning';
                                                        } else {
                                                            echo 'bi-star';
                                                        }
                                                        ?>"></i>
                                                    <?php endfor; ?>

                                                </div>
                                                </p>
                                                <a href="info_profile.php?id=<?= $freelancer['user_id'] ?>"
                                                    class="btn btn-primary">Voir Profil</a>
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
                <button class="carousel-control-prev px-3" type="button" data-bs-target="#freelancerCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon "></span>
                </button>
                <button class="carousel-control-next px-3" type="button" data-bs-target="#freelancerCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon "></span>
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