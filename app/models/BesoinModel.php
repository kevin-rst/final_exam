<?php 

namespace app\models;
use PDO;

class BesoinModel {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createBesoin($data) {
        $query = "INSERT INTO bngrc_besoin (id_ville, id_type, quantite, date_saisie) VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $data["ville"], $data["type_besoin"], $data["quantite"], $data["date"] ]);
    }

    public function getBesoinByIdType($id_type) {
        $query = "SELECT * FROM bngrc_besoin WHERE id_type = ? ORDER BY date_saisie, id_besoin";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $id_type ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}