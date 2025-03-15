<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function verifierConnexion() {
    if (!isset($_SESSION['utilisateur_id'])) {
        header('Location: connexion.php');
        exit();
    }
}

function verifierAdmin() {
    if (!isset($_SESSION['utilisateur_role']) || $_SESSION['utilisateur_role'] !== 'admin') {
        header('Location: index_admin.php');
        exit();
    }
}
?>