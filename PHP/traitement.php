<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

if (isset($_POST['envoyer'])) {
    $description = filter_input(INPUT_POST, 'demande', FILTER_SANITIZE_STRING);
    $categorie = filter_input(INPUT_POST, 'categorie', FILTER_SANITIZE_STRING);
    $nom_utilisateur = filter_input(INPUT_POST, 'client', FILTER_SANITIZE_STRING);
    $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING);
    $budget = filter_input(INPUT_POST, 'budget', FILTER_VALIDATE_FLOAT);
    $date_souhaitee = filter_input(INPUT_POST, 'date_souhaitee', FILTER_SANITIZE_STRING);

    // Validation des champs requis
    if (empty($description)) {
        $erreur['description'] = "Ce champ est requis";
    }
    if (empty($titre)) {
        $erreur['titre'] = "Ce champ est requis";
    }
    if (empty($categorie)) {
        $erreur['categorie'] = "Ce champ est requis";
    }
    if (empty($nom_utilisateur)) {
        $erreur['nomDUtilisateur'] = "Ce champ est requis";
    }
    if ($budget === false || $budget === null) {
        $erreur['budget'] = "Le budget est requis";
    } elseif ($budget < 0) {
        $erreur['budget'] = "Le budget doit être un montant positif";
    }

    // Validation de la date souhaitée (facultatif)
    if (!empty($date_souhaitee)) {
        $today = new DateTime();
        $input_date = DateTime::createFromFormat('Y-m-d', $date_souhaitee);
        if (!$input_date || $input_date < $today) {
            $erreur['date_souhaitee'] = "La date souhaitée doit être dans le futur";
        }
    }

    // Validation du nom d'utilisateur
    $user_id = $_SESSION["user_id"];
    $nomDutilisateur = $bdd->prepare('SELECT nomDUtilisateur FROM inscription WHERE id = :id');
    $nomDutilisateur->execute(['id' => $user_id]);
    $user = $nomDutilisateur->fetch(PDO::FETCH_ASSOC);

    if ($user['nomDUtilisateur'] !== $nom_utilisateur) {
        $erreur['nomDUtilisateur'] = "Nom d'utilisateur incorrect";
    }

    // Insertion si pas d'erreurs
    if (empty($erreur)) {
        $requete = $bdd->prepare('
            INSERT INTO demande (description, categorie, titre, user_id, date_soumission, budget, date_souhaitee)
            VALUES (:description, :categorie, :titre, :user_id, :date_soumission, :budget, :date_souhaitee)
        ');
        $requete->execute([
            'description' => $description,
            'categorie' => $categorie,
            'titre' => $titre,
            'user_id' => $user_id,
            'date_soumission' => date('Y-m-d'),
            'budget' => $budget,
            'date_souhaitee' => !empty($date_souhaitee) ? $date_souhaitee : null
        ]);

        header("Location: accueil.php");
        exit();
    }
}
?>