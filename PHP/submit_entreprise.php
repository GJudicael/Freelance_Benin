<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . "/../bdd/creation_bdd.php"); // Ensure $bdd is correctly initialized here
require_once(__DIR__ . "/sendmail.php");

$errors = [];
$entreprise = []; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['creer'])) {


    // 1. Sanitize and retrieve POST data using filter_input()
    // Using ?? '' to ensure a default empty string if input is missing/null after filtering
    // Note: FILTER_SANITIZE_STRING is deprecated in PHP 8.1+. For modern PHP, consider htmlspecialchars() directly.
    // For simplicity, I'll use FILTER_UNSAFE_RAW combined with htmlspecialchars() for general text fields.
    $entreprise = [
        "nom_utilisateur" => filter_input(INPUT_POST, "nom_utilisateur", FILTER_UNSAFE_RAW) ?? '',
        "nom" => filter_input(INPUT_POST, "nom", FILTER_UNSAFE_RAW) ?? '',
        "description" => filter_input(INPUT_POST, "description", FILTER_UNSAFE_RAW) ?? '',
        "secteur" => filter_input(INPUT_POST, "secteur", FILTER_UNSAFE_RAW) ?? '',
        "site" => filter_input(INPUT_POST, "site", FILTER_SANITIZE_URL) ?? '',
        "email" => filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL) ?? '',
        "facebook" => filter_input(INPUT_POST, "facebook", FILTER_SANITIZE_URL) ?? '',
        "linkdin" => filter_input(INPUT_POST, "linkdin", FILTER_SANITIZE_URL) ?? '',
        "employes" => filter_input(INPUT_POST, "employes", FILTER_SANITIZE_NUMBER_INT) ?? '', // Sanitize as integer
        "numero" => filter_input(INPUT_POST, "numero", FILTER_SANITIZE_NUMBER_INT) ?? '', // Assuming legal ID is numeric
        "telephone" => filter_input(INPUT_POST, "telephone", FILTER_UNSAFE_RAW) ?? '',
        "pays" => filter_input(INPUT_POST, "pays", FILTER_UNSAFE_RAW) ?? '',
        "ville" => filter_input(INPUT_POST, "ville", FILTER_UNSAFE_RAW) ?? '',
        "adresse" => filter_input(INPUT_POST, "adresse", FILTER_UNSAFE_RAW) ?? '',
        "annee" => filter_input(INPUT_POST, "annee", FILTER_UNSAFE_RAW) ?? '', // Keep as raw for date type HTML input, validate later
        "motDepasse" => $_POST["mot_de_passe"] ?? '',
        "motDepasseConfirmer" => $_POST["mot_de_passe_confirmation"] ?? ''
    ];


    // Placeholder for logo file name
    $logoFileName = null;

    $required_fields = [
        "nom_utilisateur",
        "nom",
        "description",
        "secteur",
        "site",
        "email",
        "facebook",
        "linkdin",
        "employes",
        "numero",
        "telephone",
        "pays",
        "ville",
        "adresse",
        "annee"
    ];

    foreach ($required_fields as $field) {
        if (empty($entreprise[$field])) {
            $errors[$field] = "Ce champ est requis.";
        }
    }

    // Password validation
    if (empty($entreprise['motDepasse'])) {
        $errors['motDepasse'] = "Le mot de passe est requis.";
    } elseif (strlen($entreprise['motDepasse']) < 6 || !preg_match('/^[A-Z]/', $entreprise['motDepasse']) || !preg_match('/\d/', $entreprise['motDepasse'])) {
        $errors["motDepasse"] = "Le mot de passe doit contenir au moins 6 caractères, commencer par une lettre majuscule et contenir au moins un chiffre.";
    }

    if (empty($entreprise['motDepasseConfirmer'])) {
        $errors['motDepasseConfirmer'] = "La confirmation du mot de passe est requise.";
    } elseif ($entreprise['motDepasse'] !== $entreprise['motDepasseConfirmer']) {
        $errors['motDepasseConfirmer'] = "Les mots de passe ne correspondent pas.";
    }

    // URL validation
    $url_fields = ['site', 'facebook', 'linkdin'];
    foreach ($url_fields as $field) {
        // Only validate if field is not empty and no "required" error already exists for it
        if (!empty($entreprise[$field]) && !filter_var($entreprise[$field], FILTER_VALIDATE_URL)) {
            $errors[$field] = "L'URL fournie pour " . $field . " n'est pas valide.";
        }
    }

    // Email validation
    if (!empty($entreprise['email']) && !filter_var($entreprise['email'], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Cet email est invalide.";
    }

    //user_name validation
    if (!isset(($errors['nom_utilisateur']))) {
        try {
            $requete = $bdd->prepare('SELECT COUNT(*) FROM inscription WHERE nomDUtilisateur = ?');
            $requete->execute([$entreprise['nom_utilisateur']]);
            if ($requete->fetchColumn() > 0) {
                $errors["nom_utilisateur"] = "Ce nom_utilisateur est déjà utilisé.";
            }
        } catch (PDOException $e) {
            $errors['db_error'] = "Erreur de base de données lors de la vérification de l'email.";
            error_log("DB Error: " . $e->getMessage()); // Log detailed error for debugging
        }
    }

    // Check if email already exists in DB (only if no other email errors)
    if (!isset($errors['email'])) {
        try {
            $requete = $bdd->prepare('SELECT COUNT(*) FROM inscription WHERE email = :email');
            $requete->execute(['email' => $entreprise['email']]);
            if ($requete->fetchColumn() > 0) {
                $errors["email"] = "Cette adresse email est déjà utilisée.";
            }
        } catch (PDOException $e) {
            $errors['db_error'] = "Erreur de base de données lors de la vérification de l'email.";
            error_log("DB Error: " . $e->getMessage()); // Log detailed error for debugging
        }
    } 

    // Year validation (for 'annee' input type date)
    if (!empty($entreprise['annee'])) {
        // Date format from HTML input type="date" is 'YYYY-MM-DD'
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $entreprise['annee']) || strtotime($entreprise['annee']) === false) {
            $errors['annee'] = "Le format de l'année est invalide (doit être YYYY-MM-DD).";
        } else {
            $submittedYear = (int) date('Y', strtotime($entreprise['annee']));
            if ($submittedYear < 1900 || $submittedYear > date('Y')) {
                $errors['annee'] = "L'année de création n'est pas valide.";
            }
        }
    }


    // Employees count validation
    if (!empty($entreprise['employes']) && (!is_numeric($entreprise['employes']) || $entreprise['employes'] < 0)) {
        $errors['employes'] = "Le nombre d'employés doit être un nombre positif.";
    }

    // Legal ID (numero) validation
    if (!empty($entreprise['numero']) && (!is_numeric($entreprise['numero']) || $entreprise['numero'] < 0)) {
        $errors['numero'] = "L'ID légal doit être un nombre positif.";
    }


    // Logo file upload validation and processing (ONE BLOCK ONLY)
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $fichier = $_FILES['logo'];
        $nomTemporaire = $fichier['tmp_name'];
        $nomOriginal = basename($fichier['name']);
        $taille = $fichier['size'];
        $extension = strtolower(pathinfo($nomOriginal, PATHINFO_EXTENSION));
        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($extension, $extensionsAutorisees)) {
            $errors['logo'] = "Format de fichier non autorisé. Formats acceptés : JPG, JPEG, PNG, GIF.";
        } elseif ($taille > $maxFileSize) {
            $errors['logo'] = "Le fichier est trop volumineux. Taille maximale : 2 Mo.";
        } else {
            $nouveauNom = uniqid('logo_') . '.' . $extension; // Unique name for file
            $cheminDestination = __DIR__ . '/../logo/' . $nouveauNom;

            if (move_uploaded_file($nomTemporaire, $cheminDestination)) {
                $logoFileName = $nouveauNom; } else {
                $errors['logo'] = "Erreur lors du déplacement du fichier téléchargé.";
            }
        }
    } elseif (isset($_FILES['logo']) && $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors['logo'] = "Erreur d'upload du fichier : Code " . $_FILES['logo']['error'];
    } else {
        $errors['logo'] = "Le logo de l'entreprise est requis.";
    }
    


    
    if (empty($errors)) {
        
        $entreprise['motDepasse'] = password_hash($entreprise['motDepasse'], PASSWORD_DEFAULT);

    
        unset($entreprise['motDepasseConfirmer']);
        $token = bin2hex(string: random_bytes(32)); // Génère un token sécurisé


        try {
            $requete = $bdd->prepare("
                INSERT INTO inscription(
                    nom, nomDUtilisateur, email, description, activity_sector, web_site,
                    ville, pays, numero, facebook_url, linkdin_url, nombre_employes,
                    legal_id, adresse, annee, photo, motDepasse,token, role 
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'entreprise')
            ");

            $requete->execute([
                $entreprise['nom'],
                $entreprise['nom_utilisateur'], // Corrected key: nom_utilisateur
                $entreprise['email'],
                $entreprise['description'],    // Corrected key: description
                $entreprise['secteur'],
                $entreprise['site'],
                $entreprise['ville'],
                $entreprise['pays'],
                $entreprise['telephone'],
                $entreprise['facebook'],
                $entreprise['linkdin'],
                $entreprise['employes'],
                $entreprise['numero'],         // Corrected key: numero (for legal_id)
                $entreprise['adresse'],
                $entreprise['annee'],
                $logoFileName,
                $entreprise['motDepasse'],
                $token,

            ]);

            traieMail($entreprise['email'], $token);


            $_SESSION['success_message'] = "Vos informations sont enregistrées avec succès !";
            header('Location: ../front_projet_EDL/confirmation1.php');
            exit();

        } catch (PDOException $e) {

            $errors['db_insert_error'] = "Une erreur est survenue lors de l'enregistrement de vos informations.";
            error_log("DB Insert Error: " . $e->getMessage()); // Log the actual database error
            // Potentially remove uploaded logo if DB insert fails
            if ($logoFileName && file_exists($cheminDestination)) {
                unlink($cheminDestination);
            }

        }
    }
}

$entreprise = array_merge([
    "nom_utilisateur" => '',
    "nom" => '',
    "description" => '',
    "secteur" => '',
    "site" => '',
    "email" => '',
    "facebook" => '',
    "linkdin" => '',
    "employes" => '',
    "numero" => '',
    "pays" => '',
    "ville" => '',
    "adresse" => '',
    "annee" => '',
    "motDepasse" => '',
    "motDepasseConfirmer" => ''
], $entreprise);
?>