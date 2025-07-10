<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . "/../bdd/creation_bdd.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $demande_id = $_POST['demande_id'] ?? null;
    $raison = trim($_POST['raison'] ?? '');
    $signale_par = $_SESSION['user_id'] ?? null;

    if ($demande_id && $signale_par && !empty($raison)) {
        // Vérifie que la demande existe
        $check = $bdd->prepare("SELECT id FROM demande WHERE id = :id");
        $check->execute(['id' => $demande_id]);
        if (!$check->fetch()) {
            echo "❌ Demande introuvable.";
            exit();
        }

        // Vérifie qu'elle n'a pas déjà été signalée par cet utilisateur
        $doublon = $bdd->prepare("SELECT id FROM signalements WHERE demande_id = :demande_id AND signale_par = :signale_par");
        $doublon->execute([
            'demande_id' => $demande_id,
            'signale_par' => $signale_par
        ]);
        if ($doublon->fetch()) {
            echo "⚠️ Vous avez déjà signalé cette demande.";
            exit();
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
        echo "❌ Données invalides ou incomplètes.";
    }
}
?>
