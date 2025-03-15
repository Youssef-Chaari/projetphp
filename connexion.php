<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php';

// Rediriger l'utilisateur s'il est déjà connecté
if (isset($_SESSION['utilisateur_id'])) {
    // Vérifier le rôle de l'utilisateur pour la redirection
    if ($_SESSION['utilisateur_role'] === 'admin') {
        header('Location: index-admin.php');
    } else {
        header('Location: index.php');
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $utilisateur = $stmt->fetch();

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        // Enregistrer les informations de l'utilisateur dans la session
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['utilisateur_nom'] = $utilisateur['nom'];
        $_SESSION['utilisateur_role'] = $utilisateur['role'];

        // Rediriger en fonction du rôle de l'utilisateur
        if ($utilisateur['role'] === 'admin') {
            header('Location: admin/index-admin.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <h1>Connexion</h1>
        <?php if (isset($erreur)) : ?>
            <p style="color: red;"><?= $erreur ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="email">Email :</label>
            <input type="email" name="email" required><br>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" name="mot_de_passe" required><br>

            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous ici</a>.</p>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>