-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 01. Apr 2025 um 03:38
-- Server-Version: 11.7.2-MariaDB
-- PHP-Version: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `cms_database`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `footer`
--

CREATE TABLE `footer` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `link` varchar(50) NOT NULL,
  `language` varchar(10) NOT NULL,
  `label` varchar(50) NOT NULL,
  `version` varchar(50) NOT NULL,
  `css` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `header`
--

CREATE TABLE `header` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `text` text NOT NULL DEFAULT '',
  `link` varchar(50) NOT NULL,
  `images` varchar(50) NOT NULL,
  `language` varchar(10) NOT NULL,
  `label` varchar(50) NOT NULL,
  `css` varchar(50) NOT NULL,
  `role` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page`
--

CREATE TABLE `page` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` timestamp NULL DEFAULT NULL,
  `idx` int(11) DEFAULT 0,
  `type` varchar(10) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `css` text NOT NULL,
  `fk_translation_placeholder` varchar(40) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `print_all` tinyint(1) NOT NULL DEFAULT 0,
  `role` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page_config`
--

CREATE TABLE `page_config` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `idx` tinyint(1) NOT NULL,
  `fk_page_id` timestamp NULL DEFAULT NULL,
  `fk_plugin_id` timestamp NULL DEFAULT NULL,
  `plugin_content_id` timestamp NULL DEFAULT NULL,
  `cardstack_id` timestamp NULL DEFAULT NULL,
  `content_label` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugin`
--

CREATE TABLE `plugin` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(100) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `table_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `plugin`
--

INSERT INTO `plugin` (`id`, `name`, `enabled`, `table_name`) VALUES
('2023-08-09 09:30:29', 'card', 0, 'p_content_card'),
('2023-08-09 09:31:05', 'diashow', 1, 'p_content_diashow'),
('2023-08-09 09:31:45', 'plaintext', 1, 'p_content_plaintext'),
('2023-08-24 14:47:37', 'login', 1, 'p_content_login'),
('2023-08-24 15:29:14', 'bewerbung', 1, 'p_content_bewerbung'),
('2023-09-07 17:28:20', 'register', 0, 'p_content_register'),
('2023-09-15 11:21:08', 'member_start', 1, 'p_content_member'),
('2023-09-23 10:20:44', 'admin_page_editor', 1, 'p_admin_page_editor'),
('2023-09-23 13:53:32', 'admin_page_config_editor', 1, 'p_admin_page_config_editor'),
('2023-09-23 16:05:26', 'admin_formular_editor', 1, 'p_admin_formular_editor'),
('2023-09-23 16:58:27', 'admin_content_text_editor', 1, 'p_admin_content_text_editor'),
('2023-09-26 15:19:58', 'admin_start', 1, 'p_admin_start'),
('2023-10-03 11:57:36', 'admin_formular_field', 1, 'p_content_formular_field'),
('2023-10-03 12:57:14', 'admin_formular', 1, 'p_content_formular'),
('2023-10-04 19:30:58', 'web_besucher', 1, 'admin_web_besucher'),
('2023-10-13 12:28:55', 'admin_card_editor', 1, 'p_admin_card_editor'),
('2023-10-13 14:40:36', 'admin_card_content_editor', 1, 'p_admin_card_content_editor'),
('2025-03-13 08:37:05', 'logout', 1, 'p_logout'),
('2025-04-01 02:37:01', 'statistik', 1, 'p_content_statistik');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugin_besucher`
--

CREATE TABLE `plugin_besucher` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `besucherdatum` timestamp NOT NULL DEFAULT current_timestamp(),
  `browser` varchar(50) NOT NULL,
  `betriebssystem` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugin_bewerbung`
--

CREATE TABLE `plugin_bewerbung` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `vorname` varchar(255) NOT NULL,
  `nachname` varchar(255) NOT NULL,
  `strasse` varchar(255) NOT NULL,
  `plz` varchar(10) NOT NULL,
  `ort` varchar(255) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `beruf` varchar(255) NOT NULL,
  `gehalt` varchar(10) NOT NULL,
  `geburstag` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugin_login_users`
