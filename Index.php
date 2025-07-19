<?php
    require_once(__DIR__.'/bdd/creation_bdd.php'); 
    require_once(__DIR__.'/PHP/traitement_de_la_connexion.php');
?>

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
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class=" d-flex flex-column min-vh-100">
    <nav class="navbar navbar-light bg-light static-top">
        <div class="container">
            <a class="navbar-brand text-secondary site fs-4 fw-bolder" href="#!">FreeBenin</a>
            <a class="btn btn-primary" href="front_projet_EDL/Connexion.php">Connexion</a>
        </div>
    </nav>
    <header class="masthead flex-fill">
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <div class="text-center text-white">
                        <h1 class="mb-5"> Bienvenus sur FreeBenin</h1>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <a href="front_projet_EDL/Creation_d_un_compte.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus me-2"></i> Créer un compte ordinaire
                    </a>
                    <a href="front_projet_EDL/compte_entreprise.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-building me-2"></i> Créer un compte entreprise
                    </a>
                </div>
            </div>

            <div class="text-center text-white">
                <h3 class="py-5 fw-semibold fst-italic" style="
        font-family: 'Fraunces', serif;
        font-size: 1.6rem;
        letter-spacing: 0.5px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    ">
                    Connecter les talents du Bénin au monde
                </h3>
            </div>

        </div>

        </header>
        
        <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
   <a href="#" class="btn btn-success btn-lg" onclick="envoyerVersConnexion()">
    <i class="bi bi-eye me-2"></i> Découvrir la plateforme
</a>

    <a href="http://localhost/freelance_benin/HTML/Documentation" class="btn btn-secondary btn-lg">
        <i class="bi bi-journal-text me-2"></i> Lire la documentation
    </a>
</div>
<script>
function envoyerVersConnexion() {
    const donnees = new URLSearchParams();
    donnees.append('nom_d_utilisateur', 'Utilisateur'); // à adapter dynamiquement
    donnees.append('mot_de_passe', 'JUDICAEL1234'); // à sécuriser
    donnees.append('envoyer', '1');

    fetch('PHP/traitement_de_la_connexion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: donnees
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url; // Redirige vers accueil.php si succès
        } else {
            return response.text();
        }
    })
    .then(data => {
        if (data) console.log("Réponse serveur :", data); // utile pour voir les erreurs
    })
    .catch(error => console.error("Erreur fetch :", error));
}
</script>

</body>

</html>