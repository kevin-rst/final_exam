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
}