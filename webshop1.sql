-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 06. Jan 2023 um 20:29
-- Server-Version: 10.4.21-MariaDB
-- PHP-Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `webshop1`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellung`
--

CREATE TABLE `bestellung` (
  `bes_id` int(11) NOT NULL,
  `u_idf` int(11) NOT NULL,
  `bes_datum` varchar(150) NOT NULL,
  `bes_versand` decimal(7,2) NOT NULL,
  `bes_total` decimal(7,2) NOT NULL,
  `bes_totalrabatt` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gutscheincode`
--

CREATE TABLE `gutscheincode` (
  `gut_id` int(11) NOT NULL,
  `gut_code` varchar(10) NOT NULL,
  `gut_rabatt` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `gutscheincode`
--

INSERT INTO `gutscheincode` (`gut_id`, `gut_code`, `gut_rabatt`) VALUES
(9, 'ABCDE12345', 10),
(10, 'FGHIJ67890', 20);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posten`
--

CREATE TABLE `posten` (
  `posten_id` int(11) NOT NULL,
  `bes_idf` int(11) NOT NULL,
  `pro_idf` int(11) NOT NULL,
  `posten_menge` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produkt`
--

CREATE TABLE `produkt` (
  `pro_id` int(11) NOT NULL,
  `pro_name` varchar(100) NOT NULL,
  `pro_preis` decimal(7,2) NOT NULL,
  `pro_image` varchar(100) NOT NULL,
  `pro_groesse` varchar(100) NOT NULL,
  `pro_farbe` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `produkt`
--

INSERT INTO `produkt` (`pro_id`, `pro_name`, `pro_preis`, `pro_image`, `pro_groesse`, `pro_farbe`) VALUES
(1, 'Princeton Champion Sweater Pullover Übermaße', '25.00', 'images/products/p1.jpg', 'L', 'schwarz'),
(2, 'Champion Sweater Pullover', '20.00', 'images/products/p2.jpg', 'L', 'grau'),
(3, 'Champion New York Shirt', '11.00', 'images/products/p3.jpg', 'L', 'schwarz'),
(4, 'Champion Shirt USCB', '9.00', 'images/products/p4.jpg', 'L', 'rot'),
(5, 'Disney Sweater Pullover', '18.00', 'images/products/p5.jpg', 'XL', 'grau'),
(6, 'Nike Stranger Things Sweater Pullover', '21.00', 'images/products/p6.jpg', 'M', 'grün'),
(7, 'Michigan State Vintage Hoodie Pullover', '18.00', 'images/products/p7.jpg', 'M', 'dunkelgrün'),
(8, 'Nike Packers Hoodie Pullover', '23.00', 'images/products/p8.jpg', 'XL', 'dunkelgrün'),
(9, 'New York Troopers Vintage Sweater Pullover', '28.00', 'images/products/p9.jpg', 'XL', 'dunkelblau'),
(10, 'Vintage Motor Sports Hoodie Pullover', '32.00', 'images/products/p10.jpg', 'XL', 'schwarz'),
(11, 'Saint Marys Vintage Sweater Pullover', '28.00', 'images/products/p11.jpg', 'XL', 'weiß');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `u_id` int(11) NOT NULL,
  `u_anrede` varchar(100) NOT NULL,
  `u_fname` varchar(100) NOT NULL,
  `u_lname` varchar(100) NOT NULL,
  `u_mail` varchar(150) NOT NULL,
  `u_password` varchar(150) NOT NULL,
  `u_lastlogin` varchar(150) NOT NULL,
  `u_screenheight` varchar(150) NOT NULL,
  `u_screenwidth` varchar(150) NOT NULL,
  `u_os` varchar(150) NOT NULL,
  `u_firstlogin` int(50) NOT NULL,
  `u_loginstatus` int(50) NOT NULL,
  `u_strasse` varchar(100) NOT NULL,
  `u_hausnr` varchar(100) NOT NULL,
  `u_land` varchar(100) NOT NULL,
  `u_plz` varchar(100) NOT NULL,
  `u_ort` varchar(100) NOT NULL,
  `u_tele` varchar(100) NOT NULL,
  `u_secret` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `warenkorb`
--

CREATE TABLE `warenkorb` (
  `w_id` int(11) NOT NULL,
  `u_idf` int(11) NOT NULL,
  `pro_idf` int(11) NOT NULL,
  `w_menge` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD PRIMARY KEY (`bes_id`),
  ADD KEY `test3` (`u_idf`);

--
-- Indizes für die Tabelle `gutscheincode`
--
ALTER TABLE `gutscheincode`
  ADD PRIMARY KEY (`gut_id`);

--
-- Indizes für die Tabelle `posten`
--
ALTER TABLE `posten`
  ADD PRIMARY KEY (`posten_id`),
  ADD KEY `test4` (`bes_idf`),
  ADD KEY `test5` (`pro_idf`);

--
-- Indizes für die Tabelle `produkt`
--
ALTER TABLE `produkt`
  ADD PRIMARY KEY (`pro_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`);

--
-- Indizes für die Tabelle `warenkorb`
--
ALTER TABLE `warenkorb`
  ADD PRIMARY KEY (`w_id`),
  ADD KEY `test1` (`u_idf`),
  ADD KEY `test2` (`pro_idf`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `gutscheincode`
--
ALTER TABLE `gutscheincode`
  MODIFY `gut_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `posten`
--
ALTER TABLE `posten`
  MODIFY `posten_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT für Tabelle `warenkorb`
--
ALTER TABLE `warenkorb`
  MODIFY `w_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD CONSTRAINT `test3` FOREIGN KEY (`u_idf`) REFERENCES `user` (`u_id`);

--
-- Constraints der Tabelle `posten`
--
ALTER TABLE `posten`
  ADD CONSTRAINT `test4` FOREIGN KEY (`bes_idf`) REFERENCES `bestellung` (`bes_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test5` FOREIGN KEY (`pro_idf`) REFERENCES `produkt` (`pro_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `warenkorb`
--
ALTER TABLE `warenkorb`
  ADD CONSTRAINT `test1` FOREIGN KEY (`u_idf`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test2` FOREIGN KEY (`pro_idf`) REFERENCES `produkt` (`pro_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
