-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+focal2
-- https://www.phpmyadmin.net/
--
-- Généré le : ven. 23 jan. 2026 à 12:24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dutinfopw201633`
--

-- --------------------------------------------------------

--
-- Structure de la table `achat`
--

CREATE TABLE `achat` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date_achat` date NOT NULL,
  `prix_total` decimal(10,0) NOT NULL,
  `fournisseur_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `compte_id` bigint UNSIGNED NOT NULL,
  `niveau_acces` int DEFAULT '1',
  PRIMARY KEY (`compte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `appartient`
--

CREATE TABLE `appartient` (
  `compte_id` bigint UNSIGNED NOT NULL,
  `association_id` bigint UNSIGNED NOT NULL,
  `role` enum('client','barman') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`compte_id`,`association_id`,`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `association`
--

CREATE TABLE `association` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `adresse` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `solde` decimal(10,2) NOT NULL,
  `status` enum('en_attente','validee','refusee') COLLATE utf8mb4_general_ci DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

INSERT INTO association (nom, adresse, email, telephone, solde, status) VALUES
('BDE Informatique', 'Campus Nord', 'bdeinfo@mail.fr', '0101010101', 500.00, 'validee'),
('BDE MMI', 'Campus Sud', 'bdemmi@mail.fr', '0202020202', 600.00, 'validee'),
('BDE TC', 'Campus Ouest', 'bdetc@mail.fr', '0303030303', 550.00, 'validee');

-- --------------------------------------------------------

--
-- Structure de la table `commande_fournisseur`
--

CREATE TABLE `commande_fournisseur` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_fournisseur` int NOT NULL,
  `id_association` int NOT NULL,
  `date_commande` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `montant_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `statut` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comprend`
--

CREATE TABLE `comprend` (
  `produit_id` bigint UNSIGNED NOT NULL,
  `achat_id` bigint UNSIGNED NOT NULL,
  `quantite` int NOT NULL,
  `prix_achat_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`produit_id`,`achat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE `compte` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mdp` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `solde` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `actif` tinyint(1) DEFAULT '1',
  `code_validation` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `code_expiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

INSERT INTO compte (nom, prenom, email, mdp, solde) VALUES
-- Association 1
('Client', 'Alice', 'alice1@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 20),
('Client', 'Bob', 'bob1@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 15),
('Client', 'Chloe', 'chloe1@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 30),
('Barman', 'Lucas', 'barman1@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 0),
('Gestionnaire', 'Emma', 'gest1@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 0),

-- Association 2
('Client', 'David', 'alice2@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 25),
('Client', 'Eva', 'bob2@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 10),
('Client', 'Fanny', 'chloe2@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 18),
('Barman', 'Leo', 'barman2@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 0),
('Gestionnaire', 'Nina', 'gest2@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 0),

-- Association 3
('Client', 'Hugo', 'alice3@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 22),
('Client', 'Iris', 'bob3@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 14),
('Client', 'Jade', 'chloe3@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 19),
('Barman', 'Noah', 'barman3@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 0),
('Gestionnaire', 'Sarah', 'gest3@mail.fr', '$2y$10$8o7uP5v0Y0bQq4n1pD2gQe3C5l9QJk6R2xMZJwJ0jvZxE7JcM2M3S', 0);

-- --------------------------------------------------------

--
-- Structure de la table `concerne`
--

CREATE TABLE `concerne` (
  `produit_id` bigint UNSIGNED NOT NULL,
  `inventaire_id` bigint UNSIGNED NOT NULL,
  `stock_theorique` int NOT NULL,
  `stock_reel` int NOT NULL,
  `perte` int NOT NULL,
  PRIMARY KEY (`produit_id`,`inventaire_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contient`
--

CREATE TABLE `contient` (
  `produit_id` bigint UNSIGNED NOT NULL,
  `vente_id` bigint UNSIGNED NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`produit_id`,`vente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demandes_association`
--

CREATE TABLE `demandes_association` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_gestionnaire` bigint UNSIGNED NOT NULL,
  `nom_association` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_contact` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pdf_identite` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pdf_pv_creation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pdf_statut` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `statut` enum('en_attente','validee','refusee') COLLATE utf8mb4_general_ci DEFAULT 'en_attente',
  `raison_refus` text COLLATE utf8mb4_general_ci,
  `date_soumission` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demandes_creation_assos`
--

CREATE TABLE `demandes_creation_assos` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_demandeur` bigint UNSIGNED NOT NULL,
  `nom_association` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `pdf_identite` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `pdf_pv` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `pdf_ago` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_soumission` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en_attente','validee','refusee') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `detail_commande_fournisseur`
--

CREATE TABLE `detail_commande_fournisseur` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_commande` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dispose`
--

CREATE TABLE `dispose` (
  `role_id` bigint UNSIGNED NOT NULL,
  `compte_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`compte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

INSERT INTO fournisseur (nom, telephone, email) VALUES
('Metro', '0100000001', 'metro@mail.fr'),
('Promocash', '0100000002', 'promo@mail.fr'),
('Transgourmet', '0100000003', 'trans@mail.fr'),
('Sysco', '0100000004', 'sysco@mail.fr'),
('CashAlim', '0100000005', 'cash@mail.fr'),
('FoodPro', '0100000006', 'food@mail.fr'),
('AlimPlus', '0100000007', 'alim@mail.fr'),
('Distrifood', '0100000008', 'distri@mail.fr'),
('SnackSupply', '0100000009', 'snack@mail.fr'),
('DessertCo', '0100000010', 'dessert@mail.fr');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur_produit`
--

CREATE TABLE `fournisseur_produit` (
  `id_fournisseur` bigint UNSIGNED NOT NULL,
  `id_produit` bigint UNSIGNED NOT NULL,
  `prix_achat` decimal(10,2) NOT NULL COMMENT 'Prix proposé par ce fournisseur',
  `delai_livraison` int DEFAULT '7' COMMENT 'Délai en jours',
  PRIMARY KEY (`id_fournisseur`,`id_produit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gere`
--

CREATE TABLE `gere` (
  `association_id` bigint UNSIGNED NOT NULL,
  `produit_id` bigint UNSIGNED NOT NULL,
  `stock_asso` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`association_id`,`produit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gestionne`
--

CREATE TABLE `gestionne` (
  `compte_id` bigint UNSIGNED NOT NULL,
  `association_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`compte_id`,`association_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventaire`
--

CREATE TABLE `inventaire` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date_inventaire` date NOT NULL,
  `association_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lier`
--

CREATE TABLE `lier` (
  `fournisseur_id` bigint UNSIGNED NOT NULL,
  `association_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`fournisseur_id`,`association_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ligne_achat`
--

CREATE TABLE `ligne_achat` (
  `produit_id` bigint UNSIGNED NOT NULL,
  `achat_id` bigint UNSIGNED NOT NULL,
  `quantite` int NOT NULL,
  `prix_achat_unitaire` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ligne_inventaire`
--

CREATE TABLE `ligne_inventaire` (
  `produit_id` bigint UNSIGNED NOT NULL,
  `inventaire_id` bigint UNSIGNED NOT NULL,
  `stock_theorique` int NOT NULL,
  `stock_reel` int NOT NULL,
  `perte` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ligne_vente`
--

CREATE TABLE `ligne_vente` (
  `produit_id` bigint UNSIGNED NOT NULL,
  `vente_id` bigint UNSIGNED NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,0) NOT NULL,
  `statut` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_expediteur` bigint UNSIGNED NOT NULL,
  `id_destinataire` bigint UNSIGNED NOT NULL,
  `objet` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `contenu` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_envoi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `prix` decimal(10,2) NOT NULL,
  `quantiteActuelle` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

INSERT INTO produit (nom, type, prix, quantiteActuelle) VALUES
-- Asso 1
('Burger', 'plat', 6.50, 50),
('Pizza', 'plat', 7.00, 40),
('Chips', 'snack', 1.50, 100),
('Cookie', 'dessert', 2.00, 60),
('Brownie', 'dessert', 2.50, 50),

-- Asso 2
('Pasta', 'plat', 6.00, 45),
('Sandwich', 'plat', 5.50, 55),
('Barre', 'snack', 1.20, 120),
('Donut', 'dessert', 2.20, 70),
('Muffin', 'dessert', 2.40, 65),

-- Asso 3
('Wrap', 'plat', 6.20, 48),
('Tacos', 'plat', 6.80, 42),
('Popcorn', 'snack', 1.00, 130),
('Tarte', 'dessert', 2.80, 40),
('Gâteau', 'dessert', 3.00, 35);

-- --------------------------------------------------------

--
-- Structure de la table `rechargement`
--

CREATE TABLE `rechargement` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `valeur` decimal(10,2) NOT NULL,
  `date_rechargement` date NOT NULL,
  `compte_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vente`
--

CREATE TABLE `vente` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date_vente` datetime NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `statut` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'payee',
  `compte_id` bigint UNSIGNED NOT NULL,
  `association_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- AJOUT DES INDEX SECONDAIRES ET CONTRAINTES
-- --------------------------------------------------------

--
-- Index pour la table `achat`
--
ALTER TABLE `achat`
  ADD KEY `FK achat` (`fournisseur_id`);

--
-- Index pour la table `appartient`
--
ALTER TABLE `appartient`
  ADD KEY `fk_appartient_association` (`association_id`);

--
-- Index pour la table `comprend`
--
ALTER TABLE `comprend`
  ADD KEY `FK 1 comprend` (`achat_id`);

--
-- Index pour la table `concerne`
--
ALTER TABLE `concerne`
  ADD KEY `FK 1 concerne` (`inventaire_id`);

--
-- Index pour la table `contient`
--
ALTER TABLE `contient`
  ADD KEY `FK 2 contient` (`vente_id`);

--
-- Index pour la table `demandes_association`
--
ALTER TABLE `demandes_association`
  ADD KEY `id_gestionnaire` (`id_gestionnaire`);

--
-- Index pour la table `demandes_creation_assos`
--
ALTER TABLE `demandes_creation_assos`
  ADD KEY `id_demandeur` (`id_demandeur`);

--
-- Index pour la table `detail_commande_fournisseur`
--
ALTER TABLE `detail_commande_fournisseur`
  ADD KEY `fk_commande` (`id_commande`);

--
-- Index pour la table `dispose`
--
ALTER TABLE `dispose`
  ADD KEY `FK 1 dispose` (`compte_id`);

--
-- Index pour la table `fournisseur_produit`
--
ALTER TABLE `fournisseur_produit`
  ADD KEY `fk_fp_produit` (`id_produit`);

--
-- Index pour la table `gere`
--
ALTER TABLE `gere`
  ADD KEY `FK 2 gere` (`produit_id`);

--
-- Index pour la table `gestionne`
--
ALTER TABLE `gestionne`
  ADD KEY `association_id` (`association_id`);

--
-- Index pour la table `inventaire`
--
ALTER TABLE `inventaire`
  ADD KEY `FK inventaire` (`association_id`);

--
-- Index pour la table `lier`
--
ALTER TABLE `lier`
  ADD KEY `FK 1 lier` (`association_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD KEY `FK_expediteur` (`id_expediteur`),
  ADD KEY `FK_destinataire` (`id_destinataire`);

--
-- Index pour la table `rechargement`
--
ALTER TABLE `rechargement`
  ADD KEY `FK rechargement` (`compte_id`);

--
-- Index pour la table `vente`
--
ALTER TABLE `vente`
  ADD KEY `FK vente` (`compte_id`),
  ADD KEY `fk_vente_association` (`association_id`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achat`
--
ALTER TABLE `achat`
  ADD CONSTRAINT `FK achat` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseur` (`id`);

--
-- Contraintes pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD CONSTRAINT `fk_admin_compte` FOREIGN KEY (`compte_id`) REFERENCES `compte` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `FK 2 concerne` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `contient`
--
ALTER TABLE `contient`
  ADD CONSTRAINT `FK 1 contient` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`),
  ADD CONSTRAINT `FK 2 contient` FOREIGN KEY (`vente_id`) REFERENCES `vente` (`id`);

--
-- Contraintes pour la table `demandes_association`
--
ALTER TABLE `demandes_association`
  ADD CONSTRAINT `demandes_association_ibfk_1` FOREIGN KEY (`id_gestionnaire`) REFERENCES `compte` (`id`);

--
-- Contraintes pour la table `demandes_creation_assos`
--
ALTER TABLE `demandes_creation_assos`
  ADD CONSTRAINT `fk_demandeur_compte` FOREIGN KEY (`id_demandeur`) REFERENCES `compte` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `detail_commande_fournisseur`
--
ALTER TABLE `detail_commande_fournisseur`
  ADD CONSTRAINT `fk_commande` FOREIGN KEY (`id_commande`) REFERENCES `commande_fournisseur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dispose`
--
ALTER TABLE `dispose`
  ADD CONSTRAINT `FK 1 dispose` FOREIGN KEY (`compte_id`) REFERENCES `compte` (`id`),
  ADD CONSTRAINT `FK 2 dispose` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Contraintes pour la table `fournisseur_produit`
--
ALTER TABLE `fournisseur_produit`
  ADD CONSTRAINT `fk_fp_fournisseur` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fp_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `FK_msg_dest` FOREIGN KEY (`id_destinataire`) REFERENCES `compte` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_msg_exp` FOREIGN KEY (`id_expediteur`) REFERENCES `compte` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rechargement`
--
ALTER TABLE `rechargement`
  ADD CONSTRAINT `FK rechargement` FOREIGN KEY (`compte_id`) REFERENCES `compte` (`id`);

--
-- Contraintes pour la table `vente`
--
ALTER TABLE `vente`
  ADD CONSTRAINT `FK vente` FOREIGN KEY (`compte_id`) REFERENCES `compte` (`id`),
  ADD CONSTRAINT `fk_vente_association` FOREIGN KEY (`association_id`) REFERENCES `association` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;