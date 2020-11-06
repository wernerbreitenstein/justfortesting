-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Erstellungszeit: 06. Nov 2020 um 22:19
-- Server-Version: 5.7.26
-- PHP-Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `adressbuch`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kontakte`
--

CREATE TABLE `kontakte` (
  `id` int(11) NOT NULL,
  `vorname` varchar(100) NOT NULL,
  `nachname` varchar(100) NOT NULL,
  `geburtsdatum` date DEFAULT NULL,
  `anmerkungen` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `kontakte`
--

INSERT INTO `kontakte` (`id`, `vorname`, `nachname`, `geburtsdatum`, `anmerkungen`) VALUES
(1, 'Simon', 'Breitenstein', '2002-04-03', '--'),
(2, 'Daniel', 'Breitenstein', '2004-01-19', '--'),
(3, 'Jonah', 'Breitenstein', '2006-06-04', '--'),
(4, 'Levi', 'Breitenstein', '2009-10-13', '--'),
(5, 'Tatjana', 'Breitenstein', '1971-04-07', '--'),
(6, 'Werner', 'Breitenstein', '1969-06-23', '--');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `kontakte`
--
ALTER TABLE `kontakte`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `kontakte`
--
ALTER TABLE `kontakte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
