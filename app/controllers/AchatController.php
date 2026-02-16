<?php

namespace app\controllers;

use app\models\AchatModel;
use app\service\AchatService;
use flight\Engine;

class AchatController
{

    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function acheter()
    {
        $pdo = $this->app->db();

        $data = $this->app->request()->data;

        $model = new AchatModel($pdo);
        $svc = new AchatService($model);
        $svc->acheter($data["id_besoin"], $data["quantite"], $pdo);

        $this->app->redirect('/');
    }
}
