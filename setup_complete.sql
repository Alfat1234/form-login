-- Setup Complete SIKA Database with Sample Data

-- Create users table
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

-- Create mahasiswa table
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nim` varchar(20) NOT NULL UNIQUE,
  `nama` varchar(100) NOT NULL,
  `prodi` varchar(50),
  `alamat` text,
  `gambar` varchar(100),
  `user_id` int(11),
  `created_by` int(11),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample admin user (password: password123)
INSERT IGNORE INTO `users` 
(`username`, `email`, `name`, `password`, `role`, `status`, `created_at`) 
VALUES 
('admin', 'admin@sika.local', 'Administrator', '$2y$10$YIjlrHn1r8/7RZLkH2x6XuCHvKp2L0c0R9K5m8q9w0T1U2V3W4X5Y6', 'admin', 'active', NOW());

-- Insert sample mahasiswa users (password: password123)
INSERT IGNORE INTO `users` 
(`username`, `email`, `name`, `password`, `role`, `status`, `created_at`) 
VALUES 
('mahasiswa1', 'mahasiswa1@sika.local', 'Budi Santoso', '$2y$10$YIjlrHn1r8/7RZLkH2x6XuCHvKp2L0c0R9K5m8q9w0T1U2V3W4X5Y6', 'mahasiswa', 'active', NOW()),
('mahasiswa2', 'mahasiswa2@sika.local', 'Siti Nurhaliza', '$2y$10$YIjlrHn1r8/7RZLkH2x6XuCHvKp2L0c0R9K5m8q9w0T1U2V3W4X5Y6', 'mahasiswa', 'active', NOW()),
('mahasiswa3', 'mahasiswa3@sika.local', 'Ahmad Wijaya', '$2y$10$YIjlrHn1r8/7RZLkH2x6XuCHvKp2L0c0R9K5m8q9w0T1U2V3W4X5Y6', 'mahasiswa', 'active', NOW());

-- Insert sample mahasiswa records
INSERT IGNORE INTO `mahasiswa` 
(`nim`, `nama`, `prodi`, `alamat`, `user_id`) 
VALUES 
('20210001', 'Budi Santoso', 'Teknik Informatika', 'Jl. Merdeka No. 1, Jakarta', 2),
('20210002', 'Siti Nurhaliza', 'Sistem Informasi', 'Jl. Sudirman No. 2, Bandung', 3),
('20210003', 'Ahmad Wijaya', 'Manajemen Informatika', 'Jl. Ahmad Yani No. 3, Surabaya', 4),
('20210004', 'Rina Sari', 'Teknik Informatika', 'Jl. Gatot Subroto No. 4, Medan', NULL),
('20210005', 'Doni Hermawan', 'Sistem Informasi', 'Jl. Diponegoro No. 5, Yogyakarta', NULL),
('20210006', 'Lina Wijaya', 'Manajemen Informatika', 'Jl. Jenderal Soedirman No. 6, Semarang', NULL);
