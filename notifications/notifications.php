<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('traitements/notifications.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="./../assets/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./../assets/fontawesome-free/css/all.min.css">

</head>

<body>
    <style>
        h1 {
            color: #555555;
        }

        .btn-outline-primary:hover {
            color: #fff;
        }

        .icon-circle {
            height: 2.5rem;
            width: 2.5rem;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .unread {
            background: rgb(177 177 177 / 15%);
        }
    </style>

    <?php require_once(__DIR__ . "/../front_projet_EDL/header.php") ?>

    <main>
        <div class="container-fluid my-4" id="wrapper">
            <div class="card border-0">
                <div class="card-body">
                    <h1 class="fs-3 fw-bold">Notifications</h1>

                    <!-- Options de tri -->

                    <ul class="nav nav-pills mt-3" role="tablist">
                        <li class="nav-item me-2">
                            <button
                                type="button"
                                class="nav-link active rounded-pill btn"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-top-all"
                                aria-controls="navs-pills-top-all"
                                aria-selected="true" id="tous">
                                Tous
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link btn rounded-pill btn-outline-primary"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-top-unread"
                                aria-controls="navs-pills-top-unread"
                                aria-selected="false" id="non_lues">
                                Non lues
                            </button>
                        </li>
                    </ul>
                </div>
                <hr class="m-0">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                            <!-- Toutes les notifications -->
                            <?php if (count($notifications) != 0) : ?>
                                <?php foreach ($notifications as $notification) : ?>
                                    <div class="d-flex align-items-center mb-2 <?= $notification['is_read'] == 0 ? 'unread' : '' ?> p-4 rounded-4">
                                        <div class="me-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-file-alt text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <?php $date = new DateTime($notification['created_at']) ?>
                                            <div class="small text-gray-500"><?= $formatter->format($date) ?></div>
                                            <span class="<?= $notification['is_read'] == 0 ? 'fw-bold' : '' ?>"><?= $notification['message'] ?></span>
                                        </div>
                                    </div>
                                    <?php marquerCommeLue($notification['id']) ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center mt-3">Il semble que vous n'avez aucune notification.</p>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-top-unread" role="tabpanel">
                            <!-- Non lues -->
                            <?php if ($notifications_non_lues) : ?>
                                <?php foreach ($notifications as $notification) : ?>
                                    <?php if ($notification['is_read'] == 0) : ?>
                                        <div class="d-flex align-items-center mb-2 p-4 rounded-4">
                                            <div class="me-3">
                                                <div class="icon-circle bg-primary">
                                                    <i class="fas fa-file-alt text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <?php $date = new DateTime($notification['created_at']) ?>
                                                <div class="small text-gray-500"><?= $formatter->format($date) ?></div>
                                                <span class="fw-bold"><?= $notification['message'] ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center mt-3">Il semble que vous n'avez aucune notification non lue.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>

    <?php require_once(__DIR__ . "/../front_projet_EDL/footer.php") ?>
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const tousOption = document.getElementById('tous');
        const non_luesOption = document.getElementById('non_lues');


        tousOption.addEventListener('click', () => afficherNotifications('tous'));

        function afficherNotifications(filtre) {
            console.log(tous);
            console.log(non_lues);

            // fetch('get_notifications.php')
            //     .then(res => res.json())
            //     .then(data => {
            //         let html = '';
            //         data.notifications.forEach(n => {
            //             html += `<p>${n.message} <em>(${n.created_at})</em></p>`;
            //         });
            //         document.getElementById('notifications-liste').innerHTML = html;
            //         fetch('marquer_lues.php'); // Marquer comme lues
            //         document.getElementById('notification-count').textContent = '';
            //     });
        }
    </script>
</body>

</html>