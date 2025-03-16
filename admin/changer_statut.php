<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php'; 
verifierAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commande_id = $_POST['commande_id'];
    $nouveau_statut = $_POST['nouveau_statut'];

    $sql = "UPDATE commandes SET statut = :statut WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'statut' => $nouveau_statut,
        'id' => $commande_id
    ]);

    header('Location: liste_commandes.php?status=success');
    exit;
} else {
    header('Location: liste_commandes.php');
    exit;
}
?>