-- Tabel Users untuk autentikasi
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `email` varchar(100) NOT NULL UNIQUE,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','mahasiswa') NOT NULL DEFAULT 'mahasiswa',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample admin dan mahasiswa (password: password123)
INSERT IGNORE INTO `users` (`username`, `email`, `name`, `password`, `role`, `status`) VALUES
('admin', 'admin@sika.local', 'Administrator', '$2y$10$YIjlrHn1r8/7RZLkH2x6XuCHvKp2L0c0R9K5m8q9w0T1U2V3W4X5Y6', 'admin', 'active'),
('mahasiswa1', 'mahasiswa1@sika.local', 'Mahasiswa Satu', '$2y$10$YIjlrHn1r8/7RZLkH2x6XuCHvKp2L0c0R9K5m8q9w0T1U2V3W4X5Y6', 'mahasiswa', 'active');

-- Tabel Mahasiswa (tetap seperti sebelumnya tapi dengan tambahan user_id)
ALTER TABLE `mahasiswa` ADD COLUMN `user_id` int(11) AFTER `id`;
ALTER TABLE `mahasiswa` ADD COLUMN `created_by` int(11) AFTER `user_id`;
ALTER TABLE `mahasiswa` ADD COLUMN `created_at` timestamp DEFAULT CURRENT_TIMESTAMP AFTER `created_by`;
ALTER TABLE `mahasiswa` ADD COLUMN `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;
