<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_POST['id'] ?? null;
$type = $_POST['type'] ?? '';

if ($id && $type) {
    switch ($type) {
        case 'client':
        case 'entreprise':
            $stmt = $bdd->prepare("DELETE FROM inscription WHERE id = ?");
            break;
        case 'freelancer':
            $stmt = $bdd->prepare("DELETE FROM freelancers WHERE user_id = ?");
            break;
        case 'demande':
            $stmt = $bdd->prepare("DELETE FROM demande WHERE id = ?");
            break;
        default:
            exit("❌ Type non reconnu");
    }

    $stmt->execute([$id]);
}

header("Location: recherche.php");
exit();
?>
