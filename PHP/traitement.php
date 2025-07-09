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
     if(empty($nom_utilisateur)){
        $erreur['nomDUtilisateur'] = "Ce champ est requis";
    }


    $user_id = $_SESSION["user_id"];
    $nomDutilisateur = $bdd->prepare('SELECT nomDUtilisateur FROM inscription WHERE id = :id');
    $nomDutilisateur->execute([
        'id' => $user_id
    ]);
    $user = $nomDutilisateur->fetch(PDO::FETCH_ASSOC);

    if($user['nomDUtilisateur'] !== $nom_utilisateur){
        $erreur['nomDUtilisateur'] = "Nom d'utilisateur incorrect";
    }
$mots_interdits = file(__DIR__ . '/mots_interdits.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$texte = $description;
$texte_normalise = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $texte));

// Vérification
foreach ($mots_interdits as $mot) {
    $mot_normalise = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $mot));
    if (stripos($texte_normalise, $mot_normalise) !== false) {
        // Incrémenter le nombre d'avertissements
        $update = $bdd->prepare("UPDATE inscription SET avertissement = avertissement + 1 WHERE id = :id");
        $update->execute(['id' => $user_id]);

        // Vérifier le nombre d'avertissements après mise à jour
        $check = $bdd->prepare("SELECT * FROM inscription WHERE id = :id");
        $check->execute(['id' => $user_id]);
        $user = $check->fetch(PDO::FETCH_ASSOC);

        if ($user['avertissement'] >= 3) {
            // Archiver les données dans la table bannis
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

            // Supprimer le compte
            $delete = $bdd->prepare("DELETE FROM inscription WHERE id = :id");
            $delete->execute(['id' => $user_id]);

            // Déconnecter l'utilisateur
            session_destroy();

            die("🚫 Votre compte a été supprimé après 3 avertissements pour non-respect des règles.");
        }

        die("❌ Contenu refusé : mot interdit détecté (« $mot »). Vous avez reçu un avertissement. ⚠️\nAu bout de 3, votre compte sera supprimé.");
    }
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
