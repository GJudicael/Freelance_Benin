<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php
date_default_timezone_set('Africa/Lagos');

// $date_1 a été fait en partant sur un lundi

$date_1 = time();
$date_2 = time();
$timestamp_semaine = 86400 * 6;

for ($i = 1; $i <= 17; $i++) {
    $date_2 = $date_1 + $timestamp_semaine; // $date_2 doit contenir un dimanche
    echo "Semaine du " . date('d', $date_1) . " au " . date('d', $date_2) . " ".date('F', $date_2)." ".date('Y')."<br>";
    $date_1 = $date_2 + 86400; // on ramène $date_1 au lundi qui vient après le dimanche indiqué
}
?>

<body>

</body>

</html>