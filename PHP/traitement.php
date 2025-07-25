<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");
require_once(__DIR__.'/../notifications/fonctions_utilitaires.php');
//$succes_demande = null;

if (isset($_POST['envoyer'])) {
   $description = htmlspecialchars($_POST['demande'] ?? '', ENT_QUOTES, 'UTF-8');
    $categorie = htmlspecialchars($_POST['categorie'] ?? '', ENT_QUOTES, 'UTF-8');
    $nom_utilisateur = htmlspecialchars($_POST['client'] ?? '', ENT_QUOTES, 'UTF-8');
    $titre = htmlspecialchars($_POST['titre'] ?? '', ENT_QUOTES, 'UTF-8');
    $budget = filter_input(INPUT_POST, 'budget', FILTER_VALIDATE_FLOAT);
    $date_souhaitee = htmlspecialchars($_POST['date_souhaitee'] ?? '', ENT_QUOTES, 'UTF-8');

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

    $user_id = $_SESSION["user_id"];
    $nomDutilisateur = $bdd->prepare('SELECT nomDUtilisateur FROM inscription WHERE id = :id');
    $nomDutilisateur->execute(['id' => $user_id]);
    $user = $nomDutilisateur->fetch(PDO::FETCH_ASSOC);

    if ($user['nomDUtilisateur'] !== $nom_utilisateur) {
        $erreur['nomDUtilisateur'] = "Nom d'utilisateur incorrect";
    }
$mots_interdits = file(__DIR__ . '/mots_interdits.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$texte = $description;
$texte_normalise = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $texte));
$texte_mots = preg_split('/\s+/', $texte_normalise);

foreach ($mots_interdits as $mot) {
    $mot_normalise = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $mot));
    foreach ($texte_mots as $mot_du_texte) {
        if ($mot_du_texte === $mot_normalise) {
            $update = $bdd->prepare("UPDATE inscription SET avertissement = avertissement + 1 WHERE id = :id");
            $update->execute(['id' => $user_id]);

            $check = $bdd->prepare("SELECT * FROM inscription WHERE id = :id");
            $check->execute(['id' => $user_id]);
            $user = $check->fetch(PDO::FETCH_ASSOC);

            if ($user['avertissement'] >= 3) {
                $archive = $bdd->prepare("
                    INSERT INTO bannis (nom, prenom, numero, email, nomDUtilisateur, photo, role)
                    VALUES (:nom, :prenom, :numero, :email, :nomDUtilisateur, :photo, :role)
                ");
                $archive->execute([
                    'nom' => $user['nom'],
                    'prenom' => $user['prenom'],
                    'numero' => $user['numero'],
                    'email' => $user['email'],
                    'nomDUtilisateur' => $user['nomDUtilisateur'],
                    'photo' => $user['photo'],
                    'role' => $user['role']
                ]);

                $delete = $bdd->prepare("DELETE FROM inscription WHERE id = :id");
                $delete->execute(['id' => $user_id]);

                session_destroy();
                header("Location: /freelance_benin/index.php");
                    exit();
            }
                    $message="❌ Mot interdit détecté (« $mot »). Vous avez reçu un avertissement. ⚠️";
                    ajouterNotification($message);
                    $erreur['description']="Mot interdit détecté $mot";
           // die();
        }
    }
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

       $_SESSION['$succes_demande'] = "Votre demande a été effectué avec succès. Contacter un freelanceur pour le lui attribué";
        header("Location: ../front_projet_EDL/Mesdemandes.php");
        exit;
    }
}
?>