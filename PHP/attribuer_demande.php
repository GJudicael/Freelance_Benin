<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");
require_once(__DIR__.'/../notifications/fonctions_utilitaires.php');
// Vérifications de sécurité
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['user_id'];
$demande_id = $_POST['demande_id'];
$freelance_username = trim($_POST['freelance_username']);

// Initialisation des variables
$_SESSION['error'] = null;
$_SESSION['success'] = null;

try {
    // 1. Vérifier que la demande appartient bien au client
    $req = $bdd->prepare("SELECT id FROM demande WHERE id = ? AND user_id = ?");
    $req->execute([$demande_id, $client_id]);
    
    if (!$req->fetch()) {
        throw new Exception("Cette demande ne vous appartient pas");
    }

    // 2. Vérifier que le freelanceur existe
    $req = $bdd->prepare("SELECT i.id, f.id as freelancer_id 
                         FROM inscription i
                         LEFT JOIN freelancers f ON i.id = f.user_id
                         WHERE i.nomDUtilisateur = ? AND i.role = 'freelance'");
    $req->execute([$freelance_username]);
    $freelance = $req->fetch();

    if (!$freelance || empty($freelance['freelancer_id'])) {
        throw new Exception("Freelanceur introuvable ou profil incomplet");
    }

    // 3. Mettre à jour la demande
    $req = $bdd->prepare("UPDATE demande SET 
                         freelancer_id = ?,
                         statut = 'attribué',
                         date_attribution = NOW()
                         WHERE id = ?");
    $success = $req->execute([$freelance['freelancer_id'], $demande_id]);

    if (!$success) {
        throw new Exception("Erreur lors de la mise à jour de la demande");
    }
// Récupérer les infos du demandeur
$stmtDemandeur = $bdd->prepare("SELECT nom, prenom FROM inscription WHERE id = :id");
$stmtDemandeur->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmtDemandeur->execute();
$demandeur = $stmtDemandeur->fetch(PDO::FETCH_ASSOC);

// Récupérer l’ID du freelance
$stmtFreelance = $bdd->prepare("SELECT id FROM inscription WHERE nomDUtilisateur = :username AND role = 'freelance'");
$stmtFreelance->bindParam(':username', $_POST['freelance_username'], PDO::PARAM_STR);
$stmtFreelance->execute();
$freelancer = $stmtFreelance->fetch(PDO::FETCH_ASSOC);

// Envoyer la notification
$stmtTitre = $bdd->prepare("SELECT titre FROM demande WHERE id = ?");
$stmtTitre->execute([$demande_id]);
$demande = $stmtTitre->fetch(PDO::FETCH_ASSOC);



if ($freelancer) {
    $receiver_id = $freelancer['id'];
    $message = $demandeur['nom'].' '.$demandeur['prenom'].' vous a attribué la mission : "' . $demande['titre'] . '".';
    ajouterNotification($message, $receiver_id);
}

    $_SESSION['success'] = "Demande attribuée avec succès à $freelance_username";
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header("Location: ../front_projet_EDL/Mesdemandes.php");
exit();