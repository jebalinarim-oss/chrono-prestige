-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 28, 2026 at 02:30 PM
-- Server version: 10.5.29-MariaDB-0+deb11u1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_grp15`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(5, 'Chronographe'),
(1, 'Classique'),
(6, 'Édition limitée'),
(3, 'Luxe'),
(4, 'Plongée'),
(2, 'Sport');

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `acheteur_id` int(11) NOT NULL,
  `montre_id` int(11) NOT NULL,
  `prix_achat` decimal(10,2) NOT NULL DEFAULT 0.00,
  `statut` enum('en_cours','expediee','livree','annulee') NOT NULL DEFAULT 'en_cours',
  `adresse_livraison` text DEFAULT NULL,
  `numero_suivi` varchar(100) DEFAULT '',
  `date_commande` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `commandes`
--

INSERT INTO `commandes` (`id`, `acheteur_id`, `montre_id`, `prix_achat`, `statut`, `adresse_livraison`, `numero_suivi`, `date_commande`) VALUES
(4, 6, 7, '4500.00', 'expediee', '95 rue andre jacques', '', '2026-04-14 14:56:33'),
(5, 6, 3, '6800.00', 'expediee', '95 rue andre jacques', '', '2026-04-14 14:56:33'),
(6, 1, 5, '87000.00', 'livree', 'lkjsdfqqsdf', '', '2026-04-22 06:49:39'),
(7, 8, 9, '2500.00', 'en_cours', 'b bo', '', '2026-04-27 06:27:34'),
(8, 9, 3, '6800.00', 'livree', 'bourget', '', '2026-04-27 20:21:07');

-- --------------------------------------------------------

--
-- Table structure for table `montres`
--

CREATE TABLE `montres` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `marque` varchar(100) NOT NULL DEFAULT '',
  `prix` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT '',
  `statut` enum('disponible','vendu','suspendu') NOT NULL DEFAULT 'disponible',
  `vendeur_id` int(11) DEFAULT NULL,
  `date_ajout` datetime DEFAULT current_timestamp(),
  `reference` varchar(100) DEFAULT '',
  `materiau` varchar(100) DEFAULT '',
  `mouvement` varchar(100) DEFAULT '',
  `couleur` varchar(100) DEFAULT '',
  `categorie_id` int(11) DEFAULT NULL,
  `nb_vues` int(11) DEFAULT 0,
  `authentique` tinyint(1) DEFAULT NULL COMMENT 'NULL=non vérifié, 1=authentique, 0=refusé',
  `masquee` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `montres`
--

INSERT INTO `montres` (`id`, `titre`, `marque`, `prix`, `description`, `image`, `statut`, `vendeur_id`, `date_ajout`, `reference`, `materiau`, `mouvement`, `couleur`, `categorie_id`, `nb_vues`, `authentique`, `masquee`) VALUES
(3, 'Seamaster Diver 300M', 'Omega', '6800.00', 'Plongeur professionnel légendaire, compagnon officiel de James Bond depuis 1995. Lunette céramique bleue.', 'omega-seamaster-diver-300m-ceramique-noire-cover.jpg.jpeg', 'disponible', 2, '2026-04-05 17:24:30', 'Réf. 210.30.42.20.03.001', 'Acier inoxydable', 'Automatique Cal. 8800', 'noir \r\n', 4, 34, 1, 0),
(4, 'Nautilus 5711', 'Patek Philippe', '145000.00', 'La montre sport-chic par excellence dessinée par Gérald Genta en 1976. Cadran bleu gradient légendaire.', 'nautilus.jpg', 'disponible', 2, '2026-04-05 17:24:30', 'Réf. 5711/1A-010', 'Acier inoxydable', 'Automatique Cal. 26-330', 'Bleu gradient', 3, 19, 1, 0),
(5, 'Royal Oak Selfwinding', 'Audemars Piguet', '87000.00', 'La montre qui a révolutionné l\'horlogerie de luxe en 1972. Boîtier octogonal emblématique.', 'Audemars-Piguet-Royal-Oak-Royal-Oak-15510ST.OO_.1320ST.06.jpg', 'disponible', 2, '2026-04-05 17:24:30', 'Réf. 15510ST', 'Acier inoxydable', 'Automatique Cal. 4302', 'Bleu nuit', 3, 27, 1, 0),
(6, 'Santos de Cartier', 'Cartier', '8200.00', 'Première montre-bracelet sportive de l\'histoire, créée pour l\'aviateur Santos-Dumont en 1904.', 'santos-de-cartier.jpg', 'disponible', 2, '2026-04-05 17:24:30', 'Réf. WSSA0018', 'Acier inoxydable', 'Automatique Cal. 1847 MC', 'Argenté', 1, 10, 1, 0),
(7, 'Carrera Chronographe', 'TAG Heuer', '4500.00', 'Née sur les circuits de F1 en 1963. Expression ultime du chronographe sportif, précision au 1/100e.', 'TAG-Heuer-Carrera-Chronograph-Glassbox.jpg', 'disponible', 2, '2026-04-05 17:24:30', 'Réf. CBN2010.BA0642', 'Acier / Céramique', 'Automatique Heuer 02', 'Noir', 5, 4, 1, 0),
(8, 'sub mariner', 'rolex', '95000.00', 'mashalah', 'rolex sub mariner.webp', 'disponible', 2, '2026-04-22 08:42:49', '31542', 'vice inoxidable', 'automatique', 'bleu nuit', NULL, 21, 1, 0),
(9, 'Pepsi', 'rolex', '22.00', '', 'rolex pepsi.webp', 'disponible', 2, '2026-04-22 09:10:44', '', '', '', '', NULL, 17, NULL, 0),
(10, 'Cosmograph-Daytona-Panda-Dial-Steel-Mens-Watch', 'rolex', '2500.00', 'hktfg', 'rolex panda.jpeg', 'disponible', 2, '2026-04-22 09:22:32', '', '', '', '', NULL, 10, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `acheteur_id` int(11) NOT NULL,
  `montre_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `panier`
