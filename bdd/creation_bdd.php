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

    $sqlTables =
        "

        CREATE TABLE IF NOT EXISTS inscription (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NULL,
    nomDUtilisateur VARCHAR(100) NOT NULL UNIQUE,
    numero VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    activity_sector VARCHAR(100) NULL,
    web_site VARCHAR(100) NULL,
    ville VARCHAR(200) NULL,
    pays VARCHAR(200) NULL,
    facebook_url VARCHAR(100) NULL,
    linkdin_url VARCHAR(100) NULL,
    nombre_employes INT NULL,
    legal_id INT(20) NULL,
    adresse VARCHAR(200) NULL,
    annee DATE NULL,
    photo VARCHAR(200) DEFAULT 'photo_profile.jpg',
    motDePasse VARCHAR(100) NOT NULL,
    token VARCHAR(255) DEFAULT NULL,
    est_confirme BOOLEAN DEFAULT FALSE,
    admin ENUM('admin','non_admin') DEFAULT 'non_admin',
    avertissement INT DEFAULT 0,
    role ENUM('client','freelance','entreprise') DEFAULT 'client',
    date_debut_abonnement DATE DEFAULT CURRENT_DATE,
    date_fin_abonnement DATE DEFAULT (CURRENT_DATE + INTERVAL 6 MONTH)
);

    CREATE TABLE IF NOT EXISTS signalements_profil (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    signale_par INT NOT NULL,
    raison TEXT NOT NULL,
    date_signalement DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES inscription(id) ON DELETE CASCADE,
    FOREIGN KEY (signale_par) REFERENCES inscription(id) ON DELETE CASCADE
);
       
        CREATE TABLE IF NOT EXISTS freelancers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNIQUE,
        bio TEXT,
        competences TEXT,
        gitHub VARCHAR(100) NOT NULL,
        linkdin VARCHAR(100) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES inscription(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS demande
        (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL ,
        freelancer_id INT NULL ,
        categorie VARCHAR(100) NOT NULL,
        titre VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        budget DECIMAL (10,2) NOT NULL,
        date_soumission DATE ,
        date_souhaitee DATE  NULL,
        date_attribution DATE NULL,
        avancement INT DEFAULT 0,
        date_fin DATE NULL,
        statut ENUM('en attente', 'attribué', 'en cours', 'terminé', 'annulé','signalee') DEFAULT 'en attente',

        FOREIGN KEY (user_id) REFERENCES inscription(id) ON DELETE CASCADE ,
        FOREIGN KEY (freelancer_id) REFERENCES freelancers(id) ON DELETE CASCADE  
        );

        CREATE TABLE IF NOT EXISTS signalements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        demande_id INT NOT NULL,
        signale_par INT NOT NULL,
        raison TEXT NOT NULL,
        date_signalement DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (demande_id) REFERENCES demande(id) ON DELETE CASCADE,
        FOREIGN KEY (signale_par) REFERENCES inscription(id) ON DELETE CASCADE
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
        FOREIGN KEY (demande_id) REFERENCES demande(id) ON DELETE CASCADE );
        
        CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        message TEXT NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        modifie BOOL DEFAULT FALSE NOT NULL,
        sup_for_sender BOOL DEFAULT FALSE NOT NULL,
        sup_for_receiver BOOL DEFAULT FALSE NOT NULL,
        sup_tout_le_monde BOOL DEFAULT FALSE NOT NULL,
        lu BOOL DEFAULT FALSE NOT NULL,
        FOREIGN KEY (sender_id) REFERENCES inscription(id),
        FOREIGN KEY (receiver_id) REFERENCES inscription(id)
        );

        CREATE TABLE IF NOT EXISTS bannis (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        numero VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        nomDUtilisateur VARCHAR(100) NOT NULL,
        photo VARCHAR(200) DEFAULT 'photo_profile.jpg',
        role ENUM('client','freelance') DEFAULT 'client',
        date_bannissement DATETIME DEFAULT CURRENT_TIMESTAMP
        )
        ;

        CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        message TEXT NOT NULL,
        is_read BOOLEAN DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES inscription(id)
        );

        CREATE TABLE IF NOT EXISTS transaction (
          id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          utilisateur_id INT NOT NULL,
          mois INT NOT NULL,
          transaction_id VARCHAR(100) NOT NULL,
          date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,

          FOREIGN KEY (utilisateur_id) REFERENCES inscription(id) ON DELETE CASCADE
      );


