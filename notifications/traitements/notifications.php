<?php
require_once(__DIR__.'/../fonctions_utilitaires.php');
require_once(__DIR__ . '/../../bdd/creation_bdd.php');

// Un formateur localisé en français

$formatter = new IntlDateFormatter(
    'fr_FR', // locale
    IntlDateFormatter::LONG, // format de la date (LONG => 12 décembre 2019)
    IntlDateFormatter::NONE  // pas d'heure
);

$notifications = recupererNotifications('tous');
$notifications_non_lues = recupererNotifications('non_lues');
$notifications_non_lues = count($notifications_non_lues) != 0 ? true : false;