--

INSERT INTO `panier` (`id`, `acheteur_id`, `montre_id`, `quantite`) VALUES
(11, 1, 4, 1),
(19, 4, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `nom` varchar(100) DEFAULT '',
  `prenom` varchar(100) DEFAULT '',
  `age` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('client','vendeur','admin') NOT NULL DEFAULT 'client',
  `suspendu` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `nom`, `prenom`, `age`, `email`, `password`, `role`, `suspendu`, `created_at`) VALUES
(1, 'client', 'client', 'Site', 30, 'admin@chrono.fr', '123456', 'client', 0, '2026-04-02 19:05:31'),
(2, 'vendeur', 'vendeur', 'vendeur', 35, 'vendeur@chrono.fr', 'vendeur', 'vendeur', 0, '2026-04-02 19:05:31'),
(4, 'samy', 'Bouilles', 'Samy', 20, 'samibouilles332@gmail.com', '123456', 'admin', 0, '2026-04-02 19:07:48'),
(6, 'jebali_narim', 'jebali', 'narim', 18, 'jebalinarim@gmail.com', 'jebalinarim2007', 'client', 0, '2026-04-14 13:30:57'),
(7, 'axel', 'c', 'axel', 18, 'axel.charriot@sfr.fr', '1234', 'client', 0, '2026-04-14 14:56:32'),
(8, 'admin', 'admin', 'admin', 19, 'admin', 'admin', 'admin', 0, '2026-04-22 09:00:52'),
(9, 'acrrt', 'crrt', 'axel', 19, 'a@a', 'a', 'client', 0, '2026-04-27 09:03:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Indexes for table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acheteur_id` (`acheteur_id`),
  ADD KEY `montre_id` (`montre_id`);

--
-- Indexes for table `montres`
--
ALTER TABLE `montres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendeur_id` (`vendeur_id`),
  ADD KEY `categorie_id` (`categorie_id`);

--
-- Indexes for table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acheteur_id` (`acheteur_id`),
  ADD KEY `montre_id` (`montre_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `montres`
--
ALTER TABLE `montres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`acheteur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`montre_id`) REFERENCES `montres` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `montres`
--
ALTER TABLE `montres`
  ADD CONSTRAINT `montres_ibfk_1` FOREIGN KEY (`vendeur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `montres_ibfk_2` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`acheteur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`montre_id`) REFERENCES `montres` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
