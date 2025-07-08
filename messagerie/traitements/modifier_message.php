<?php

session_start();

// Inclusion du fichier de la base de données
require_once(__DIR__ . '/../../bdd/creation_bdd.php');
$current_user_id = $_SESSION['user_id'];

// 1- message_id n'est pas défini
// 2- l'id indiqué n'est pas dans la table messages
// 3- l'id indiqué ne correspond pas à un message que l'utilisateur connecté a envoyé
// 4- Le délai de modification du message est passé
$delai_modification = 5 * 60; //s
$redirect = false;

if (!isset($_GET['message_id']) || !filter_input(INPUT_GET, 'message_id', FILTER_VALIDATE_INT)) {
    $redirect = true;
} else {
    $stmt = $bdd->query('SELECT * FROM messages WHERE id=' . $_GET['message_id'] . ' AND sender_id=' . $current_user_id . ' AND UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(created_at)  <' . $delai_modification);
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if($stmt->rowCount() == 0){
        $redirect = true;
    }else{
        $_SESSION['modifier_message'] = true;
        $_SESSION['message_id'] = $_GET['message_id'];
        header('location:../discussions.php?user_id='.$resultat['receiver_id']);
        exit;
    }
}

// On a reçu un formulaire pour modifier le message

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modifier'])) {

    // 1- S'assurer que la valeur indiquée est valide
    if(empty($_POST['message'])){
        $erreur = true;
    }

    if(!isset($erreur)){
        $id_message = $_SESSION['message_id'];
        $stmt = $bdd->prepare('UPDATE messages SET message =:message WHERE id ='.$id_message);
        $stmt->bindParam('message', $_POST['message']);
        $stmt->execute();

        unset($_SESSION['modifier_message']);
        unset($_SESSION['message_id']);
        header('location:../discussions.php?user_id='.$_SESSION['receiver_id']);
        exit;
    }
    
} else {
}