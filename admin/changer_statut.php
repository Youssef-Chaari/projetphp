<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php'; // Vérifie si l'utilisateur est admin
verifierAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $commande_id = $_POST['commande_id'];
    $nouveau_statut = $_POST['nouveau_statut'];

    // Mettre à jour le statut de la commande dans la base de données
    $sql = "UPDATE commandes SET statut = :statut WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'statut' => $nouveau_statut,
        'id' => $commande_id
    ]);

    // Rediriger vers la page des commandes avec un message de succès
    header('Location: liste_commandes.php?status=success');
    exit;
} else {
    // Rediriger vers la page des commandes si la méthode n'est pas POST
    header('Location: liste_commandes.php');
    exit;
}
?>