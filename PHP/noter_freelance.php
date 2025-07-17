<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification des données reçues
    $demande_id = filter_input(INPUT_POST, 'demande_id', FILTER_VALIDATE_INT);
    $freelancer_id = filter_input(INPUT_POST, 'freelancer_id', FILTER_VALIDATE_INT);
    $stars = filter_input(INPUT_POST, 'stars', FILTER_VALIDATE_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    
    // Validation des champs requis
    if (!$demande_id || !$freelancer_id || !$stars || $stars < 1 || $stars > 5) {
        $_SESSION['error'] = "Données de notation invalides";
        header("Location: ../front_projet_EDL/suivi_notation_projet_client.php?id=" . $demande_id);
        exit();
    }

    // Vérification que l'utilisateur peut noter cette demande
    $stmt = $bdd->prepare("SELECT * FROM demande 
                          WHERE id = :demande_id 
                          AND user_id = :user_id 
                          AND freelancer_id = :freelancer_id 
                          AND statut = 'terminé'");
    $stmt->execute([
        ':demande_id' => $demande_id,
        ':user_id' => $_SESSION['user_id'],
        ':freelancer_id' => $freelancer_id
    ]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = "Accès non autorisé ou demande non terminée";
        header("Location: ../front_projet_EDL/suivi_notation_projet_client.php?id=" . $demande_id);
        exit();
    }

    // Vérification si une notation existe déjà
    $stmtCheck = $bdd->prepare("SELECT * FROM notation 
                               WHERE order_id = :demande_id 
                               AND user_id = :user_id");
    $stmtCheck->execute([
        ':demande_id' => $demande_id,
        ':user_id' => $_SESSION['user_id']
    ]);
    
    if ($stmtCheck->fetch()) {
        $_SESSION['error'] = "Vous avez déjà noté ce projet";
        header("Location: ../front_projet_EDL/suivi_notation_projet_client.php?id=" . $demande_id);
        exit();
    }

    // Insertion de la notation
    try {
        $stmt = $bdd->prepare("INSERT INTO notation (freelancer_id, user_id, order_id, stars, comment) 
                              VALUES (:freelancer_id, :user_id, :order_id, :stars, :comment)");
        $stmt->execute([
            ':freelancer_id' => $freelancer_id,
            ':user_id' => $_SESSION['user_id'],
            ':order_id' => $demande_id,
            ':stars' => $stars,
            ':comment' => $comment ?: null // Commentaire facultatif
        ]);
        
        $_SESSION['success'] = "Notation enregistrée avec succès";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de l'enregistrement de la notation : " . $e->getMessage();
    }
    
    header("Location: ../front_projet_EDL/suivi_notation_projet_client.php?id=" . $demande_id);
    exit();
} else {
    $_SESSION['error'] = "Méthode non autorisée";
    header("Location: ../front_projet_EDL/suivi_notation_projet_client.php");
    exit();
}
?>