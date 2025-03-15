<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php'; // Vérifie si l'utilisateur est connecté

if (empty($_SESSION['panier'])) {
    header('Location: panier.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Enregistrer la commande
    $sql = "INSERT INTO commandes (id_utilisateur, date_commande, statut, total) VALUES (:id_utilisateur, NOW(), 'en cours de traitement', :total)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_utilisateur' => $_SESSION['utilisateur_id'], 'total' => array_sum(array_column($_SESSION['panier'], 'prix'))]);

    // Vider le panier
    $_SESSION['panier'] = [];

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main>
<link rel="stylesheet" href="assets/css/style.css">

        <h1>Passer une commande</h1>
        <form method="POST" action="">
            <button type="submit">Confirmer la commande</button>
        </form>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>