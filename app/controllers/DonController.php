<?php

namespace app\controllers;

use flight\Engine;
use app\models\TypeModel;
use app\models\DonModel;
use app\service\Dispatcher;

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

    public function create() {
        $pdo = $this->app->db();

        $data = $this->app->request()->data;
        $donModel = new DonModel($pdo);

        $donModel->createDon($data);

        $this->app->redirect('/');
    }

    public function dispatch() {
        $pdo = $this->app->db();

        Dispatcher::dispatch($pdo);

        $this->app->redirect('/');
    }
}