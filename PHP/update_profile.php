<?php

require_once(__DIR__."/../bdd/creation_bdd.php");

$user_id = $_SESSION['user_id']; // ⚠️ vérifier que l'utilisateur est bien connecté

$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$email = $_POST['email'] ?? '';
$numero = $_POST['numero'] ?? '';
$nom_utilisateur = $_POST['nomDUtilisateur']?? '';
$password = $_POST['password'] ?? '';

// Mise à jour sans mot de passe
if(isset($_POST["enregistrer"])){

    if (empty($password)) {
        $stmt = $bdd->prepare("UPDATE inscription SET nom = ?, prenom = ?, email = ?, numero = ?, nomDUtilisateur = ? WHERE id = ?");
        $stmt->execute([$nom, $prenom, $email, $numero, $nom_utilisateur, $user_id]);
    } else {
        $motDePasseHache = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $bdd->prepare("UPDATE inscription SET nom = ?, prenom = ?, email = ?, mot_de_passe = ?, numero = ?, nomDUtilisateur = ? WHERE id = ?");
        $stmt->execute([$nom, $prenom, $email, $motDePasseHache, $numero, $nom_utilisateur, $user_id]);
    }

    $succes = "Infomations changées avec succès";

    
}


?>