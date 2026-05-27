-- Bağımlılık Azaltma ve Tasarruf Takip Sistemi
-- Public GitHub için temizlenmiş SQL dosyası
-- Not: Gerçek kullanıcı verileri, e-posta adresleri, alışkanlık kayıtları ve tasarruf kayıtları kaldırılmıştır.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `addiction_tracking`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `addiction_tracking`;

SET NAMES utf8mb4;

-- --------------------------------------------------------
-- Eski tabloları temizle
-- --------------------------------------------------------

DROP TABLE IF EXISTS `user_badges`;
DROP TABLE IF EXISTS `savings`;
DROP TABLE IF EXISTS `habits`;
DROP TABLE IF EXISTS `badges`;
DROP TABLE IF EXISTS `users`;

-- --------------------------------------------------------
-- Tablo yapısı: users
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tablo yapısı: badges
-- --------------------------------------------------------

CREATE TABLE `badges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `color_class` varchar(50) DEFAULT NULL,
  `requirement_type` varchar(50) DEFAULT NULL,
  `requirement_value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Varsayılan rozet verileri
-- --------------------------------------------------------

INSERT INTO `badges` (`id`, `name`, `description`, `icon`, `color_class`, `requirement_type`, `requirement_value`) VALUES
(1, 'İlk Adım', 'Sisteme başarıyla kayıt oldun.', 'fa-seedling', 'text-success', 'total_savings', 0),
(2, 'Kararlı', 'Yolculuğun ilk adımlarını atıyorsun.', 'fa-calendar-check', 'text-primary', 'total_savings', 50),
(3, 'Bronz Kumbara', 'İlk 100₺ tasarrufa ulaştın!', 'fa-piggy-bank', 'text-warning', 'total_savings', 100),
(4, 'Akıllı Bildirim', 'Telegram botu ile sistem entegre edildi.', 'fa-brands fa-telegram', 'text-info', 'total_savings', 150),
(5, 'Kusursuz Hafta', 'Tasarruf hedeflerinde istikrar sağlıyorsun.', 'fa-fire', 'text-danger', 'total_savings', 250),
(6, 'Gümüş Kasa', '500₺ hedefine başarıyla ulaştın.', 'fa-vault', 'text-secondary', 'total_savings', 500),
(7, 'Altın Külçe', '1.000₺ tasarruf ile büyük ligdesin.', 'fa-coins', 'text-warning', 'total_savings', 1000),
(8, 'Platin Kasa', '5.000₺ tasarruf barajını devirdin.', 'fa-money-bill-trend-up', 'text-info', 'total_savings', 5000),
(9, 'Elmas Kasa', '10.000₺ tasarruf ile bir efsanesin!', 'fa-gem', 'text-primary', 'total_savings', 10000),
(10, 'Çelik İrade', 'Bağımlılıklarına karşı tam kontrol.', 'fa-shield-halved', 'text-dark', 'total_savings', 15000),
(11, 'Sağlık Elçisi', 'Hem cebini hem sağlığını korudun.', 'fa-heart-pulse', 'text-danger', 'total_savings', 20000),
(12, 'Usta Tasarrufçu', 'Sistemin tüm sınırlarını zorladın.', 'fa-crown', 'text-warning', 'total_savings', 25000);

-- --------------------------------------------------------
-- Tablo yapısı: habits
-- --------------------------------------------------------

CREATE TABLE `habits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `target` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `date` date NOT NULL,
  `status` enum('pending','completed') DEFAULT 'completed',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `habits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tablo yapısı: savings
-- --------------------------------------------------------

CREATE TABLE `savings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `saved_money` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `savings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tablo yapısı: user_badges
-- --------------------------------------------------------

CREATE TABLE `user_badges` (
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`, `badge_id`),
  KEY `badge_id` (`badge_id`),
  CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
