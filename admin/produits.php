<?php
include '../includes/config.php';
include '../includes/auth.php';
verifierConnexion();
verifierAdmin();

include '../includes/header-admin.php';

// Récupérer la liste des produits
$sql = "SELECT * FROM produits";
$stmt = $pdo->query($sql);
$produits = $stmt->fetchAll();
?>

<h1>Gestion des Produits</h1>
<link rel="stylesheet" href="styleadmin.css">

<a href="ajouter_produit.php" class="btn">Ajouter un produit</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $produit) : ?>
            <tr>
                <td><?= $produit['id'] ?></td>
                <td><?= $produit['nom'] ?></td>
                <td><?= $produit['prix'] ?> DT</td>
                <td><?= $produit['stock'] ?></td>
                <td>
                    <a href="modifier_produit.php?id=<?= $produit['id'] ?>" class="btn">Modifier</a>
                    <a href="supprimer_produit.php?id=<?= $produit['id'] ?>" class="btn-supprimer">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include '../includes/footer.php';
?>