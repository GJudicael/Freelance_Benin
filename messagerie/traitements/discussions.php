<?php

session_start();
// Inclusion du fichier de la base de données
require_once(__DIR__ . '/../../bdd/creation_bdd.php');
$current_user_id = $_SESSION['user_id'];

// Gestion du timezone pour qu'il s'adapte au Bénin
date_default_timezone_set('Africa/Lagos');

$client_id = isset($_GET['client_id']) ? intval($_GET['client_id']) : null;
const LONGUEUR_MESSAGE = 30;

function formatterChaine($string, $longeur)
{
    // Coupe le message si sa longeur dépasse la longueur indiquée
    if (!(strlen($string) > $longeur)) {
        return $string;
    } else {
        $string = substr($string, 0, $longeur);
        $mots = explode(' ', $string);
        array_pop($mots);
        $string = implode(' ', $mots) . '...';
        return $string;
    }
}
function afficherDate($date_message)
{
    $timestamp_message = strtotime($date_message);
    $timestamp_aujourdhui = time();

    if (date('d/m/Y', $timestamp_message) == date('d/m/Y', $timestamp_aujourdhui)) {
        return getdate($timestamp_message)['hours'] . ':' . getdate($timestamp_message)['minutes'];
    } elseif (date('d/m/Y', $timestamp_message) == date('d/m/Y', $timestamp_aujourdhui - 86400)) {
        // Hier
        return "Hier";
    } else {
        return date('d/m/Y', $timestamp_message);
    }
}

// Utilisateur sélectionné pour affichage de la discussion

if (isset($_GET['user_id'])) {
    $selected_user_id = intval($_GET['user_id']);
} elseif (isset($_GET['client_id'])) {
    $selected_user_id = intval($_GET['client_id']);
} else {
    $selected_user_id = null;
}

