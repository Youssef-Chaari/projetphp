<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php'; 
verifierAdmin();

$sqlStatuts = "SELECT DISTINCT statut FROM commandes";
$stmtStatuts = $pdo->query($sqlStatuts);
$statuts = $stmtStatuts->fetchAll(PDO::FETCH_COLUMN);

// recuperer tou les statuts pour le filtre
$statutFiltre = $_GET['statut'] ?? null;

$sql = "SELECT commandes.id, utilisateurs.nom AS utilisateur, commandes.date_commande, commandes.statut, commandes.total 
        FROM commandes 
        JOIN utilisateurs ON commandes.id_utilisateur = utilisateurs.id";

if ($statutFiltre && in_array($statutFiltre, $statuts)) {
    $sql .= " WHERE commandes.statut = :statut";
}

$stmt = $pdo->prepare($sql);

if ($statutFiltre && in_array($statutFiltre, $statuts)) {
    $stmt->bindParam(':statut', $statutFiltre);
}

$stmt->execute();
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des commandes</title>
    <link rel="stylesheet"href="C:/xampp/htdocs/projetweb/assets/css/style.css">
</head>
<body>
    <?php include '../includes/header-admin.php'; ?>
    <main>
        <h1>Liste des commandes</h1>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success') : ?>
            <div class="alert alert-success">Le statut de la commande a été mis à jour avec succès.</div>
        <?php endif; ?>

        <form method="GET" action="">
            <label for="statut">Filtrer par statut :</label>
            <select name="statut" id="statut">
                <option value="">Tous les statuts</option>
                <?php foreach ($statuts as $statut) : ?>
                    <option value="<?= $statut ?>" <?= ($statutFiltre === $statut) ? 'selected' : '' ?>>
                        <?= $statut ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Filtrer</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($commandes)) : ?>
                    <tr>
                        <td colspan="6">Aucune commande trouvée.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($commandes as $commande) : ?>
                        <tr>
                            <td><?= $commande['id'] ?></td>
                            <td><?= $commande['utilisateur'] ?></td>
                            <td><?= $commande['date_commande'] ?></td>
                            <td><?= $commande['statut'] ?></td>
                            <td><?= $commande['total'] ?> DT</td>
                            <td>
                                <div class="actions-buttons">
                                    <form action="changer_statut.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
                                        <input type="hidden" name="nouveau_statut" value="Livrée">
                                        <button type="submit" class="btn">Marquer comme Livrée</button>
                                    </form>
                                    <form action="changer_statut.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
                                        <input type="hidden" name="nouveau_statut" value="Annulée">
                                        <button type="submit" class="btn btn-supprimer">Marquer comme Annulée</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>