<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php
date_default_timezone_set('Africa/Lagos');
$date_aujourdhui = time();

echo $date_aujourdhui;
echo '<br>';
echo date('d/m/Y', $date_aujourdhui);
echo '<br>';
echo date('d/m/Y', $date_aujourdhui-86400);
// echo date('d/m/Y', strtotime($date_aujourdhui-86400));
?>

<body>

</body>

</html>