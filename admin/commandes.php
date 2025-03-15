<?php
include '../includes/config.php';
include '../includes/auth.php';
verifierConnexion();
verifierAdmin();

include '../includes/header-admin.php';

// Récupérer la liste des commandes
$sql = "SELECT commandes.id, utilisateurs.nom AS utilisateur_nom, commandes.total, commandes.date_commande, commandes.statut 
        FROM commandes
        INNER JOIN utilisateurs ON commandes.client_id = utilisateurs.id
        ORDER BY commandes.date_commande DESC";
$stmt = $pdo->query($sql);
$commandes = $stmt->fetchAll();
?>
    <link rel="stylesheet" href="../assets/css/style.css">

<h1>Gestion des Commandes</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Utilisateur</th>
            <th>Total</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($commandes as $commande) : ?>
            <tr>
                <td><?= $commande['id'] ?></td>
                <td><?= $commande['utilisateur_nom'] ?></td>
                <td><?= $commande['total'] ?> DT</td>
                <td><?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?></td>
                <td><?= $commande['statut'] ?></td>
                <td>
                    <a href="voir_commande.php?id=<?= $commande['id'] ?>" class="btn">Voir</a>
                    <a href="supprimer_commande.php?id=<?= $commande['id'] ?>" class="btn-supprimer">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include '../includes/footer.php';
?>