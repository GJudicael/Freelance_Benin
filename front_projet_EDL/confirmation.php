<?php
require_once(__DIR__."/../bdd/creation_bdd.php");

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

        echo "✅ Votre compte a été confirmé avec succès. <p><a href='http://localhost/Freelance_Benin-master/front_projet_EDL/Connexion.php'>Vous pouvez maintenant vous connecter</a></p>.";
    } else {
        echo "❌ Lien invalide ou compte déjà confirmé.";
    }
} else {
    echo "❌ Aucun token fourni dans le lien.";
}
?>
