<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php';
verifierConnexion();

if (!isset($_GET['id'])) {
    header('Location: historique_achats.php');
    exit();
}

$commande_id = intval($_GET['id']);
$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer les informations de la commande
$sql = "SELECT id, statut FROM commandes WHERE id = :commande_id AND id_utilisateur = :utilisateur_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['commande_id' => $commande_id, 'utilisateur_id' => $utilisateur_id]);
$commande = $stmt->fetch();

if (!$commande) {
    header('Location: historique_achats.php');
    exit();
}

// Récupérer les détails de la commande
$sql = "SELECT produits.nom, produits.prix, details_commande.quantite 
        FROM details_commande 
        INNER JOIN produits ON details_commande.id_produit = produits.id 
        WHERE details_commande.id_commande = :commande_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['commande_id' => $commande_id]);
$details_commande = $stmt->fetchAll();

// Normaliser le statut de la commande
$statut = strtolower(trim($commande['statut']));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la commande</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main>
        <h1>Détails de la commande #<?= $commande_id ?></h1>

        <!-- Afficher un message de succès ou d'erreur -->
        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (empty($details_commande)) : ?>
            <p>Aucun détail trouvé pour cette commande.</p>
        <?php else : ?>
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
                    <?php foreach ($details_commande as $detail) : ?>
                        <tr>
                            <td><?= $detail['nom'] ?></td>
                            <td><?= $detail['prix'] ?> DT</td>
                            <td><?= $detail['quantite'] ?></td>
                            <td><?= $detail['prix'] * $detail['quantite'] ?> DT</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Bouton pour annuler la commande -->
        <?php if ($statut === 'en cours de traitement') : ?>
            <form action="annuler_commande.php" method="POST" style="margin-top: 20px;">
                <input type="hidden" name="commande_id" value="<?= $commande_id ?>">
                <button type="submit" class="btn btn-danger">Annuler la commande</button>
            </form>
        <?php else : ?>
            <p>Statut de la commande : <?= ucfirst($statut) ?></p>
        <?php endif; ?>

        <a href="historique_achats.php" class="btn">Retour à l'historique</a>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>