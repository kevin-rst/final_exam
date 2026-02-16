<?php

namespace app\controllers;

use flight\Engine;
use app\models\VilleModel;
use app\models\TypeModel;
use app\models\BesoinModel;

class BesoinController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

    public function showForm() {
        $pdo = $this->app->db();

        $cityModel = new VilleModel($pdo);
        $cities = $cityModel->getAllCities();

        $typeModel= new TypeModel($pdo);
        $types = $typeModel->getAllTypes();

        $this->app->render('besoins/form.php', [  
            'villes' => $cities,
            'types' => $types
        ]);
    }

    public function create() {
        $pdo = $this->app->db();

        $data = $this->app->request()->data;

        $besoinModel = new BesoinModel($pdo);
        $besoinModel->createBesoin($data);

        $this->app->redirect('/');
    }

    public function getBesoinDetails() {
        $pdo = $this->app->db();

        $besoinModel = new BesoinModel($pdo);
        $details = $besoinModel->getBesoinDetails();

        $this->app->render('besoins/list', [
            'besoins' => $details
        ]);
    }
}