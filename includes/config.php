<?php
$host = '127.0.0.1'; // Serveur MySQL
$port = '3306';      // Port MySQL
$dbname = 'mrewel_koura'; // Nom de la base de données
$user = 'root';      // Nom d'utilisateur MySQL
$pass = '';          // Mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>