<?php
header('Content-Type: application/json');
@session_start();

require_once(__DIR__ . "/../bdd/creation_bdd.php");
require_once(__DIR__ . "/../notifications/fonctions_utilitaires.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        'status' => 'error',
        'message' => '❌ Méthode non autorisée.'
    ]);
    exit();
}

$demande_id = $_POST['demande_id'] ?? null;
$raison = trim($_POST['raison'] ?? '');
$signale_par = $_SESSION['user_id'] ?? null;

if (!$demande_id || !$signale_par || empty($raison)) {
    echo json_encode([
        'status' => 'error',
        'message' => '❌ Veuillez entrer la raison du signalement.'
    ]);
    exit();
}

// Vérifie si la demande existe
$check = $bdd->prepare("SELECT id FROM demande WHERE id = :id");
$check->execute(['id' => $demande_id]);

if (!$check->fetch()) {
    echo json_encode([
        'status' => 'error',
        'message' => '❌ Demande introuvable.'
    ]);
    exit();
}

// Vérifie si ce user a déjà signalé cette demande
$doublon = $bdd->prepare("SELECT id FROM signalements WHERE demande_id = :demande_id AND signale_par = :signale_par");
$doublon->execute([
    'demande_id' => $demande_id,
    'signale_par' => $signale_par
]);

if ($doublon->fetch()) {
    echo json_encode([
        'status' => 'error',
        'message' => '⚠️ Vous avez déjà signalé cette demande.'
    ]);
    exit();
}

// Insertion du signalement
$stmt = $bdd->prepare("INSERT INTO signalements (demande_id, signale_par, raison) VALUES (:demande_id, :signale_par, :raison)");
$stmt->execute([
    'demande_id' => $demande_id,
    'signale_par' => $signale_par,
    'raison' => $raison
]);

// Comptage des signalements
$countStmt = $bdd->prepare("SELECT COUNT(*) FROM signalements WHERE demande_id = :demande_id");
$countStmt->execute(['demande_id' => $demande_id]);
$nbSignalements = $countStmt->fetchColumn();

// Suspension si nécessaire
if ($nbSignalements >= 10) {
    $update = $bdd->prepare("UPDATE demande SET statut = 'signalee' WHERE id = :id");
    $update->execute(['id' => $demande_id]);

    $reqDemande = $bdd->prepare("SELECT titre, user_id FROM demande WHERE id = ?");
    $reqDemande->execute([$demande_id]);
    $demandeInfos = $reqDemande->fetch(PDO::FETCH_ASSOC);

    $message = "⚠️ Votre demande intitulée \"" . $demandeInfos['titre'] . "\" a été signalée par plusieurs utilisateurs et est désormais suspendue.";
    ajouterNotification($message, $demandeInfos['user_id']);
}

// ✅ Réponse finale
echo json_encode([
    'status' => 'success',
    'message' => '✅ Signalement envoyé avec succès.'
]);
exit();
?>
