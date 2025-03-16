<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php';
verifierConnexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commande_id'])) {
    $commande_id = intval($_POST['commande_id']);
    $utilisateur_id = $_SESSION['utilisateur_id'];

    $sql = "SELECT id FROM commandes WHERE id = :commande_id AND id_utilisateur = :utilisateur_id AND statut = 'en cours de traitement'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['commande_id' => $commande_id, 'utilisateur_id' => $utilisateur_id]);
    $commande = $stmt->fetch();

    if ($commande) {
        $sql = "UPDATE commandes SET statut = 'Annulée' WHERE id = :commande_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['commande_id' => $commande_id]);

        $_SESSION['message'] = "La commande a été annulée avec succès.";
        header('Location: details_commande.php?id=' . $commande_id);
        exit();
    } else {
        $_SESSION['message'] = "La commande ne peut pas être annulée.";
        header('Location: historique_achats.php');
        exit();
    }
} else {
    header('Location: historique_achats.php');
    exit();
}