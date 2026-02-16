<?php

namespace app\controllers;

use flight\Engine;
use app\models\VilleModel;

class VilleController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function getVilleDetails()
    {
        $pdo = $this->app->db();

        $villeModel = new VilleModel($pdo);
        $details = $villeModel->getCityDetails();

        $this->app->render('index', [
            'details' => $details
        ]);
    }
}
