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
        $query = "INSERT INTO bngrc_don (id_type, quantite, date_don) VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $data["type"], $data["quantite"], $data["date"] ]);
    }

    public function getAllDons() {
        $query = "SELECT * FROM bngrc_don ORDER BY date_don, id_don";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}