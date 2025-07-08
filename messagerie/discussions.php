<?php require_once('traitements/discussions.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>

<body>

    <?php require_once(__DIR__ . "/../front_projet_EDL/header.php") ?>

    <main>
        <div class="container-fluid d-flex px-0 mt-2" id="wrapper">
            <!-- Colonne de gauche : Liste des conversations -->

            <div class="left border-3 border-end">
                <h6 class="mt-2">Conversations</h6>
                <hr class="mb-4">
                <?php if (empty($conversations)) : ?>
                    <p>Aucune conversation entamée...</p>
                <?php else: ?>
                    <?php foreach ($conversations as $conv) : ?>
                        <a href="discussions.php?user_id=<?= $conv['id'] ?>" class="text-decoration-none text-black" id="conv">
                            <div class="d-flex align-items-center p-3 rounded-3 <?= $conv['id'] == $selected_user_id ? 'selected' : '' ?>" id="conv">
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
            <div class="right d-flex flex-column <?= $receiver_id ? 'justify-content-between' : 'justify-content-center align-items-center' ?> position-relative">

                <?php if ($receiver_id) : ?>
                    <!-- Header -->
                    <div class="pb-2 border-bottom border-3 container" id="header">
                        <div class="row">
                            <!-- Photo de profil et nom de l'utilisateur -->
                            <div class="col-11">
                                <img src="./../photo_profile/<?= $infos_destinataire['photo'] ?>" alt="Photo de <?= $infos_destinataire['nom'] . ' ' . $infos_destinataire['prenom'] ?>" class="rounded-circle" width="40" height="40">
                                <p class="mx-2 mb-0 d-inline-block"><strong><?= $infos_destinataire['nom'] . ' ' . $infos_destinataire['prenom'] ?></strong> (<i><?= $infos_destinataire['nomDUtilisateur'] ?></i>)</p>
                            </div>

                            <!-- Dropdown -->

                            <div class="dropdown col-1">
                                <button class="btn dropdown-toggle text-end hide-arrow btn-light" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a href="../front_projet_EDL/info_profile.php?id=<?= $selected_user_id ?>" class="dropdown-item">Voir le profil</a></li>

                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Messages proprements dits -->

                    <div class="d-flex flex-column h-75 py-2 mb-3 overflow-y-scroll" id="messagesCont">
                        <?php if (empty($messages_discussion)) : ?>
                            <!-- Rien à afficher -->
                        <?php else: ?>
                            <?php foreach ($messages_discussion as $index => $msg) : ?>
                                <?php
                                if ($index != 0) {
                                    $message_precedent = $messages_discussion[$index - 1];
                                    $meme_auteur = $message_precedent['sender_id'] == $msg['sender_id'] ? true : false;
                                }
                                $from_current_user = $msg['sender_id'] == $current_user_id;
                                ?>

                                <div class="mb-1 <?= $from_current_user ? 'from-me bg-primary text-white' : 'from-other bg-light' ?> message">
                                    <p class="py-2 px-4 mb-0 me-[20px]"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                                    <span class="message_id d-none"><?= $msg['id'] ?></span>
                                    <span class="d-none created_at"><?= htmlspecialchars($msg['created_at']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Formulaire d'envoi -->
                    <form action="traitements/<?= !isset($message_a_modifier) ? 'send_message' : 'modifier_message' ?>.php" method="post" class="mb-3">
                        <?php if (isset($message_a_modifier)) : ?>
                            <div class="card mb-2">
                                <div class="card-header">Edition de message</div>
                                <div class="card-body"><?= htmlspecialchars($message_a_modifier['message']) ?></div>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex">
                            <textarea name="message" id="messageCont" class="form-control" placeholder="Saissisez votre message" rows="1" required><?= isset($message_a_modifier) ? $message_a_modifier['message'] : ''?></textarea>
                            <!-- <input type="text" name="message" id="message" class="form-control form-control-sm" placeholder="Saisissez votre message"> -->
                            <button type="submit" class="btn btn-primary mx-2" name="<?= isset($message_a_modifier) ? 'modifier' : 'envoyer' ?>"><?= isset($message_a_modifier) ? 'Modifier' : 'Envoyer' ?></button>
                            <?php if (isset($message_a_modifier)) : ?>
                                <a href="discussions.php?user_id=<?= $selected_user_id ?>" class="btn btn-primary">Annuler</a>
                            <?php endif; ?>
                        </div>
                    </form>


                <?php else: ?>
                    <p>Aucune discussion n'a été sélectionnée.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div id="menu-container">
        <ul class="menu">
            <li><a href="./traitements/modifier_message.php?message_id=" class="menu-item d-flex align-items-center" id="modifier"><i class="bi bi-pen me-2"></i>Modifier</a></li>
            <li id="menu-divider">
                <hr class="dopdown-divider">
            </li>
            <!-- <li id="supprimer-pour-moi"><a href="#" class="menu-item text-danger d-flex align-items-center"><i class="bi bi-trash me-2"></i>Supprimer pour moi</a></li> -->
            <li id="supprimer"><a href="#" class="menu-item text-danger d-flex align-items-center"><i class="bi bi-trash me-2"></i>Supprimer</a></li>
            <!-- <li><a href="#" class="menu-item">Something else</a></li> -->
        </ul>
    </div>



    <script src="script.js?v=<?= time() ?>"></script>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>