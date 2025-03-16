<?php
session_start();
include 'includes/config.php';

if (isset($_SESSION['utilisateur_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, date_inscription) VALUES (:nom, :email, :mot_de_passe, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nom' => $nom, 'email' => $email, 'mot_de_passe' => $mot_de_passe]);

    header('Location: connexion.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main>
<link rel="stylesheet" href="assets/css/style.css">

        <h1>Inscription</h1>
        <form method="POST" action="">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" required><br>

            <label for="email">Email :</label>
            <input type="email" name="email" required><br>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" name="mot_de_passe" required><br>

            <button type="submit">S'inscrire</button>
        </form>
        <p>Déjà inscrit ? <a href="connexion.php">Connectez-vous ici</a>.</p>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>