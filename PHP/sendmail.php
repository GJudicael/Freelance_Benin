<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function traieMail($email)
{

$mail = new PHPMailer(true);

$mail->isSMTP();//Specifier que PHPMailer utilise le protocole SMTP
$mail->Host ='smtp.gmail.com';//Specifier le sereur gmail
$mail->SMTPAuth = true; //Actiation de l'authentification
$mail->Username = 'sitefreelancebenin@gmail.com';
$mail->Password = 'ijqmqnmajqiklmgl';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->CharSet = "utf-8";
$mail->setFrom('sitefreelancebenin@gmail.com', 'Site Freelance Bénin');
$mail->addAddress($email,'Site Freelance Bénin');
$mail->isHTML(true);//Pour activer l'envoi de mail sous forme html

$mail->Subject = 'Confirmation d\'email';
$mail->Body = "Bonjout, vous venez de recevoir un mail de test ! http://localhost/Freelance_Benin-master/front_projet_EDL/Connexion.php";

$mail->SMTPDebug =0;//Pour desactiver le debug

if(!$mail->send())
{
    $message = "Mail non envoye";
    echo 'Erreurs:' .$mail->ErrorInfo;
}
else
{
    $message = "Un mail vous a ete envoye!";
    echo $message;
}
}