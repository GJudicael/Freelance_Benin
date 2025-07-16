<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__."/../bdd/creation_bdd.php"); // adapt pour ton chemin

// Active les erreurs PHP pour debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = [];
$message = null;

if (isset($_POST['envoyer'])) {

    // Nettoyage
    $nom = trim($_POST['nom'] ?? '');
    $nomUtilisateur = trim($_POST['nom_d_utilisateur'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $secteur = trim($_POST['secteur'] ?? '');
    $employes = trim($_POST['employes'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $site = trim($_POST['site'] ?? '');
    $facebook = trim($_POST['facebook'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $annee = trim($_POST['annee'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';
    $motDePasseConfirmation = $_POST['mot_de_passe_confirmation'] ?? '';

    // Validation
    if (empty($nom)) $error['nom'] = "Nom de l'entreprise requis";
    if (empty($nomUtilisateur)) $error['nomDutilisateur'] = "Nom d'utilisateur requis";
    if (!preg_match('/^\+?[0-9\s\-]{8,20}$/', $telephone)) $error['telephone'] = "Téléphone invalide";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error['email'] = "Email invalide";
    if (empty($site) || !filter_var($site, FILTER_VALIDATE_URL)) $error['site'] = "Site invalide";
    if (empty($facebook) || !preg_match('/^https?:\/\/(www\.)?facebook\.com\//', $facebook)) $error['facebook'] = "Lien Facebook invalide";
    if (empty($linkedin) || !preg_match('/^https?:\/\/(www\.)?linkedin\.com\//', $linkedin)) $error['linkedin'] = "Lien LinkedIn invalide";
    if (!preg_match('/^[0-9]{1,20}$/', $numero)) $error['numero'] = "Numéro IFU invalide";

    if (strlen($motDePasse) < 6 || !preg_match('/^[A-Z]/', $motDePasse) || !preg_match('/\d/', $motDePasse)) {
        $error['password'] = "Le mot de passe doit contenir au moins 6 caractères, commencer par une majuscule et contenir un chiffre";
    }
    if ($motDePasse !== $motDePasseConfirmation) {
        $error['pass_confirm'] = "Les mots de passe ne correspondent pas";
    }

    // Vérifier si utilisateur ou email déjà existants
    if (empty($error)) {
        // Vérif email
        $stmt = $bdd->prepare("SELECT * FROM entreprises WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $error['email'] = "Cet email existe déjà.";
        }

        // Vérif username
        $stmt = $bdd->prepare("SELECT * FROM entreprises WHERE nomDUtilisateur = :nom");
        $stmt->execute(['nom' => $nomUtilisateur]);
        if ($stmt->fetch()) {
            $error['nomDutilisateur'] = "Ce nom d'utilisateur existe déjà.";
        }
    }

    // Upload du logo
    $logoPath = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $allowed = ['png', 'jpg', 'jpeg', 'webp'];
        if (in_array(strtolower($ext), $allowed)) {
            $logoPath = "../uploads/".uniqid().".".$ext;
            move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath);
        }
    }

    // Si pas d'erreurs, on insère
    if (empty($error)) {
        $sql = "INSERT INTO entreprises 
            (nom, nomDUtilisateur, description, secteur, employes, telephone, email, site, facebook, linkedin, numeroIFU, adresse, anneeCreation, motDePasse, logo)
            VALUES 
            (:nom, :nomDUtilisateur, :description, :secteur, :employes, :telephone, :email, :site, :facebook, :linkedin, :numeroIFU, :adresse, :anneeCreation, :motDePasse, :logo)";
        
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'nomDUtilisateur' => $nomUtilisateur,
            'description' => $description,
            'secteur' => $secteur,
            'employes' => $employes ?: null,
            'telephone' => $telephone,
            'email' => $email,
            'site' => $site,
            'facebook' => $facebook,
            'linkedin' => $linkedin,
            'numeroIFU' => $numero,
            'adresse' => $adresse,
            'anneeCreation' => $annee ?: null,
            'motDePasse' => password_hash($motDePasse, PASSWORD_DEFAULT),
            'logo' => $logoPath
        ]);

        $_SESSION["message"] = "Entreprise créée avec succès. Connectez-vous.";
        header("Location: ../front_projet_EDL/confirmation1.php");
        exit;
    } else {
        $_SESSION["error"] = $error;
        $_SESSION["message"] = "Veuillez corriger les erreurs ci-dessous.";
        header("Location: ../front_projet_EDL/compte_entreprise.php"); // adapt ton chemin
        exit;
    }
}
?>
