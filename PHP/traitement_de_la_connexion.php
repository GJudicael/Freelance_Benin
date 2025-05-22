
    <?php
    session_start();
    require_once(__DIR__."/../bdd/creation_bdd.php");

    
    
    if(isset($_POST['envoyer']))
    {
        $nom_utilisateur = $_POST['nom_d_utilisateur'];
        $mot_de_passe = $_POST['mot_de_passe'];

        if (isset($nom_utilisateur) && isset($mot_de_passe) && (empty($nom_utilisateur) || empty($mot_de_passe)))
        {
            $message_error = "Tous les champs sont requis";
        }
        else
        {
            
            $requete = $bdd->prepare('SELECT motDePasse, id FROM inscription WHERE nomDUtilisateur = :nomDUtilisateur');
            $requete->execute([
                'nomDUtilisateur' => $nom_utilisateur
            ]);
            $user = $requete->fetch();
            var_dump($user);

            if(!$user){
                $error["user_name"] = "Nom d'utilisateur incorrect";
            }elseif ($user && password_verify($mot_de_passe,$user['motDePasse'])) {
                $_SESSION["user_name"] = $nom_utilisateur;
                $_SESSION["user_id"] = $user['id'];

                header("Location: accueil.php");
                exit();
            } else {
                $error["password"]= "Mot de passe incorrect";
            }

        }
    }
    ?>