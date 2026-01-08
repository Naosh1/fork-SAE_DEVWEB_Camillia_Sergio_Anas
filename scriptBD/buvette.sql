-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 30 déc. 2025 à 02:05
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
  `solde` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE `compte` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mdp` varchar(100) NOT NULL,
  `solde` decimal(10,0) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table `dispose`
--

CREATE TABLE `dispose` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `compte_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table `gere`
--

CREATE TABLE `gere` (
  `association_id` bigint(20) UNSIGNED NOT NULL,
  `produit_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventaire`
--

CREATE TABLE `inventaire` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_inventaire` date NOT NULL,
  `association_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Index pour les tables déchargées
--

--
-- Index pour la table `achat`
--
ALTER TABLE `achat`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK achat` (`fournisseur_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `association`
--
ALTER TABLE `association`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `compte`
--
ALTER TABLE `compte`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inventaire`
--
ALTER TABLE `inventaire`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rechargement`
--
ALTER TABLE `rechargement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `vente`
--
ALTER TABLE `vente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achat`
--
ALTER TABLE `achat`
  ADD CONSTRAINT `FK achat` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseur` (`id`);

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