--

CREATE TABLE `plugin_login_users` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `admin` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_admin_card_content_editor`
--

CREATE TABLE `p_admin_card_content_editor` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_admin_card_editor`
--

CREATE TABLE `p_admin_card_editor` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_admin_content_formular`
--

CREATE TABLE `p_admin_content_formular` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(100) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_admin_content_text_editor`
--

CREATE TABLE `p_admin_content_text_editor` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_admin_formular_field`
--

CREATE TABLE `p_admin_formular_field` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(100) NOT NULL,
  `folder` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_admin_page_config_editor`
--

CREATE TABLE `p_admin_page_config_editor` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_admin_page_editor`
--

CREATE TABLE `p_admin_page_editor` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_admin_start`
--

CREATE TABLE `p_admin_start` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_card_content`
--

CREATE TABLE `p_card_content` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_card_id` timestamp NULL DEFAULT NULL,
  `headline` text NOT NULL,
  `text` text NOT NULL,
  `link` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_card_editor`
--

CREATE TABLE `p_card_editor` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_cardstack_id` timestamp NULL DEFAULT NULL,
  `label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_card_stack`
--

CREATE TABLE `p_card_stack` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `p_card_stack`
--

INSERT INTO `p_card_stack` (`id`, `label`) VALUES
('2025-03-26 09:20:35', 'Card 1'),
('2025-03-27 02:40:28', 'Card 2'),
('2025-03-27 02:55:27', 'Card 3'),
('2025-03-27 03:01:06', 'Card 4');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_content_formular`
--

CREATE TABLE `p_content_formular` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `label` varchar(50) NOT NULL,
  `columns` varchar(255) NOT NULL,
  `use_placeholder` tinyint(1) NOT NULL,
  `use_extra_label` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_content_formular_field`
--

CREATE TABLE `p_content_formular_field` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_formular_id` timestamp NOT NULL,
  `type` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `column` varchar(255) NOT NULL,
  `row` varchar(255) NOT NULL,
  `label_enabled` int(1) NOT NULL,
  `folder` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_content_login`
--

CREATE TABLE `p_content_login` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(10) NOT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_content_member`
--

CREATE TABLE `p_content_member` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(20) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `label` varchar(20) NOT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_content_plaintext`
--

CREATE TABLE `p_content_plaintext` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `text` text NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL,
  `fk_language_id` varchar(2) NOT NULL,
  `image_path` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `image_description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_content_register`
--

CREATE TABLE `p_content_register` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `folder` varchar(50) NOT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_content_statistik`
--

CREATE TABLE `p_content_statistik` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `folder` varchar(50) NOT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_logout`
--

CREATE TABLE `p_logout` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(50) NOT NULL,
  `folder` varchar(255) DEFAULT NULL,
  `enabled` int(1) NOT NULL,
  `idx` int(1) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `p_logout`
--

INSERT INTO `p_logout` (`id`, `headline`, `folder`, `enabled`, `idx`, `label`) VALUES
('2025-03-14 11:42:55', 'Logout', '/plugin/logout.php', 1, 0, 'Logout');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `translation`
--

