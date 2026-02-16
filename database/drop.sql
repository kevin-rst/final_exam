-- =====================================================
-- SCRIPT COMPLET POUR LA V2
-- =====================================================

-- 1. SUPPRESSION DES TABLES (si existantes) dans l'ordre inverse des dépendances
DROP TABLE IF EXISTS bngrc_achat_don;
DROP TABLE IF EXISTS bngrc_distribution;
DROP TABLE IF EXISTS bngrc_achat;
DROP TABLE IF EXISTS bngrc_param_frais;
DROP TABLE IF EXISTS bngrc_don;
DROP TABLE IF EXISTS bngrc_besoin;
DROP TABLE IF EXISTS bngrc_type_besoin;
DROP TABLE IF EXISTS bngrc_categorie_besoin;
DROP TABLE IF EXISTS bngrc_ville;
DROP TABLE IF EXISTS bngrc_region;

-- =====================================================
-- 2. TABLES DE BASE (existantes)
-- =====================================================

-- Table région
CREATE TABLE bngrc_region (
    id_region INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table ville
CREATE TABLE bngrc_ville (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    id_region INT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_region) REFERENCES bngrc_region(id_region)
);

-- Table catégorie de besoin (Nature, Matériaux, Argent)
CREATE TABLE bngrc_categorie_besoin (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insertion des catégories de base
INSERT INTO bngrc_categorie_besoin (nom, description) VALUES
('Nature', 'Produits alimentaires et de première nécessité'),
('Matériaux', 'Matériaux de construction et réparation'),
('Argent', 'Dons monétaires');

-- Table type de besoin
CREATE TABLE bngrc_type_besoin (
    id_type INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    id_categorie INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES bngrc_categorie_besoin(id_categorie)
);

-- Insertion des types de besoin par défaut
INSERT INTO bngrc_type_besoin (nom, id_categorie, prix_unitaire, unite_mesure) VALUES
('Riz', 1, 5000.00, 'kg'),
('Huile', 1, 8000.00, 'litre'),
('Farine', 1, 4000.00, 'kg'),
('Tôle', 2, 25000.00, 'unité'),
('Clou', 2, 500.00, 'kg'),
('Ciment', 2, 30000.00, 'sac'),
('Argent', 3, 1.00, 'Ariary');

-- Table besoin
CREATE TABLE bngrc_besoin (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_type INT NOT NULL,
    quantite INT NOT NULL,
    quantite_restante INT NOT NULL, -- AJOUT V2
    date_saisie DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES bngrc_ville(id_ville),
    FOREIGN KEY (id_type) REFERENCES bngrc_type_besoin(id_type)
);

-- Table don (existante mais modifiée)
CREATE TABLE bngrc_don (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_type INT NOT NULL,
    quantite DECIMAL(12,2) NOT NULL, -- Changé en DECIMAL pour l'argent
    date_don DATETIME DEFAULT CURRENT_TIMESTAMP,
    est_utilise BOOLEAN DEFAULT FALSE, -- AJOUT V2
    id_achat_source INT NULL, -- AJOUT V2 (pour les dons générés par achat)
    donateur_nom VARCHAR(200) NULL,
    description TEXT NULL,
    FOREIGN KEY (id_type) REFERENCES bngrc_type_besoin(id_type)
    -- Note: La FK pour id_achat_source sera ajoutée après création de bngrc_achat
);

-- =====================================================
-- 3. NOUVELLES TABLES POUR LA V2
-- =====================================================

-- Table param_frais (paramétrable)
CREATE TABLE bngrc_param_frais (
    id_frais INT AUTO_INCREMENT PRIMARY KEY,
    taux DECIMAL(5,2) NOT NULL, -- Pourcentage (ex: 10.00 pour 10%)
    date_application DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_fin DATETIME NULL, -- NULL = toujours actif
    est_actif BOOLEAN DEFAULT TRUE,
    description VARCHAR(255),
    created_by VARCHAR(100) NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insertion du frais par défaut
INSERT INTO bngrc_param_frais (taux, description, est_actif) 
VALUES (10.00, 'Frais par défaut 10%', TRUE);

-- Table achat
CREATE TABLE bngrc_achat (
    id_achat INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    quantite_achetee INT NOT NULL,
    montant_sous_total DECIMAL(12,2) NOT NULL, -- Prix unitaire * quantite (sans frais)
    montant_frais DECIMAL(12,2) NOT NULL, -- Montant des frais
    montant_total DECIMAL(12,2) NOT NULL, -- Sous-total + frais
    frais_applique DECIMAL(5,2) NOT NULL, -- Taux de frais au moment de l'achat
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('simule', 'valide', 'annule') DEFAULT 'simule',
    date_validation DATETIME NULL,
    date_annulation DATETIME NULL,
    motif_annulation TEXT NULL,
    FOREIGN KEY (id_besoin) REFERENCES bngrc_besoin(id_besoin)
);

-- Table de liaison achat_don (cruciale pour la traçabilité)
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

-- Table distribution (existante mais modifiée)
CREATE TABLE bngrc_distribution (
    id_distribution INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    id_ville INT NOT NULL,
    id_type INT NOT NULL,
    quantite INT NOT NULL,
    date_distribution DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_achat_source INT NULL, -- AJOUT V2
    type_distribution ENUM('don_direct', 'achat') DEFAULT 'don_direct', -- AJOUT V2
    commentaire TEXT NULL,
    FOREIGN KEY (id_don) REFERENCES bngrc_don(id_don),
    FOREIGN KEY (id_ville) REFERENCES bngrc_ville(id_ville),
    FOREIGN KEY (id_type) REFERENCES bngrc_type_besoin(id_type),
    FOREIGN KEY (id_achat_source) REFERENCES bngrc_achat(id_achat)
);

-- =====================================================
-- 4. AJOUT DES CONTRAINTES MANQUANTES
-- =====================================================

-- Ajout de la FK pour id_achat_source dans bngrc_don
ALTER TABLE bngrc_don
ADD FOREIGN KEY (id_achat_source) REFERENCES bngrc_achat(id_achat);

-- =====================================================
-- 5. VUES UTILES
-- =====================================================

-- Vue pour les besoins avec détails
CREATE VIEW bngrc_vue_besoins_complet AS
SELECT 
    b.id_besoin,
    v.nom as ville_nom,
    r.nom as region_nom,
    tb.nom as type_nom,
    cb.nom as categorie_nom,
    tb.prix_unitaire,
    b.quantite as quantite_initiale,
    b.quantite_restante,
    (b.quantite - b.quantite_restante) as quantite_satisfaite,
    (b.quantite * tb.prix_unitaire) as montant_total,
    (b.quantite_restante * tb.prix_unitaire) as montant_restant,
    ((b.quantite - b.quantite_restante) * tb.prix_unitaire) as montant_satisfait,
    b.date_saisie
FROM bngrc_besoin b
JOIN bngrc_ville v ON b.id_ville = v.id_ville
JOIN bngrc_region r ON v.id_region = r.id_region
JOIN bngrc_type_besoin tb ON b.id_type = tb.id_type
JOIN bngrc_categorie_besoin cb ON tb.id_categorie = cb.id_categorie;

-- Vue pour les dons disponibles
CREATE VIEW bngrc_vue_dons_disponibles AS
SELECT 
    d.id_don,
    tb.nom as type_nom,
    cb.nom as categorie_nom,
    d.quantite,
    d.date_don,
    d.est_utilise,
    CASE 
        WHEN d.id_achat_source IS NOT NULL THEN 'Généré par achat'
        ELSE 'Don direct'
    END as origine,
    d.donateur_nom
FROM bngrc_don d
JOIN bngrc_type_besoin tb ON d.id_type = tb.id_type
JOIN bngrc_categorie_besoin cb ON tb.id_categorie = cb.id_categorie
WHERE d.est_utilise = FALSE;

-- Vue pour les statistiques par ville
CREATE VIEW bngrc_vue_stats_ville AS
SELECT 
    v.id_ville,
    v.nom as ville_nom,
    r.nom as region_nom,
    COUNT(DISTINCT b.id_besoin) as nombre_besoins,
    SUM(b.quantite * tb.prix_unitaire) as total_besoins,
    SUM(b.quantite_restante * tb.prix_unitaire) as total_restant,
    SUM((b.quantite - b.quantite_restante) * tb.prix_unitaire) as total_satisfait,
    COUNT(DISTINCT d.id_distribution) as nombre_distributions
FROM bngrc_ville v
LEFT JOIN bngrc_region r ON v.id_region = r.id_region
LEFT JOIN bngrc_besoin b ON v.id_ville = b.id_ville
LEFT JOIN bngrc_type_besoin tb ON b.id_type = tb.id_type
LEFT JOIN bngrc_distribution d ON v.id_ville = d.id_ville
GROUP BY v.id_ville, v.nom, r.nom;

-- Vue pour la traçabilité des achats
CREATE VIEW bngrc_vue_tracabilite_achat AS
SELECT 
    a.id_achat,
    b.id_besoin,
    v.nom as ville_nom,
    tb.nom as type_achete,
    a.quantite_achetee,
    a.montant_total,
    a.frais_applique,
    a.statut,
    a.date_achat,
    GROUP_CONCAT(CONCAT(d.id_don, ' (', ad.montant_utilise, ' Ar)') SEPARATOR ', ') as dons_utilises,
    COUNT(DISTINCT ad.id_don) as nombre_dons_utilises
FROM bngrc_achat a
JOIN bngrc_besoin b ON a.id_besoin = b.id_besoin
JOIN bngrc_ville v ON b.id_ville = v.id_ville
JOIN bngrc_type_besoin tb ON b.id_type = tb.id_type
LEFT JOIN bngrc_achat_don ad ON a.id_achat = ad.id_achat
LEFT JOIN bngrc_don d ON ad.id_don = d.id_don
GROUP BY a.id_achat;

-- =====================================================
-- 6. INDEX POUR LES PERFORMANCES
-- =====================================================

CREATE INDEX idx_besoin_ville ON bngrc_besoin(id_ville);
CREATE INDEX idx_besoin_type ON bngrc_besoin(id_type);
CREATE INDEX idx_besoin_restant ON bngrc_besoin(quantite_restante);
CREATE INDEX idx_don_type ON bngrc_don(id_type);
CREATE INDEX idx_don_utilise ON bngrc_don(est_utilise);
CREATE INDEX idx_don_date ON bngrc_don(date_don);
CREATE INDEX idx_achat_besoin ON bngrc_achat(id_besoin);
CREATE INDEX idx_achat_statut ON bngrc_achat(statut);
CREATE INDEX idx_achat_don_achat ON bngrc_achat_don(id_achat);
CREATE INDEX idx_achat_don_don ON bngrc_achat_don(id_don);
CREATE INDEX idx_distribution_ville ON bngrc_distribution(id_ville);
CREATE INDEX idx_distribution_date ON bngrc_distribution(date_distribution);
CREATE INDEX idx_distribution_type ON bngrc_distribution(type_distribution);

-- =====================================================
-- 7. DONNÉES DE TEST (optionnel)
-- =====================================================

-- Insertion de régions
INSERT INTO bngrc_region (nom) VALUES
('Analamanga'),
('Vakinankaratra'),
('Atsinanana');

-- Insertion de villes
INSERT INTO bngrc_ville (nom, id_region) VALUES
('Antananarivo', 1),
('Antsirabe', 2),
('Toamasina', 3),
('Ambohidratrimo', 1);

-- Insertion de besoins (avec quantite_restante initialisée)
INSERT INTO bngrc_besoin (id_ville, id_type, quantite, quantite_restante) VALUES
(1, 1, 1000, 1000), -- Antananarivo a besoin de 1000 kg de riz
(1, 4, 500, 500),   -- Antananarivo a besoin de 500 tôles
(2, 2, 800, 800),   -- Antsirabe a besoin de 800 litres d'huile
(2, 6, 200, 200),   -- Antsirabe a besoin de 200 sacs de ciment
(3, 1, 1500, 1500), -- Toamasina a besoin de 1500 kg de riz
(3, 5, 1000, 1000); -- Toamasina a besoin de 1000 kg de clous

-- Insertion de dons en argent
INSERT INTO bngrc_don (id_type, quantite, date_don, est_utilise, donateur_nom) VALUES
(7, 2000000, '2026-02-01 10:30:00', FALSE, 'M. Rakoto'),
(7, 1500000, '2026-02-05 14:15:00', FALSE, 'Mme Ravao'),
(7, 3000000, '2026-02-10 09:45:00', FALSE, 'Entreprise XYZ');

-- Insertion de dons en nature
INSERT INTO bngrc_don (id_type, quantite, date_don, est_utilise, donateur_nom) VALUES
(1, 500, '2026-02-03 11:20:00', FALSE, 'Secours Populaire'),
(4, 200, '2026-02-07 16:30:00', FALSE, 'M. Randria');

-- =====================================================
-- 8. PROCEDURES STOCKÉES UTILES
-- =====================================================

-- Procédure pour obtenir le taux de frais actif
DELIMITER //
CREATE PROCEDURE GetTauxFraisActif(OUT taux DECIMAL(5,2))
BEGIN
    SELECT taux INTO taux 
    FROM bngrc_param_frais 
    WHERE est_actif = TRUE 
    ORDER BY date_application DESC 
    LIMIT 1;
END//
DELIMITER ;

-- Procédure pour vérifier les dons disponibles avant achat
DELIMITER //
CREATE PROCEDURE CheckDonsDisponiblesPourAchat(
    IN p_montant_necessaire DECIMAL(12,2),
    OUT p_total_disponible DECIMAL(12,2),
    OUT p_peut_acheter BOOLEAN
)
BEGIN
    SELECT SUM(quantite) INTO p_total_disponible
    FROM bngrc_don 
    WHERE id_type = 7 -- Argent
    AND est_utilise = FALSE;
    
    SET p_peut_acheter = (p_total_disponible >= p_montant_necessaire);
END//
DELIMITER ;

-- =====================================================
-- 9. TRIGGERS POUR LA COHÉRENCE
-- =====================================================

-- Trigger pour s'assurer que quantite_restante <= quantite
DELIMITER //
CREATE TRIGGER check_quantite_restante 
BEFORE INSERT ON bngrc_besoin
FOR EACH ROW
BEGIN
    IF NEW.quantite_restante > NEW.quantite THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'quantite_restante ne peut pas être supérieur à quantite';
    END IF;
END//
DELIMITER ;

-- Trigger pour mettre à jour automatiquement quantite_restante
DELIMITER //
CREATE TRIGGER update_quantite_restante
AFTER INSERT ON bngrc_distribution
FOR EACH ROW
BEGIN
    UPDATE bngrc_besoin 
    SET quantite_restante = quantite_restante - NEW.quantite
    WHERE id_ville = NEW.id_ville 
    AND id_type = NEW.id_type
    AND quantite_restante >= NEW.quantite;
END//
DELIMITER ;

-- =====================================================
-- FIN DU SCRIPT
-- =====================================================