<?php

require_once(__DIR__ . "/../bdd/creation_bdd.php");
require_once(__DIR__ . "/../PHP/upload_photo.php");
require_once(__DIR__ . "/../PHP/update_profile.php");
//require_once(__DIR__ . "/signaler_profil.php");


// Simulation d'utilisateur connecté (à remplacer par session et requête réelle)

$user_id = isset($_GET['id']) ? (int) $_GET['id'] : $_SESSION['user_id'];

//$user_name = isset($_GET['user_name']) ? $_GET['user_name'] : $_SESSION['user_name'];

$stmt = $bdd->prepare("SELECT  role FROM inscription WHERE id = ? ");
$stmt->execute([$user_id]);
$role = $stmt->fetch(PDO::FETCH_ASSOC);

if ($role['role'] === "entreprise"){
  header("Location: info_profile_entreprise.php?id=".$user_id);
  exit;
}


$stmt = $bdd->prepare("SELECT nom, prenom, email, numero, nomDUtilisateur, photo, role, admin FROM inscription WHERE id = ? ");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


$check = $bdd->prepare("SELECT * FROM freelancers WHERE user_id = ?");
$check->execute([$user_id]);
$freelancer = $check->fetch();

if ($freelancer && isset($freelancer['id'])) {
  $stmt2 = $bdd->prepare("SELECT d.description, d.categorie, d.titre, d.date_fin, n.comment FROM demande d
    INNER JOIN notation n 
    ON d.freelancer_id = n.freelancer_id
    WHERE d.freelancer_id = ? AND statut = 'terminé' ");

  $stmt2->execute([$freelancer['id']]);
  $projets = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}


// Récupération des notes pour les demandes terminées
$stmtRatings = $bdd->prepare("SELECT n.stars
                             FROM notation n
                             JOIN demande d ON n.order_id = d.id
                             JOIN freelancers f ON f.id = n.freelancer_id
                             WHERE f.user_id = :user_id
                             AND d.statut = 'terminé'");
$stmtRatings->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtRatings->execute();
$ratings = $stmtRatings->fetchAll(PDO::FETCH_ASSOC);

// Calcul de la moyenne des notes et du nombre de votants
$total_ratings = count($ratings);
$average_rating = 0;
if ($total_ratings > 0) {
  $sum = array_sum(array_column($ratings, 'stars'));
  $average_rating = round($sum / $total_ratings, 1);
}

$_SESSION['photo'] = $user['photo'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon profile</title>

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

<body>

  <?php require_once(__DIR__ . "/header.php") ?>

  <div class="container py-5">
    <h2 class="mb-4">Profil</h2>

    <div class="row">
      <!-- Profile public -->
      <?php if (isset($_GET['id'])): ?>
        <div class="col-md-4">
          <img src="../photo_profile/<?= htmlspecialchars($_SESSION['photo']) ?>" class=" mb-3 rounded-circle"
            width="150px" height="150px" alt="Photo de profil">
          <?php if ($user['role'] === 'freelance'): ?>

            <div class="mb-3">
              <h5>Note moyenne:</h5>
              <div class="rating">
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
                <span>(<?= $total_ratings ?> avis)</span>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <div class="col-md-8">
          <div id="infos-affichage">
            <?php if (isset($succes)) {
              echo '<div class="alert alert-success">' . htmlspecialchars($succes) . ' </div>';
            } ?>
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
            <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['nomDUtilisateur']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Numéro de téléphone :</strong> <?= htmlspecialchars($user['numero']) ?></p>
            <?php if ($freelancer): ?>

              <p> <strong> Biographie</strong></p>
              <p><?= nl2br(htmlspecialchars($freelancer['bio'])) ?></p>

              <p> <strong> Compétences </strong></p>
              <p><?= htmlspecialchars($freelancer['competences']) ?></p>


            <?php endif; ?>

            <div class="pt-3">
              <a href="../messagerie/discussions.php?user_id=<?= $_GET['id'] ?>" class="btn btn-info"> Me contacter </a>
            </div>

          <?php else: ?>
            <!-- Profile privé -->
            <!-- PHOTO -->
            <div class="col-md-4">
              <img src="../photo_profile/<?= htmlspecialchars($_SESSION['photo']) ?>" class=" mb-3 rounded-circle"
                width="150px" height="150px" alt="Photo de profil">

              <form action="" method="post" enctype="multipart/form-data">
                <input type="file" name="photo" class="form-control mb-2" accept="image/*">
                <p> <small class="text-danger">
                    <?php echo isset($message) ? htmlspecialchars($message) : "";
                    echo isset($erreur["format"]) ? htmlspecialchars($erreur["format"]) : "";
                    echo isset($erreur["fichier"]) ? htmlspecialchars($erreur["fichier"]) : ""; ?>
                  </small></p>
                <button type="submit" name="changer" class="btn btn-primary btn-sm">Changer la photo</button>
              </form>

              <?php if ($user['role'] === 'freelance'): ?>

                <div class="my-3">
                  <h5>Note moyenne:</h5>
                  <div class="rating">
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
                    <span>(<?= $total_ratings ?> avis)</span>
                  </div>
                </div>
              <?php endif; ?>
            </div>


            <!-- INFOS -->
            <div class="col-md-8">
              <!-- Affichage simple -->
              <div id="infos-affichage">
                <?php if (isset($succes)) {
                  echo '<div class="alert alert-success">' . htmlspecialchars($succes) . ' </div>';
                } ?>
                <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
                <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['nomDUtilisateur']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Numéro de téléphone :</strong> <?= htmlspecialchars($user['numero']) ?></p>

                <?php if ($freelancer): ?>
                  <p> <strong> Domaine(s) de spécialité </strong></p>
                  <p><?= nl2br(htmlspecialchars($freelancer['bio'])) ?></p>

                  <p> <strong> Compétences </strong></p>
                  <p><?= htmlspecialchars($freelancer['competences']) ?></p>

                  <p> <strong> Linkdin </strong></p>
                  <p><a class="text-decoration-none" href="<?= htmlspecialchars($freelancer['linkdin']) ?>">
                      <?= htmlspecialchars($freelancer['linkdin']) ?></a></p>

                  <p> <strong> GitHub </strong></p>
                  <p><a class="text-decoration-none" href="<?= htmlspecialchars($freelancer['gitHub']) ?>">
                      <?= htmlspecialchars($freelancer['gitHub']) ?></a></p>

                <?php endif; ?>

                <?php if ($user['email'] !== 'decouverte_de_platform@gmail.com') : ?>
                    <button class="btn btn-outline-primary" onclick="afficherFormulaire()">Modifier mes informations</button>
                <?php endif; ?>
                <?php if ($user['role'] === 'client') : ?>

                  <div class="alert alert-info mt-4">
                    Vous êtes actuellement en mode <strong>Client</strong>.

                    <form method="POST" action="profile_freelance.php" class="d-inline">
                      <input type="hidden" name="switch_role" value="freelancer">
                      <button type="submit" class="btn btn-sm btn-primary ms-3">Passer en mode Freelance</button>
                    </form>
                  </div>
                <?php else: ?>
                  <div class=" my-2 ">
                    <strong> Statut : </strong> Freelancer
                  </div>
                  <p><a href="profile_freelance.php" class="btn btn-info"> Complèter mon profile </a></p>
                <?php endif; ?>

              </div>

              <!-- Formulaire caché -->
              <div id="infos-formulaire" style="display: none;">
                <form action="" method="post">
                  <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>">

                  </div>
                  <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control"
                      value="<?= htmlspecialchars($user['prenom']) ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="nomDUtilisateur" class="form-control"
                      value="<?= htmlspecialchars($user['nomDUtilisateur']) ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Numéro</label>
                    <input type="tel" name="numero" class="form-control" value="<?= htmlspecialchars($user['numero']) ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Mot de passe <small>(laisser vide pour ne pas changer)</small></label>
                    <input type="password" name="password" class="form-control" placeholder="Nouveau mot de passe">
                  </div>
                  <button type="submit" name="enregistrer" class="btn btn-success">Enregistrer</button>
                  <button type="button" class="btn btn-secondary" onclick="annulerFormulaire()">Annuler</button>
                </form>
              </div>
            </div>
          </div>
          <hr>
          <?php if ($user['admin'] === 'admin'): ?>
            <div class="mt-4 p-3  bg-light text-center">
              <h5 class="text-warning pb-2">⚙️ Accès Administrateur</h5>
              <a href="http://localhost/freelance_benin/Page_administracteur/admin.php" class="btn btn-warning">
                🛡️Aller au Dashboard
              </a>

            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>



    </div>
    <div>
      <?php if (isset($_GET['id']) && $freelancer): ?>
        <?php if (!empty($projets)): ?>
          <hr class="my-3">
          <h4 class=" text-center text-warning">Projets réalisés</h4>

          <div id="freelancerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <!-- Slides ici -->
              <?php
              $count = count($projets);
              $perSlide = 3;
              $chunked = array_chunk($projets, $perSlide);
              $active = true;
              foreach ($chunked as $group): ?>
                <div class="carousel-item <?= $active ? 'active' : '' ?>">
                  <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-3 ">

                    <?php foreach ($group as $projet): ?>

                      <div class="col">
                        <div class="card h-100">

                          <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($projet['titre']) ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($projet['description'])) ?></p>
                            <p class="card-text mt-3"> Commentaire : <?= nl2br(htmlspecialchars($projet['comment'])) ?> </p>

                          </div>
                          <div class="card-footer text-dark bg-warning">
                            Réalisé le : <?= htmlspecialchars($projet['date_fin']) ?>
                          </div>
                        </div>
                      </div>

                    <?php endforeach; ?>
                  </div>
                </div>
                <?php $active = false; endforeach; ?>

              <!-- Contrôles -->
              <button class="carousel-control-prev px-3" type="button" data-bs-target="#freelancerCarousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-black opacity-50"></span>
              </button>
              <button class="carousel-control-next px-3" type="button" data-bs-target="#freelancerCarousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon bg-black opacity-50"></span>

              <?php else: ?>
                <p class="mt-4 text-muted">Aucun projet ajouté pour le moment.</p>
              <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($_GET['id']) && $user["admin"] !== "admin"): 
              $profil_id = isset($_GET['id']) ? (int) $_GET['id'] : null;?>

              <div class="container">
  <!-- Bouton Signaler -->
  <button class="btn btn-outline-danger btn-sm" onclick="toggleSignalement()">🚩 Signaler le profil</button>

  <!-- Zone de message -->
  <div id="message-signalement" class="mt-2"></div>

  <!-- Formulaire de signalement -->
  <form id="form-signalement" class="mt-3 d-none">
    <input type="hidden" name="utilisateur_id" value="<?= htmlspecialchars($profil_id) ?>">
    <textarea name="raison" class="form-control mb-2" rows="3" placeholder="Expliquez la raison du signalement..." required></textarea>
    <button type="submit" class="btn btn-danger btn-sm">Envoyer le signalement</button>
  </form>
</div>



              
            <?php endif; ?>


        </div>



        <script>
          function afficherFormulaire() {
            document.getElementById('infos-affichage').style.display = 'none';
            document.getElementById('infos-formulaire').style.display = 'block';
          }

          function annulerFormulaire() {
            document.getElementById('infos-formulaire').style.display = 'none';
            document.getElementById('infos-affichage').style.display = 'block';
          }

  function toggleSignalement() {
    document.getElementById('form-signalement').classList.toggle('d-none');
    document.getElementById('message-signalement').innerHTML = ''; // on efface l'ancien message
  }

  document.getElementById('form-signalement').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const messageDiv = document.getElementById('message-signalement');

    fetch('signaler_profil.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.text())
      .then(response => {
        if (response.includes("✅") || response.includes("pris en compte")) {
          messageDiv.innerHTML = `<div class="alert alert-success">✅ Signalement envoyé avec succès !</div>`;
          form.classList.add('d-none');
          form.reset();
        } else if (response.includes("No1")) {
          messageDiv.innerHTML = `<div class="alert alert-warning">⚠️ Vous ne pouvez pas vous signaler vous-même.</div>`;
        } else {
          messageDiv.innerHTML = `<div class="alert alert-danger">${response}</div>`;
        }
      })
      .catch(error => {
        console.error("Erreur:", error);
        messageDiv.innerHTML = `<div class="alert alert-danger">Une erreur est survenue. Veuillez réessayer plus tard.</div>`;
      });
  });


        </script>
        <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>