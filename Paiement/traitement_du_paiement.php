<?php 
session_start(); // Démarre la session

require_once __DIR__ . '/../vendor/autoload.php';

use Kkiapay\Kkiapay;

$publicKey  = "ea9534dc15c123315069269740eb2c74980ae74f";

// Récupération des données
$plan = $_GET['plan'] ?? null;
$mois = isset($_GET['mois']) ? intval($_GET['mois']) : 0;
$prix_unitaire = isset($_GET['prix_unitaire']) ? intval($_GET['prix_unitaire']) : 5000;
$prix = 0;

// Déterminer la durée
if ($mois <= 0 && $plan) {
    if ($plan === "1mois") {
        $mois = 1;
    } elseif ($plan === "3mois") {
        $mois = 3;
    } elseif ($plan === "12mois") {
        $mois = 12;
    }
}

// Calcul du prix
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
    $prix = 0;

    if ($nb_forfaits >= 4) {
        $prix += 40000;
        $nb_forfaits -= 4;
    }

    $prix += $nb_forfaits * 12000;
    $prix += $reste * 5000;
}

// Stocker les données en session
$_SESSION['mois'] = $mois;
$_SESSION['user_id'] = $_SESSION['user_id'] ?? null; // Doit être défini lors de la connexion

// Redirection vers Kkiapay
$callbackUrl = "callback.php"; // À adapter
$url = "https://widget-v2.kkiapay.me/?amount={$prix}&key={$publicKey}&sandbox=false&reason=Abonnement%20FreeBenin%20{$mois}mois&callback={$callbackUrl}";

header("Location: $url");
exit;
