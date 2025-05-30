<?php 

// Définition de constantes

const BDD_HOST = 'localhost';
const BDD_NAME = 'freelance_benin';
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
        nomDUtilisateur VARCHAR(100) NOT NULL PRIMARY KEY ,
        photo VARCHAR(200) DEFAULT 'photo_profile.jpg',
        role ENUM('client','freelance') DEFAULT 'client'
        );

        CREATE TABLE IF NOT EXISTS freelancers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNIQUE,
        bio TEXT,
        competences TEXT,
        FOREIGN KEY (user_id) REFERENCES inscription(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS demande
        (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL ,
        freelancer_id INT NULL ,
        categorie VARCHAR(100) NOT NULL,
        titre VARCHAR(100) NOT NULL,
        description TEXT(1000) NOT NULL,
        date_soumission DATE ,
        date_attribution DATE NULL,
        avancement INT DEFAULT 0,
        date_fin DATE NULL,
        statut ENUM('en attente', 'attribué', 'en cours', 'terminé', 'annulé') DEFAULT 'en attente',
        FOREIGN KEY (user_id) REFERENCES inscription(id) ON DELETE CASCADE ,
        FOREIGN KEY (freelancer_id) REFERENCES freelancers(id) ON DELETE CASCADE  
        );

        

        CREATE TABLE IF NOT EXISTS projets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        freelancer_id INT,
        titre VARCHAR(255),
        description TEXT,
        image VARCHAR(255), -- chemin vers l’image (facultatif)
        lien VARCHAR(255),   -- lien externe (ex: GitHub, site démo)
        date_projet DATE,
        FOREIGN KEY (freelancer_id) REFERENCES freelancers(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS notation (
        id INT AUTO_INCREMENT PRIMARY KEY,
        freelancer_id INT,
        user_id INT,
        order_id INT,
        stars TINYINT,
        comment TEXT(1000),
        FOREIGN KEY (freelancer_id) REFERENCES freelancers(id) ON DELETE CASCADE ,
        FOREIGN KEY (user_id) REFERENCES inscription(id) ON DELETE CASCADE,
        FOREIGN KEY (order_id) REFERENCES demande(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS suivi_projet (
        id INT AUTO_INCREMENT PRIMARY KEY,
        demande_id INT NOT NULL,
        etape VARCHAR(100) NOT NULL,
        pourcentage INT DEFAULT 0,
        date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP,
        commentaire TEXT,
        FOREIGN KEY (demande_id) REFERENCES demande(id) ON DELETE CASCADE
);

        
    ";

    $bdd->exec($sqlTables);

    //echo "Tables créées avec succès.";

} catch (PDOException $e) {
    echo "Echec lors de la connexion : " . $e->getMessage();
}


?>