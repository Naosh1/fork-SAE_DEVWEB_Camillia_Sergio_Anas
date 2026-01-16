-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 16 jan. 2026 à 03:04
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
    (1, '2026-01-14', 80, 1);

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
                                                                     (2, 1, 'barman'),
                                                                     (3, 1, 'barman'),
                                                                     (4, 1, 'barman'),
                                                                     (6, 1, 'barman'),
                                                                     (8, 1, 'barman'),
                                                                     (9, 1, 'barman');

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
                               `solde` decimal(10,2) NOT NULL,
                               `status` enum('en_attente','validee','refusee') DEFAULT 'en_attente'
                               `status` enum('en_attente','validee','refusee') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `association`
--

INSERT INTO `association` (`id`, `nom`, `adresse`, `email`, `telephone`, `solde`, `status`) VALUES
                                                                                                (1, 'Asso Sportive', '12 rue des Sports', 'contact@assosport.fr', '0140506070', 499.70, 'validee'),
                                                                                                (2, 'Asso Musique', '5 Avenue des Arts', 'musique@univ.fr', '0144332211', 150.00, 'en_attente'),
                                                                                                (3, 'Asso Sportive', '12 rue des Sports', 'contact@assosport.fr', '0140506070', 500.00, 'validee'),
                                                                                                (4, 'Asso Musique', '5 Avenue des Arts', 'musique@univ.fr', '0144332211', 150.00, 'en_attente');

-- --------------------------------------------------------

--
-- Structure de la table `commande_fournisseur`
--

CREATE TABLE `commande_fournisseur` (
                                        `id` int(11) NOT NULL,
                                        `id_fournisseur` int(11) NOT NULL,
                                        `id_association` int(11) NOT NULL,
                                        `date_commande` datetime NOT NULL DEFAULT current_timestamp(),
                                        `montant_total` decimal(10,2) NOT NULL DEFAULT 0.00,
                                        `statut` varchar(50) DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande_fournisseur`
--

INSERT INTO `commande_fournisseur` (`id`, `id_fournisseur`, `id_association`, `date_commande`, `montant_total`, `statut`) VALUES
    (1, 1, 1, '2026-01-16 02:05:07', 0.30, 'en_attente');

-- --------------------------------------------------------

--
-- Structure de la table `comprend`
--

CREATE TABLE `comprend` (
                            `produit_id` bigint(20) UNSIGNED NOT NULL,
                            `achat_id` bigint(20) UNSIGNED NOT NULL,
                            `quantite` int(10) NOT NULL,
                            `prix_achat_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comprend`
--

INSERT INTO `comprend` (`produit_id`, `achat_id`, `quantite`, `prix_achat_unitaire`) VALUES
    (1, 1, 100, 1.00);

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
                          `solde` decimal(10,2) NOT NULL,
                          `role` varchar(50) NOT NULL,
                          `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`id`, `nom`, `prenom`, `date_naissance`, `email`, `tel`, `mdp`, `solde`, `role`, `photo`) VALUES
                                                                                                                    (1, 'Emtir', 'Camillia', '2006-02-07', 'cam@mail.com', '0101010101', '$2y$10$XUSnXRZui2V8jT30vHhV5Oj9DEhtcy5QgLLkolCX5qzqyDOFQ9ty6', 0.00, 'gestionnaire', NULL),
                                                                                                                    (2, 'Dupont', 'Jean', '1995-05-15', 'jean.dupont@mail.com', '0601020304', '$2y$10$XUSnXRZui2V8jT30vHhV5Oj9DEhtcy5QgLLkolCX5qzqyDOFQ9ty6', 60.00, 'barman', NULL),
                                                                                                                    (3, 'Martin', 'Alice', '1998-11-20', 'alice.barman@mail.com', '0611223344', '$2y$10$XUSnXRZui2V8jT30vHhV5Oj9DEhtcy5QgLLkolCX5qzqyDOFQ9ty6', 10.00, 'barman', NULL),
                                                                                                                    (4, 'Dupont', 'Jean', '1995-05-15', 'jean.dupont@mail.com', '0601020304', '$2y$10$XUSnXRZui2V8jT30vHhV5Oj9DEhtcy5QgLLkolCX5qzqyDOFQ9ty6', 50.00, 'barman', NULL),
                                                                                                                    (5, 'Martin', 'Alice', '1998-11-20', 'alice.barman@mail.com', '0611223344', '$2y$10$XUSnXRZui2V8jT30vHhV5Oj9DEhtcy5QgLLkolCX5qzqyDOFQ9ty6', 10.00, 'barman', NULL),
                                                                                                                    (6, 'Dupuis', 'Laura', '2004-05-10', 'laura.dupuis@mail.com', '0102030401', '$2y$10$ExempleHash1', 0.00, 'barman', NULL),
                                                                                                                    (7, 'Morel', 'Lucas', '2005-07-21', 'lucas.morel@mail.com', '0102030402', '$2y$10$ExempleHash2', 0.00, 'client', NULL),
                                                                                                                    (8, 'Petit', 'Emma', '2005-09-15', 'emma.petit@mail.com', '0102030403', '$2y$10$ExempleHash3', 0.00, 'barman', NULL),
                                                                                                                    (9, 'Blanc', 'Mathieu', '2004-12-03', 'mathieu.blanc@mail.com', '0102030404', '$2y$10$ExempleHash4', 0.00, 'barman', NULL);

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
                                                                                                     (1, 1, 60, 58, 2),
                                                                                                     (2, 1, 10, 10, 0),
                                                                                                     (3, 1, 50, 49, 1);

