<?php
include '../includes/config.php';
include '../includes/auth.php';
verifierConnexion();
verifierAdmin();

if (!isset($_GET['id'])) {
    header('Location: commandes.php');
    exit;
}

$id = $_GET['id'];

// Récupérer les détails de la commande
$sql = "SELECT commandes.*, utilisateurs.nom AS utilisateur_nom 
        FROM commandes
        INNER JOIN utilisateurs ON commandes.utilisateur_id = utilisateurs.id
        WHERE commandes.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$commande = $stmt->fetch();

if (!$commande) {
    header('Location: commandes.php');
    exit;
}

// Récupérer les produits de la commande
$sql = "SELECT produits.nom, produits.prix, commande_produits.quantite 
        FROM commande_produits
        INNER JOIN produits ON commande_produits.produit_id = produits.id
        WHERE commande_produits.commande_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$produits = $stmt->fetchAll();

include '../includes/header-admin.php';
?>

<main>
<link rel="stylesheet" href="../assets/css/style.css">

    <h1>Détails de la Commande #<?= $commande['id'] ?></h1>
    <div class="commande-details">
        <p><strong>Utilisateur :</strong> <?= $commande['utilisateur_nom'] ?></p>
        <p><strong>Total :</strong> <?= $commande['total'] ?> €</p>
        <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?></p>
        <p><strong>Statut :</strong> <?= $commande['statut'] ?></p>
    </div>

    <h2>Produits commandés</h2>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $produit) : ?>
                <tr>
                    <td><?= $produit['nom'] ?></td>
                    <td><?= $produit['prix'] ?> €</td>
                    <td><?= $produit['quantite'] ?></td>
                    <td><?= $produit['prix'] * $produit['quantite'] ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php
include '../includes/footer.php';
?>