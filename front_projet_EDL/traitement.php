<?php
include("refresh.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['demande'];
    $categorie = $_POST['categorie'];

    $requete = $bddPDO->prepare('INSERT INTO demande (description, categorie) VALUES (:description, :categorie)');
    $requete->execute([
        'description' => $description,
        'categorie' => $categorie
    ]);

    header("Location: accueil.php");
    exit();
}
?>
