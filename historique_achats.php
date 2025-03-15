<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php'; // Vérifie si l'utilisateur est connecté
verifierConnexion(); // Applique la vérification
include 'includes/header.php';

// Récupérer l'ID de l'utilisateur connecté
$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer l'historique des commandes de l'utilisateur
$sql = "SELECT commandes.id, commandes.date_commande, commandes.statut, commandes.total 
        FROM commandes 
        WHERE commandes.id_utilisateur = :utilisateur_id
        ORDER BY commandes.date_commande DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['utilisateur_id' => $utilisateur_id]);
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des commandes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main>
        <h1>Historique des commandes</h1>
        <?php if (empty($commandes)) : ?>
            <p>Vous n'avez passé aucune commande.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Commande</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande) : ?>
                        <tr>
                            <td><?= $commande['id'] ?></td>
                            <td><?= $commande['date_commande'] ?></td>
                            <td><?= $commande['statut'] ?></td>
                            <td><?= $commande['total'] ?> DT</td>
                            <td>
                                <a href="details_commande.php?id=<?= $commande['id'] ?>" class="btn">Voir les détails</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>