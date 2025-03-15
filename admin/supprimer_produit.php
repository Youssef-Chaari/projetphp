<?php
include '../includes/config.php';
include '../includes/auth.php';
verifierConnexion();
verifierAdmin();

if (!isset($_GET['id'])) {
    header('Location: produits.php');
    exit;
}

$id = $_GET['id'];

try {
    // Commencer une transaction
    $pdo->beginTransaction();

    // Supprimer les détails de commande liés à ce produit
    $sql = "DELETE FROM details_commande WHERE id_produit = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    // Supprimer les avis liés à ce produit (si nécessaire)
    $sql = "DELETE FROM avis WHERE id_produit = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    // Supprimer le produit
    $sql = "DELETE FROM produits WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    // Valider la transaction
    $pdo->commit();

    header('Location: produits.php');
    exit;
} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    die("Erreur lors de la suppression du produit : " . $e->getMessage());
}
?>