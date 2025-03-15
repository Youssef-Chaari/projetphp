<?php
include 'includes/config.php';
include 'includes/auth.php';
verifierConnexion();

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['name'];
    $email = $_POST['email'];
    $sujet = $_POST['subject'];
    $message = $_POST['message'];

    // Insérer le message dans la base de données
    $sql = "INSERT INTO messages (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'email' => $email,
        'sujet' => $sujet,
        'message' => $message
    ]);

    // Rediriger vers la page de contact avec un message de succès
    header('Location: contact.php?status=success');
    exit;
} else {
    // Rediriger vers la page de contact si le formulaire n'a pas été soumis
    header('Location: contact.php');
    exit;
}
?>