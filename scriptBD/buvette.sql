-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 23 jan. 2026 à 09:30
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
-- Structure de la table `appartient`
--

CREATE TABLE `appartient` (
                              `compte_id` bigint(20) UNSIGNED NOT NULL,
                              `association_id` bigint(20) UNSIGNED NOT NULL,
                              `role` enum('client','barman','gestionnaire') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `association` (
                               `id` bigint(20) UNSIGNED NOT NULL,
                               `nom` varchar(50) NOT NULL,
                               `adresse` varchar(50) NOT NULL,
                               `email` varchar(50) NOT NULL,
                               `telephone` varchar(50) NOT NULL,
                               `solde` decimal(10,2) NOT NULL,
                               `status` enum('en_attente','validee','refusee') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


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
                          `photo` varchar(255) DEFAULT NULL,
                          `actif` tinyint(1) DEFAULT 1,
                          `code_validation` varchar(4) DEFAULT NULL,
                          `code_expiration` datetime DEFAULT NULL
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
                            `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contient`
--



-- --------------------------------------------------------

--
-- Structure de la table `demandes_association`
--

CREATE TABLE `demandes_association` (
                                        `id` int(11) NOT NULL,
                                        `id_gestionnaire` bigint(20) UNSIGNED NOT NULL,
                                        `nom_association` varchar(255) NOT NULL,
                                        `pdf_identite` varchar(255) DEFAULT NULL,
                                        `pdf_pv_creation` varchar(255) DEFAULT NULL,
                                        `pdf_ago` varchar(255) DEFAULT NULL,
                                        `statut` enum('en_attente','validee','refusee') DEFAULT 'en_attente',
                                        `date_soumission` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Structure de la table `fournisseur_produit`
--

CREATE TABLE `fournisseur_produit` (
                                       `id_fournisseur` bigint(20) UNSIGNED NOT NULL,
                                       `id_produit` bigint(20) UNSIGNED NOT NULL,
                                       `prix_achat` decimal(10,2) NOT NULL COMMENT 'Prix proposé par ce fournisseur',
                                       `delai_livraison` int(11) DEFAULT 7 COMMENT 'Délai en jours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gere`
--

CREATE TABLE `gere` (
                        `association_id` bigint(20) UNSIGNED NOT NULL,
                        `produit_id` bigint(20) UNSIGNED NOT NULL,
                        `stock_asso` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Structure de la table `ligne_achat`
--

CREATE TABLE `ligne_achat` (
                               `produit_id` bigint(20) UNSIGNED NOT NULL,
                               `achat_id` bigint(20) UNSIGNED NOT NULL,
                               `quantite` int(10) NOT NULL,
                               `prix_achat_unitaire` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ligne_inventaire`
--

CREATE TABLE `ligne_inventaire` (
                                    `produit_id` bigint(20) UNSIGNED NOT NULL,
                                    `inventaire_id` bigint(20) UNSIGNED NOT NULL,
                                    `stock_theorique` int(10) NOT NULL,
                                    `stock_reel` int(10) NOT NULL,
                                    `perte` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ligne_vente`
--

CREATE TABLE `ligne_vente` (
                               `produit_id` bigint(20) UNSIGNED NOT NULL,
                               `vente_id` bigint(20) UNSIGNED NOT NULL,
                               `quantite` int(10) NOT NULL,
                               `prix_unitaire` decimal(10,0) NOT NULL,
                               `statut` enum('en_attente','validee','en_preparation','prete','livree','annulee') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ligne_vente`


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
                         `date_vente` datetime NOT NULL,
                         `montant_total` decimal(10,2) NOT NULL,
                         `compte_id` bigint(20) UNSIGNED NOT NULL,
                         `association_id` bigint(20) UNSIGNED DEFAULT NULL,
                         `statut` enum('en_attente','validee','en_preparation','prete','livree','annulee') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vente`
--

--

--
-- Index pour la table `achat`
--
ALTER TABLE `achat`
    ADD PRIMARY KEY (`id`),
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
    ADD PRIMARY KEY (`id`),
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
    ADD PRIMARY KEY (`id`),
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
-- Index pour la table `demandes_association`
--
ALTER TABLE `demandes_association`
    ADD PRIMARY KEY (`id`),
  ADD KEY `id_gestionnaire` (`id_gestionnaire`);

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
    ADD PRIMARY KEY (`id`),
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
-- Index pour la table `ligne_achat`
--
ALTER TABLE `ligne_achat`
    ADD PRIMARY KEY (`produit_id`,`achat_id`),
  ADD KEY `FK 1 ligne_achat` (`achat_id`);

--
-- Index pour la table `ligne_inventaire`
--
ALTER TABLE `ligne_inventaire`
    ADD PRIMARY KEY (`produit_id`,`inventaire_id`),
  ADD KEY `FK 1 ligne_inventaire` (`inventaire_id`);

--
-- Index pour la table `ligne_vente`
--
ALTER TABLE `ligne_vente`
    ADD PRIMARY KEY (`produit_id`,`vente_id`),
  ADD KEY `FK 2 ligne_vente` (`vente_id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `vente`
--
ALTER TABLE `vente`
    ADD PRIMARY KEY (`id`);

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
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `compte`
--
ALTER TABLE `compte`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `vente`
--
ALTER TABLE `vente`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