";

    $bdd->exec($sqlTables);

    $bdd->exec("DROP TRIGGER IF EXISTS after_suivi_insert");

    $triggerSql = "
    CREATE TRIGGER after_suivi_insert
    AFTER INSERT ON suivi_projet
    FOR EACH ROW
    BEGIN
        DECLARE max_pourcentage INT;
        
        SELECT MAX(pourcentage) INTO max_pourcentage 
        FROM suivi_projet 
        WHERE demande_id = NEW.demande_id;
        
        IF max_pourcentage = 100 THEN
            UPDATE demande 
            SET statut = 'terminé', 
                avancement = 100,
                date_fin = NOW()
            WHERE id = NEW.demande_id;
        END IF;
    END";

    //echo "Tables créées avec succès.";

    $bdd->exec($triggerSql);
    $motDePasse = password_hash('JUDICAEL1234', PASSWORD_DEFAULT);
    $sqlInsertions = "

-- INSCRIPTION
INSERT IGNORE INTO inscription (
  id,nom, prenom, numero, email, motDePasse, nomDUtilisateur, photo, token, est_confirme, admin, avertissement, role
) VALUES 
(
  1,'GBAGUIDI', 'Judicael', '0197000000', 'gbaguidijudicael520@gmail.com',
  '$motDePasse', 'G.J', 'photo_profile.jpg', NULL, TRUE, 'non_admin', 0, 'client'
),
(
  2,'Decouverte', 'Utilisateur', '01--------', 'decouverte_de_platform@gmail.com',
  '$motDePasse', 'Utilisateur', 'photo_profile.jpg', NULL, TRUE, 'non_admin', 0, 'freelance'
);

-- FREELANCERS
INSERT IGNORE INTO freelancers (user_id, bio, competences)
VALUES (
  2,
  'Je suis un utilisateur découvrant la plateforme de freelance pour le Bénin. La plateforme connectant les talents béninois',
  'Mes compétences sont diverses et variées'
);

INSERT IGNORE INTO demande (
  id,user_id, categorie, titre, description, budget, date_soumission, statut, avancement, date_fin
) VALUES (
  1,
  1,
  'Site Web',
  'site de vente',
  'je souhaite un site web pour la vente de mes articles',
  50000,
  CURDATE(),
  'terminé',
  100,
  CURDATE()
);

-- SUIVI PROJET
INSERT IGNORE INTO suivi_projet (id,demande_id, etape, pourcentage,date_mise_a_jour, commentaire)
VALUES 
(1,1, '1er etape', 25,CURDATE(), 'Réalisation du backend'),
(2,1, '2e etape', 50,CURDATE(), 'Le frontend'),
(3,1, '3e etape', 55,CURDATE(), 'La dynamisation'),
(4,1, 'Dernière etape', 100, CURDATE(),'La mise en ligne');

-- MESSAGES
INSERT IGNORE INTO messages (
  sender_id, receiver_id, message, created_at, modifie, sup_for_sender, sup_for_receiver, sup_tout_le_monde, lu
) VALUES
(
  1, 2, 'Bonjour comment allez-vous ?', '2025-07-17 23:17:03',
  0, 0, 0, 0, 1
),
(
  1, 2,'Jai vu votre profil et je souhaite collaborer', '2025-07-17 23:18:08',
  0, 1, 0, 0, 1
);




INSERT IGNORE INTO notation (
  freelancer_id, user_id, stars, comment
) VALUES (
  1, 1, 5, 'Du bon travail a été fait je suis satisfait'
);

-- NOTIFICATIONS
INSERT IGNORE INTO notifications (
  user_id, message, is_read, created_at
) VALUES 
(
  2, 'GBAGUIDI Judicael vous a envoyé un nouveau message...', 1, '2025-07-17 23:17:03'
),
(
  2, 'GBAGUIDI Judicael vous a envoyé un nouveau message...', 1, '2025-07-17 23:18:08'
);
";

$check = $bdd->query("SELECT COUNT(*) FROM inscription WHERE id = 1");
$exists = $check->fetchColumn();

if ($exists == 0) {
    $bdd->exec($sqlInsertions); // Lancement des insertions si pas déjà présentes
}


} catch (PDOException $e) {
    echo "Echec lors de la connexion : " . $e->getMessage();
}

//echo "Tables créées avec succès.";
