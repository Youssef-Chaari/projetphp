<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php'; // Vérifie si l'utilisateur est connecté

$sql = "SELECT * FROM utilisateurs WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_SESSION['utilisateur_id']]);
$utilisateur = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main>
<link rel="stylesheet" href="assets/css/style.css">

        <h1>Profil</h1>
        <p>Nom : <?= $utilisateur['nom'] ?></p>
        <p>Email : <?= $utilisateur['email'] ?></p>
        <p>Date d'inscription : <?= $utilisateur['date_inscription'] ?></p>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>