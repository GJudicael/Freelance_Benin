<?php
require_once(__DIR__ . "/../bdd/creation_bdd.php");

$action = $_POST['action_type'] ?? null;
$signalement_id = $_POST['demande_id'] ?? null;

if (!$action || !$signalement_id) {
    header("Location: signalements_demandes.php?erreur=1");
    exit();
}

switch ($action) {
    case 'supprimer':
        // Supprime la demande signalée et le signalement associé
        $demande = $bdd->prepare("SELECT demande_id FROM signalements WHERE id = :id");
        $demande->execute(['id' => $signalement_id]);
        $data = $demande->fetch();

        if ($data) {
            $bdd->prepare("DELETE FROM demande WHERE id = :demande_id")->execute(['demande_id' => $data['demande_id']]);
            $bdd->prepare("DELETE FROM signalements WHERE id = :id")->execute(['id' => $signalement_id]);
        }
        break;

    case 'retablir':
        // Supprime seulement le signalement, et restaure le statut de la demande
        $demande = $bdd->prepare("SELECT demande_id FROM signalements WHERE id = :id");
        $demande->execute(['id' => $signalement_id]);
        $data = $demande->fetch();

        if ($data) {
            $bdd->prepare("UPDATE demande SET statut = 'en attente' WHERE id = :id")->execute(['id' => $data['demande_id']]);
            $bdd->prepare("DELETE FROM signalements WHERE id = :id")->execute(['id' => $signalement_id]);
        }
        break;
}

header("Location: signalements_demandes.php?success=1");
exit();
?>
