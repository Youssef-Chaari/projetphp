<?php
include '../includes/config.php';
include '../includes/auth.php';
verifierConnexion();
verifierAdmin();

include '../includes/header-admin.php';
?>
<link rel="stylesheet" href="../assets/css/style.css">
<h1>Tableau de Bord Administrateur</h1>
<div class="dashboard-grid">
    <div class="dashboard-card">
        <h2>Produits</h2>
        <p>Gérez les produits disponibles.</p>
        <a href="produits.php" class="btn">Voir les produits</a>
    </div>
    <div class="dashboard-card">
        <h2>Commandes</h2>
        <p>Gérez les commandes des clients.</p>
        <a href="commandes.php" class="btn">Voir les commandes</a>
    </div>
    <div class="dashboard-card">
        <h2>Utilisateurs</h2>
        <p>Gérez les utilisateurs inscrits.</p>
        <a href="utilisateurs.php" class="btn">Voir les utilisateurs</a>
    </div>
</div>

<?php
include '../includes/footer.php';
?>