<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Assure-toi que Composer est installé

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Données de paiement
    $amount = 100;
    $apiKey = '3750400f49fae9d08f04cb2d2cd714b393b05ef3d0c93d12afd413edc9413114'; // Remplace par ta vraie clé API
    $return_url = 'https://votresite.com/success.php';
    $cancel_url = 'https://votresite.com/cancel.php';
    $ipn_url = 'https://votresite.com/ipn.php'; // Lien vers ton IPN

    // Données à envoyer à PayTech
    $data = [
        'item_name'    => 'Paiement FreeBenin',
        'item_price'   => $amount,
        'currency'     => 'XOF',
        'ref_command'  => uniqid('FB_'),
        'command_name' => 'FreeBenin - Transaction',
        'client_email' => 'client@email.com', // À remplacer par email réel
        'client_phone' => '229XXXXXXXX',      // Numéro facultatif
        'env'          => 'test',
        'success_url'  => $return_url,
        'cancel_url'   => $cancel_url,
        'ipn_url'      => $ipn_url
    ];

    // Requête CURL vers PayTech
    $ch = curl_init('https://paytech.sn/api/checkout/initiate');

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['API_KEY: ' . $apiKey]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['redirect_url'])) {
        // Envoi de l'email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.votresite.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'email@votresite.com';
            $mail->Password   = 'motdepasse';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('no-reply@freebenin.com', 'FreeBenin');
            $mail->addAddress($data['client_email']);

            $mail->isHTML(true);
            $mail->Subject = 'Lien de Paiement FreeBenin';
            $mail->Body    = "Cliquez ici pour finaliser le paiement : <a href='" . $result['redirect_url'] . "'>Payer 100 FCFA</a>";

            $mail->send();
        } catch (Exception $e) {
            error_log("Erreur email : " . $mail->ErrorInfo);
        }

        // Redirection vers PayTech
        header('Location: ' . $result['redirect_url']);
        exit;
    } else {
        echo "<pre>Réponse PayTech : ";
        print_r($response);
        echo "</pre>";
        echo "❌ Erreur lors de la création du paiement.";
    }
}
?>

<!-- Formulaire HTML simple -->
<form method="POST">
  <button type="submit">Payer 100 FCFA</button>
</form>
