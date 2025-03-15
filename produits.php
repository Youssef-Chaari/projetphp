<?php
include 'includes/config.php';
include 'includes/auth.php'; // Vérifie si l'utilisateur est connecté
verifierConnexion(); // Applique la vérification
include 'includes/header.php';

// Récupérer les produits
$sql = "SELECT * FROM produits";
$stmt = $pdo->query($sql);
$produits = $stmt->fetchAll();
?>

<main>
<link rel="stylesheet" href="assets/css/style.css">

    <h1>Nos Maillots</h1>
    <div class="produits">
        <?php foreach ($produits as $produit) : ?>
            <div class="produit">
                <img src="assets/images/<?= $produit['image'] ?>" alt="<?= $produit['nom'] ?>">
                <h2><?= $produit['nom'] ?></h2>
                <p><?= $produit['prix'] ?> DT</p>
                <a href="produit_detail.php?id=<?= $produit['id'] ?>" class="btn">Voir plus</a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php
include 'includes/footer.php';
?>