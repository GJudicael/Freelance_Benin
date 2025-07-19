<?php
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (isset($data['status']) && $data['status'] === 'completed') {
    $ref = $data['ref_command'];
    $email = $data['client_email'];

    // Exemple : mise à jour d’une table SQL ou envoi email
    file_put_contents("logs.txt", "Paiement confirmé : $ref\n", FILE_APPEND);
    echo 'Paiement validé';
} else {
    file_put_contents("logs.txt", "Paiement non confirmé : $raw\n", FILE_APPEND);
    echo 'Erreur de validation';
}
?>
