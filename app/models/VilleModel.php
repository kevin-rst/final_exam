<?php 

namespace app\models;
use PDO;

class VilleModel {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllCities() {
        $query = "SELECT * FROM bngrc_ville ORDER BY nom";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCityDetail() {
        $query = "SELECT * FROM v_ville_details ORDER BY nom_ville, nom_type, date_distribution";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}