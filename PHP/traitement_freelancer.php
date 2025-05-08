<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-with,initial-scale=1.0">
    <title>Traitement de la demande</title>
</head>
<body>
    <?php
    $bddPDO = new PDO('mysql:host=localhost;dbname=freelaance_benin','root',"");
    $bddPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $id_client = $_POST['client'];
    $activiter = $_POST['activiter'];
    $description = $_POST['description'];
    
    if(isset($_POST['envoyer']))
    {
        if(empty($id_client) || empty($activiter) || empty($description))
        {
            echo "Veuiller entrer toute les information";
        }
        else
        {
            $requete = $bddPDO->prepare('SELECT * FROM inscription WHERE nomDUtilisateur = :nomDUtilisateur');
            $requete->bindParam(':nomDUtilisateur', $id_client, PDO::PARAM_STR);
            $requete->execute();
            $user = $requete->fetch();

            if (!$user) {
                echo "Nom d'utilisateur mal ecrit.";
            }
            else
            {
                $requete = $bddPDO->prepare('INSERT INTO freelancer (nomDutilisateur , activite, descriptionDeLActivite) VALUES(:nomDutilisateur, :activite, :descriptionDeLActivite)');
                $requete->bindvalue(':nomDutilisateur', $id_client);
                $requete->bindvalue(':activite', $activiter);
                $requete->bindvalue(':descriptionDeLActivite',$description);
                $result = $requete->execute();

                if($result)
                {
                    header("Location:http://localhost/Freelance_Benin/front_projet_EDL/accueil.php");
                    exit();
                }
                else
                {
                    echo "Un souci est survenu";
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