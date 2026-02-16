CREATE DATABASE bngrc;
USE bngrc;

CREATE TABLE bngrc_region (
    id_region INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE bngrc_ville (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    id_region INT,
    FOREIGN KEY (id_region) REFERENCES bngrc_region(id_region)
);

CREATE TABLE bngrc_categorie_besoin (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE bngrc_type_besoin (
    id_type INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    id_categorie INT,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES bngrc_categorie_besoin(id_categorie)
);

CREATE TABLE bngrc_besoin (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_type INT NOT NULL,
    quantite INT NOT NULL,
    date_saisie DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES bngrc_ville(id_ville),
    FOREIGN KEY (id_type) REFERENCES bngrc_type_besoin(id_type)
);

CREATE TABLE bngrc_don (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_type INT NOT NULL,
    quantite INT NOT NULL,
    date_don DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_type) REFERENCES bngrc_type_besoin(id_type)
);

CREATE TABLE bngrc_distribution (
    id_distribution INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    id_ville INT NOT NULL,
    id_type INT NOT NULL,
    quantite INT NOT NULL,
    date_distribution DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_don) REFERENCES bngrc_don(id_don),
    FOREIGN KEY (id_ville) REFERENCES bngrc_ville(id_ville),
    FOREIGN KEY (id_type) REFERENCES bngrc_type_besoin(id_type)
);

CREATE OR REPLACE VIEW v_ville_besoins_dons AS
SELECT
    v.id_ville,
    v.nom AS nom_ville,
    r.nom AS nom_region,
    t.id_type,
    t.nom AS nom_type,
    c.nom AS nom_categorie,
    COALESCE(SUM(b.quantite), 0) AS quantite_besoin,
    COALESCE(SUM(d.quantite), 0) AS quantite_don_attribue
FROM bngrc_ville v
JOIN bngrc_region r ON v.id_region = r.id_region
LEFT JOIN bngrc_besoin b ON b.id_ville = v.id_ville
LEFT JOIN bngrc_type_besoin t ON t.id_type = b.id_type
LEFT JOIN bngrc_categorie_besoin c ON c.id_categorie = t.id_categorie
LEFT JOIN bngrc_distribution d ON d.id_ville = v.id_ville AND d.id_type = b.id_type
GROUP BY
    v.id_ville,
    v.nom,
    r.nom,
    t.id_type,
    t.nom,
    c.nom;


CREATE OR REPLACE VIEW v_ville_besoins_dons_details AS
SELECT
    v.id_ville,
    v.nom AS nom_ville,
    r.nom AS nom_region,
    b.id_besoin,
    b.date_saisie,
    b.quantite AS quantite_besoin,
    t.id_type,
    t.nom AS nom_type,
    c.nom AS nom_categorie,
    d.id_distribution,
    d.id_don,
    d.quantite AS quantite_don_attribue,
    d.date_distribution
FROM bngrc_ville v
JOIN bngrc_region r ON v.id_region = r.id_region
LEFT JOIN bngrc_besoin b ON b.id_ville = v.id_ville
LEFT JOIN bngrc_type_besoin t ON t.id_type = b.id_type
LEFT JOIN bngrc_categorie_besoin c ON c.id_categorie = t.id_categorie
LEFT JOIN bngrc_distribution d ON d.id_ville = v.id_ville AND d.id_type = b.id_type;


