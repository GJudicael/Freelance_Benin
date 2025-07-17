<?php

$userId = $_SESSION['user_id'];

function ajouterNotification($message, $id_user=null)
{
    global $bdd, $userId;

    if($id_user){
        $userId = $id_user;
    }

    $stmt = $bdd->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->execute([$userId, $message]);
}

function recupererNotifications($filtre)
{
    global $bdd, $userId;
    if ($filtre == 'tous') {
        $stmt = $bdd->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
    } elseif ($filtre == 'non_lues') {
        // $valeur 
        $stmt = $bdd->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC");
    }
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function marquerCommeLue($id_notification)
{
    global $bdd, $userId;
    $stmt = $bdd->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND id=".$id_notification);
    $stmt->execute([$userId]);
}
