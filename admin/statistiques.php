<?php
include '../includes/config.php';
include '../includes/auth.php';
verifierConnexion();
verifierAdmin();

include '../includes/header-admin.php';

// Exemple de statistiques
$sql = "SELECT COUNT(*) as total_produits FROM produits";
$stmt = $pdo->query($sql);
$total_produits = $stmt->fetch()['total_produits'];

$sql = "SELECT COUNT(*) as total_utilisateurs FROM utilisateurs";
$stmt = $pdo->query($sql);
$total_utilisateurs = $stmt->fetch()['total_utilisateurs'];

$sql = "SELECT COUNT(*) as total_commandes FROM commandes";
$stmt = $pdo->query($sql);
$total_commandes = $stmt->fetch()['total_commandes'];
?>

<main>
<link rel="stylesheet" href="../assets/css/style.css">

    <h1>Statistiques</h1>
    <div class="stats-grid">
        <div class="stats-card">
            <h2>Produits</h2>
            <p><?= $total_produits ?> produits</p>
        </div>
        <div class="stats-card">
            <h2>Utilisateurs</h2>
            <p><?= $total_utilisateurs ?> utilisateurs</p>
        </div>
        <div class="stats-card">
            <h2>Commandes</h2>
            <p><?= $total_commandes ?> commandes</p>
        </div>
    </div>
</main>

<?php
include '../includes/footer.php';
?>