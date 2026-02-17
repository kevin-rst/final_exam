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

    public function getMinBesoinByIdType($id_type) {
        $query = "SELECT * FROM bngrc_besoin WHERE id_type = ? ORDER BY quantite_restante, id_besoin";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $id_type ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBesoinsByIdType($id_type) {
        $query = "SELECT * FROM bngrc_besoin WHERE id_type = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_type]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBesoinById($id_besoin) {
        $query = "SELECT * FROM bngrc_besoin WHERE id_besoin = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $id_besoin ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateQuantiteRestante($id_besoin, $quantite_restante) {
        $query = "UPDATE bngrc_besoin SET quantite_restante = ? WHERE id_besoin = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $quantite_restante, $id_besoin ]);
    }

    public function resetQuantiteRestanteFromAchats() {
        $query = "UPDATE bngrc_besoin b
            LEFT JOIN (
                SELECT a.id_besoin, COALESCE(SUM(d.quantite), 0) AS quantite_achat
                FROM bngrc_achat a
                LEFT JOIN bngrc_distribution d
                    ON d.id_achat_source = a.id_achat
                    AND d.type_distribution = 'achat'
                GROUP BY a.id_besoin
            ) AS x ON x.id_besoin = b.id_besoin
            SET b.quantite_restante = b.quantite - COALESCE(x.quantite_achat, 0)";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
    }

    public function getBesoinDetails() {
        $query = "SELECT * FROM v_besoin_details WHERE quantite_restante > 0 ORDER BY date_saisie, id_besoin";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrixUnitaireByBesoinId($id_besoin) {
        $query = "SELECT t.prix_unitaire
            FROM bngrc_besoin b
            JOIN bngrc_type_besoin t ON t.id_type = b.id_type
            WHERE b.id_besoin = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $id_besoin ]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['prix_unitaire'];
    }
}