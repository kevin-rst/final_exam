<?php 

namespace app\models;
use PDO;

class DonModel {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createDon($data) {
        $query = "INSERT INTO bngrc_don (id_type, quantite, date_don, id_achat_source) VALUES (?, ?, ?, ?)";

        $achatSource = $data["achat_source"] ?? null;

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $data["type"], $data["quantite"], $data["date"], $achatSource ]);

        return $this->db->lastInsertId();
    }

    public function getAllDons() {
        $query = "SELECT * FROM bngrc_don ORDER BY date_don, id_don";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setDonUtilise($id_don, $est_utilise) {
        $query = "UPDATE bngrc_don SET est_utilise = ? WHERE id_don = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ (int) $est_utilise, $id_don ]);
    }

    public function getByType($type) {
        $query = "SELECT * FROM bngrc_don WHERE id_type = ? AND est_utilise = FALSE ORDER BY date_don, id_don";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $type ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRemainQuantity($id_don) {
        $query = "SELECT 
            d.quantite,
            COALESCE(SUM(dist.quantite), 0) AS quantite_distribuee
        FROM bngrc_don d
        LEFT JOIN bngrc_distribution dist ON d.id_don = dist.id_don
        WHERE d.id_don = ?
        GROUP BY d.id_don";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $id_don ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['quantite'] - $result['quantite_distribuee'];
        }
        return 0;
    }

    public function resetUtiliseForNonAchats() {
        $query = "UPDATE bngrc_don d
            LEFT JOIN bngrc_achat_don ad ON ad.id_don = d.id_don
            SET d.est_utilise = 0
            WHERE d.id_achat_source IS NULL
            AND ad.id_don IS NULL";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
    }
}