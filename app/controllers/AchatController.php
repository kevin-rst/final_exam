<?php

namespace app\controllers;

use app\models\AchatModel;
use app\models\VilleModel;
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

    public function getAllAchats() {
        $pdo = $this->app->db();
        $achatModel = new AchatModel($pdo);

        $villeFilter = $this->app->request()->query['ville'] ?? null;

        $achats = $achatModel->getAllAchats($villeFilter);

        $villeModel = new VilleModel($pdo);
        $villes = $villeModel->getAllCities();

        $this->app->render('achat/list', [ 'achats' => $achats, 'villes' => $villes, 'ville_selected' => $villeFilter ] );
    }

    public function validerAchat($id)
    {
        $pdo = $this->app->db();

        $model = new AchatModel($pdo);
        $svc = new AchatService($model);
        $result = $svc->validerAchat($id, $pdo);

        if (is_array($result) && isset($result['success']) && $result['success'] === false) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['error_message'] = $result['message'] ?? 'Erreur lors de la validation.';
        }

        $this->app->redirect('/achats/list');
    }

    public function simulerAchat($id)
    {
        $pdo = $this->app->db();

        $model = new AchatModel($pdo);
        $svc = new AchatService($model);
        $result = $svc->simulerAchat($id, $pdo);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['simulation_result'] = $result;

        $this->app->redirect('/achats/list');
    }
}
