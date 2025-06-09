<?php
session_start();
require_once(__DIR__."/../bdd/creation_bdd.php");

// Vérification accès
$user_id = $_SESSION["user_id"];
$stmt = $bdd->prepare("SELECT i.role FROM inscription i WHERE i.id=:user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result['role'] !== 'freelance') {
    header("Location: ../front_projet_EDL/Mesmissions.php");
    exit();
}

// Récupération mission
$stmt = $bdd->prepare("SELECT d.*, 
                      c.nom AS client_nom, 
                      c.prenom AS client_prenom,
                      f.user_id,
                      (SELECT MAX(pourcentage) FROM suivi_projet WHERE demande_id = d.id) AS avancement
                      FROM demande d
                      JOIN inscription c ON d.user_id = c.id
                      JOIN freelancers f ON f.id = d.freelancer_id
                      WHERE d.id = ? AND f.user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$mission = $stmt->fetch();

if (!$mission) {
    $_SESSION['error'] = "Mission introuvable";
    header("Location: ../front_projet_EDL/Mesmissions.php");
    exit();
}

// Traitement formulaire nouvelle étape
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation du pourcentage
    $last_percent = $bdd->query("SELECT MAX(pourcentage) FROM suivi_projet WHERE demande_id = ".$mission['id'])
                       ->fetchColumn();
    
    if ($_POST['pourcentage'] <= $last_percent) {
        $_SESSION['error'] = "Le pourcentage doit être supérieur au dernier enregistrement ($last_percent%)";
        header("Location: traitement_suivi_projet.php?id=".$mission['id']);
        exit();
    }

    // Empêcher les pourcentages > 100
    if ($_POST['pourcentage'] > 100) {
        $_SESSION['error'] = "Ce projet est déjà terminé , vous ne pouvez plus ajouter une étape";
        header("Location: traitement_suivi_projet.php?id=".$mission['id']);
        exit();
}

    // Insertion de la nouvelle étape
    $stmt = $bdd->prepare("INSERT INTO suivi_projet 
                          (demande_id, etape, pourcentage, commentaire)
                          VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $mission['id'],
        $_POST['etape'],
        $_POST['pourcentage'],
        $_POST['commentaire'] ?? null
    ]);
    
    // Mise à jour statut si premier ajout
    if ($mission['statut'] === 'attribué') {
        $bdd->prepare("UPDATE demande SET statut = 'en cours' WHERE id = ?")
           ->execute([$mission['id']]);
    }
    
    // Mise à jour de l'avancement global
    $bdd->prepare("UPDATE demande 
                  SET avancement = (SELECT MAX(pourcentage) 
                                   FROM suivi_projet 
                                   WHERE demande_id = ?)
                  WHERE id = ?")
       ->execute([$mission['id'], $mission['id']]);
    
    $_SESSION['success'] = "Étape ajoutée avec succès";
    header("Location: traitement_suivi_projet.php?id=".$mission['id']);
    exit();


    // Après avoir inséré une nouvelle étape
    if ($_POST['pourcentage'] == 100) {
    // Marquer la mission comme terminée
    $stmt = $bdd->prepare("UPDATE demande 
                          SET statut = 'terminé', 
                              avancement = 100,
                              date_fin = NOW() 
                          WHERE id = ?");
    $stmt->execute([$mission['id']]);
    
    $_SESSION['success'] = "Félicitations ! Le projet est marqué comme terminé.";
     }
}

// Récupération des étapes existantes pour affichage
$etapes = $bdd->query("SELECT * FROM suivi_projet 
                      WHERE demande_id = ".$mission['id']." 
                      ORDER BY date_mise_a_jour DESC");

// Préparation des données pour la vue
$viewData = [
    'mission' => $mission,
    'etapes' => $etapes->fetchAll(PDO::FETCH_ASSOC),
    'error' => $_SESSION['error'] ?? null,
    'success' => $_SESSION['success'] ?? null
];

// Nettoyage des messages de session
unset($_SESSION['error']);
unset($_SESSION['success']);

// Inclusion de la vue

require_once('../front_projet_EDL/suivi_projet_view.php');
?>