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
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $numero = $_POST['numero'];
    $email = $_POST['email'];
    $motDepasse = $_POST['mot_de_passe'];
    $motDepasseConfirmation = $_POST['mot_de_passe_confirmation'];
    $nomUtilisateur=$_POST['nom_d_utilisateur'];

    if(isset($_POST['envoyer']))
    {
        if(empty($nom) || empty($prenom) || empty($numero) || empty($email))
        {
            echo "Veuiller entrer toute les information";
        }
        else
        {
            $requete = $bddPDO->prepare('SELECT * FROM inscription WHERE nomDUtilisateur = :nomDUtilisateur');
            $requete->bindParam(':nomDUtilisateur', $nomUtilisateur, PDO::PARAM_STR);
            $requete->execute();
            $user = $requete->fetch();

            if ($user) {
                echo "Ce nom d'utilisateur existe déjà.";
            }
            else if ($motDepasse != $motDepasseConfirmation)
            {
                echo "Mot de passe incorrecte veuiller entrer le meme mot de passe dans les deux champs";
            }
            else
            {
                $requete = $bddPDO->prepare('INSERT INTO inscription(nom , prenom, numero, email, motDePasse, nomDUtilisateur) VALUES(:nom, :prenom, :numero, :email, :motDePasse, :nomDUtilisateur)');
                $requete->bindvalue(':nom', $nom);
                $requete->bindvalue(':prenom', $prenom);
                $requete->bindvalue(':numero', $numero);
                $requete->bindvalue(':email', $email);
                $requete->bindvalue(':motDePasse', $motDepasse);
                $requete->bindvalue(':nomDUtilisateur', $nomUtilisateur);
                $result = $requete->execute();

                if(!$result)
                {
                    echo "Un souci est survenu";
                }
                else
                {
                    header("http://localhost/Freelance_Benin/front_projet_EDL/accueil.php");
                    exit();
                }
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
<!--CREATE TABLE `freelaance_benin`.`inscription` (`nom` INT NOT NULL , `prenom` INT NOT NULL , `numero` TEXT NOT NULL AUTO_INCREMENT , `email` TEXT NOT NULL , `motDePasse` TEXT NOT NULL , `nomDeLActivite` TEXT NOT NULL , `activie` TEXT NOT NULL , PRIMARY KEY (`numero`)) ENGINE = InnoDB-->