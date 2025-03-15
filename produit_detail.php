<?php
session_start();
include 'includes/config.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: produits.php');
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM produits WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$produit = $stmt->fetch();

if (!$produit) {
    header('Location: produits.php');
    exit();
}
?>

<main>
    <link rel="stylesheet" href="assets/css/style.css">
    <h1><?= $produit['nom'] ?></h1>
    <div class="produit-detail">
        <img src="uploads/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" class="produit-image">
        <div class="produit-info">
            <p><?= $produit['description'] ?></p>
            <p><strong>Prix :</strong> <?= $produit['prix'] ?> DT</p>
            <p><strong>Équipe :</strong> <?= $produit['equipe'] ?></p>
            <p><strong>Ligue :</strong> <?= $produit['ligue'] ?></p>
            <p><strong>Taille :</strong> <?= $produit['taille'] ?></p>
            <p><strong>Saison :</strong> <?= $produit['saison'] ?></p>
            <a href="panier.php?action=ajouter&id=<?= $produit['id'] ?>" class="btn">Ajouter au panier</a>
        </div>
    </div>
</main>

<?php
include 'includes/footer.php';
?>