<?php

namespace app\service;

use app\models\BesoinModel;
use app\models\DistributionModel;
use app\models\DonModel;

class Dispatcher
{

    public static function dispatch($pdo)
    {
        $donModel = new DonModel($pdo);
        $dons = $donModel->getAllDons();

        $distributionModel = new DistributionModel($pdo);
        $besoinModel = new BesoinModel($pdo);

        foreach ($dons as $don) {
            $idDon = $don['id_don'];
            $idType = $don['id_type'];
            $quantiteInitiale = $don['quantite'];

            $dejaDistribue = $distributionModel->getDistributedQuantity($idDon)['total'];

            $resteDon = $quantiteInitiale - $dejaDistribue;

            if ($resteDon <= 0) continue;

            $besoins = $besoinModel->getBesoinByIdType($idType);

            foreach ($besoins as $besoin) {
                if ($resteDon <= 0) break;

                $idVille = $besoin['id_ville'];
                $quantiteBesoin = $besoin['quantite'];

                $dejaDistribueBesoin = $distributionModel->getDistributionBy($idType, $idVille)['total'];

                $resteBesoin = $quantiteBesoin - $dejaDistribueBesoin;

                if ($resteBesoin <= 0) continue;

                $quantite = min($resteDon, $resteBesoin);

                $distributionModel->createDistribution([
                    'don' => $idDon,
                    'ville' => $idVille,
                    'type' => $idType,
                    'quantite' => $quantite
                ]);
            }
        }
    }
}
