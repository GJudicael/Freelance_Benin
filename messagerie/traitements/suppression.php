<?php
// Inclusion du fichier de la base de données
require_once(__DIR__ . '/../../bdd/creation_bdd.php');

$message_id = $_GET['message_id'];
// $receiver_id = $_GET['receiver_id'];
// $sender_id = $_GET['sender_id'];
$user_id = $_GET['user_id'];

$str = $_GET['sender'] ? 'sender' : 'receiver';

$choix_suppression = $_GET['choix_suppression'];
if ($choix_suppression == 'moi') {
    // Ici il y a deux cas, soit il vient de supprimer le message pour la première fois, soit il veut supprimer le message de remplacement
    $stmt = $bdd->query('UPDATE messages SET sup_for_'.$str.'=TRUE WHERE id =' . $message_id);
} elseif ($choix_suppression == 'tout_le_monde') {
    $stmt = $bdd->query('UPDATE messages SET sup_tout_le_monde=TRUE WHERE id =' . $message_id);
}
// if(isset($_GET['choix_suppression'])){
// }else{
//     // $stmt = $bdd->query('UPDATE messages SET sup_remp_msg_sender=TRUE WHERE id =' . $message_id);
// }


header('location:../discussions.php?user_id='.$user_id);
exit;