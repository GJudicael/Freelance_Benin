<?php
require_once(__DIR__ . "/../bdd/creation_bdd.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$signalement_id = $_POST['signalement_id'] ?? null;
$action = $_POST['action_type'] ?? null;

if (!$signalement_id || !$action) {
    header("Location: signalements_profils.php?erreur=1");
    exit();
}

// Récupère l'ID de l'utilisateur signalé
$stmt = $bdd->prepare("SELECT utilisateur_id FROM signalements_profil WHERE id = :id");
$stmt->execute(['id' => $signalement_id]);
$data = $stmt->fetch();

if (!$data) {
    header("Location: signalements_profils.php?erreur=profil-inexistant");
    exit();
}

$utilisateur_id = $data['utilisateur_id'];

switch ($action) {
    case 'retablir':
        // ✅ Supprime uniquement le signalement
        $bdd->prepare("DELETE FROM signalements_profil WHERE id = :id")
            ->execute(['id' => $signalement_id]);
        break;

    case 'supprimer':
        // 🔍 Récupère les infos du compte à bannir
        $recup = $bdd->prepare("SELECT nom, prenom, numero, email, nomDUtilisateur, photo, role FROM inscription WHERE id = :id");
        $recup->execute(['id' => $utilisateur_id]);
        $profil = $recup->fetch();

        if ($profil) {
            // 📥 Insère dans la table bannis
            $insert = $bdd->prepare("
                INSERT INTO bannis (nom, prenom, numero, email, nomDUtilisateur, photo, role)
                VALUES (:nom, :prenom, :numero, :email, :pseudo, :photo, :role)
            ");
            $insert->execute([
                'nom'     => $profil['nom'],
                'prenom'  => $profil['prenom'],
                'numero'  => $profil['numero'],
                'email'   => $profil['email'],
                'pseudo'  => $profil['nomDUtilisateur'],
                'photo'   => $profil['photo'],
                'role'    => $profil['role'],
            ]);
        }

        // 🧹 Supprime les signalements et le compte
        $bdd->prepare("DELETE FROM signalements_profil WHERE utilisateur_id = :id")
            ->execute(['id' => $utilisateur_id]);

        $bdd->prepare("DELETE FROM inscription WHERE id = :id")
            ->execute(['id' => $utilisateur_id]);
        break;
}

header("Location: signalements_profils.php?success=1");
exit();
?>
