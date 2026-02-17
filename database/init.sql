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
    quantite DECIMAL(10, 2) NOT NULL,
    quantite_restante DECIMAL(10, 2) NOT NULL,
    date_saisie DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES bngrc_ville(id_ville),
    FOREIGN KEY (id_type) REFERENCES bngrc_type_besoin(id_type)
);

--
CREATE TABLE bngrc_param_frais (
    id_frais INT AUTO_INCREMENT PRIMARY KEY,
    taux DECIMAL(5,2) NOT NULL, -- Pourcentage (ex: 10.00 pour 10%)
    date_application DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bngrc_achat (
    id_achat INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    quantite_achetee DECIMAL(10,2) NOT NULL,
    montant_sous_total DECIMAL(12,2) NOT NULL, -- Prix unitaire * quantite (sans frais)
    montant_frais DECIMAL(12,2) NOT NULL, -- Montant des frais
    montant_total DECIMAL(12,2) NOT NULL, -- Sous-total + frais
    frais_applique DECIMAL(5,2) NOT NULL, -- Taux de frais au moment de l'achat
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('simule', 'valide', 'annule') DEFAULT 'simule',
    date_validation DATETIME NULL,
    date_annulation DATETIME NULL,
    FOREIGN KEY (id_besoin) REFERENCES bngrc_besoin(id_besoin)
);

CREATE TABLE bngrc_don (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_type INT NOT NULL,
    quantite DECIMAL(10, 2) NOT NULL,
    est_utilise BOOLEAN DEFAULT FALSE,
    date_don DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_achat_source INT NULL, -- AJOUT V2 (pour les dons générés par achat)
    FOREIGN KEY (id_type) REFERENCES bngrc_type_besoin(id_type),
    FOREIGN KEY (id_achat_source) REFERENCES bngrc_achat(id_achat)
);

CREATE TABLE bngrc_achat_don (
    id_achat_don INT AUTO_INCREMENT PRIMARY KEY,
    id_achat INT NOT NULL,
    id_don INT NOT NULL,
    montant_utilise DECIMAL(12,2) NOT NULL,
    date_liaison DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_achat) REFERENCES bngrc_achat(id_achat),
    FOREIGN KEY (id_don) REFERENCES bngrc_don(id_don),
    UNIQUE KEY unique_achat_don (id_achat, id_don) -- Éviter les doublons
);

CREATE TABLE bngrc_distribution (
    id_distribution INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    id_ville INT NOT NULL,
    id_type INT NOT NULL,
    quantite DECIMAL(10, 2) NOT NULL,
    id_achat_source INT NULL, -- AJOUT V2
    type_distribution ENUM('don_direct', 'achat') DEFAULT 'don_direct', -- AJOUT V2
    date_distribution DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_don) REFERENCES bngrc_don(id_don),
    FOREIGN KEY (id_ville) REFERENCES bngrc_ville(id_ville),
    FOREIGN KEY (id_type) REFERENCES bngrc_type_besoin(id_type),
    FOREIGN KEY (id_achat_source) REFERENCES bngrc_achat(id_achat)
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
    COALESCE(SUM(b.quantite - b.quantite_restante), 0) AS quantite_don_attribue
FROM bngrc_ville v
JOIN bngrc_region r ON v.id_region = r.id_region
LEFT JOIN bngrc_besoin b ON b.id_ville = v.id_ville
LEFT JOIN bngrc_type_besoin t ON t.id_type = b.id_type
LEFT JOIN bngrc_categorie_besoin c ON c.id_categorie = t.id_categorie
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
    b.quantite_restante,
    (b.quantite - b.quantite_restante) AS quantite_don_attribue,
    t.id_type,
    t.nom AS nom_type,
    c.nom AS nom_categorie
FROM bngrc_ville v
JOIN bngrc_region r ON v.id_region = r.id_region
LEFT JOIN bngrc_besoin b ON b.id_ville = v.id_ville
LEFT JOIN bngrc_type_besoin t ON t.id_type = b.id_type
LEFT JOIN bngrc_categorie_besoin c ON c.id_categorie = t.id_categorie;

CREATE OR REPLACE VIEW v_besoin_details AS
SELECT
    b.id_besoin,
    b.id_ville,
    v.nom AS nom_ville,
    r.nom AS nom_region,
    b.id_type,
    t.nom AS nom_type,
    c.nom AS nom_categorie,
    t.prix_unitaire,
    b.quantite,
    b.quantite_restante,
    (b.quantite - b.quantite_restante) AS quantite_satisfaite,
    b.date_saisie,
    (b.quantite * t.prix_unitaire) AS montant_total_besoin,
    ((b.quantite - b.quantite_restante) * t.prix_unitaire) AS montant_satisfait
FROM bngrc_besoin b
JOIN bngrc_ville v ON b.id_ville = v.id_ville
JOIN bngrc_region r ON v.id_region = r.id_region
JOIN bngrc_type_besoin t ON b.id_type = t.id_type
LEFT JOIN bngrc_categorie_besoin c ON t.id_categorie = c.id_categorie;


-- Vues
-- DROP VIEW IF EXISTS v_ville_besoins_dons;
-- DROP VIEW IF EXISTS v_ville_besoins_dons_details;
-- DROP VIEW IF EXISTS v_besoin_details;

-- -- Tables (ordre dépendances FK)
-- DROP TABLE IF EXISTS bngrc_distribution;
-- DROP TABLE IF EXISTS bngrc_achat_don;
-- DROP TABLE IF EXISTS bngrc_don;
-- DROP TABLE IF EXISTS bngrc_achat;
-- DROP TABLE IF EXISTS bngrc_besoin;
-- DROP TABLE IF EXISTS bngrc_type_besoin;
-- DROP TABLE IF EXISTS bngrc_ville;
-- DROP TABLE IF EXISTS bngrc_categorie_besoin;
-- DROP TABLE IF EXISTS bngrc_region;
-- DROP TABLE IF EXISTS bngrc_param_frais;