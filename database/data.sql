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


<?php
// Récupérer l'achat simulé
$achat = $pdo->query("
    SELECT * FROM bngrc_achat WHERE id_achat = $id_achat_simule
")->fetch();

// Récupérer les dons argent disponibles (encore une fois, pour être sûr)
$dons_argent = $pdo->query("
    SELECT id_don, quantite as montant, date_don 
    FROM bngrc_don 
    WHERE id_type = (SELECT id_type FROM bngrc_type_besoin WHERE nom = 'Argent')
    AND est_utilise = FALSE 
    ORDER BY date_don ASC
")->fetchAll();

// Commencer une transaction
$pdo->beginTransaction();

try {
    $montant_restant_a_prendre = $achat['montant_total'];
    
    // Parcourir les dons dans l'ordre (FIFO)
    foreach ($dons_argent as $don) {
        if ($montant_restant_a_prendre <= 0) break;
        
        // Calculer combien prendre de ce don
        $montant_a_prendre = min($don['montant'], $montant_restant_a_prendre);
        
        // === LIEN CRUCIAL : Enregistrer que ce don a servi pour cet achat ===
        $sql_lien = "INSERT INTO bngrc_achat_don 
                     (id_achat, id_don, montant_utilise) 
                     VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql_lien);
        $stmt->execute([$id_achat_simule, $don['id_don'], $montant_a_prendre]);
        
        // Mettre à jour le don
        if ($montant_a_prendre == $don['montant']) {
            // Cas 1: Le don est entièrement utilisé
            $sql_update_don = "UPDATE bngrc_don 
                               SET est_utilise = TRUE 
                               WHERE id_don = ?";
            $pdo->prepare($sql_update_don)->execute([$don['id_don']]);
        } else {
            // Cas 2: Le don est partiellement utilisé
            // On crée un nouveau don avec le montant restant
            $montant_restant_don = $don['montant'] - $montant_a_prendre;
            
            $sql_nouveau_don = "INSERT INTO bngrc_don 
                               (id_type, quantite, date_don, est_utilise) 
                               VALUES (
                                   (SELECT id_type FROM bngrc_type_besoin WHERE nom = 'Argent'),
                                   ?,
                                   NOW(),
                                   FALSE
                               )";
            $pdo->prepare($sql_nouveau_don)->execute([$montant_restant_don]);
            
            // Marquer l'ancien don comme utilisé
            $pdo->prepare("UPDATE bngrc_don SET est_utilise = TRUE WHERE id_don = ?")
                ->execute([$don['id_don']]);
        }
        
        $montant_restant_a_prendre -= $montant_a_prendre;
    }
    
    // Créer le don en nature (le riz acheté)
    $sql_don_nature = "INSERT INTO bngrc_don 
                       (id_type, quantite, date_don, id_achat_source, est_utilise) 
                       VALUES (
                           (SELECT id_type FROM bngrc_type_besoin WHERE nom = 'Riz'),
                           ?,
                           NOW(),
                           ?,
                           FALSE
                       )";
    $pdo->prepare($sql_don_nature)->execute([$achat['quantite_achetee'], $id_achat_simule]);
    $id_nouveau_don = $pdo->lastInsertId();
    
    // Distribuer ce don à la ville
    $sql_distribution = "INSERT INTO bngrc_distribution 
                         (id_don, id_ville, id_type, quantite, date_distribution, id_achat_source, type_distribution) 
                         VALUES (
                             ?,
                             (SELECT id_ville FROM bngrc_besoin WHERE id_besoin = ?),
                             (SELECT id_type FROM bngrc_besoin WHERE id_besoin = ?),
                             ?,
                             NOW(),
                             ?,
                             'achat'
                         )";
    $pdo->prepare($sql_distribution)->execute([
        $id_nouveau_don,
        $achat['id_besoin'],
        $achat['id_besoin'],
        $achat['quantite_achetee'],
        $id_achat_simule
    ]);
    
    // Mettre à jour le besoin
    $sql_besoin = "UPDATE bngrc_besoin 
                   SET quantite_restante = quantite_restante - ? 
                   WHERE id_besoin = ?";
    $pdo->prepare($sql_besoin)->execute([$achat['quantite_achetee'], $achat['id_besoin']]);
    
    // Valider l'achat
    $pdo->prepare("UPDATE bngrc_achat SET statut = 'valide' WHERE id_achat = ?")
        ->execute([$id_achat_simule]);
    
    $pdo->commit();
    
    echo "Achat validé avec succès !\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    throw $e;
}
?>