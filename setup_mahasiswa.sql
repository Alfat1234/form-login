CREATE TABLE IF NOT EXISTS mahasiswa (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nim VARCHAR(20) UNIQUE NOT NULL,
  nama VARCHAR(100) NOT NULL,
  prodi VARCHAR(50),
  alamat TEXT,
  gambar VARCHAR(100),
  user_id INT,
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO mahasiswa (nim, nama, prodi, alamat, gambar) VALUES
('20210001', 'Budi Santoso', 'Teknik Informatika', 'Jl. Merdeka No. 1', ''),
('20210002', 'Siti Nurhaliza', 'Sistem Informasi', 'Jl. Sudirman No. 2', ''),
('20210003', 'Ahmad Wijaya', 'Manajemen Informatika', 'Jl. Ahmad Yani No. 3', '');
