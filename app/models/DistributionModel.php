<?php

namespace app\models;
use PDO;

class DistributionModel {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getDistributedQuantity($id_don) {
        $query = "SELECT  COALESCE(SUM(quantite), 0) as total FROM bngrc_distribution WHERE id_don = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $id_don ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDistributionBy($id_type, $id_ville) {
        $query = "SELECT COALESCE(SUM(quantite), 0) as total FROM bngrc_distribution WHERE id_type = ? AND id_ville = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $id_type, $id_ville ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createDistribution($data) {
        $query = "INSERT INTO bngrc_distribution (id_don, id_ville, id_type, quantite) VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $data["don"], $data["ville"], $data["type"], $data["quantite"] ]);
    }
}