<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php';
verifierConnexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commande_id'])) {
    $commande_id = intval($_POST['commande_id']);
    $utilisateur_id = $_SESSION['utilisateur_id'];

    // Vérifier que la commande appartient à l'utilisateur connecté et est en cours de traitement
    $sql = "SELECT id FROM commandes WHERE id = :commande_id AND id_utilisateur = :utilisateur_id AND statut = 'en cours de traitement'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['commande_id' => $commande_id, 'utilisateur_id' => $utilisateur_id]);
    $commande = $stmt->fetch();

    if ($commande) {
        // Mettre à jour le statut de la commande à "Annulée"
        $sql = "UPDATE commandes SET statut = 'Annulée' WHERE id = :commande_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['commande_id' => $commande_id]);

        // Rediriger vers la page des détails de la commande avec un message de succès
        $_SESSION['message'] = "La commande a été annulée avec succès.";
        header('Location: details_commande.php?id=' . $commande_id);
        exit();
    } else {
        // Rediriger si la commande n'appartient pas à l'utilisateur ou n'est pas en cours de traitement
        $_SESSION['message'] = "La commande ne peut pas être annulée.";
        header('Location: historique_achats.php');
        exit();
    }
} else {
    // Rediriger si la méthode n'est pas POST ou si l'ID de la commande n'est pas défini
    header('Location: historique_achats.php');
    exit();
}