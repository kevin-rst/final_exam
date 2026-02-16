<?php

namespace app\models;
use PDO;

class ParamFraisModel {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getTaux() {
        $query = "SELECT taux FROM bngrc_param_frais ORDER BY date_application DESC, id_frais DESC LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
}
