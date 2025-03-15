<?php
include 'includes/config.php';
include 'includes/auth.php'; 
verifierConnexion();
include 'includes/header.php';
?>

<main>
<link rel="stylesheet" href="assets/css/style.css">

    <h1>Bienvenue sur Maillots de Foot</h1>
    <p>Découvrez notre collection de maillots officiels.</p>
    <a href="produits.php" class="btn">Voir les produits</a>
</main>

<?php
include 'includes/footer.php';
?>