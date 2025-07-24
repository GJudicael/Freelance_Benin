<?php
session_start();

require_once(__DIR__ . "/../bdd/creation_bdd.php");

$user_id = $_SESSION["user_id"] ?? 0;
$user_name = $_SESSION['user_name'] ?? '';

// Récupération des freelances
$smt = $bdd->prepare("SELECT i.nom, i.prenom, i.nomDUtilisateur, i.photo, f.bio, f.user_id FROM inscription i 
INNER JOIN freelancers f 
ON i.id = f.user_id 
WHERE f.user_id != ?");
$smt->execute([$user_id]);
$freelancers = $smt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des entreprises
$smt = $bdd->prepare("SELECT i.id, i.nom, i.photo, i.description FROM inscription i 
WHERE i.id != ? AND i.role = 'entreprise'");
$smt->execute([$user_id]);
$entreprise = $smt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des notes pour freelances
$stmtRatings = $bdd->prepare("SELECT 
    SUM(n.stars) AS total_note,
    f.user_id,
    n.freelancer_id,
    COUNT(*) AS occurence
FROM notation n
JOIN demande d ON n.order_id = d.id
JOIN freelancers f ON f.user_id = n.freelancer_id
WHERE f.user_id != :user_id 
AND d.statut = 'terminé'
GROUP BY freelancer_id");
$stmtRatings->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtRatings->execute();
$ratings = $stmtRatings->fetchAll(PDO::FETCH_ASSOC);

// Récupération des notes pour entreprises
$stmtRatingsentreprise = $bdd->prepare("SELECT 
    SUM(n.stars) AS total_note,
    i.id,
    n.freelancer_id,
    COUNT(*) AS occurence
FROM notation n
JOIN demande d ON n.order_id = d.id
JOIN inscription i ON i.id = n.freelancer_id
WHERE i.id != :user_id
AND d.statut = 'terminé' AND i.role = 'entreprise'
GROUP BY i.id");
$stmtRatingsentreprise->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtRatingsentreprise->execute();
$ratingsentreprise = $stmtRatingsentreprise->fetchAll(PDO::FETCH_ASSOC);

// Calcul moyennes notes freelances
$notes = [];
foreach ($ratings as $rating) {
    if (!empty($rating['user_id']) && $rating['occurence'] > 0) {
        $notes[$rating['user_id']] = $rating['total_note'] / $rating['occurence'];
    }
}

// Calcul moyennes notes entreprises
$notes_entreprise = [];
foreach ($ratingsentreprise as $rating) {
    if (!empty($rating['id']) && $rating['occurence'] > 0) {
        $notes_entreprise[$rating['id']] = $rating['total_note'] / $rating['occurence'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Accueil FreeBenin - Freelances & Entreprises</title>

    <!-- Bootstrap CSS -->
    <link href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1;
            padding: 2rem 1rem;
        }

        h3 {
            color: #0d6efd;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 0.8rem;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card img.rounded-circle {
            object-fit: cover;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            border-radius: 50px;
            padding: 0.4rem 1.5rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .rating i {
            font-size: 1.1rem;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #0d6efd;
            border-radius: 50%;
            padding: 0.5rem;
            filter: drop-shadow(0 0 3px rgba(0, 0, 0, 0.4));
        }

        .carousel-inner {
            padding: 0 1rem;
        }

        @media (max-width: 767.98px) {
            .card {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>

<body>

    <?php require_once(__DIR__ . "/header.php"); ?>

    <main class="container">

        <section class="mb-5 text-center">
            <p class="fs-4 text-secondary">
                <strong>FreeBenin</strong> est un site de freelance local qui met en relation les freelances béninois avec des clients à la recherche
                de compétences spécifiques. Le site permet aux freelances de créer un profil professionnel, de proposer leurs services
                et de recevoir des offres de missions, tandis que les clients peuvent publier des projets et entrer en contact avec des prestataires qualifiés.
            </p>
        </section>

        <!-- Section Freelancers -->
        <section class="mb-5">
            <h3 class="text-center">Nos Freelances</h3>
            <div id="freelancerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000">
                <div class="carousel-inner">
                    <?php
                    $perSlide = 3;
                    $chunked = array_chunk($freelancers, $perSlide);
                    $active = true;
                    foreach ($chunked as $group): ?>
                        <div class="carousel-item <?= $active ? 'active' : '' ?>">
                            <div class="row justify-content-center g-4">
                                <?php foreach ($group as $freelancer): ?>
                                    <?php if ($freelancer['prenom'] != 'Utilisateur') { ?>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card h-100 text-center shadow-sm">
                                                <img src="../photo_profile/<?= htmlspecialchars($freelancer['photo']) ?>" alt="Photo de <?= htmlspecialchars($freelancer['prenom']) ?>" class="rounded-circle mx-auto mt-3" height="120" width="120" />
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($freelancer['nom']) ?> <?= htmlspecialchars($freelancer['prenom']) ?></h5>
                                                    <p class="card-text flex-grow-1"><?= htmlspecialchars($freelancer['bio']) ?></p>
                                                    <div class="mb-3 rating">
                                                        <?php
                                                        $id = $freelancer['user_id'];
                                                        $moyenne = $notes[$id] ?? 0;
                                                        $full_stars = floor($moyenne);
                                                        $has_half_star = ($moyenne - $full_stars) >= 0.5;
                                                        for ($i = 1; $i <= 5; $i++): ?>
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
                                                    <a href="info_profile.php?id=<?= $freelancer['user_id'] ?>" class="btn btn-primary mt-auto">Voir Profil</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php
                    $active = false;
                    endforeach;
                    ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#freelancerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#freelancerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            </div>
        </section>


<section class=" my-4 py-4">
    <h3 class="mb-2 text-center fw-bold text-warning p-2 historique"> NOS ENTREPRISES </h3>

    <div id="entrepriseCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $count = count($entreprise);
            $perSlide = 3;
            $chunked = array_chunk($entreprise, $perSlide);
            $active = true;

            // Préparation des moyennes pour entreprises
            $notes_entreprise = [];
            foreach ($ratingsentreprise as $rating) {
                if (!empty($rating['id']) && $rating['occurence'] > 0) {
                    $notes_entreprise[$rating['id']] = $rating['total_note'] / $rating['occurence'];
                }
            }

            foreach ($chunked as $group): ?>
                <div class="carousel-item <?= $active ? 'active' : '' ?>">
                    <div class="row justify-content-center px-3">
                        <?php foreach ($group as $entreprise): ?>
                            <div class="col-lg-3 col-md-6 ">
                                <div class="card text-center py-3 my-2 border-0 ">
                                    <img src="../logo/<?= htmlspecialchars($entreprise['photo']) ?>"
                                        class="rounded-circle mx-auto d-block" alt="Entreprise" height="100" width="100">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold"><?= htmlspecialchars($entreprise['nom']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($entreprise['description']) ?></p>

                                        <div class="rating card-text mb-2">
                                            <?php
                                            $id = $entreprise['id'];
                                            //$moyenne = isset($notes_entreprise[$id]) ? round($notes_entreprise[$id], 1) : 0;
                                            //echo afficherEtoiles($moyenne);
                                            ?>

                                                    <div class="mb-3">
                                                    <h5>Note moyenne:</h5>
                                                    <div class="rating">
                                                    <?php
                                                    $id = $entreprise['id'];
                                                    $moyenne = $notes_entreprise[$id] ?? 0;
                                                    $full_stars = floor($moyenne);
                                                    $has_half_star = ($moyenne - $full_stars) >= 0.5;
                                                    for ($i = 1; $i <= 5; $i++): ?>
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
                                                <a href="info_profile_entreprise.php?id=<?= $entreprise['id'] ?>" class="btn btn-primary mt-auto">Voir Profil</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php
                    $active = false;
                    endforeach;
                    ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#entrepriseCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#entrepriseCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            </div>
        </section>

        <!-- Section Demandes Publiées -->
        <section class="mb-5 p-4 shadow bg-white rounded">
            <h4 class="text-center text-primary mb-4">Demandes Publiées</h4>
            <?php require_once(__DIR__ . "/historique.php"); ?>
        </section>

    </main>

    <?php require_once(__DIR__ . "/footer.php"); ?>

    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
