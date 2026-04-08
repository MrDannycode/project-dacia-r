INSERT INTO Motorizari 
    (nume_motor, tip_combustibil, capacitate_cilindrica_cm3, putere_cp, tip_electrificare, transmisie, tractiune)
VALUES
    -- Sistemele Full Hybrid
    ('Hybrid 155', 'Benzina + Electric', 1800, 155, 'Full Hybrid', 'Automata', '4x2'),
    ('Hybrid-G 150 4x4', 'Benzina + GPL + Electric', 1200, 150, 'Full Hybrid', 'Automata', '4x4'),
    
    -- Sistemele GPL
    ('Eco-G 120', 'Benzina + GPL', 1200, 120, NULL, 'Manuala / Automata (EDC)', '4x2'),
    
    -- Sistemele Mild Hybrid & Termice
    ('TCe 140', 'Benzina', 1200, 140, 'Mild Hybrid 48V', 'Manuala', '4x2'),
    ('TCe 130', 'Benzina', 1200, 130, 'Mild Hybrid 48V', 'Manuala', '4x2 / 4x4'),
    
    -- Sistemele 100% Electrice (Dacia Spring)
    ('Electric 70', 'Electric', NULL, 70, '100% Electric', 'Automata (Reductor)', '4x2'),
    ('Electric 100', 'Electric', NULL, 100, '100% Electric', 'Automata (Reductor)', '4x2'),

    -- Sistemele pentru Sandero (id_masina = 2)
    ('SCe 65', 'Benzina', 999, 65, NULL, 'Manuala', '4x2'),
    ('TCe 90', 'Benzina', 999, 90, NULL, 'Manuala', '4x2'),
    ('ECO-G 100', 'Benzina + GPL', 999, 100, NULL, 'Manuala', '4x2');