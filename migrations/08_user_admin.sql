-- User Admin Migration
-- Password: superadmin123 (hashed with password_hash in PHP)
-- To generate a new hash use: echo password_hash('yourpassword', PASSWORD_DEFAULT);

INSERT INTO utilizatori (first_name, last_name, email, password_hash, role)
VALUES (
    'Admin',
    'Utilizatori',
    'admin_users@dacia.ro',
    '$2y$10$kKpMT1S7nuizD4532QywAexQErMi03QC/yRgoDkqKCMNHKXBDPtpO',
    'user_admin'
)
ON CONFLICT (email) DO NOTHING;
