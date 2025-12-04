-- Migration: 005_create_computers_table.sql
-- Buat tabel 'computers' untuk menyimpan daftar komputer/akun sesuai permintaan

CREATE TABLE IF NOT EXISTS `computers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(150) NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `department` VARCHAR(100) DEFAULT NULL,
  `pc_password` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `parent_email` VARCHAR(150) DEFAULT NULL,
  `email_password` VARCHAR(255) DEFAULT NULL,
  `hostname` VARCHAR(150) DEFAULT NULL,
  `os` VARCHAR(100) DEFAULT NULL,
  `asset_tag` VARCHAR(100) DEFAULT NULL,
  `location` VARCHAR(150) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ip` (`ip_address`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Catatan: Password disimpan plain-text sesuai permintaan (hanya dokumentasi).
