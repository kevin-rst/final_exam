<?php 

namespace app\models;
use PDO;

class ProduitModel {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function getProduits() {
        $query = "SELECT * FROM Produits ORDER BY name";
        $stmt = $this->db->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduit($id) {
        $query = "SELECT * FROM Produits WHERE id = %d";
        $query = sprintf($query, $id);

        $stmt = $this->db->query($query);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createProduit($data) {
        $query = "INSERT INTO Produits (name, image, prix, description) VALUES ('%s', '%s', %f, '%s')";
        $query = sprintf($query, $data['name'], $data['image'], $data['prix'], $data['description']);

        $this->db->exec($query);
    }

    public function updateProduit($data) {
        $query = "UPDATE Produits SET name = '%s', image = '%s', prix = %f, description = '%s' WHERE id = %d";
        $query = sprintf($query, $data['name'], $data['image'], $data['prix'], $data['description'], $data['id']);

        $this->db->exec($query);
    }

    public function deleteProduit($id) {
        $query = "DELETE FROM Produits WHERE id = %d";
        $query = sprintf($query, $id);

        $this->db->exec($query);
    }

}