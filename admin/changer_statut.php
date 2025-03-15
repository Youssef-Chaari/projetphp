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

    // Si le statut est "Livrée", mettre à jour le stock des produits
    if ($nouveau_statut === 'Livrée') {
        // Récupérer les produits de la commande
        $sql = "SELECT id_produit, quantite FROM details_commande WHERE id_commande = :id_commande";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_commande' => $commande_id]);
        $details_commande = $stmt->fetchAll();

        // Mettre à jour le stock pour chaque produit
        foreach ($details_commande as $detail) {
            $id_produit = $detail['id_produit'];
            $quantite = $detail['quantite'];

            // Mettre à jour le stock
            $sql = "UPDATE produits SET stock = stock - :quantite WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'quantite' => $quantite,
                'id' => $id_produit
            ]);
        }
    }

    // Rediriger vers la page des commandes avec un message de succès
    header('Location: liste_commandes.php?status=success');
    exit;
} else {
    // Rediriger vers la page des commandes si la méthode n'est pas POST
    header('Location: liste_commandes.php');
    exit;
}
?>