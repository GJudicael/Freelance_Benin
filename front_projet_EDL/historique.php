<?php
include("refresh.php");

$result = $bddPDO->query("SELECT * FROM demande ORDER BY descriptionDeLaDemande DESC");

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "<div>";
    echo "<h3>Demande : " . htmlspecialchars($row['categorie']) . "</h3>";
    echo "<p>" . htmlspecialchars($row['descriptionDeLaDemande']) . "</p>";
    echo "<hr>";
    echo "</div>";
}
?>
