<?php

namespace app\service;

use app\models\BesoinModel;
use app\models\ParamFraisModel;

class AchatService
{
    private $repo;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    public function acheter($id_besoin, $quantite, $pdo) {
        $besoinRepo = new BesoinModel($pdo);
        $paramRepo = new ParamFraisModel($pdo);

        $prix_unitaire = $besoinRepo->getPrixUnitaireByBesoinId($id_besoin);
        $taux_frais = $paramRepo->getTaux();

        $sous_total = $quantite * $prix_unitaire;
        $montant_frais = $sous_total * ($taux_frais / 100);
        $total_achat = $sous_total + $montant_frais;

        $this->repo->createAchat([
            'id_besoin' => $id_besoin,
            'quantite_achetee' => $quantite,
            'montant_sous_total' => $sous_total,
            'montant_frais' => $montant_frais,
            'montant_total' => $total_achat,
            'frais_applique' => $taux_frais
        ]);
    }
}