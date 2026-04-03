CREATE TABLE utilizatori (
    id_utilizator SERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'customer' CHECK (role IN ('customer', 'car_admin', 'user_admin', 'super_admin')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Masini (
    id_masina SERIAL PRIMARY KEY,
    nume_model VARCHAR(50) NOT NULL,
    tip_caroserie VARCHAR(50) NOT NULL,
    numar_locuri INT NOT NULL DEFAULT 5
);

CREATE TABLE Motorizari (
    id_motorizare SERIAL PRIMARY KEY,
    nume_motor VARCHAR(50) NOT NULL,
    tip_combustibil VARCHAR(50) NOT NULL,
    capacitate_cilindrica_cm3 INT, -- Poate fi NULL pentru electrice
    putere_cp INT NOT NULL,
    tip_electrificare VARCHAR(50), -- Full Hybrid, Mild Hybrid 48V, Electric sau NULL
    transmisie VARCHAR(50) NOT NULL,
    tractiune VARCHAR(20) NOT NULL
);

CREATE TABLE Versiuni (
    id_versiune SERIAL PRIMARY KEY,
    id_masina INT NOT NULL,
    id_motorizare INT NOT NULL,
    nivel_echipare VARCHAR(50) NOT NULL,
    pret_euro DECIMAL(10, 2) NOT NULL,
    
    -- Cheile străine care leagă acest tabel de celelalte două
    FOREIGN KEY (id_masina) REFERENCES Masini(id_masina),
    FOREIGN KEY (id_motorizare) REFERENCES Motorizari(id_motorizare),
    
    -- O regulă opțională, dar utilă: nu putem avea două versiuni IDENTICE cu același preț
    UNIQUE (id_masina, id_motorizare, nivel_echipare)
);

CREATE TABLE Comenzi (
    id_comanda SERIAL PRIMARY KEY,
    id_utilizator INT NOT NULL,
    id_versiune INT NOT NULL,
    
    -- Detalii specifice configurării
    culoare_exterior VARCHAR(50) DEFAULT 'Alb', 
    pret_final_euro DECIMAL(10, 2) NOT NULL, -- Poate diferi de prețul versiunii de bază dacă adaugă opționale/vopsea
    
    -- Date administrative
    status_comanda VARCHAR(30) DEFAULT 'Configurare Salvată', -- Ex: 'Salvată', 'Trimisă la dealer', 'Anulată'
    data_crearii TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Cheile străine
    FOREIGN KEY (id_utilizator) REFERENCES Utilizatori(id_utilizator),
    FOREIGN KEY (id_versiune) REFERENCES Versiuni(id_versiune)
);