<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php'; // Vérifie si l'utilisateur est admin

if ($_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header-admin.php'; ?>
    <main>
        <h1>Tableau de Bord Admin</h1>
        <ul>
            <li><a href="ajouter_produit.php">Ajouter un produit</a></li>
            <li><a href="liste_produits.php">Liste des produits</a></li>
            <li><a href="liste_commandes.php">Liste des commandes</a></li>
        </ul>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>