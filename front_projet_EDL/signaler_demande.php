<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . "/../bdd/creation_bdd.php");
require_once(__DIR__ . "/../notifications/fonctions_utilitaires.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $demande_id = $_POST['demande_id'] ?? null;
    $raison = trim($_POST['raison'] ?? '');
    $signale_par = $_SESSION['user_id'] ?? null;

    if ($demande_id && $signale_par && !empty($raison)) {
       
        $check = $bdd->prepare("SELECT id FROM demande WHERE id = :id");
        $check->execute(['id' => $demande_id]);
        if (!$check->fetch()) {
            $erreur["introuvable"] = "❌ Demande introuvable.";
            exit();
        }

        
        $doublon = $bdd->prepare("SELECT id FROM signalements WHERE demande_id = :demande_id AND signale_par = :signale_par");
        $doublon->execute([
            'demande_id' => $demande_id,
            'signale_par' => $signale_par
        ]);
        if ($doublon->fetch()) {
            $erreur["deja_signale"] = "⚠️ Vous avez déjà signalé cette demande.";
           
        }

        // Insertion du signalement
            $stmt = $bdd->prepare("INSERT INTO signalements (demande_id, signale_par, raison) VALUES (:demande_id, :signale_par, :raison)");
            $stmt->execute([
                'demande_id' => $demande_id,
                'signale_par' => $signale_par,
                'raison' => $raison
            ]);

            // ✅ Mise à jour du statut de la demande à 'signalee'
            $update = $bdd->prepare("UPDATE demande SET statut = 'Signalee' WHERE id = :id");
            $update->execute(['id' => $demande_id]);

            // Redirection ou message de succès
            header("Location: accueil.php");
            exit();

    } else {
        $erreur["raison"] = " Veillez entrer la raison du signalement";
        

    }
}
?>
