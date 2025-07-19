<?php

require_once(__DIR__ . "/../bdd/creation_bdd.php");

$user_id = $_SESSION['user_id']; // ⚠️ vérifier que l'utilisateur est bien connecté

$nom = $_POST['nom'] ?? '';
$nom_utilisateur = $_POST['nom_utilisateur'];
$email = $_POST['email'] ?? '';
$numero = $_POST['numero'] ?? '';
$description = $_POST['description'] ?? '';
$secteur = $_POST['secteur'] ?? '';
$site = $_POST['site'] ?? '';
$facebook = $_POST['facebook'] ?? '';
$linkdin = $_POST['linkdin'] ?? '';
$employes = $_POST['employes'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$pays = $_POST['pays'] ?? '';
$ville = $_POST['ville'] ?? '';
$adresse = $_POST['adresse'] ?? '';
$password = $_POST['mot_de_passe'] ?? '';

// Mise à jour sans mot de passe
if (isset($_POST["enregistrer"])) {

    if (empty($nom_utilisateur)) {
        $erreur = 'Ce champ est requis';
    }
    if (empty($password)) {
        $stmt = $bdd->prepare("UPDATE entreprise SET nom = ?, user_name = ?, email = ?, legal_id = ?, description = ?, activity_sector = ?, web_site = ?, facebook_url = ?, linkdin_url = ?,
        telephone = ?, nombre_employes = ?, pays = ?, ville = ?, adresse = ? WHERE id = ?");
        $stmt->execute([$nom, $nom_utilisateur, $email, $numero, $description, $secteur, $site, $facebook, $linkdin, $telephone, $employes, $pays, $ville, $adresse, $user_id]);

        $_SESSION['user_name'] = $nom_utilisateur;
    } else {
        $motDePasseHache = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $bdd->prepare("UPDATE entreprise SET nom = ?, user_name = ?, email = ?, legal_id = ?, description = ?, activity_sector = ?, web_site = ?, facebook_url = ?, linkdin_url = ?,
        telephone = ?, nombre_employes = ?, pays = ?, ville = ?, adresse = ?, motDepasse = ? WHERE id = ?");
        $stmt->execute([$nom, $nom_utilisateur, $email, $numero, $description, $secteur, $site, $facebook, $linkdin, $telephone, $employes, $pays, $ville, $adresse, $motDePasseHache, $user_id]);
        $_SESSION['user_name'] = $nom_utilisateur;
    }

    $succes = "Infomations changées avec succès";


}


?>