CREATE TABLE `translation` (
  `id` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_translation_placeholder` varchar(40) NOT NULL,
  `fk_language_id` varchar(2) NOT NULL,
  `label` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `translation_placeholder`
--

CREATE TABLE `translation_placeholder` (
  `id` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `trans_language`
--

CREATE TABLE `trans_language` (
  `id` varchar(2) NOT NULL,
  `label` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `trans_language`
--

INSERT INTO `trans_language` (`id`, `label`) VALUES
('de', 'Deutsch'),
('en', 'English'),
('fr', 'Français');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `footer`
--
ALTER TABLE `footer`
  ADD PRIMARY KEY (`id`,`language`);

--
-- Indizes für die Tabelle `header`
--
ALTER TABLE `header`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `page_config`
--
ALTER TABLE `page_config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `c_content_plugin` (`fk_plugin_id`),
  ADD KEY `content_page` (`fk_page_id`);

--
-- Indizes für die Tabelle `plugin`
--
ALTER TABLE `plugin`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `plugin_besucher`
--
ALTER TABLE `plugin_besucher`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `plugin_bewerbung`
--
ALTER TABLE `plugin_bewerbung`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `plugin_login_users`
--
ALTER TABLE `plugin_login_users`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_admin_card_content_editor`
--
ALTER TABLE `p_admin_card_content_editor`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_admin_card_editor`
--
ALTER TABLE `p_admin_card_editor`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_admin_content_formular`
--
ALTER TABLE `p_admin_content_formular`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_admin_content_text_editor`
--
ALTER TABLE `p_admin_content_text_editor`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_admin_formular_field`
--
ALTER TABLE `p_admin_formular_field`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_admin_page_config_editor`
--
ALTER TABLE `p_admin_page_config_editor`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_admin_page_editor`
--
ALTER TABLE `p_admin_page_editor`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_admin_start`
--
ALTER TABLE `p_admin_start`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_card_content`
--
ALTER TABLE `p_card_content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `card_content` (`fk_card_id`);

--
-- Indizes für die Tabelle `p_card_editor`
--
ALTER TABLE `p_card_editor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cardstack_id` (`fk_cardstack_id`);

--
-- Indizes für die Tabelle `p_card_stack`
--
ALTER TABLE `p_card_stack`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_content_formular`
--
ALTER TABLE `p_content_formular`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_content_formular_field`
--
ALTER TABLE `p_content_formular_field`
  ADD PRIMARY KEY (`id`),
  ADD KEY `c_content_formular` (`fk_formular_id`);

--
-- Indizes für die Tabelle `p_content_login`
--
ALTER TABLE `p_content_login`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_content_member`
--
ALTER TABLE `p_content_member`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_content_plaintext`
--
ALTER TABLE `p_content_plaintext`
  ADD PRIMARY KEY (`id`,`fk_language_id`),
  ADD KEY `c2.optionsfeld_language_id` (`fk_language_id`);

--
-- Indizes für die Tabelle `p_content_register`
--
ALTER TABLE `p_content_register`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_content_statistik`
--
ALTER TABLE `p_content_statistik`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_logout`
--
ALTER TABLE `p_logout`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `translation`
--
ALTER TABLE `translation`
  ADD PRIMARY KEY (`fk_translation_placeholder`,`fk_language_id`),
  ADD KEY `c_language_id` (`fk_language_id`);

--
-- Indizes für die Tabelle `translation_placeholder`
--
ALTER TABLE `translation_placeholder`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `trans_language`
--
ALTER TABLE `trans_language`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `plugin_besucher`
--
ALTER TABLE `plugin_besucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5117;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `page_config`
--
ALTER TABLE `page_config`
  ADD CONSTRAINT `c_content_plugin` FOREIGN KEY (`fk_plugin_id`) REFERENCES `plugin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `content_page` FOREIGN KEY (`fk_page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `p_card_content`
--
ALTER TABLE `p_card_content`
  ADD CONSTRAINT `card_content` FOREIGN KEY (`fk_card_id`) REFERENCES `p_card_editor` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `p_card_editor`
--
ALTER TABLE `p_card_editor`
  ADD CONSTRAINT `card_editor` FOREIGN KEY (`fk_cardstack_id`) REFERENCES `p_card_stack` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `p_content_formular_field`
--
ALTER TABLE `p_content_formular_field`
  ADD CONSTRAINT `c_content_formular` FOREIGN KEY (`fk_formular_id`) REFERENCES `p_content_formular` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `p_content_plaintext`
--
ALTER TABLE `p_content_plaintext`
  ADD CONSTRAINT `c2.optionsfeld_language_id` FOREIGN KEY (`fk_language_id`) REFERENCES `trans_language` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `translation`
--
ALTER TABLE `translation`
  ADD CONSTRAINT `c_language_id` FOREIGN KEY (`fk_language_id`) REFERENCES `trans_language` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `c_translation_selector_id` FOREIGN KEY (`fk_translation_placeholder`) REFERENCES `translation_placeholder` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
