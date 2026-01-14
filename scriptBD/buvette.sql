-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 14 jan. 2026 à 21:13
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `buvette`
--

-- --------------------------------------------------------

--
-- Structure de la table `achat`
--

CREATE TABLE `achat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_achat` date NOT NULL,
  `prix_total` decimal(10,0) NOT NULL,
  `fournisseur_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `achat`
--

INSERT INTO `achat` (`id`, `date_achat`, `prix_total`, `fournisseur_id`) VALUES
(1, '2026-01-10', 200, 1),
(2, '2026-01-11', 150, 2);

-- --------------------------------------------------------

--
-- Structure de la table `appartient`
--

CREATE TABLE `appartient` (
  `compte_id` bigint(20) UNSIGNED NOT NULL,
  `association_id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('client','barman') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `appartient`
--

INSERT INTO `appartient` (`compte_id`, `association_id`, `role`) VALUES
(40, 2, 'client'),
(41, 2, 'barman'),
(42, 2, 'client'),
(43, 2, 'barman'),
(43, 3, 'client'),
(46, 2, 'barman'),
(47, 2, 'barman'),
(47, 3, 'barman'),
(50, 2, 'barman'),
(53, 3, 'barman'),
(54, 2, 'barman'),
(56, 3, 'barman');

-- --------------------------------------------------------

--
-- Structure de la table `association`
--

CREATE TABLE `association` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(50) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `solde` decimal(10,0) NOT NULL,
  `status` enum('en_attente','validee','refusee') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `association`
--

INSERT INTO `association` (`id`, `nom`, `adresse`, `email`, `telephone`, `solde`, `status`) VALUES
(2, 'Association Informatique', '123 Rue du Code', 'info@asso.fr', '0123456789', 500, 'validee'),
(3, 'Club BDE', '456 Avenue des Étudiants', 'bde@campus.fr', '0987654321', 750, 'validee'),
(4, 'Médecins Sans Frontières', '34 Av. Jean Jaurès, 75019 Paris', 'medecinsansfrontiere@gmail.com', '0645454545', 500, 'en_attente'),
(5, 'Association Sportive', '12 Rue du Stade', 'sport@asso.fr', '0123456780', 300, 'validee'),
(6, 'Club Musique', '45 Avenue des Arts', 'musique@campus.fr', '0987654322', 400, 'validee'),
(7, 'Club Lecture', '78 Boulevard des Livres', 'lecture@campus.fr', '0112233445', 200, 'en_attente'),
(8, 'Les Amis du Bar', '12 Rue Centrale', 'amis@bar.com', '0102030405', 1000, 'en_attente'),
(9, 'Buvette Solidaire', '45 Avenue du Soleil', 'buvette@solidaire.com', '0607080910', 500, 'en_attente');

-- --------------------------------------------------------

--
-- Structure de la table `comprend`
--

