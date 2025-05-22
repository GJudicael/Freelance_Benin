<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

$user_id = $_SESSION['user_id']; // ⚠️ assure-toi que l'utilisateur est connecté

// Vérifier si un fichier a été envoyé
if(isset($_POST["changer"])){

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $fichier = $_FILES['photo'];
    $nomTemporaire = $fichier['tmp_name'];
    $nomOriginal = basename($fichier['name']);
    $taille = $fichier['size'];
    $extension = strtolower(pathinfo($nomOriginal, PATHINFO_EXTENSION));
    $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($extension, $extensionsAutorisees) && $taille < 2 * 1024 * 1024) { // max 2MB
        $nouveauNom = uniqid('profil_') . '.' . $extension;
        $cheminDestination = __DIR__ . "/../PHP/" . $nouveauNom;

        if (move_uploaded_file($nomTemporaire, $cheminDestination)) {
            // Mise à jour dans la base
            $sql = $bdd->prepare("UPDATE inscription SET photo = ? WHERE id = ?");
            $sql->execute([$nouveauNom, $user_id]);

            
        } else {
            $message = "Erreur lors de l'envoi du fichier.";
        }
    } else {
        $erreur["format"] = "Format de fichier non autorisé ou fichier trop volumineux.";
    }
} else {
    $erreur["fichier"] = "Aucun fichier reçu.";
}
}