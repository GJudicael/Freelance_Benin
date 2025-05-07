<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-with,initial-scale=1.0">
    <title>Creation d'un compte</title>
</head>
<body>
    <?php
    $bddPDO = new PDO('mysql:host=localhost;dbname=freelaance_benin','root',"");
    $bddPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $nom_utilisateur = $_POST['nom_d_utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];
    if(isset($_POST['envoyer']))
    {
        if (empty($nom_utilisateur) || empty($mot_de_passe))
        {
            echo "Veuiller entrer toute les information";
        }
        else
        {
            
            $nom_utilisateur = $_POST['nom_d_utilisateur'];
            $mot_de_passe = $_POST['mot_de_passe'];

            $requete = $bddPDO->prepare('SELECT motDePasse FROM inscription WHERE nomDUtilisateur = :nomDUtilisateur');
            $requete->bindParam(':nomDUtilisateur', $nom_utilisateur, PDO::PARAM_STR);
            $requete->execute();
            $user = $requete->fetch();

            if ($user && $mot_de_passe === $user['motDePasse']) {
                header("Location: http://localhost/Freelance_Benin/front_projet_EDL/accueil.php");
                exit();
            } else {
                echo "Nom d'utilisateur ou mot de passe incorrect.";
            }

        }
    }
    else
    {
        echo "ERREUR";
    }
    ?>
</body>
</html>