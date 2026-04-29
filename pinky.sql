CREATE TABLE produits (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix NUMERIC(10, 2) NOT NULL,
    image_url VARCHAR(255)
);

CREATE TABLE commandes (
    id SERIAL PRIMARY KEY,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total NUMERIC(10, 2) NOT NULL
);

CREATE TABLE details_commande (
    id SERIAL PRIMARY KEY,
    commande_id INT REFERENCES commandes(id) ON DELETE CASCADE,
    produit_id INT REFERENCES produits(id),
    quantite INT NOT NULL,
    prix_unitaire NUMERIC(10, 2) NOT NULL
);
CREATE TABLE IF NOT EXISTS clients (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO produits (nom, description, prix, image_url) VALUES
('Rouge à lèvres Mat', 'Teinte Blush Nude, tenue 24h.', 127.80, 'rouge.jpg'),
('Fond de teint éclat', 'Couvrance parfaite, fini lumineux.', 174.70, 'fond.jpg'),
('Palette Nude & Pink', '12 teintes douces pour tous les jours.', 209.60, 'palette.jpg'),
('Mascara Volume Intense', 'Cils démultipliés et noirs intenses.', 138.00, 'mascara.jpg'),
('Crayon Lèvres Nude',    'Contour précis et longue tenue, teinte naturelle rosée.',89.90,  'crayon.jpg'),
('Sérum Éclat Visage',    'Formule légère aux vitamines C & E pour un teint lumineux.',245.00, 'serum.jpg'),
('Blush Rose Poudré',     'Poudre soyeuse bonne mine, teinte corail doux.',156.50, 'blush.jpg'),
('Gloss Volume Miroir',   'Lèvres volumisées et brillantes, finition miroir rose nude.',112.00, 'gloss.jpg');
