<?php
//if (session_status() === PHP_SESSION_NONE) {
    session_start();
//}

require_once(__DIR__."/../bdd/creation_bdd.php");
require_once(__DIR__.'/../notifications/fonctions_utilitaires.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $utilisateur_id = isset($_POST['utilisateur_id']) ? (int) $_POST['utilisateur_id'] : null;
$signale_par = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
$raison = trim($_POST['raison'] ?? '');
    //$signale_par = $_SESSION['user_id'] ?? null;
    if ($utilisateur_id === $signale_par)
        {
            echo "No1";
            exit;
        }

    if (!empty($raison)) {
    if ($utilisateur_id === $signale_par) {
        echo "❌ Vous ne pouvez pas vous signaler vous-même.";
        exit;
    }

    $stmt = $bdd->prepare("INSERT INTO signalements_profil (utilisateur_id, signale_par, raison) VALUES (:utilisateur_id, :signale_par, :raison)");
    $stmt->execute([
        'utilisateur_id' => $utilisateur_id,
        'signale_par' => $signale_par,
        'raison' => $raison
    ]);
    
    // pas de redirection ici
    echo "✅ Votre signalement a été pris en compte.";
    exit;
} else {
    echo "❌ Veuillez fournir une raison.";
    exit;
}

}
?>
