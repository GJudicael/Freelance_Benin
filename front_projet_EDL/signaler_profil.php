<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__."/../bdd/creation_bdd.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $utilisateur_id = $_POST['utilisateur_id'] ?? null;
    $raison = trim($_POST['raison'] ?? '');
    $signale_par = $_SESSION['user_id'] ?? null;

    if (!empty($raison)) {
        // Debug si besoin :
        // echo "<pre>"; print_r($_POST); print_r($_SESSION); echo "</pre>"; 

        $stmt = $bdd->prepare("INSERT INTO signalements_profil (utilisateur_id, signale_par, raison) VALUES (:utilisateur_id, :signale_par, :raison)");
        $stmt->execute([
            'utilisateur_id' => $utilisateur_id,
            'signale_par' => $signale_par,
            'raison' => $raison
        ]);

        header("Location: ../front_projet_EDL/info_profile.php?id=$utilisateur_id&signalement=ok");
        exit();
    } else {
        echo "❌ Veuillez fournir une raison.";
    }
}
?>
