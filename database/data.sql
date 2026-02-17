INSERT INTO bngrc_region (id_region, nom) VALUES
(1, 'Atsinanana'),
(2, 'Alaotra-Mangoro'),
(3, 'Sava'),
(4, 'Diana');

INSERT INTO bngrc_ville (id_ville, nom, id_region) VALUES
(1, 'Toamasina', 1),
(2, 'Mananjary', 2),
(3, 'Farafangana', 3),
(4, 'Nosy Be', 4),
(5, 'Morondava', 2);

INSERT INTO bngrc_categorie_besoin (id_categorie, nom) VALUES
(1, 'nature'),
(2, 'materiel'),
(3, 'argent');

INSERT INTO bngrc_type_besoin (id_type, nom, id_categorie, prix_unitaire) VALUES
(1, 'Riz (kg)', 1, 3000),
(2, 'Eau (L)', 1, 1000),
(3, 'Huile (L)', 1, 6000),
(4, 'Tole', 2, 25000),
(5, 'Bache', 2, 15000),
(6, 'Clous (kg)', 2, 8000),
(7, 'Bois', 2, 10000),
(8, 'Argent', 3, 1),
(9, 'groupe', 2, 6750000);

INSERT INTO bngrc_type_besoin (id_type, nom, id_categorie, prix_unitaire)
VALUES (10, 'Haricots', 1, 4000);

INSERT INTO bngrc_param_frais (taux)
VALUES (10.00);
