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

        $this->app->render('ville/ville', [
            'details' => $details
        ]);
    }

    public function resetSimulation()
    {
        $pdo = $this->app->db();

        $pdo->beginTransaction();

        try {
            $pdo->exec("DELETE FROM bngrc_distribution");
            $pdo->exec("DELETE FROM bngrc_achat_don");
            $pdo->exec("DELETE FROM bngrc_don");
            $pdo->exec("DELETE FROM bngrc_achat");
            $pdo->exec("DELETE FROM bngrc_besoin");

            $seedPath = dirname(__DIR__, 2) . '/database/test.sql';
            $seedSql = file_get_contents($seedPath);

            if ($seedSql !== false) {
                $statements = array_filter(array_map('trim', explode(';', $seedSql)));
                foreach ($statements as $statement) {
                    if ($statement === '' || str_starts_with(ltrim($statement), '--')) {
                        continue;
                    }
                    $pdo->exec($statement);
                }
            }

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }

        $this->app->redirect('/ville/details');
    }
}
