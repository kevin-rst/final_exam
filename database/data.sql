INSERT INTO bngrc_region (nom) VALUES
('Analamanga'),
('Vakinankaratra'),
('Atsinanana');

INSERT INTO bngrc_ville (nom, id_region) VALUES
('Antananarivo', 1),
('Ambohidratrimo', 1),
('Antsirabe', 2),
('Betafo', 2),
('Toamasina', 3);

INSERT INTO bngrc_categorie_besoin (nom) VALUES
('En nature'),
('Materiaux'),
('Argent');

INSERT INTO bngrc_type_besoin (nom, id_categorie, prix_unitaire) VALUES
('Riz', 1, 3000.00),
('Huile', 1, 8000.00),
('Sucre', 1, 4000.00),

('Tôle', 2, 25000.00),
('Clou', 2, 500.00),
('Bois', 2, 15000.00),

('Don en argent', 3, 1.00);

INSERT INTO bngrc_param_frais (taux) 
VALUES (10.00);


-- DROP TABLE bngrc_distribution;          
-- DROP TABLE bngrc_don;                  
-- DROP TABLE bngrc_besoin;
-- DROP TABLE bngrc_type_besoin ;
-- DROP TABLE bngrc_categorie_besoin;
-- DROP TABLE bngrc_ville;
-- DROP TABLE bngrc_region;