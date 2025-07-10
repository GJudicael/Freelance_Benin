
<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

if (isset($_POST['envoyer'])) {
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
           echo "🚫 Ce compte a été banni le " . date('d/m/Y à H:i', strtotime($banni['date_bannissement'])) . ".";
        } else {
            // Vérifier si l'utilisateur existe et récupérer ses infos
            $stmt = $bdd->prepare("SELECT * FROM inscription WHERE nomDUtilisateur = :nom");
            $stmt->execute(['nom' => $nom_utilisateur]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
    if (!$user['est_confirme']) {
        echo "⚠️ Veuillez confirmer votre compte via le lien reçu par email.";
    } elseif (password_verify($mot_de_passe, $user['motDePasse'])) {
        $_SESSION["user_name"] = $user['nomDUtilisateur'];
        $_SESSION["user_id"] = $user['id'];

        header("Location: accueil.php");
        exit();
    } else {
        $error["password"] = "Mot de passe incorrect";
    }
} else {
    $error["user_name"] = "Nom d'utilisateur incorrect";
}

        }
    }
}
?>

