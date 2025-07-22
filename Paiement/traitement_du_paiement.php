<?php 
require_once __DIR__ . '/../vendor/autoload.php';

use Kkiapay\Kkiapay;

$publicKey  = "ea9534dc15c123315069269740eb2c74980ae74f";
$privateKey = "pk_60d9268ac6adf471691f93ca71ae6a61f2353c8f044967a257de97c7c7ce1270";
$secretKey  = "sk_3dd42cfbae5ec2e0bcf50fe5d966be0e581b9616ddba4343c622a0474c86808e";

$kkiapay = new Kkiapay($publicKey, $privateKey, $secretKey); // sandbox=true pour test

$plan = $_GET['plan'] ?? null;
$mois = isset($_GET['mois']) ? intval($_GET['mois']) : 0;
$prix_unitaire = isset($_GET['prix_unitaire']) ? intval($_GET['prix_unitaire']) : 5000;
$prix = 0;

if ($mois <= 0) {
    die("Durée invalide");
} elseif ($mois === 1) {
    $prix = 5000;
} elseif ($mois === 3) {
    $prix = 12000;
} elseif ($mois === 12) {
    $prix = 40000;
} else {
    $nb_forfaits = intdiv($mois, 3);
    $reste = $mois % 3;

    if ($nb_forfaits >= 4) {
        $prix = 40000;
        $nb_forfaits -= 4;
    }

    $prix += $nb_forfaits * 12000;
    $prix = 100;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Paiement Kkiapay - FreeBenin</title>
</head>
<body>

<h2>Abonnement FreeBenin : <?php echo htmlspecialchars($mois); ?> mois</h2>
<p>Montant à payer : <?php echo number_format($prix, 0, ',', ' '); ?> FCFA</p>

<!-- Bouton de paiement -->
<button id="payButton">Payer maintenant</button>

<!-- Intégration du SDK Kkiapay -->
<script src="https://cdn.kkiapay.me/k.js"></script>
<script>
    const payButton = document.getElementById('payButton');

    payButton.addEventListener('click', () => {
        openKkiapayWidget({
            amount: "100",
            key: "<?php echo $publicKey; ?>",
            sandbox: false, // Mettre false en production
            reason: "Abonnement FreeBenin de <?php echo $mois; ?> mois",
            callback: "https://ton-domaine.com/callback.php", // URL de traitement serveur après paiement
            position: "center",
            theme: "#0095ff",
            // Tu peux ajouter email, phone, name, paymentmethod etc.
        });

        // Écouteurs d'événements (optionnel)
        addSuccessListener(response => {
            console.log("Paiement réussi :", response);
            // Ici tu peux déclencher une action, rediriger, etc.
        });

        addFailedListener(error => {
            console.error("Paiement échoué :", error);
            alert("Le paiement a échoué. Veuillez réessayer.");
        });
    });
</script>

</body>
</html>
