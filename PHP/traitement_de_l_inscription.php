
    <?php

        session_start();
        require_once(__DIR__."/../bdd/creation_bdd.php");
    
    if(isset($_POST['envoyer']))
    {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $numero = $_POST['numero'];
        $email = $_POST['email'];
        $motDepasse = $_POST['mot_de_passe'];
        $motDepasseConfirmation = $_POST['mot_de_passe_confirmation'];
        $nomUtilisateur=$_POST['nom_d_utilisateur'];

        if(empty($nom) || empty($prenom) || empty($numero) || empty($email) || empty($motDepasse) || empty($motDepasseConfirmation) || empty($nomUtilisateur))
        {
            $message = "Tous les champs sont requis";
        }
        else
        {
            $requete = $bdd->prepare('SELECT * FROM inscription WHERE email = :email');
            $requete->bindParam(':email', $nomUtilisateur, PDO::PARAM_STR);
            $requete->execute();
            $user = $requete->fetch();

            if ($user) {
                $error["email"] = "Cet email existe déjà.";
            }
            else if ($motDepasse != $motDepasseConfirmation)
            {
                $error["password"] = "Mot de passe incorrecte veuiller entrer le même mot de passe dans les deux champs";
            }
            else
            {
                $requete = $bdd->prepare('INSERT INTO inscription(nom , prenom, numero, email, motDePasse, nomDUtilisateur) VALUES(:nom, :prenom, :numero, :email, :motDePasse, :nomDUtilisateur)');
                $requete->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'numero' => $numero,
                    'email' => $email,
                    'motDePasse' => $motDepasse,
                    'nomDUtilisateur' => $nomUtilisateur
                ]);

                    $_SESSION["succes"] = 'Vos informations sont enregistrées avec succès. Vous pouvez à présent vous connecter';
                    header("Location:../front_projet_EDL/Connexion.php");
                    exit();
            }
            
        }
    }
   
    ?>
</body>
</html>
<!--CREATE TABLE `freelaance_benin`.`inscription` (`nom` INT NOT NULL , `prenom` INT NOT NULL , `numero` TEXT NOT NULL AUTO_INCREMENT , `email` TEXT NOT NULL , `motDePasse` TEXT NOT NULL , `nomDeLActivite` TEXT NOT NULL , `activie` TEXT NOT NULL , PRIMARY KEY (`numero`)) ENGINE = InnoDB-->