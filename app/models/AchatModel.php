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

    public function getAllAchats($id_ville = null) {
        $query = "SELECT 
                    a.id_achat,
                    a.id_besoin,
                    a.quantite_achetee,
                    a.montant_sous_total,
                    a.montant_frais,
                    a.montant_total,
                    a.frais_applique,
                    a.date_achat,
                    a.statut,
                    a.date_validation,
                    a.date_annulation,
                    b.id_ville,
                    b.id_type,
                    b.quantite,
                    b.quantite_restante,
                    b.date_saisie,
                    v.nom AS nom_ville,
                    t.nom AS nom_type,
                    t.prix_unitaire
                FROM bngrc_achat a
                JOIN bngrc_besoin b ON a.id_besoin = b.id_besoin
                JOIN bngrc_ville v ON b.id_ville = v.id_ville
                JOIN bngrc_type_besoin t ON b.id_type = t.id_type
                WHERE a.statut = ?";

        if ($id_ville !== null && $id_ville !== '') {
            $query .= " AND b.id_ville = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["simule", $id_ville]);
        } else {
            $stmt = $this->db->prepare($query);
            $stmt->execute(["simule"]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id_achat) {
        $query = "SELECT * FROM bngrc_achat WHERE id_achat = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_achat]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertAchatDon($data) {
        $query = "INSERT INTO bngrc_achat_don (id_achat, id_don, montant_utilise, date_liaison) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        
        $stmt->execute([ $data["id_achat"], $data["id_don"], $data["montant_utilise"], $data["date_liaison"] ]);
    }

    public function setAchatValide($id_achat) {
        $query = "UPDATE bngrc_achat SET statut = 'valide', date_validation = ? WHERE id_achat = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([ date('Y-m-d H:i:s'), $id_achat ]);
    }
}