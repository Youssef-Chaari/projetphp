<?php
include '../includes/config.php';
include '../includes/auth.php';
verifierConnexion();
verifierAdmin();

if (!isset($_GET['id'])) {
    header('Location: produits.php');
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM produits WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$produit = $stmt->fetch();

if (!$produit) {
    header('Location: produits.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $stock = $_POST['stock'];

    $sql = "UPDATE produits SET nom = :nom, prix = :prix, stock = :stock WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nom' => $nom, 'prix' => $prix, 'stock' => $stock, 'id' => $id]);

    header('Location: produits.php');
    exit;
}

include '../includes/header-admin.phpin.phpin.phpin.phpin.phpin.php';
?>

<h1>Modifier le Produit</h1>
<form method="POST">
    <label for="nom">Nom du produit</label>
    <input type="text" id="nom" name="nom" value="<?= $produit['nom'] ?>" required>

    <label for="prix">Prix</label>
    <input type="number" id="prix" name="prix" step="0.01" value="<?= $produit['prix'] ?>" required>

    <label for="stock">Stock</label>
    <input type="number" id="stock" name="stock" value="<?= $produit['stock'] ?>" required>

    <button type="submit" class="btn">Enregistrer</button>
</form>

<?php
include '../includes/footer.php';
?>