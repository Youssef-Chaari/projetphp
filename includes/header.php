<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maillots de Foot</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="assets/images/logo.png" alt="Logo">
        </div>
        <nav>
            <ul>

                <?php if (isset($_SESSION['utilisateur_id'])) : ?>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="produits.php">Produits</a></li>
                    <li><a href="profil.php">Profil</a></li>
                    <li><a href="panier.php">Panier</a></li>
                    <li><a href="historique_achats.php">Historique des commandes</a></li>
                    <li><a href="contact.php">Contacter Nous</a></li>
                    <li><a href="deconnexion.php">Déconnexion</a></li>
                <?php else : ?>
                    <li><a href="connexion.php">Connexion</a></li>
                    <li><a href="inscription.php">Inscription</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['utilisateur_role']) && $_SESSION['utilisateur_role'] === 'admin') : ?>
                    <li><a href="admin/dashboard.php">Admin</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>