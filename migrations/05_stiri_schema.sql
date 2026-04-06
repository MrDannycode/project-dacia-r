CREATE TABLE Stiri (
    id_stire SERIAL PRIMARY KEY,
    titlu VARCHAR(255) NOT NULL,
    descriere TEXT NOT NULL,
    continut TEXT NOT NULL,
    imagine VARCHAR(255),
    alt_imagine VARCHAR(255),
    data_publicarii TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Drop the existing role constraint (if it exists, using standard postgres naming)
ALTER TABLE utilizatori DROP CONSTRAINT IF EXISTS utilizatori_role_check;
ALTER TABLE utilizatori ADD CONSTRAINT utilizatori_role_check CHECK (role IN ('customer', 'car_admin', 'user_admin', 'super_admin', 'news_admin'));

-- Insert the news administrator (ignore if already exists due to constraints, although this is PostgreSQL, so we'll just insert unless email matches)
INSERT INTO utilizatori (first_name, last_name, email, password_hash, role)
VALUES ('Admin', 'Stiri', 'admin_stiri@dacia.ro', '$2y$10$Bqc2kbqDLGvENxAeGws1QuxCzjrLLrPL2he1sla6QT93nUKW1i8eu', 'news_admin')
ON CONFLICT (email) DO NOTHING;

-- Insert initial news
INSERT INTO Stiri (titlu, descriere, continut, imagine, alt_imagine, data_publicarii) VALUES
('Lansare noua Dacia', 'Descoperă ultimele noutăți din gama Dacia. Modele noi, tehnologii inovatoare și oferte speciale pentru tine.', '<p>Descoperă ultimele noutăți din gama Dacia. Modele noi, tehnologii inovatoare și oferte speciale pentru tine. Vino în showroom-urile noastre pentru mai multe detalii.</p>', 'assets/images/news/news-lansare.jpg', 'Noutate Dacia', CURRENT_TIMESTAMP),

('Oferte de primăvară', 'Profita de promoțiile sezonului. Condiții avantajoase de finanțare și reduceri exclusive la modelele selectate.', '<p>Odată cu venirea primăverii, te întâmpinăm cu oferte de neratat. Finanțare flexibilă, reduceri direct la achiziție, pachete speciale.</p>', 'assets/images/news/news-primavara.jpg', 'Oferte promoționale', CURRENT_TIMESTAMP),

('Test drive gratuit', 'Înscrie-te la o sesiune de test drive și experimentează personal calitățile vehiculelor Dacia.', '<p>Experimentează o plimbare cu modelele tale preferate complet gratuit, contactându-ne la cel mai apropiat dealer.</p>', 'assets/images/news/news-test-drive.webp', 'Eveniment Dacia', CURRENT_TIMESTAMP),

('Hybrid-G 150 4x4 pe Dacia Duster (2026)', 'Primul sistem din lume care combină hibridul, tracțiunea integrală și transmisia automată cu alimentare benzină-GPL.', '<p>Sfidând toate așteptările, Dacia a lansat cel mai nou grup motopropulsor complet inovator. Unitatea este acum disponibilă...</p>', 'assets/images/news/news-hybrid.jpg', 'Hybrid-G 150 4x4', CURRENT_TIMESTAMP),

('Bigster devine realitate', 'Noul SUV Dacia Bigster aduce o prezență impunătoare, spațiu generos și motorizări noi.', '<p>Bigster reprezintă noul val pentru gama Dacia, cu dimensiuni mari, aspect impunător și preț accesibil pentru un SUV din segmentul său.</p>', 'assets/images/news/news-bigster.webp', 'Dacia Bigster', CURRENT_TIMESTAMP),

('Dacia participă la Dakar', 'Echipa Dacia Sandriders se pregătește pentru cea mai dură competiție de motorsport din lume.', '<p>Echipa oficială Dacia este pregătită pentru marele Raliu Dakar. Piloții noștri legendari se vor duela cu deșertul și provocările acestuia.</p>', 'assets/images/news/news-dakar.webp', 'Dacia Sandrider', CURRENT_TIMESTAMP),

('Facelift major pentru Spring', 'Cel mai accesibil model electric se înnoiește cu un design modern și conectivitate de top.', '<p>Pentru noul an, Spring vine cu un layout complet refacut, o nouă identitate de marcă, și eficiență maximă ca vehicul urban.</p>', 'assets/images/news/news-spring.webp', 'Dacia Spring facelift', CURRENT_TIMESTAMP);
