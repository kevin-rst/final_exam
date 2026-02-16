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
        $query = "INSERT INTO bngrc_besoin (id_ville, id_type, quantite, quantite_restante, date_saisie) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $data["ville"], $data["type_besoin"], $data["quantite"], $data["quantite"], $data["date"] ]);
    }

    public function getBesoinByIdType($id_type) {
        $query = "SELECT * FROM bngrc_besoin WHERE id_type = ? ORDER BY date_saisie, id_besoin";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $id_type ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateQuantiteRestante($id_besoin, $quantite_restante) {
        $query = "UPDATE bngrc_besoin SET quantite_restante = ? WHERE id_besoin = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $quantite_restante, $id_besoin ]);
    }

    public function getBesoinDetails() {
        $query = "SELECT * FROM v_besoin_dons_details WHERE quantite_restante > 0 ORDER BY date_saisie, id_besoin";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}