-- --------------------------------------------------------

--
-- Structure de la table `contient`
--

CREATE TABLE `contient` (
                            `produit_id` bigint(20) UNSIGNED NOT NULL,
                            `vente_id` bigint(20) UNSIGNED NOT NULL,
                            `quantite` int(10) NOT NULL,
                            `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contient`
--

INSERT INTO `contient` (`produit_id`, `vente_id`, `quantite`, `prix_unitaire`) VALUES
                                                                                   (1, 1, 40, 3.00),
                                                                                   (1, 2, 1, 2.50),
                                                                                   (2, 1, 1, 5.00);

-- --------------------------------------------------------

--
-- Structure de la table `detail_commande_fournisseur`
--

CREATE TABLE `detail_commande_fournisseur` (
                                               `id` int(11) NOT NULL,
                                               `id_commande` int(11) NOT NULL,
                                               `id_produit` int(11) NOT NULL,
                                               `quantite` int(11) NOT NULL,
                                               `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `detail_commande_fournisseur`
--

INSERT INTO `detail_commande_fournisseur` (`id`, `id_commande`, `id_produit`, `quantite`, `prix_unitaire`) VALUES
    (1, 1, 3, 1, 0.30);

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
                                                   (1, 7),
                                                   (2, 2),
                                                   (2, 3),
                                                   (2, 4),
                                                   (2, 6),
                                                   (3, 1);

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
                                                                  (1, 'Grossiste Boissons', '0102030405', 'ventes@boissons.com'),
                                                                  (2, 'Snack & Co', '0506070809', 'commandes@snack.fr');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur_produit`
--

CREATE TABLE `fournisseur_produit` (
                                       `id_fournisseur` bigint(20) UNSIGNED NOT NULL,
                                       `id_produit` bigint(20) UNSIGNED NOT NULL,
                                       `prix_achat` decimal(10,2) NOT NULL COMMENT 'Prix proposé par ce fournisseur',
                                       `delai_livraison` int(11) DEFAULT 7 COMMENT 'Délai en jours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur_produit`
--

INSERT INTO `fournisseur_produit` (`id_fournisseur`, `id_produit`, `prix_achat`, `delai_livraison`) VALUES
                                                                                                        (1, 1, 0.80, 3),
                                                                                                        (1, 3, 0.30, 2),
                                                                                                        (2, 2, 2.00, 1);

-- --------------------------------------------------------

--
-- Structure de la table `gere`
--

CREATE TABLE `gere` (
                        `association_id` bigint(20) UNSIGNED NOT NULL,
                        `produit_id` bigint(20) UNSIGNED NOT NULL,
                        `stock_asso` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gere`
--

INSERT INTO `gere` (`association_id`, `produit_id`, `stock_asso`) VALUES
                                                                      (1, 1, 40),
                                                                      (1, 2, 10);

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
    (1, 1);

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
    (1, '2026-01-15', 1);

-- --------------------------------------------------------

--
-- Structure de la table `lier`
--

CREATE TABLE `lier` (
                        `fournisseur_id` bigint(20) UNSIGNED NOT NULL,
                        `association_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lier`
--

INSERT INTO `lier` (`fournisseur_id`, `association_id`) VALUES
                                                            (1, 1),
                                                            (2, 1),
                                                            (2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
                            `id` bigint(20) UNSIGNED NOT NULL,
                            `id_expediteur` bigint(20) UNSIGNED NOT NULL,
                            `id_destinataire` bigint(20) UNSIGNED NOT NULL,
                            `objet` varchar(255) NOT NULL,
                            `contenu` text NOT NULL,
                            `date_envoi` datetime NOT NULL DEFAULT current_timestamp(),
                            `lu` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `id_expediteur`, `id_destinataire`, `objet`, `contenu`, `date_envoi`, `lu`) VALUES
                                                                                                              (1, 1, 2, 'Bienvenue', 'Bienvenue sur la plateforme de la buvette !', '2026-01-16 01:45:11', 0),
                                                                                                              (2, 1, 5, 'test', 'test', '2026-01-16 02:20:10', 0);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
                           `id` bigint(20) UNSIGNED NOT NULL,
                           `nom` varchar(50) NOT NULL,
                           `type` varchar(50) NOT NULL,
                           `prix` decimal(10,2) NOT NULL,
                           `quantiteActuelle` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `type`, `prix`, `quantiteActuelle`) VALUES
                                                                            (1, 'Coca-Cola 33cl', 'boisson', 2.50, 100),
                                                                            (2, 'Sandwich Jambon', 'nourriture', 3.50, 20),
                                                                            (3, 'Eau Minérale 50cl', 'Boisson', 1.00, 50);

-- --------------------------------------------------------

--
-- Structure de la table `rechargement`
--

CREATE TABLE `rechargement` (
                                `id` bigint(20) UNSIGNED NOT NULL,
                                `valeur` decimal(10,2) NOT NULL,
                                `date_rechargement` date NOT NULL,
                                `compte_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rechargement`
--

INSERT INTO `rechargement` (`id`, `valeur`, `date_rechargement`, `compte_id`) VALUES
    (1, 20.00, '2026-01-15', 2);

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
                                     (1, 'client'),
                                     (2, 'barman'),
                                     (3, 'gestionnaire'),
                                     (4, 'client'),
                                     (5, 'barman'),
                                     (6, 'gestionnaire');

-- --------------------------------------------------------

--
-- Structure de la table `vente`
--

CREATE TABLE `vente` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `date_vente` date NOT NULL,
                         `montant_total` decimal(10,2) NOT NULL,
                         `compte_id` bigint(20) UNSIGNED NOT NULL,
                         `association_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vente`
--

INSERT INTO `vente` (`id`, `date_vente`, `montant_total`, `compte_id`, `association_id`) VALUES
                                                                                             (1, '2026-01-16', 7.00, 2, 1),
                                                                                             (2, '2026-01-16', 2.50, 7, 1);

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
-- Index pour la table `commande_fournisseur`
--
ALTER TABLE `commande_fournisseur`
    ADD PRIMARY KEY (`id`);

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
-- Index pour la table `detail_commande_fournisseur`
--
ALTER TABLE `detail_commande_fournisseur`
    ADD PRIMARY KEY (`id`),
  ADD KEY `fk_commande` (`id_commande`);

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
-- Index pour la table `fournisseur_produit`
--
ALTER TABLE `fournisseur_produit`
    ADD PRIMARY KEY (`id_fournisseur`,`id_produit`),
  ADD KEY `fk_fp_produit` (`id_produit`);

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
-- Index pour la table `messages`
--
ALTER TABLE `messages`
    ADD PRIMARY KEY (`id`),
  ADD KEY `FK_expediteur` (`id_expediteur`),
  ADD KEY `FK_destinataire` (`id_destinataire`);

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
    ADD KEY `FK vente` (`compte_id`),
    ADD KEY `fk_vente_association` (`association_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `achat`
--
ALTER TABLE `achat`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `association`
--
ALTER TABLE `association`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande_fournisseur`
--
ALTER TABLE `commande_fournisseur`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `compte`
--
ALTER TABLE `compte`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `detail_commande_fournisseur`
--
ALTER TABLE `detail_commande_fournisseur`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `inventaire`
--
ALTER TABLE `inventaire`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `rechargement`
--
ALTER TABLE `rechargement`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `vente`
--
ALTER TABLE `vente`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
