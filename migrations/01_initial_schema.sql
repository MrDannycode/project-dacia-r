CREATE TABLE utilizatori (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'customer' CHECK (role IN ('customer', 'car_admin', 'user_admin', 'super_admin')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE masini (
    id SERIAL PRIMARY KEY,
    marca VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    an INT NOT NULL,
    pret NUMERIC(10,2) NOT NULL,
    descriere TEXT,
    creat_la TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    utilizator_id INT NOT NULL,
    masina_id INT NOT NULL,
    data_comanda TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilizator_id) REFERENCES utilizatori(id) ON DELETE CASCADE,
    FOREIGN KEY (masina_id) REFERENCES masini(id) ON DELETE CASCADE
);