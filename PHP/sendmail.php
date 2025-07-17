<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
function traieMail($email, $token)

{

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP(); //Specifier que PHPMailer utilise le protocole SMTP
        $mail->Host = 'smtp.gmail.com'; //Specifier le sereur gmail
        $mail->SMTPAuth = true; //Actiation de l'authentification
        $mail->Username = 'sitefreelancebenin@gmail.com';
        $mail->Password = 'ijqmqnmajqiklmgl';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = "utf-8";
        $mail->setFrom('sitefreelancebenin@gmail.com', 'Site Freelance Bénin');
        $mail->addAddress($email, 'Site Freelance Bénin');
        $mail->isHTML(true); //Pour activer l'envoi de mail sous forme html

        

        $lienConfirmation = "http://localhost/freelance_benin/front_projet_EDL/Connexion.php?token=$token";

        $mail->Subject = "Confirmation de votre inscription";
        $mail->Body = "
            <p>Bonjour</p>
            <p>Merci pour votre inscription sur <strong>Freelance Bénin</strong>.</p>
            <p>Veuillez confirmer votre compte en cliquant sur le lien suivant :</p>
            <p><a href='$lienConfirmation'>Confirmer mon compte</a></p>
            <p>Si vous n'avez pas demandé cette inscription, ignorez ce message.</p>
        ";

        $mail->SMTPDebug = 0; //Pour desactiver le debug

        if ($mail->send()) {
            
            $_SESSION["mail_envoye"]  = "Un mail de validation vous a été envoyé à votre adresse mail! Cliquez sur le lien pour confirmer votre email";
    
        }
        
    } catch (Exception $e) {
        //throw $th;
        $_SESSION["erreur"] = " Erreur : Impossible d’envoyer l’e-mail. Vérifiez votre connexion Internet ou réessayez plus tard.";

    }

}
