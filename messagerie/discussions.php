<?php
session_start();

// Inclusion du fichier de la base de données
require_once(__DIR__ . '/../bdd/creation_bdd.php');
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
                SELECT sender_id, receiver_id, message
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
            $discussion = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "L'utilisateur sélectionné n'est pas présent dans la base de données";
            die(-1);
        }
    } else {
        header('location:/messagerie/discussions.php');
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
SELECT u.id, nom, prenom, photo, message, created_at
FROM
(
    SELECT *
    FROM
	(
        SELECT u.id as id_utilisateur, MAX(m.id) as id_dernier_message
		FROM
		inscription u
		INNER join messages m on (m.receiver_id = u.id or m.sender_id = u.id)
		where (m.receiver_id = $current_user_id or m.sender_id = $current_user_id) and u.id != $current_user_id
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
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="./../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container-fluid d-flex px-0" id="wrapper">
        <!-- Colonne de gauche : Liste des conversations -->

        <div class="left border-3 border-end">
            <h6 class="mt-2">Conversations</h6>
            <hr class="mb-4">
            <?php if (empty($conversations)) : ?>
                <p>Aucune conversation entamée...</p>
            <?php else: ?>
                <?php foreach ($conversations as $conv) : ?>
                    <a href="discussions.php?user_id=<?= $conv['id'] ?>" class="text-decoration-none text-black" id="conv">
                        <div class="d-flex align-items-center p-2 rounded-3" id="conv">
                            <img src="./../photo_profile/<?= $conv['photo'] ?>" alt="Photo de profil de <?= $conv['nom'] . ' ' . $conv['prenom'] ?>" class="rounded-circle" width="50" height="50">
                            <div class="ms-3 w-100 infos_conv">
                                <p class="mb-0 fs-6 fw-bolder"><?= $conv['nom'] . ' ' . $conv['prenom'] ?></p>
                                <p class="mb-0"><?= formatterChaine($conv['message'], LONGUEUR_MESSAGE) ?></p>
                                <span id="date" class="fs-6 text-black fw-light"><?= afficherDate($conv['created_at']) ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Colonne de droite : Affichage de la discussion -->
        <div class="right d-flex flex-column <?= $receiver_id ? 'justify-content-between' : 'justify-content-center align-items-center' ?> ">

            <?php if ($receiver_id) : ?>
                <!-- Header -->
                <div class="pb-2 d-flex align-items-center border-bottom border-3">
                    <img src="./../photo_profile/<?= $infos_destinataire['photo'] ?>" alt="Photo de <?= $infos_destinataire['nom'] . ' ' . $infos_destinataire['prenom'] ?>" class="rounded-circle" width="40" height="40">
                    <p class="mx-2 mb-0"><strong><?= $infos_destinataire['nom'] . ' ' . $infos_destinataire['prenom'] ?></strong> (<i><?= $infos_destinataire['nomDUtilisateur'] ?></i>)</p>
                    <!-- <?php if (empty($discussion)) : ?>
                        <a href="../front_projet_EDL/info_profile.php?id=<?= $selected_user_id ?>">Voir le profil</a>
                    <?php endif; ?> -->
                </div>

                <!-- Messages proprements dits -->

                <div class="d-flex flex-column h-75 py-2 mb-3 overflow-y-scroll" id="messagesCont">
                    <?php if (empty($discussion)) : ?>
                        <!-- Rien à afficher -->
                    <?php else: ?>
                        <?php foreach ($discussion as $index => $msg) : ?>
                            <?php
                            if ($index != 0) {
                                $message_precedent = $discussion[$index - 1];
                                $meme_auteur = $message_precedent['sender_id'] == $msg['sender_id'] ? true : false;
                            }
                            $from_current_user = $msg['sender_id'] == $current_user_id;
                            ?>

                            <div class="mb-1 <?= $from_current_user ? 'from-me bg-primary text-white' : 'from-other bg-light' ?> message">
                                <p class="py-2 px-4 mb-0 me-[20px]"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Formulaire d'envoi -->
                <form action="traitements/send_message.php" method="post" class="mb-3">
                    <div class="d-flex">
                        <textarea name="message" id="messageCont" class="form-control" placeholder="Saissisez votre message" rows="1"></textarea>
                        <!-- <input type="text" name="message" id="message" class="form-control form-control-sm" placeholder="Saisissez votre message"> -->
                        <button type="submit" class="btn btn-primary mx-2">Envoyer</button>
                    </div>
                </form>

            <?php else: ?>
                <p>Aucune discussion n'a été sélectionnée.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        window.addEventListener('keydown', (event) => {
            if (event.key == "Escape") {
                document.location.href = "./../messagerie/discussions.php";
            }
        })
    </script>
</body>

</html>