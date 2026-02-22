-- Drop tables if they already exist (safe reset)
DROP TABLE IF EXISTS vanzari CASCADE;
DROP TABLE IF EXISTS oferte CASCADE;
DROP TABLE IF EXISTS masini CASCADE;
DROP TABLE IF EXISTS utilizatori CASCADE;

-- =========================
-- UTILIZATORI
-- =========================
CREATE TABLE utilizatori (
    id SERIAL PRIMARY KEY,
    nume VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    parola VARCHAR(255) NOT NULL,
    creat_la TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- MASINI
-- =========================
CREATE TABLE masini (
    id SERIAL PRIMARY KEY,
    marca VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    an INT NOT NULL,
    pret NUMERIC(10,2) NOT NULL,
    descriere TEXT,
    creat_la TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- OFERTE
-- =========================
CREATE TABLE oferte (
    id SERIAL PRIMARY KEY,
    masina_id INT NOT NULL,
    titlu VARCHAR(150) NOT NULL,
    descriere TEXT,
    reducere NUMERIC(5,2),
    data_start DATE,
    data_sfarsit DATE,
    FOREIGN KEY (masina_id) REFERENCES masini(id) ON DELETE CASCADE
);

-- =========================
-- VANZARI
-- =========================
CREATE TABLE vanzari (
    id SERIAL PRIMARY KEY,
    utilizator_id INT NOT NULL,
    masina_id INT NOT NULL,
    data_vanzare TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilizator_id) REFERENCES utilizatori(id) ON DELETE CASCADE,
    FOREIGN KEY (masina_id) REFERENCES masini(id) ON DELETE CASCADE
);