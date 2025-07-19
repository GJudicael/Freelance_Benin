<?php

require_once(__DIR__ . "/../bdd/creation_bdd.php");

$user_id = $_SESSION['user_id']; // ⚠️ vérifier que l'utilisateur est bien connecté

$nom = $_POST['nom'] ?? '';
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


    if (empty($password)) {
        $stmt = $bdd->prepare("UPDATE inscription SET nom = ?, email = ?, legal_id = ?, description = ?, activity_sector = ?, web_site = ?, facebook_url = ?, linkdin_url = ?,
        numero = ?, nombre_employes = ?, pays = ?, ville = ?, adresse = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $numero, $description, $secteur, $site, $facebook, $linkdin, $telephone, $employes, $pays, $ville, $adresse, $user_id]);


    } else {
        $motDePasseHache = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $bdd->prepare("UPDATE inscription SET nom = ?, email = ?, legal_id = ?, description = ?, activity_sector = ?, web_site = ?, facebook_url = ?, linkdin_url = ?,
        numero = ?, nombre_employes = ?, pays = ?, ville = ?, adresse = ?, motDepasse = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $numero, $description, $secteur, $site, $facebook, $linkdin, $telephone, $employes, $pays, $ville, $adresse, $motDePasseHache, $user_id]);

    }

    $succes = "Infomations changées avec succès";


}


?>