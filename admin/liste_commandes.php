<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php'; // Vérifie si l'utilisateur est admin
verifierAdmin();

// Récupérer la liste des commandes
$sql = "SELECT commandes.id, utilisateurs.nom AS utilisateur, commandes.date_commande, commandes.statut, commandes.total 
        FROM commandes 
        JOIN utilisateurs ON commandes.id_utilisateur = utilisateurs.id";
$stmt = $pdo->query($sql);
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des commandes</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header-admin.php'; ?>
    <main>
        <h1>Liste des commandes</h1>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success') : ?>
            <div class="alert alert-success">Le statut de la commande a été mis à jour avec succès.</div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Total</th>
                    <th>Actions</th> <!-- Nouvelle colonne pour les boutons -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $commande) : ?>
                    <tr>
                        <td><?= $commande['id'] ?></td>
                        <td><?= $commande['utilisateur'] ?></td>
                        <td><?= $commande['date_commande'] ?></td>
                        <td><?= $commande['statut'] ?></td>
                        <td><?= $commande['total'] ?> DT</td>
                        <td>
                            <div class="actions-buttons">
                                <!-- Bouton pour marquer comme "Livrée" -->
                                <form action="changer_statut.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
                                    <input type="hidden" name="nouveau_statut" value="Livrée">
                                    <button type="submit" class="btn">Marquer comme Livrée</button>
                                </form>
                                <!-- Bouton pour marquer comme "Annulée" -->
                                <form action="changer_statut.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
                                    <input type="hidden" name="nouveau_statut" value="Annulée">
                                    <button type="submit" class="btn btn-supprimer">Marquer comme Annulée</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>