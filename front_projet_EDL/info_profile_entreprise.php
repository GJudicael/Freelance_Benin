<?php
session_start();
require_once(__DIR__ . "/../bdd/creation_bdd.php");
require_once(__DIR__ . "/../PHP/update_profile_entreprise.php");

$entreprise_id = isset($_GET['id']) ? (int) $_GET['id'] : $_SESSION['user_id'];

//$user_name = isset($_GET['user_name']) ? $_GET['user_name'] : $_SESSION['user_name'];

$stm = $bdd->prepare("SELECT * FROM inscription WHERE id = ?");
$stm->execute([$entreprise_id]);
$comp = $stm->fetch(PDO::FETCH_ASSOC);


$_SESSION['logo'] = $comp['photo'];


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile entreprise</title>

    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="../assets/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">

</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . "/header.php") ?>

    <main class="container bg-white my-4 flex-fill p-4">
        <div id="infos-affichage">
            <section class=" my-4 p-5">
                <div class="d-flex flex-row justify-content-center align-items-center gap-4">
                    <img src="../logo/<?= htmlspecialchars($_SESSION['logo']) ?>" class="rounded-circle me-5"
                        width="150px" height="150px" alt="logo">
                    <h1 class="fw-bold nom_entreprise"> <?= htmlspecialchars($comp['nom']) ?> </h1>
                </div>
                <div class="text-center pt-5 lh-1">
                    <p class=""> <?= htmlspecialchars($comp['description']) ?> </p>

                    <p> </i> <i><?= htmlspecialchars($comp['pays']) ?>,
                            <?= htmlspecialchars($comp['ville']) ?> </i> </p>
                    <p> <?= htmlspecialchars($comp['adresse']) ?></p>
                    <p> Numéro IFU : <?= htmlspecialchars($comp['legal_id']) ?></p>
                    <p> Nombre d'employés : <?= htmlspecialchars($comp['nombre_employes']) ?></p>
                </div>
            </section>
            <section class="px-5">
                <div>
                    <h5> Secteur d'activité </h5>
                    <hr>
                    <p class="ps-4 "> <?= htmlspecialchars($comp['activity_sector']) ?></p>
                </div>

                <div>
                    <h5> Réseaux Sociaux </h5>
                    <hr>
                    <ul class="px-4">
                        <li> <i class="bi bi-globe me-2"></i> <a href="<?= htmlspecialchars($comp['web_site']) ?>"
                                class="text-decoration-none  text-dark">
                                <?= htmlspecialchars($comp['web_site']) ?> </a> </li>
                        <li> <i class="bi bi-facebook me-2"></i> <a
                                href="<?= htmlspecialchars($comp['facebook_url']) ?>"
                                class="text-decoration-none text-dark">
                                <?= htmlspecialchars($comp['facebook_url']) ?></a></li>
                        <li><i class="bi bi-linkedin me-2"></i> <a href="<?= htmlspecialchars($comp['linkdin_url']) ?>"
                                class="text-decoration-none text-dark"><?= htmlspecialchars($comp['linkdin_url']) ?></a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h5> Contacts</h5>
                    <hr>
                    <ul class="px-4">
                        <li> <i class="bi bi-envelope me-2"></i><a href="https://gmail.com/"
                                .<?= htmlspecialchars($comp['email']) ?> class="text-decoration-none text-dark">
                                <?= htmlspecialchars($comp['email']) ?></a> </li>
                        <li> <i class="bi bi-telephone me-2"></i> <?= htmlspecialchars($comp['numero']) ?> </li>
                        <li></li>
                    </ul>

                </div>
            </section>
            
            <?php if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$entreprise_id): ?>               
                <span class=" d-flex justify-content-end me-5"><button class="btn btn-outline-primary"
                        onclick="afficherFormulaire()">Modifier mes
                        informations</button></span>

            <?php else: ?>
            <div class="pt-3">
              <a href="../messagerie/discussions.php?user_id=<?= $_GET['id'] ?>" class="btn btn-info"> Me contacter </a>
            </div>
            <?php endif; ?>

         
        </div>



        <div id="infos-formulaire" style="display: none;">
            <form action="" method="post">
                <div class="mb-3">
                    <label class="form-label" for="nom">Nom l'entreprise</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($comp['nom']) ?>">

                </div>

                <div class="mb-3">
                    <label for="description" class="form-text fs-6 p-1">Description de l'entreprise</label>
                    <textarea id="description" name="description"
                        class="form-control"><?= htmlspecialchars($comp['description']) ?></textarea>

                </div>

                <div class="mb-3">
                    <label for="secteur" class="form-text fs-6 p-1">Secteur d'activité</label>
                    <input id="secteur" type="text" name="secteur" class="form-control"
                        value="<?= htmlspecialchars($comp['activity_sector']) ?>">

                </div>

                <div class="mb-3 ">
                    <label for="email" class="form-text fs-6 p-1">Email</label>
                    <input id="email" type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($comp['email']) ?>">

                </div>

                <div class="mb-3">
                    <label for="site" class="form-text fs-6 p-1">Site web</label>
                    <input id="site" type="url" name="site" class="form-control"
                        value="<?= htmlspecialchars($comp['web_site']) ?>">

                </div>

                <div class="row">
                    <div class="py-2 col-md-6">
                        <label for="facebook" class="form-text fs-6 p-1">Facebook</label>
                        <input id="facebook" type="url" name="facebook" class="form-control"
                            value="<?= htmlspecialchars($comp['facebook_url']) ?>">

                    </div>

                    <div class="py-2 col-md-6">
                        <label for="linkdin" class="form-text fs-6 p-1">Linkdin</label>
                        <input id="linkdin" type="url" name="linkdin" class="form-control"
                            value="<?= htmlspecialchars($comp['linkdin_url']) ?>">

                    </div>
                </div>

                <div class="mb-3">
                    <label for="employes" class="form-text fs-6 p-1">Nombre d'employés </label>
                    <input id="employes" type="number" name="employes" class="form-control"
                        value="<?= htmlspecialchars($comp['nombre_employes']) ?>">

                </div>


                <div class="mb-3 ">
                    <label for="telephone" class="form-text fs-6 p-1">Téléphone</label>
                    <input id="telephone" type="text" name="telephone" class="form-control"
                        value="<?= htmlspecialchars($comp['numero']) ?>">

                </div>

                <div class="mb-3">
                    <label for="numero" class="form-text fs-6 p-1">Numéro IFU</label>
                    <input id="numero" type="number" name="numero" class="form-control" pattern="[0-9]{1,20}"
                        maxlength="20" value="<?= htmlspecialchars($comp['legal_id']) ?>">

                </div>


                <div class=" row ">
                    <div class="mb-3 col-md-6">
                        <label for="pays" class="form-text fs-6 p-1">Pays</label>
                        <input id="pays" type="text" name="pays" class="form-control"
                            value="<?= htmlspecialchars($comp['pays']) ?>">

                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="ville" class="form-text fs-6 p-1">Ville</label>
                        <input id="ville" type="text" name="ville" class="form-control"
                            value="<?= htmlspecialchars($comp['ville']) ?>">

                    </div>
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-text fs-6 p-1">Adresse</label>
                    <input id="adresse" type="text" name="adresse" class="form-control"
                        value="<?= htmlspecialchars($comp['adresse']) ?>">

                </div>

                <div class="mb-3 form-group form-password-toggle">
                    <label for="mot_de_passe" class="form-text fs-6 p-1">Mot de passe <small>(laisser vide pour ne pas
                            changer)</small></label>
                    <div class="input-group input-group-merge">
                        <input id="mot_de_passe" type="password" name="mot_de_passe" class="form-control"
                            aria-describedby="passwordHelp">
                        <span class="input-group-text cursor-pointer"><i class="bi bi-eye" id="eyeToggle1"></i></span>
                    </div>

                </div>

                <button type="submit" name="enregistrer" class="btn btn-success">Enregistrer</button>
                <button type="button" class="btn btn-secondary" onclick="annulerFormulaire()">Annuler</button>
            </form>
        </div>
    </main>

    <?php require_once(__DIR__ . "/footer.php") ?>

    <script>
        function afficherFormulaire() {
            document.getElementById('infos-affichage').style.display = 'none';
            document.getElementById('infos-formulaire').style.display = 'block';
        }

        function annulerFormulaire() {
            document.getElementById('infos-formulaire').style.display = 'none';
            document.getElementById('infos-affichage').style.display = 'block';
        }

    </script>

    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>

</body>

</html>