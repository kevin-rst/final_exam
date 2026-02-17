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
                    break;
                default:
                    break;
            }

            if ($method === 'proportion') {
                $besoinsFiltres = [];
                $totalBesoin = 0;

                foreach ($besoins as $besoin) {
                    $quantiteRestante = $besoin['quantite_restante'] ?? $besoin['quantite'];
                    $resteBesoin = (int) $quantiteRestante;

                    if ($resteBesoin <= 0) {
                        continue;
                    }

                    $besoinsFiltres[] = [
                        'id_besoin' => $besoin['id_besoin'],
                        'id_ville' => $besoin['id_ville'],
                        'reste_besoin' => $resteBesoin,
                    ];
                    $totalBesoin += $resteBesoin;
                }

                if ($totalBesoin > 0) {
                    $allocations = [];
                    $quantiteDistribuee = 0;

                    foreach ($besoinsFiltres as $besoin) {
                        $raw = ($resteDon * $besoin['reste_besoin']) / $totalBesoin;
                        $quantite = (int) floor($raw);
                        if ($quantite > $besoin['reste_besoin']) {
                            $quantite = $besoin['reste_besoin'];
                        }
                        $decimal = $raw - $quantite;

                        $allocations[] = [
                            'id_besoin' => $besoin['id_besoin'],
                            'id_ville' => $besoin['id_ville'],
                            'reste_besoin' => $besoin['reste_besoin'],
                            'quantite' => $quantite,
                            'decimal' => $decimal,
                        ];
                        $quantiteDistribuee += $quantite;
                    }

                    $resteARepartir = $resteDon - $quantiteDistribuee;
                    if ($resteARepartir > 0) {
                        usort($allocations, function ($a, $b) {
                            if ($a['decimal'] === $b['decimal']) {
                                return $a['id_besoin'] <=> $b['id_besoin'];
                            }
                            return $b['decimal'] <=> $a['decimal'];
                        });

                        $didAllocate = true;
                        while ($resteARepartir > 0 && $didAllocate) {
                            $didAllocate = false;
                            foreach ($allocations as $index => $allocation) {
                                if ($resteARepartir <= 0) {
                                    break;
                                }
                                if ($allocation['quantite'] < $allocation['reste_besoin']) {
                                    $allocations[$index]['quantite'] += 1;
                                    $resteARepartir -= 1;
                                    $didAllocate = true;
                                }
                            }
                        }
                    }

                    $totalDistribue = 0;
                    foreach ($allocations as $allocation) {
                        if ($allocation['quantite'] <= 0) {
                            continue;
                        }

                        $distributionModel->createDistribution([
                            'don' => $idDon,
                            'ville' => $allocation['id_ville'],
                            'type' => $idType,
                            'quantite' => $allocation['quantite'],
                            'achat_source' => null,
                            'type_distribution' => 'don_direct'
                        ]);

                        $nouvelleQuantiteRestante = $allocation['reste_besoin'] - $allocation['quantite'];
                        $besoinModel->updateQuantiteRestante($allocation['id_besoin'], $nouvelleQuantiteRestante);

                        $totalDistribue += $allocation['quantite'];
                    }

                    $resteDon -= $totalDistribue;
                }
            } else {
                foreach ($besoins as $besoin) {
                    if ($resteDon <= 0) break;

                    $idBesoin = $besoin['id_besoin'];
                    $idVille = $besoin['id_ville'];
                    $quantiteRestante = $besoin['quantite_restante'] ?? $besoin['quantite'];
                    $resteBesoin = $quantiteRestante;

                    if ($resteBesoin <= 0) continue;

                    $quantite = min($resteDon, $resteBesoin);

                    $distributionModel->createDistribution([
                        'don' => $idDon,
                        'ville' => $idVille,
                        'type' => $idType,
                        'quantite' => $quantite,
                        'achat_source' => null,
                        'type_distribution' => 'don_direct'
                    ]);

                    $resteDon -= $quantite;

                    $nouvelleQuantiteRestante = $resteBesoin - $quantite;
                    $besoinModel->updateQuantiteRestante($idBesoin, $nouvelleQuantiteRestante);
                }
            }

            if ($resteDon <= 0) {
                $donModel->setDonUtilise($idDon, true);
            }
        }
    }
}
