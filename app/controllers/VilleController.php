<?php

namespace app\controllers;

use flight\Engine;
use app\models\VilleModel;
use app\models\TypeModel;
use app\models\BesoinModel;

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

        $this->app->render('ville/ville', [
            'details' => $details
        ]);
    }
}
