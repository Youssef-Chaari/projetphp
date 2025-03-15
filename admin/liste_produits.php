<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php'; // Vérifie si l'utilisateur est admin
verifierAdmin();

$sql = "SELECT * FROM produits";
$stmt = $pdo->query($sql);
$produits = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des produits</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header-admin.php'; ?>
    <main>
<link rel="stylesheet" href="assets/css/style.css">

        <h1>Liste des produits</h1>
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
                            <a href="modifier_produit.php?id=<?= $produit['id'] ?>">Modifier</a>
                            <a href="supprimer_produit.php?id=<?= $produit['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>