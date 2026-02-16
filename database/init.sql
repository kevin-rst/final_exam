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





