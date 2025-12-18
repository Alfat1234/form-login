SIKA - Sistem Informasi Kemahasiswaan

DATABASE: localhost/root (no password) - sika

LOGIN:
- Admin: admin / password123
- Mahasiswa: mahasiswa1 / password123

START:
1. Run XAMPP - start Apache & MySQL
2. Go to: http://localhost/sika/login.php
3. Login with credentials above

FILES:
- includes/ - Config, Auth, Header, Footer templates
- process/ - Login, Logout, Register handlers
- assets/ - CSS, JS, Vendor libraries
- [Pages: login, index, profile, tambah, edit, hapus]

FEATURES:
✓ User Login/Register
✓ Dashboard with Mahasiswa data table
✓ Add/Edit/Delete Mahasiswa
✓ User Profile
✓ Role-based access (Admin/Mahasiswa)
✓ SB Admin 2 Template integration
✓ Responsive design

DATABASE TABLES:
- users (id, username, email, name, password, role, status)
- mahasiswa (id, nim, nama, prodi, alamat, gambar, user_id, created_by)

SAMPLE DATA:
- 4 Users (1 admin + 3 mahasiswa)
- 6 Mahasiswa records

If "MySQL server gone away" error:
1. Start MySQL from XAMPP Control Panel
2. Recreate database: php recover-database.php
3. Login again
