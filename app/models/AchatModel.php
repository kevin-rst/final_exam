<?php 

namespace app\models;
use PDO;

class AchatModel {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createAchat($data) {
        $query = "INSERT INTO bngrc_achat (id_besoin, quantite_achetee, montant_sous_total, montant_frais, montant_total, frais_applique, statut) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ $data["id_besoin"], $data["quantite_achetee"], $data["montant_sous_total"], $data["montant_frais"], $data["montant_total"], $data["frais_applique"], "simule" ]);
    }
}