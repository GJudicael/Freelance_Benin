<?php
session_start();
require_once(__DIR__ . '/../../bdd/creation_bdd.php');

if (!isset($_SESSION["connecte"]) || $_SESSION["connecte"] !== true) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['envoyer'])) {

    // On nettoie la session
    $receiver_id = $_SESSION['receiver_id'];
    unset($_SESSION['receiver_id']);
    if ($receiver_id != $_SESSION['user_id']) {
        // Effectuer une validation du message proprement dit
        $message = $_POST['message'];
        if (empty($message)) {
            $erreur = true;
            // Est ce qu'il y aura une autre validation à appliquer ? Question pour un champion
        }

        if (!isset($erreur)) {
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
        } else {
            header('location:../discussions.php?user_id=' . $receiver_id);
            exit;
        }
    } else {
        echo 'ici';
        header('location:./discussions.php');
        exit;
    }
} else {
    echo 'ici 2';
    ?>
        <pre><?php var_dump($_POST);?></pre>
    <?php
    // header('location:./discussions.php');
    // exit;
}
