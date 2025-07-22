<?php
session_start();

if (!isset($_SESSION["connecte"]) || $_SESSION["connecte"] !== true) {
    header('Location: ../index.php');
    exit();
}

require_once(__DIR__ . "/../bdd/creation_bdd.php");

$searchResults = [];
$type = $_POST['type'] ?? '';
$keywords = $_POST['keywords'] ?? '';
$reset = isset($_POST['reset']);

if ($reset) {
    $type = '';
    $keywords = '';
} else {
    $keyword = "%$keywords%";

    if (!empty($keywords)) {
        switch ($type) {
            case 'client':
                $stmt = $bdd->prepare("SELECT * FROM inscription WHERE (nom LIKE ? OR prenom LIKE ? OR nomDUtilisateur LIKE ?) AND role = 'client'");
                $stmt->execute([$keyword, $keyword, $keyword]);
                $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'freelancer':
                $stmt = $bdd->prepare("SELECT i.nom, i.prenom, i.id AS id, i.nomDUtilisateur FROM freelancers f INNER JOIN inscription i ON f.user_id = i.id WHERE i.nom LIKE ? OR i.prenom LIKE ? OR i.nomDUtilisateur LIKE ?");
                $stmt->execute([$keyword, $keyword, $keyword]);
                $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'entreprise':
                $stmt = $bdd->prepare("SELECT * FROM inscription WHERE (nom LIKE ? OR prenom LIKE ? OR nomDUtilisateur LIKE ?) AND role = 'entreprise'");
                $stmt->execute([$keyword, $keyword, $keyword]);
                $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

            default:
                $stmt = $bdd->prepare("
                    SELECT d.*, i.nom, i.prenom, i.id 
                    FROM demande d 
                    INNER JOIN inscription i ON d.user_id = i.id 
                    WHERE (d.titre LIKE ? OR d.description LIKE ? OR d.categorie LIKE ? OR i.nom LIKE ? OR i.prenom LIKE ?)
                    AND d.statut = 'en attente'
                ");
                $stmt->execute([$keyword, $keyword, $keyword, $keyword, $keyword]);
                $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>🔍 Recherche</title>
  <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
 <?php require_once(__DIR__ . "/../front_projet_EDL/header.php"); ?>


  <main class="container py-5">
    <h3 class="mb-4 text-primary">Recherche</h3>

    <form class="d-flex gap-3 flex-wrap mb-5" method="POST" action="">
      <input 
        type="search" 
        class="form-control shadow-none border-secondary-subtle flex-grow-1" 
        placeholder="Ex : nom, domaine, titre de demande..." 
        name="keywords" 
        value="<?= htmlspecialchars($keywords ?? '') ?>"
      >

      <select name="type" class="form-select shadow-none bg-info text-white" style="min-width: 150px;">
        <option value="">Filtrer par type</option>
        <option value="client" <?= $type === 'client' ? 'selected' : '' ?>>Client</option>
        <option value="freelancer" <?= $type === 'freelancer' ? 'selected' : '' ?>>Freelancer</option>
        <option value="entreprise" <?= $type === 'entreprise' ? 'selected' : '' ?>>Entreprise</option>
        <option value="demande" <?= $type === 'demande' ? 'selected' : '' ?>>Demande</option>
      </select>

      <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i> Rechercher</button>
      <button type="submit" name="reset" class="btn btn-outline-danger">❌ Réinitialiser</button>
    </form>

    <?php if (!empty($keywords) && !$reset): ?>
      <?php if (count($searchResults) > 0): ?>
        <?php foreach ($searchResults as $result): ?>
          <div class="container py-3">
            <div class="row justify-content-center">
              <div class="col-lg-8 col-md-12">
                <div class="card p-3 shadow border-warning-subtle border-2 rounded-4">
                  <div class="card-body overflow-auto">
                    <?php if (isset($result['titre']) && isset($result['description'])): ?>
                      <div class="pb-2">
                        <i class="bi bi-person-fill"></i> Posté par : 
                        <a href="info_profile.php?id=<?= htmlspecialchars($result['id']) ?>" class="text-decoration-none">
                          <strong><?= htmlspecialchars($result["nom"]) . ' ' . htmlspecialchars($result["prenom"]) ?></strong>
                        </a>
                      </div>
                      <h5 class="card-title"><?= htmlspecialchars($result["titre"]) ?></h5>
                      <p class="card-text text-muted"><?= htmlspecialchars($result["description"]) ?></p>
                    <?php else: ?>
                      <div class="pb-2">
                        <i class="bi bi-person-fill"></i> Profil : 
                        <a href="info_profile.php?id=<?= htmlspecialchars($result['id']) ?>" class="text-decoration-none">
                          <strong><?= htmlspecialchars($result["nom"]) . ' ' . htmlspecialchars($result["prenom"]) ?> 
                            <i>(<?= htmlspecialchars($result['nomDUtilisateur']) ?>)</i>
                          </strong>
                        </a>
                        <form method="POST" action="supprimer_element.php" onsubmit="return confirm('🗑️ Êtes-vous sûr de vouloir supprimer cet élément ?');">
                            <input type="hidden" name="id" value="<?= $result['id'] ?>">
                            <input type="hidden" name="type" value="<?= $type ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger mt-2">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                            </form>

                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center my-4 text-danger">🚫 Aucun résultat trouvé.</p>
      <?php endif; ?>
    <?php endif; ?>
  </main>

  <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
