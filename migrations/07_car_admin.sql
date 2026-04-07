-- Car Admin Migration
-- Password: caradmin123 (hashed with password_hash in PHP)
-- To generate a new hash use: echo password_hash('yourpassword', PASSWORD_DEFAULT);

INSERT INTO utilizatori (first_name, last_name, email, password_hash, role)
VALUES (
    'Admin',
    'Masini',
    'admin_masini@dacia.ro',
    '$2y$10$IXirVIu9gz2kAIry/6LJgur4v.Q9ETnUb2cPB2hvMdDQ8gKmDv/lm',
    'car_admin'
)
ON CONFLICT (email) DO NOTHING;
