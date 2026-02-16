<?php

namespace app\controllers;

use flight\Engine;
use app\models\TypeModel;

class DonController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

    public function showForm() {
        $pdo = $this->app->db();

        $typeModel= new TypeModel($pdo);
        $types = $typeModel->getAllTypes();

        $this->app->render('dons/form.php', [
            'types' => $types
        ]);
    }
}