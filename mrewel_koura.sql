-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : dim. 16 mars 2025 à 00:39
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mrewel_koura`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `statut` varchar(50) DEFAULT 'en cours de traitement',
  `total` decimal(10,2) NOT NULL,
  `adresse_livraison` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `id_utilisateur`, `date_commande`, `statut`, `total`, `adresse_livraison`) VALUES
(20, 2, '2025-03-16 00:23:25', 'Livrée', '120.00', 'test'),
(21, 2, '2025-03-16 00:34:34', 'en cours de traitement', '120.00', 'test');

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE `details_commande` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `details_commande`
--

INSERT INTO `details_commande` (`id`, `id_commande`, `id_produit`, `quantite`, `prix_unitaire`) VALUES
(8, 20, 12, 1, '0.00'),
(9, 21, 12, 1, '0.00');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `nom`, `email`, `sujet`, `message`, `date_creation`) VALUES
(1, 'Test', 'mohamedazizzouari77@gmail.com', 'test', 'test', '2025-03-15 17:06:52'),
(2, 'test', 'test@gmail.com', 'test', 'c 12 12 test test\r\n', '2025-03-15 19:27:20');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `equipe` varchar(50) NOT NULL,
  `taille` varchar(10) NOT NULL,
  `saison` varchar(20) NOT NULL,
  `ligue` varchar(50) NOT NULL,
  `couleur` varchar(50) DEFAULT NULL,
  `type_maillot` varchar(50) DEFAULT NULL,
  `date_ajout` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `prix`, `image`, `stock`, `equipe`, `taille`, `saison`, `ligue`, `couleur`, `type_maillot`, `date_ajout`) VALUES
(12, 'Maillot PSG 2024-2025', 'Maillot Officiel', '120.00', '../uploads/04e4390a-54ca-46ea-9c50-bde5e53c7aa7.png', 46, 'Paris Saint Germain', 'S', '2024-2025', 'Ligue 1', 'Bleu', 'Joueur', '2025-03-15 19:58:14'),
(13, 'Maillot Manchester United', 'Manchester United', '120.00', '../uploads/861270cb-c574-4d63-8d4a-105e031ba144.jpeg', 47, 'Manchester United', 'M', '2024-2025', 'Premier League', 'Bleu', 'Joueur', '2025-03-15 20:04:45');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `adresse` text DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `code_postal` varchar(10) DEFAULT NULL,
  `pays` varchar(50) DEFAULT NULL,
  `date_inscription` datetime DEFAULT current_timestamp(),
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `adresse`, `ville`, `code_postal`, `pays`, `date_inscription`, `role`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$Vr2J5z8Q1Z7K9X8Y0fZ3eO1Z2X3Y4A5B6C7D8E9F0G1H2I3J4K5L', NULL, NULL, NULL, NULL, '2025-03-13 23:20:43', 'admin'),
(2, 'mohamed aziz zouari', 'mohamedazizzouari77@gmail.com', '$2y$10$FdF7O9hG3rdhU1pJ1/GoJumJKlZw0EALsC1LjmtW.iK.OSTItHOw6', NULL, NULL, NULL, NULL, '2025-03-13 23:21:12', 'user'),
(4, 'Admin', 'azizadmin@gmail.com', '$2y$10$FdF7O9hG3rdhU1pJ1/GoJumJKlZw0EALsC1LjmtW.iK.OSTItHOw6', NULL, NULL, NULL, NULL, '2025-03-13 23:38:27', 'admin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `details_commande`
--
ALTER TABLE `details_commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD CONSTRAINT `details_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id`),
  ADD CONSTRAINT `details_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
