<?php 

namespace app\models;
use PDO;

class TypeModel {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllTypes() {
        $query = "SELECT * FROM bngrc_type_besoin ORDER BY nom";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}