<?php
session_start();
require_once(__DIR__ . "/../bdd/creation_bdd.php");

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

        $_SESSION["succes"] = "Votre compte a été confirmé avec succès ";

    }/* else {
      $stmt = $bdd->prepare("SELECT * FROM entreprise WHERE token = :token AND est_confirme = FALSE");
      $stmt->execute(['token' => $token]);
      $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($entreprise) {
          $update = $bdd->prepare("UPDATE entreprise SET est_confirme = TRUE, token = NULL WHERE id = :id");
          $update->execute(['id' => $entreprise['id']]);

          $_SESSION["succes"] = "Le compte entreprise a été confirmé avec succès ";
      } else {
          $_SESSION["erreur"] = "Lien de confirmation invalide ou déjà utilisé ❌";
      }
  }*/
}



if (isset($_POST['envoyer'])) {
    $error = [];
    $nom_utilisateur = $_POST['nom_d_utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($nom_utilisateur) || empty($mot_de_passe)) {
        $message_error = "Tous les champs sont requis";
    } else {


        // Vérifier si l'utilisateur est banni
        $checkBan = $bdd->prepare("SELECT * FROM bannis WHERE nomDUtilisateur = :nom");
        $checkBan->execute(['nom' => $nom_utilisateur]);
        $banni = $checkBan->fetch(PDO::FETCH_ASSOC);

        if ($banni) {
            $message_error = '<div class="text-center">🚫 Ce compte a été banni le ' . date('d/m/Y à H:i', strtotime($banni['date_bannissement'])) . '</div>';
        } else {
            // Vérifier si l'utilisateur existe et récupérer ses infos
            $stmt = $bdd->prepare("SELECT * FROM inscription WHERE nomDUtilisateur = :nom");
            $stmt->execute(['nom' => $nom_utilisateur]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                if (!$user['est_confirme']) {
                    $message_error = ' Veuillez confirmer votre compte via le lien reçu par email.';
                } elseif (password_verify($mot_de_passe, $user['motDePasse'])) {
        // Vérifier si l'utilisateur a un abonnement actif
                $aujourdhui = date('Y-m-d');
                if (!empty($user['date_fin_abonnement']) && $user['date_fin_abonnement'] >= $aujourdhui) {
                    $_SESSION["user_name"] = $user['nomDUtilisateur'];
                    $_SESSION["user_id"] = $user['id'];
                    $_SESSION['connecte'] = true;

                    header("Location: http://localhost/freelance_benin/front_projet_EDL/accueil.php");
                    exit();
                } else {
                    $message_error = '⏳ Votre abonnement est expiré. Veuillez le renouveler pour accéder à votre espace.';
                }
            }
 else {
                    $error["password"] = "Mot de passe incorrect";
                }
            } else {
                $error["user_name"] = "Nom d'utilisateur incorrect";
            }
        }


    }
}
?>