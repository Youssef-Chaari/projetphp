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
    <img src="assets/images/<?= $produit['image'] ?>" alt="<?= $produit['nom'] ?>">
    <p><?= $produit['description'] ?></p>
    <p>Prix : <?= $produit['prix'] ?> DT</p>
    <p>Équipe : <?= $produit['equipe'] ?></p>
    <p>Ligue : <?= $produit['ligue'] ?></p>
    <p>Taille : <?= $produit['taille'] ?></p>
    <p>Saison : <?= $produit['saison'] ?></p>
    <a href="panier.php?action=ajouter&id=<?= $produit['id'] ?>" class="btn">Ajouter au panier</a>
</main>

<?php
include 'includes/footer.php';
?>