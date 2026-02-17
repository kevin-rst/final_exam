<?php

namespace app\service;

use app\models\BesoinModel;
use app\models\DistributionModel;
use app\models\DonModel;

class Dispatcher
{
    public static function dispatch($pdo, $method)
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

            switch($method) {
                case 'date_saisie':
                    $besoins = $besoinModel->getBesoinByIdType($idType);
                    break;
                case 'qte_min':
                    $besoins = $besoinModel->getMinBesoinByIdType($idType);
                    break;
                case 'proportion':
                    $besoins = $besoinModel->getAllBesoinsByIdType($idType);
                default:
                    break;
            }

            foreach ($besoins as $besoin) {
                if ($resteDon <= 0) break;

                $idBesoin = $besoin['id_besoin'];
                $idVille = $besoin['id_ville'];
                $quantiteRestante = $besoin['quantite_restante'] ?? $besoin['quantite'];
                $resteBesoin = $quantiteRestante;

                if ($resteBesoin <= 0) continue;

                if ($method === 'proportion') {
                    $quantite = floor($resteBesoin / $resteDon);
                } else {
                    $quantite = min($resteDon, $resteBesoin);
                }

                $distributionModel->createDistribution([
                    'don' => $idDon,
                    'ville' => $idVille,
                    'type' => $idType,
                    'quantite' => $quantite,
                    'achat_source' => null,
                    'type_distribution' => 'don_direct'
                ]);

                if ($method !== 'proportion') {
                    $resteDon -= $quantite;
                }

                $nouvelleQuantiteRestante = $resteBesoin - $quantite;
                $besoinModel->updateQuantiteRestante($idBesoin, $nouvelleQuantiteRestante);
            }

            if ($resteDon <= 0) {
                $donModel->setDonUtilise($idDon, true);
            }
        }
    }
}
