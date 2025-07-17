
<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");
require_once(__DIR__."/sendmail.php");

if (isset($_POST['entreprise'])) {
    $nom_de_l_entreprise = $_POST['nom_de_l_entreprise'];
    $nom_d_utilisateur = $_POST['nom_d_utilisateur'];
    $description = $_POST['description'];
    $secteur = $_POST['secteur'];
    $employes = $_POST['employes'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $site = $_POST['site'];
    $facebook = $_POST['facebook'];
    $linkdin = $_POST['linkdin'];
    $numero = $_POST['numero'];
    $annee = $_POST['annee'];
    $logo = $_POST['logo'];
    $adresse = $_POST['adresse'];
    $motDepasse = $_POST['mot_de_passe'];
    $motDepasseConfirmation = $_POST['mot_de_passe_confirmation'];
    

    if (empty($nom_de_l_entreprise) || empty($nom_d_utilisateur) || empty($description) || empty($secteur) || empty($employes) || empty($telephone) 
        || empty($email) || empty($site) || empty($facebook) || empty($linkdin) || empty($numero) || empty($annee) || empty($logo) || empty($adresse) || empty($motDepasse) || empty($motDepasseConfirmation)) {
        $message = "Tous les champs sont requis";
    } elseif (strlen($motDepasse) < 6 || !preg_match('/^[A-Z]/', $motDepasse) || !preg_match('/\d/', $motDepasse)) {
        $error["password"] = "Le mot de passe doit contenir au moins 06 caractères, commencer par une lettre majuscule et contenir au moins un chiffre";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error["email"] = "Cet email est invalide";
    } else {
        // Vérifier si l'utilisateur existe déjà
        

            

                $smtp = $bdd->prepare("SELECT nom_d_utilisateur FROM entreprise WHERE nom_d_utilisateur = ?");
                $smtp->execute([$nom_d_utilisateur]);
                $nom_d_utilisateur = $smtp->fetch(PDO::FETCH_ASSOC);

                if ($nom_d_utilisateur) {
                    $error["nom_d_utilisateur"] = "Ce nom d'utilisateur existe déjà";
                } else {
                    // Insérer le nouvel utilisateur
                    $token = bin2hex(random_bytes(32)); // Génère un token sécurisé
                    $requete = $bdd->prepare('
                        INSERT INTO entreprise(nom_de_l_entreprise, nom_d_utilisateur, description, secteur, employes, telephone,email,site,facebook,linkdin,numero,annee,logo,adresse,motDepasse token)
                        VALUES(:nom_de_l_entreprise, :nom_d_utilisateur, :description, :secteur, :employes,:telephone,:email,:site,:facebook,:linkdin,:numero,:annee,:logo,:adresse,:motDepasse :token)
                    ');

                    $requete->execute([
                        'nom_de_l_entreprise' => $nom_de_l_entreprise,
                        'nom_d_utilisateur' => $nom_d_utilisateur,
                        'description' => $description,
                        'secteur' => $secteur,
                        'employes' => $employes,
                        'telephone' => $telephone,
                        'email' => $email,
                        'site' => $site,
                        'facebook' => $facebook,
                        'linkdin' => $linkdin,
                        'numero' => $numero,
                        'annee' => $annee,
                        'logo' => $logo,
                        'adresse' => $adresse,
                        'motDepasse' => $motDepasse,
                        'token' => $token
                    ]);

                    traieMail($email,$token);

                    $_SESSION["succes"] = 'Vos informations sont enregistrées avec succès. Vous pouvez à présent vous connecter';
                    header("Location:../front_projet_EDL/confirmation1.php");
                    exit();
                }
            
        
    }

}
?>
