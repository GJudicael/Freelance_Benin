<?php session_start() ;

    require_once(__DIR__."/../bdd/creation_bdd.php");

    $searchResults = [];
    $type = $_POST['type'] ?? '';
    $keywords = $_POST['keywords'] ?? '';
    $keyword = "%$keywords%";

    if (!empty($keywords)) {
        switch ($type) {
            case 'client':
                $stmt = $bdd->prepare("SELECT * FROM inscription WHERE (nom LIKE ? OR prenom LIKE ? OR nomDUtilisateur LIKE ?) AND role = 'client'");
                $stmt->execute([$keyword, $keyword, $keyword]);
                $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'freelancer':
                $stmt = $bdd->prepare("SELECT i.nom, i.prenom, i.id AS id, i.nomDUtilisateur FROM freelancers f INNER JOIN inscription i ON f.user_id = i.id WHERE i.nom LIKE ? OR i.prenom LIKE ?");
                $stmt->execute([$keyword, $keyword]);
                $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

            default:
                $stmt = $bdd->prepare("SELECT d.*, i.nom, i.prenom, i.id FROM demande d INNER JOIN inscription i ON d.user_id = i.id WHERE titre LIKE ? OR description LIKE ? OR categorie LIKE ? OR nom LIKE ? OR prenom LIKE ?");
                $stmt->execute([$keyword, $keyword, $keyword , $keyword, $keyword]);
                $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de recherche</title>

    
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Baumans&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="../assets/style.css">


    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
</head>
<body>
    
    <?php require_once(__DIR__."/header.php"); ?>

    <main class="container">

        <h3 class="my-5 text-primary"> Recherche</h3>

        


        <form class="d-flex" method="POST" action="">
            <input type="search" class="form-control shadow-none border-secondary-subtle" placeholder="Recherche" name="keywords" value="<?= isset($keywords) ? htmlspecialchars($keywords) : '' ?>">
            <button class="btn btn-outline-secondary me-2" type="submit"><i class="bi bi-search"></i></button>
            <select name="type" class="form-select bg-info  shadow-none">
                <option value="">Filtre</option>
                <option value="client" <?= $type === 'client' ? 'selected' : '' ?>>Client</option>
                <option value="freelancer" <?= $type === 'freelancer' ? 'selected' : '' ?>>Freelancer</option>
                <option value="demande" <?= $type === 'demande' ? 'selected' : '' ?>>Demande</option>
            </select>
        
        
        </form>
        <?php if (!empty($keywords)): ?>
            
            <?php if (count($searchResults) > 0): ?>
                <?php foreach ($searchResults as $result): ?>
                    <div class="container py-4 ">
                        <div class="row justify-content-center ">
                            <div class="col-lg-8 col-md-12">
                                <div class="card h-100 p-2 shadow border-warning-subtle border-3 rounded-4">
                                    <div class="card-body  overflow-auto">
                                        <?php if (isset($result['titre']) && isset($result['description'])): ?>
                                            <div class="user-info pb-3">
                                                <i class="bi bi-person-fill"></i> Posté par :
                                                <a href="info_profile.php?id=<?= htmlspecialchars($result['id']) ?>" class="text-decoration-none text-tertiary">
                                                    <strong><?= htmlspecialchars($result['nom']) . ' ' . htmlspecialchars($result['prenom']) ?></strong>
                                                </a>
                                            </div>
                                            <h5 class="card-title text-secondaryg"> <?= htmlspecialchars($result["titre"]) ?></h5>
                                            <p class="card-text text-muted"> <?= htmlspecialchars($result["description"]) ?></p>
                                        <?php else: ?>
                                            <div class="user-info pb-3">
                                                <i class="bi bi-person-fill"></i> Profile :
                                                <a href="info_profile.php?id=<?= htmlspecialchars($result['id']) ?>" class="text-decoration-none text-tertiary">
                                                    <strong><?php echo htmlspecialchars($result['nom']) . ' ' . htmlspecialchars($result['prenom']) .
                                                         ' <i>('. htmlspecialchars($result['nomDUtilisateur']) . ')</i> ';
                                                    ?> </strong>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else :?>
                <p class="text-center my-4">Aucun résultat trouvé.</p>
            <?php endif; ?>
        <?php endif; ?>
    </main>

   
    <script src="../assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>