if ($selected_user_id) {

    if ($selected_user_id != $current_user_id) {
        // On a une valeur non nulle. On s'assure que cet id indexe un utilisateur bien présent dans la table des utilisateurs de la plateforme

        $stmt = "
            SELECT id
            FROM inscription
            WHERE id=$selected_user_id
            ";
        $result = $bdd->query($stmt);

        if ($result->rowCount() != 0) {
            // L'utilisateur est bien présent dans la base de données
            $receiver_id = $selected_user_id;
            $_SESSION['receiver_id'] = $receiver_id;

            // Récupérer les informations sur le destinateur
            $stmt = "
                SELECT nom, prenom, nomDUtilisateur, email, photo
                FROM inscription
                WHERE id=$current_user_id
                ";
            $result = $bdd->query($stmt);
            $infos_destinateur = $result->fetchAll(PDO::FETCH_ASSOC);
            $infos_destinateur = $infos_destinateur[0];

            // Récupérer les informations utiles sur le destinataire
            $stmt = $bdd->prepare("
                SELECT nom, prenom, nomDUtilisateur, email, photo
                FROM inscription
                WHERE id=:receiver_id
            ");
            $stmt->bindParam('receiver_id', $receiver_id, PDO::PARAM_INT);
            $stmt->execute();
            $infos_destinataire = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $infos_destinataire = $infos_destinataire[0];

            // Récupérer la discussion entre l'utilisateur connecté et le destinataire (si discussion il y eu ou non)

            $stmt = $bdd->prepare("
                SELECT id, sender_id, receiver_id, message, created_at, modifie, sup_for_sender, sup_for_receiver, sup_tout_le_monde
                FROM messages
                WHERE 
                (sender_id =:me AND receiver_id = :receiver_id)
                OR
                (sender_id = :receiver_id AND receiver_id = :me)
                ORDER BY created_at ASC
            ");
            $stmt->bindParam('me', $current_user_id, PDO::PARAM_INT);
            $stmt->bindParam('receiver_id', $receiver_id, PDO::PARAM_INT);
            $stmt->execute();
            $messages_discussion = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $toute_la_discussion_supprimee = true;

            foreach ($messages_discussion as $msg) {
                if($msg['sup_tout_le_monde'] == 0 && (($msg['sender_id'] == $current_user_id && $msg['sup_for_sender'] == 0) || ($msg['receiver_id'] == $current_user_id && $msg['sup_for_receiver'] == 0))){
                    $toute_la_discussion_supprimee = false;
                }
            }
        } else {
            echo "L'utilisateur sélectionné n'est pas présent dans la base de données";
            die(-1);
        }
    } else {
        header('location:../messagerie/discussions.php');
        exit;
    }
} else {
    $receiver_id = null;
}

// Récupération des conversations de l'utilisateur connecté

// Songe à récupérer le dernier message de la conversation et c'est ce message que tu afficheras.

// Requête de test

// select u.id, u.prenom, m.id as message_id, m.message
// from inscription u
// join messages m on (u.id = m.sender_id OR u.id = m.receiver_id)
// where (m.sender_id = 1 or m.receiver_id = 1) and u.id != 1
// order by m.created_at DESC
// limit 1

$stmt = "
SELECT u.id, nom, prenom, photo, message, created_at, sup_tout_le_monde
FROM
(
    SELECT *
    FROM
	(
        SELECT u.id as id_utilisateur, MAX(m.id) as id_dernier_message
		FROM
		inscription u
		INNER join messages m on (m.receiver_id = u.id or m.sender_id = u.id)
		where (m.receiver_id = $current_user_id or m.sender_id = $current_user_id) and u.id != $current_user_id and ((m.receiver_id = $current_user_id and m.sup_for_receiver = 0) or (m.sender_id = $current_user_id and m.sup_for_sender = 0))
		GROUP BY u.id
    ) table_inter_1
    INNER JOIN messages m ON table_inter_1.id_dernier_message = m.id
) table_inter_2 
INNER JOIN inscription u ON table_inter_2.id_utilisateur = u.id
ORDER BY created_at DESC
";

// $stmt = "
//     SELECT u.id, u.nom, u.prenom, u.photo, m.message, m.created_at
//     FROM inscription u
//     JOIN messages m ON (u.id = m.sender_id OR u.id = m.receiver_id)
//     WHERE (m.sender_id = $current_user_id OR receiver_id = $current_user_id) AND u.id != $current_user_id
//     GROUP BY u.id
//     ORDER BY m.created_at DESC
// ";

// Je pense ici que de base 'group by" prend la première ligne qu'on rencontre dans le lot

$resultat = $bdd->query($stmt);
$conversations = $resultat->fetchAll(PDO::FETCH_ASSOC);


// Cas de figure 1

// Pour contacter un individu sur la plateforme, je vais devoir à un moment donné passer par un bouton qui m'enverra par get l'id de l'individ visé sous le nom de variable 'receiver_id'. Maintenant il faudra vérifier si j'ai déjà écrit à ce receiver. Si oui son nom dans le panneau latéral gauche sera sélectionné. Autrement aucun nom ne sera sélectionné dans le panneau latéral gauche mais j'aurai quand même la discussion ouverte dans le panneau latéral de droite.

// Modification de messages

if (isset($_SESSION['modifier_message'])) {
    $id_message = $_SESSION['message_id'];

    foreach ($messages_discussion as $index => $msg) {
        if ($msg['id'] == $id_message)
            $message_a_modifier = $messages_discussion[$index];
    }
}

// Suppression d'un message

if (isset($_POST['supprimer'])) {
    foreach ($messages_discussion as $msg) {
        if ($msg['id'] == $_POST['message_id']) {
            $str = $current_user_id == $msg['sender_id'] ? '&sender=1&receiver=0' : '&sender=0&receiver=1'; 
        }
    }
    header('location:traitements/suppression.php?choix_suppression=' . $_POST['choix_suppression'] . '&message_id=' . $_POST['message_id'] . '&user_id=' . $selected_user_id.$str);
    exit;
}
