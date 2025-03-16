<?php
include 'includes/config.php';
include 'includes/auth.php';
verifierConnexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['name'];
    $email = $_POST['email'];
    $sujet = $_POST['subject'];
    $message = $_POST['message'];

    $sql = "INSERT INTO messages (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'email' => $email,
        'sujet' => $sujet,
        'message' => $message
    ]);

    header('Location: contact.php?status=success');
    exit;
} else {
    header('Location: contact.php');
    exit;
}
?>