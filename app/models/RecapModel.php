<?php

namespace app\models;
use PDO;

class RecapModel {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getRecap() {
        $query = "SELECT
            COALESCE(SUM(montant_total_besoin), 0) AS total_besoins,
            COALESCE(SUM(montant_satisfait), 0) AS total_satisfait,
            COALESCE(SUM(montant_total_besoin - montant_satisfait), 0) AS total_restant
        FROM v_besoin_details";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
