<?php

namespace app\controllers;

use flight\Engine;
use app\models\ProduitModel;
use Flight;

class ProduitController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function getProduits() {
        $produitModel = new ProduitModel($this->app->db());
        $produits = $produitModel->getProduits();

        $this->app->render('index', [ 'produits' => $produits ] );
    }
    
    public function getProduit($id) {
        $produitModel = new ProduitModel($this->app->db());
        $produit = $produitModel->getProduit($id);

        $this->app->render('produit', [ 'produit' => $produit ]);
    }

    public function findProduit($id) {
        $produitModel = new ProduitModel($this->app->db());
        $produit = $produitModel->getProduit($id);

        $this->app->render('formulaire', [ 'produit' => $produit ]);
    }

    public function cuProduit() {
        $data = $this->app->request()->data;
        $data['image'] = $this->upload($_FILES['image']);

        $produitModel = new ProduitModel($this->app->db());
        if (empty($data['id'])) {
            $produitModel->createProduit($data);
        } else {
            $produitModel->updateProduit($data);
        }

        $this->app->redirect('/');
    }

    public function deleteProduit($id) {
        $produitModel = new ProduitModel($this->app->db());
        $produitModel->deleteProduit($id);

        $this->app->redirect('/');
    }

    public function upload($file)
    {
        $uploadDir =  __DIR__ . '/../../public/images/';
        $maxSize = 50 * 1024 * 1024;
        $allowedMimeTypes = ['image/jpeg', 'image/webp', 'image/jpg', 'image/png', 'image/img'];
        
        if( $file['error'] !== UPLOAD_ERR_OK )
        {
            die( 'Erreur lors de l’upload : ' . $file['error'] );
        }
    
        if( $file['size'] > $maxSize )
        {
            die( 'Le fichier est trop volumineux.' );
        }
    
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if( !in_array( $mime, $allowedMimeTypes ) )
        {
            die( 'Type de fichier non autorisé : ' . $mime );
        }
    
        $originalName = pathinfo( $file['name'], PATHINFO_FILENAME );
        $extension = pathinfo( $file['name'], PATHINFO_EXTENSION );
        $newName = $originalName . '_' . uniqid() . '.' . $extension;
    
        if( !move_uploaded_file( $file['tmp_name'], $uploadDir . $newName ) )
        {
            die( 'Erreur lors du déplacement du fichier' );
        }

        return $newName;
    }
}