CREATE TABLE `comprend` (
  `produit_id` bigint(20) UNSIGNED NOT NULL,
  `achat_id` bigint(20) UNSIGNED NOT NULL,
  `quantite` int(10) NOT NULL,
  `prix_achat_unitaire` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comprend`
--

INSERT INTO `comprend` (`produit_id`, `achat_id`, `quantite`, `prix_achat_unitaire`) VALUES
(27, 1, 50, 2),
(28, 1, 30, 1),
(29, 2, 100, 1),
(30, 2, 20, 3);

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE `compte` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `mdp` varchar(100) NOT NULL,
  `solde` decimal(10,0) NOT NULL,
  `role` varchar(50) NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`id`, `nom`, `prenom`, `date_naissance`, `email`, `tel`, `mdp`, `solde`, `role`, `photo`) VALUES
(38, 'Emtir', 'Camillia', '2006-02-07', 'camillia@mail.com', '0101010101', '$2y$10$H1IP6/f6wUDUWjYaquG9g.c6wBl3T45C4r4luzkikSUENTWx1xmOG', 0, 'gestionnaire', 'pp_38_1768415786.jpg'),
(40, 'Semghouni', 'Anas', '2005-08-22', 'anas@mail.com', NULL, '$2y$10$mxRDa7Sh7Rxj1.nUMvrh6eJ..WbBdwtfKP/c5QSof605OiZUvCC2K', 0, 'client', NULL),
(41, 'Teixeira', 'Sergio', '2005-12-24', 'sergio@mail.com', NULL, '$2y$10$n9DzKkt4KTNBrk79O.Js0.PjX9DHb7VOOmHl16UhZQ0fjcMVnrN0e', 0, 'barman', NULL),
(42, 'Dupont', 'Alice', '2004-03-15', 'alice@mail.com', NULL, '$2y$10$XWd1vRpbKkV7g1I1fHxFhuVdQHfZbTgG0dVx6uI3t3lP7WEXxQ1yK', 100, 'client', NULL),
(43, 'Martin', 'Bob', '2003-07-21', 'bob@mail.com', NULL, '$2y$10$4zGjkC5Q9v5C7vJxTfH7yeqBnM9HhVRX7Rr0xRjR/EmUfYm0bQ1g2', 50, 'barman', NULL),
(44, 'Durand', 'Clara', '2005-11-09', 'clara@mail.com', NULL, '$2y$10$NQvQGq3PnI0yD0ZxH5fAeOeZ9b0yZgNkuV1Xr5zU9yT1X6sVdE4Hq', 75, 'client', NULL),
(45, 'Leclerc', 'David', '2002-06-12', 'david@mail.com', NULL, '$2y$10$wQ8tNq7VpK2zT6Wx9Yh7bONpJ6r8PjF2cM1LkRzF0yN5tG3vXqH1u', 0, 'gestionnaire', NULL),
(46, 'Durand', 'Alice', '2006-02-10', 'alice.durand1@example.com', NULL, '$2y$10$ExempleHash', 0, 'barman', NULL),
(47, 'Petit', 'Lucas', NULL, 'lucas.petit1@example.com', NULL, '$2y$10$ExempleHash', 0, 'barman', NULL),
(48, 'Moreau', 'Emma', NULL, 'emma.moreau1@example.com', NULL, '$2y$10$ExempleHash', 0, 'client', NULL),
(49, 'Roux', 'Gabriel', NULL, 'gabriel.roux1@example.com', NULL, '$2y$10$ExempleHash', 0, 'client', NULL),
(50, 'Faure', 'Chloe', NULL, 'chloe.faure1@example.com', NULL, '$2y$10$ExempleHash', 0, 'barman', NULL),
(51, 'Blanc', 'Marie', NULL, 'marie.blanc2@example.com', NULL, '$2y$10$ExempleHash', 0, 'client', NULL),
(52, 'Garnier', 'Theo', NULL, 'theo.garnier2@example.com', NULL, '$2y$10$ExempleHash', 0, 'client', NULL),
(53, 'Perrin', 'Léa', NULL, 'lea.perrin2@example.com', NULL, '$2y$10$ExempleHash', 0, 'barman', NULL),
(54, 'Chevalier', 'Nathan', NULL, 'nathan.chevalier2@example.com', NULL, '$2y$10$ExempleHash', 0, 'client', NULL),
(55, 'Fernandez', 'Ines', NULL, 'ines.fernandez2@example.com', NULL, '$2y$10$ExempleHash', 0, 'client', NULL),
(56, 'Martin', 'Paul', NULL, 'paul.martin1@example.com', NULL, '$2y$10$ExempleHash', 0, 'barman', NULL),
(57, 'Bernard', 'Julie', NULL, 'julie.bernard1@example.com', NULL, '$2y$10$ExempleHash', 0, 'barman', NULL),
(58, 'Dubois', 'Antoine', NULL, 'antoine.dubois1@example.com', NULL, '$2y$10$ExempleHash', 0, 'barman', NULL),
(59, 'Morel', 'Sophie', NULL, 'sophie.morel2@example.com', NULL, '$2y$10$ExempleHash', 0, 'barman', NULL),
(60, 'Lemoine', 'Victor', NULL, 'victor.lemoine2@example.com', NULL, '$2y$10$ExempleHash', 0, 'barman', NULL),
(61, 'Girard', 'Emma', NULL, 'emma.girard2@example.com', NULL, '$2y$10$ExempleHash', 0, 'barman', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `concerne`
--

CREATE TABLE `concerne` (
  `produit_id` bigint(20) UNSIGNED NOT NULL,
  `inventaire_id` bigint(20) UNSIGNED NOT NULL,
  `stock_theorique` int(10) NOT NULL,
  `stock_reel` int(10) NOT NULL,
  `perte` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `concerne`
--

INSERT INTO `concerne` (`produit_id`, `inventaire_id`, `stock_theorique`, `stock_reel`, `perte`) VALUES
(24, 1, 200, 200, 0),
(24, 2, 200, 100, 100),
(25, 1, 50, 50, 0),
(25, 2, 50, 50, 0);

-- --------------------------------------------------------

--
-- Structure de la table `contient`
--

CREATE TABLE `contient` (
  `produit_id` bigint(20) UNSIGNED NOT NULL,
  `vente_id` bigint(20) UNSIGNED NOT NULL,
  `quantite` int(10) NOT NULL,
  `prix_unitaire` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contient`
--

INSERT INTO `contient` (`produit_id`, `vente_id`, `quantite`, `prix_unitaire`) VALUES
(22, 13, 3, 2),
(22, 14, 2, 2),
(22, 15, 5, 2),
(22, 19, 2, 2),
(23, 13, 2, 1),
(23, 14, 1, 1),
(24, 16, 4, 3),
(24, 17, 2, 3),
(24, 18, 2, 3),
(25, 16, 3, 4),
(25, 17, 1, 4),
(25, 18, 1, 4),
(25, 20, 1, 4),
(26, 13, 2, 2),
(26, 15, 2, 2),
(27, 10, 2, 2),
(28, 10, 1, 1),
(29, 11, 3, 1),
(30, 12, 1, 3),
(30, 20, 1, 3),
(30, 21, 1, 3),
(31, 21, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `dispose`
--

CREATE TABLE `dispose` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `compte_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dispose`
--

INSERT INTO `dispose` (`role_id`, `compte_id`) VALUES
(1, 57);

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(50) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id`, `nom`, `telephone`, `email`) VALUES
(1, 'Fournisseur A', '0101010101', 'contact@fournisseurA.com'),
(2, 'Fournisseur B', '0202020202', 'contact@fournisseurB.com');

-- --------------------------------------------------------

--
-- Structure de la table `gere`
--

CREATE TABLE `gere` (
  `association_id` bigint(20) UNSIGNED NOT NULL,
  `produit_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gere`
--

INSERT INTO `gere` (`association_id`, `produit_id`) VALUES
(2, 22),
(2, 23),
(2, 26),
(2, 32),
(3, 24),
(3, 25),
(5, 27),
(5, 28),
(6, 29),
(6, 30),
(7, 31);

-- --------------------------------------------------------

--
-- Structure de la table `gestionne`
--

CREATE TABLE `gestionne` (
  `compte_id` bigint(20) UNSIGNED NOT NULL,
  `association_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gestionne`
--

INSERT INTO `gestionne` (`compte_id`, `association_id`) VALUES
(38, 2),
(38, 3),
(45, 5);

-- --------------------------------------------------------

--
-- Structure de la table `inventaire`
--

CREATE TABLE `inventaire` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_inventaire` date NOT NULL,
  `association_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `inventaire`
--

INSERT INTO `inventaire` (`id`, `date_inventaire`, `association_id`) VALUES
(1, '2026-01-14', 3),
(2, '2026-01-14', 3);

-- --------------------------------------------------------

--
-- Structure de la table `lier`
--

CREATE TABLE `lier` (
  `fournisseur_id` bigint(20) UNSIGNED NOT NULL,
  `association_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `quantiteActuelle` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `type`, `prix`, `quantiteActuelle`) VALUES
(22, 'Café', 'boisson', 2, 120),
(23, 'Thé', 'boisson', 1, 80),
(24, 'Bière', 'boisson', 3, 100),
(25, 'Sandwich', 'nourriture', 4, 80),
(26, 'Chips', 'nourriture', 2, 30),
(27, 'Jus d\'orange', 'boisson', 2, 100),
(28, 'Croissant', 'nourriture', 1, 60),
(29, 'Eau minérale', 'boisson', 1, 150),
(30, 'Chocolat', 'nourriture', 3, 40),
(31, 'Soda', 'boisson', 2, 120),
(32, 'Coca-Cola', 'boisson', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `rechargement`
--

CREATE TABLE `rechargement` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `valeur` decimal(10,0) NOT NULL,
  `date_rechargement` date NOT NULL,
  `compte_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `nom`) VALUES
(1, 'barman'),
(2, 'client'),
(3, 'gestionnaire');

-- --------------------------------------------------------

--
-- Structure de la table `vente`
--

CREATE TABLE `vente` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_vente` date NOT NULL,
  `montant_total` decimal(10,0) NOT NULL,
  `compte_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vente`
--

INSERT INTO `vente` (`id`, `date_vente`, `montant_total`, `compte_id`) VALUES
(10, '2026-01-12', 14, 42),
(11, '2026-01-13', 6, 43),
(12, '2026-01-13', 5, 44),
(13, '2026-01-12', 12, 38),
(14, '2026-01-12', 7, 38),
(15, '2026-01-13', 14, 38),
(16, '2026-01-13', 15, 38),
(17, '2026-01-14', 7, 38),
(18, '2026-01-14', 10, 40),
(19, '2026-01-14', 4, 42),
(20, '2026-01-14', 7, 43),
(21, '2026-01-14', 5, 44);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achat`
--
ALTER TABLE `achat`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK achat` (`fournisseur_id`);

--
-- Index pour la table `appartient`
--
ALTER TABLE `appartient`
  ADD PRIMARY KEY (`compte_id`,`association_id`,`role`),
  ADD KEY `fk_appartient_association` (`association_id`);

--
-- Index pour la table `association`
--
ALTER TABLE `association`
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `comprend`
--
ALTER TABLE `comprend`
  ADD PRIMARY KEY (`produit_id`,`achat_id`),
  ADD KEY `FK 1 comprend` (`achat_id`);

--
-- Index pour la table `compte`
--
ALTER TABLE `compte`
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `concerne`
--
ALTER TABLE `concerne`
  ADD PRIMARY KEY (`produit_id`,`inventaire_id`),
  ADD KEY `FK 1 concerne` (`inventaire_id`);

--
-- Index pour la table `contient`
--
ALTER TABLE `contient`
  ADD PRIMARY KEY (`produit_id`,`vente_id`),
  ADD KEY `FK 2 contient` (`vente_id`);

--
-- Index pour la table `dispose`
--
ALTER TABLE `dispose`
  ADD PRIMARY KEY (`role_id`,`compte_id`),
  ADD KEY `FK 1 dispose` (`compte_id`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `gere`
--
ALTER TABLE `gere`
  ADD PRIMARY KEY (`association_id`,`produit_id`),
  ADD KEY `FK 2 gere` (`produit_id`);

--
-- Index pour la table `gestionne`
--
ALTER TABLE `gestionne`
  ADD PRIMARY KEY (`compte_id`,`association_id`),
  ADD KEY `association_id` (`association_id`);

--
-- Index pour la table `inventaire`
--
ALTER TABLE `inventaire`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK inventaire` (`association_id`);

--
-- Index pour la table `lier`
--
ALTER TABLE `lier`
  ADD PRIMARY KEY (`fournisseur_id`,`association_id`),
  ADD KEY `FK 1 lier` (`association_id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `rechargement`
--
ALTER TABLE `rechargement`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK rechargement` (`compte_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `vente`
--
ALTER TABLE `vente`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK vente` (`compte_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `achat`
--
ALTER TABLE `achat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `association`
--
ALTER TABLE `association`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `compte`
--
ALTER TABLE `compte`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `inventaire`
--
ALTER TABLE `inventaire`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `rechargement`
--
ALTER TABLE `rechargement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `vente`
--
ALTER TABLE `vente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achat`
--
ALTER TABLE `achat`
  ADD CONSTRAINT `FK achat` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseur` (`id`);

--
-- Contraintes pour la table `appartient`
--
ALTER TABLE `appartient`
  ADD CONSTRAINT `fk_appartient_association` FOREIGN KEY (`association_id`) REFERENCES `association` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appartient_compte` FOREIGN KEY (`compte_id`) REFERENCES `compte` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comprend`
--
ALTER TABLE `comprend`
  ADD CONSTRAINT `FK 1 comprend` FOREIGN KEY (`achat_id`) REFERENCES `achat` (`id`),
  ADD CONSTRAINT `FK 2 comprend` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `concerne`
--
ALTER TABLE `concerne`
  ADD CONSTRAINT `FK 1 concerne` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaire` (`id`),
  ADD CONSTRAINT `FK 2 concerne` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `contient`
--
ALTER TABLE `contient`
  ADD CONSTRAINT `FK 1 contient` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`),
  ADD CONSTRAINT `FK 2 contient` FOREIGN KEY (`vente_id`) REFERENCES `vente` (`id`);

--
-- Contraintes pour la table `dispose`
--
ALTER TABLE `dispose`
  ADD CONSTRAINT `FK 1 dispose` FOREIGN KEY (`compte_id`) REFERENCES `compte` (`id`),
  ADD CONSTRAINT `FK 2 dispose` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Contraintes pour la table `gere`
--
ALTER TABLE `gere`
  ADD CONSTRAINT `FK 1 gere` FOREIGN KEY (`association_id`) REFERENCES `association` (`id`),
  ADD CONSTRAINT `FK 2 gere` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `gestionne`
--
ALTER TABLE `gestionne`
  ADD CONSTRAINT `gestionne_ibfk_1` FOREIGN KEY (`compte_id`) REFERENCES `compte` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gestionne_ibfk_2` FOREIGN KEY (`association_id`) REFERENCES `association` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `inventaire`
--
ALTER TABLE `inventaire`
  ADD CONSTRAINT `FK inventaire` FOREIGN KEY (`association_id`) REFERENCES `association` (`id`);

--
-- Contraintes pour la table `lier`
--
ALTER TABLE `lier`
  ADD CONSTRAINT `FK 1 lier` FOREIGN KEY (`association_id`) REFERENCES `association` (`id`),
  ADD CONSTRAINT `FK 2 lier` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseur` (`id`);

--
-- Contraintes pour la table `rechargement`
--
ALTER TABLE `rechargement`
  ADD CONSTRAINT `FK rechargement` FOREIGN KEY (`compte_id`) REFERENCES `compte` (`id`);

--
-- Contraintes pour la table `vente`
--
ALTER TABLE `vente`
  ADD CONSTRAINT `FK vente` FOREIGN KEY (`compte_id`) REFERENCES `compte` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
