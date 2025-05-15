<?php 

// Définition de constantes

const BDD_HOST = 'localhost';
const BDD_NAME = 'freelaance_benin';
const BDD_USER = 'root';
const BDD_PASSWORD = '';

try {
    // Connexion au serveur MySQL
    $bdd = new PDO('mysql:host=' . BDD_HOST, BDD_USER, BDD_PASSWORD);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la base de données
    $stmt = "CREATE DATABASE IF NOT EXISTS `" . BDD_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    $bdd->exec($stmt);

    //echo "Base de données créé avec succès.<br>";

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

try {
    $bdd = new PDO('mysql:host=' . BDD_HOST . ';dbname=' . BDD_NAME . ';', BDD_USER, BDD_PASSWORD);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création des tables

    $sqlTables = "

        CREATE TABLE IF NOT EXISTS inscription
        (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        numero VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        motDePasse VARCHAR(100) NOT NULL,
        nomDUtilisateur VARCHAR(100) NOT NULL
        );

        
    ";

    $bdd->exec($sqlTables);

    //echo "Tables créées avec succès.";

} catch (PDOException $e) {
    echo "Echec lors de la connexion : " . $e->getMessage();
}


?>