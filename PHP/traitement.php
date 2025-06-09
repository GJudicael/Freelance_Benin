<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

if (isset($_POST['envoyer'])) {
    $description = $_POST['demande'];
    $categorie = $_POST['categorie'];
    $nom_utilisateur = $_POST['client'];
    $titre = $_POST['titre'];

    if(!isset($description,$categorie,$nom_utilisateur)){
        $message = "Veillez remplir tous les champs";
    }

    if(empty($description)){
        $erreur['description'] = "Ce champ est requis";
    }
    if(empty($titre)){
        $erreur['titre'] = "Ce champ est requis";
    }
    if(empty($categorie)){
        $erreur['categorie'] = "Ce champ est requis";
    }
<<<<<<< HEAD
     if(empty($nom_utilisateur)){
        $erreur['nomDUtilisateur'] = "Ce champ est requis";
    }
=======
>>>>>>> 660380d9405d989d635c3099e728cf4dce17fcff

    $user_id = $_SESSION["user_id"];
    $nomDutilisateur = $bdd->prepare('SELECT nomDUtilisateur FROM inscription WHERE id = :id');
    $nomDutilisateur->execute([
        'id' => $user_id
    ]);
    $user = $nomDutilisateur->fetch(PDO::FETCH_ASSOC);

    if($user['nomDUtilisateur'] !== $nom_utilisateur){
        $erreur['nomDUtilisateur'] = "Nom d'utilisateur incorrect";
    }

    if(empty($erreur)){
        $requete = $bdd->prepare('INSERT INTO demande (description, categorie,titre, user_id, date_soumission) VALUES (:description, :categorie,:titre, :user_id, :date)');
        $requete->execute([
            'description' => $description,
            'categorie' => $categorie,
            'titre' => $titre,
            'user_id' => $user_id,
            'date' => date('Y-m-d')
        ]);

        header("Location: accueil.php");
        exit();
    }

    
}
?>
