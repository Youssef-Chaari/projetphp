<?php
include '../includes/config.php';
include '../includes/auth.php';
verifierConnexion();
verifierAdmin(); // Vérifier que l'utilisateur est un administrateur

include '../includes/header-admin.php';

// Récupérer tous les messages de la base de données
$sql = "SELECT * FROM messages ORDER BY date_creation DESC";
$stmt = $pdo->query($sql);
$messages = $stmt->fetchAll();
?>

<h1>Messages des utilisateurs</h1>

<?php if (empty($messages)) : ?>
    <p>Aucun message pour le moment.</p>
<?php else : ?>
    <link rel="stylesheet" href="../assets/css/style.css">

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Sujet</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $message) : ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($message['date_creation'])) ?></td>
                    <td><?= htmlspecialchars($message['nom']) ?></td>
                    <td><?= htmlspecialchars($message['email']) ?></td>
                    <td><?= htmlspecialchars($message['sujet']) ?></td>
                    <td><?= nl2br(htmlspecialchars($message['message'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>