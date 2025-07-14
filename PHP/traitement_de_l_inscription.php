
<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");
require_once(__DIR__."/sendmail.php");

if (isset($_POST['envoyer'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $numero = $_POST['numero'];
    $email = $_POST['email'];
    $motDepasse = $_POST['mot_de_passe'];
    $motDepasseConfirmation = $_POST['mot_de_passe_confirmation'];
    $nomUtilisateur = $_POST['nom_d_utilisateur'];

    if (empty($nom) || empty($prenom) || empty($numero) || empty($email) || empty($motDepasse) || empty($motDepasseConfirmation) || empty($nomUtilisateur)) {
        $message = "Tous les champs sont requis";
    } elseif (strlen($motDepasse) < 6 || !preg_match('/^[A-Z]/', $motDepasse) || !preg_match('/\d/', $motDepasse)) {
        $error["password"] = "Le mot de passe doit contenir au moins 06 caractères, commencer par une lettre majuscule et contenir au moins un chiffre";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error["email"] = "Cet email est invalide";
    } else {
        // Vérifier si l'utilisateur existe déjà
        $requete = $bdd->prepare('SELECT * FROM inscription WHERE email = :email');
        $requete->execute(['email' => $email]);
        $user = $requete->fetch();

        if ($user) {
            $error["email"] = "Cet email existe déjà.";
        } elseif ($motDepasse != $motDepasseConfirmation) {
            $error["pass_confirm"] = "Mot de passe incorrect : veuillez entrer le même mot de passe dans les deux champs";
        } else {
            // Vérifier si l'utilisateur a été banni
            $checkBan = $bdd->prepare("SELECT * FROM bannis WHERE email = :email OR nomDUtilisateur = :nomDUtilisateur");
            $checkBan->execute([
                'email' => $email,
                'nomDUtilisateur' => $nomUtilisateur
            ]);
            $banni = $checkBan->fetch(PDO::FETCH_ASSOC);

            if ($banni) {
                echo "🚫 Vous ne pouvez pas vous réinscrire. Ce compte a été banni.";
            } else {
                // Vérifier si le nom d'utilisateur est déjà pris

                $smtp = $bdd->prepare("SELECT nomDUtilisateur FROM inscription WHERE nomDUtilisateur = ?");
                $smtp->execute([$nomUtilisateur]);
                $nomDutilisateur = $smtp->fetch(PDO::FETCH_ASSOC);

                if ($nomDutilisateur) {
                    $error["nomDutilisateur"] = "Ce nom d'utilisateur existe déjà";
                } else {
                    // Insérer le nouvel utilisateur
                    $token = bin2hex(random_bytes(32)); // Génère un token sécurisé
                    $requete = $bdd->prepare('
                        INSERT INTO inscription(nom, prenom, numero, email, motDePasse, nomDUtilisateur, token)
                        VALUES(:nom, :prenom, :numero, :email, :motDePasse, :nomDUtilisateur, :token)
                    ');

                    $requete->execute([
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'numero' => $numero,
                        'email' => $email,

                        'motDePasse' => password_hash($motDepasse, PASSWORD_DEFAULT),
                        'nomDUtilisateur' => $nomUtilisateur,
                        'token' => $token
                    ]);

                    traieMail($email,$token);

                    $_SESSION["succes"] = 'Vos informations sont enregistrées avec succès. Vous pouvez à présent vous connecter';
                    header("Location:../front_projet_EDL/confirmation1.php");
                    exit();
                }
            }
        }
    }

}
?>
