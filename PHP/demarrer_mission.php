<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

$user_id = $_SESSION["user_id"];
$stmt = $bdd->prepare("SELECT i.role FROM inscription i WHERE i.id=:user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $result['role'] !== 'freelance') {
    header("Location: ../front_projet_EDL/Mesmissions.php");
    exit();
}

// Vérification que la mission appartient bien au freelance
$stmt = $bdd->prepare("SELECT 
d.id ,
f.user_id
FROM demande d 
LEFT JOIN freelancers f ON f.id = d.freelancer_id 
WHERE d.id = ? AND f.user_id = ?");

$stmt->execute([$_POST['mission_id'], $user_id]);

if ($stmt->fetch()) {
    $bdd->prepare("UPDATE demande SET statut = 'en cours' WHERE id = ?")
       ->execute([$_POST['mission_id']]);
    $_SESSION['success'] = "Mission démarrée avec succès";
}

header("Location: ../front_projet_EDL/Mesmissions.php");
exit();