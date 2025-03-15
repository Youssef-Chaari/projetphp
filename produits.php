<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php'; // Vérifie si l'utilisateur est connecté
verifierConnexion(); // Applique la vérification
include 'includes/header.php';

// Récupérer les produits
$sql = "SELECT * FROM produits";
$stmt = $pdo->query($sql);
$produits = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos Maillots</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main>
        <h1>Nos Maillots</h1>
        <div class="produits">
            <?php if (empty($produits)) : ?>
                <p>Aucun produit disponible pour le moment.</p>
            <?php else : ?>
                <?php foreach ($produits as $produit) : ?>
                    <div class="produit">
                        <!-- Afficher l'image du produit -->
                        <?php if (!empty($produit['image'])) : ?>
                            <img src="uploads/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                        <?php else : ?>
                            <img src="assets/images/default.jpg" alt="Image par défaut">
                        <?php endif; ?>

                        <h2><?= htmlspecialchars($produit['nom']) ?></h2>
                        <p><?= htmlspecialchars($produit['prix']) ?> DT</p>
                        <a href="produit_detail.php?id=<?= $produit['id'] ?>" class="btn">Voir plus</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>