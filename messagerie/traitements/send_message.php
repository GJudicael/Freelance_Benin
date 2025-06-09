<?php
session_start();
require_once(__DIR__.'/../bdd/creation_bdd.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // On nettoie la session
    $receiver_id = $_SESSION['receiver_id'];
    unset($_SESSION['receiver_id']);
    if ($receiver_id != $_SESSION['user_id']) {
        // Effectuer une validation du message proprement dit
        $message = $_POST['message'];
        if(empty($message)){
            $erreur = true;
            // Est ce qu'il y aura une autre validation à appliquer ? Question pour un champion
        }

        if(!isset($erreur)){
            // Ensuite on s'attaque à l'insertion dans la base de données
            
            $stmt = $bdd->prepare("
            INSERT INTO messages (sender_id, receiver_id, message)
            VALUES (:sender_id, :receiver_id, :message)
            ");
            $stmt->bindParam('sender_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindParam('receiver_id', $receiver_id, PDO::PARAM_INT);
            $stmt->bindParam('message', $_POST['message']);
            $stmt->execute();

            header('location:../discussions.php?user_id=' . $receiver_id);
            exit;
        }else{
            header('location:../discussions.php?user_id=' . $receiver_id);
            exit;
        }



    }else{
        header('location:./discussions.php');
        exit;
    }
} else {
    header('location:./discussions.php');
    exit;
}
