<?php

namespace app\controllers;

use flight\Engine;
use app\models\RecapModel;

class RecapController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function index()
    {
        $pdo = $this->app->db();
        $model = new RecapModel($pdo);
        $recap = $model->getRecap();

        $this->app->render('recap/index', [
            'recap' => $recap
        ]);
    }

    public function data()
    {
        $pdo = $this->app->db();
        $model = new RecapModel($pdo);
        $recap = $model->getRecap();

        $this->app->json($recap ?? []);
    }
}
