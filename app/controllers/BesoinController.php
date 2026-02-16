<?php

namespace app\controllers;

use flight\Engine;

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
            'cities' => $cities,
            'types' => $types
        ]);
    }
}