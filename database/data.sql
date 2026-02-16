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

 public static function lancerDispatch($pdo) {

        // 1. Récupérer tous les dons par ordre chronologique
        $dons = $pdo->query("
            SELECT * FROM bngrc_don
            ORDER BY date_don ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dons as $don) {

            $idDon = $don['id_don'];
            $idType = $don['id_type'];
            $quantiteInitiale = $don['quantite'];

            // 2. Calculer quantité déjà distribuée pour ce don
            $stmt = $pdo->prepare("
                SELECT COALESCE(SUM(quantite),0) AS total
                FROM bngrc_distribution
                WHERE id_don = ?
            ");
            $stmt->execute([$idDon]);
            $dejaDistribue = $stmt->fetch()['total'];

            $resteDon = $quantiteInitiale - $dejaDistribue;

            if ($resteDon <= 0) continue;

            // 3. Récupérer les besoins du même type
            $stmt = $pdo->prepare("
                SELECT *
                FROM bngrc_besoin
                WHERE id_type = ?
                ORDER BY date_saisie ASC
            ");
            $stmt->execute([$idType]);
            $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($besoins as $besoin) {

                if ($resteDon <= 0) break;

                $idVille = $besoin['id_ville'];
                $quantiteBesoin = $besoin['quantite'];

                // 4. Calculer déjà distribué pour ce besoin
                $stmt2 = $pdo->prepare("
                    SELECT COALESCE(SUM(quantite),0) AS total
                    FROM bngrc_distribution
                    WHERE id_ville = ? AND id_type = ?
                ");
                $stmt2->execute([$idVille, $idType]);
                $dejaDistribueBesoin = $stmt2->fetch()['total'];

                $resteBesoin = $quantiteBesoin - $dejaDistribueBesoin;

                if ($resteBesoin <= 0) continue;

                // 5. Calcul quantité à distribuer
                $quantite = min($resteDon, $resteBesoin);

                // 6. Enregistrer distribution
                $insert = $pdo->prepare("
                    INSERT INTO bngrc_distribution
                    (id_don, id_ville, id_type, quantite)
                    VALUES (?, ?, ?, ?)
                ");
                $insert->execute([$idDon, $idVille, $idType, $quantite]);

                $resteDon -= $quantite;
            }
        }
 }
