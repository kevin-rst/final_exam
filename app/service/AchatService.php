<?php

namespace app\service;

use app\models\AchatModel;
use app\models\BesoinModel;
use app\models\DonModel;
use app\models\DistributionModel;
use app\models\ParamFraisModel;
use app\models\TypeModel;

class AchatService
{
    private $repo;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    public function acheter($id_besoin, $quantite, $pdo)
    {
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

    public function validerAchat($id_achat, $pdo)
    {
        $achat = $this->repo->getById($id_achat);

        if (!$achat) {
            return;
        }

        $donModel = new DonModel($pdo);
        $typeModel = new TypeModel($pdo);
        $besoinModel = new BesoinModel($pdo);
        $distributionModel = new DistributionModel($pdo);

        $type_argent = $typeModel->getByNom("Don en argent");
        $dons_argent = $type_argent ? $donModel->getByType($type_argent['id_type']) : [];

        // Vérifier que le total disponible dans les dons en argent couvre le montant total de l'achat
        $total_disponible = 0;
        foreach ($dons_argent as $donCheck) {
            $total_disponible += $donModel->getRemainQuantity($donCheck['id_don']);
        }

        if ($total_disponible < $achat['montant_total']) {
            return [
                'success' => false,
                'message' => "Fonds insuffisants pour valider cet achat."
            ];
        }

        $montant_restant_a_prendre = $achat['montant_total'];

        foreach ($dons_argent as $don) {
            if ($montant_restant_a_prendre <= 0) {
                break;
            }

            $quantite_restante_don = $donModel->getRemainQuantity($don['id_don']);
            $montant_a_prendre = min($quantite_restante_don, $montant_restant_a_prendre);

            if ($montant_a_prendre <= 0) {
                continue;
            }

            $this->repo->insertAchatDon([
                'id_achat' => $id_achat,
                'id_don' => $don['id_don'],
                'montant_utilise' => $montant_a_prendre,
                'date_liaison' => date('Y-m-d H:i:s')
            ]);

            if ($montant_a_prendre == $quantite_restante_don) {
                $donModel->setDonUtilise($don['id_don'], true);
            } else {
                $montant_restant_don = $quantite_restante_don - $montant_a_prendre;

                $donModel->createDon([
                    'type' => $don['id_type'],
                    'quantite' => $montant_restant_don,
                    'date' => date('Y-m-d H:i:s')
                ]);

                $donModel->setDonUtilise($don['id_don'], true);
            }

            $montant_restant_a_prendre -= $montant_a_prendre;
        }

        $besoin = $besoinModel->getBesoinById($achat['id_besoin']);

        if ($besoin) {
            $idDonAchat = $donModel->createDon([
                'type' => $besoin['id_type'],
                'quantite' => $achat['quantite_achetee'],
                'date' => date('Y-m-d H:i:s'),
                'achat_source' => $id_achat
            ]);

            $distributionModel->createDistribution([
                'don' => $idDonAchat,
                'ville' => $besoin['id_ville'],
                'type' => $besoin['id_type'],
                'quantite' => $achat['quantite_achetee'],
                'achat_source' => $id_achat,
                'type_distribution' => 'achat'
            ]);

            $nouvelleQuantiteRestante = max(0, $besoin['quantite_restante'] - $achat['quantite_achetee']);
            $besoinModel->updateQuantiteRestante($besoin['id_besoin'], $nouvelleQuantiteRestante);
        }

        $this->repo->setAchatValide($id_achat);
    }

    /**
     * Simuler une validation d'achat sans modifier la base.
     * Retourne le détail des dons qui seraient utilisés et l'état final attendu.
     */
    public function simulerAchat($id_achat, $pdo)
    {
        $achat = $this->repo->getById($id_achat);

        if (!$achat) {
            return [ 'success' => false, 'message' => 'Achat introuvable.' ];
        }

        $donModel = new DonModel($pdo);
        $typeModel = new TypeModel($pdo);
        $besoinModel = new BesoinModel($pdo);

        $type_argent = $typeModel->getByNom("Don en argent");
        $dons_argent = $type_argent ? $donModel->getByType($type_argent['id_type']) : [];

        // Calculer total disponible
        $total_disponible = 0;
        foreach ($dons_argent as $donCheck) {
            $total_disponible += $donModel->getRemainQuantity($donCheck['id_don']);
        }

        if ($total_disponible < $achat['montant_total']) {
            return [ 'success' => false, 'message' => "Fonds insuffisants pour valider cet achat." ];
        }

        $montant_restant_a_prendre = $achat['montant_total'];
        $consommation = [];

        foreach ($dons_argent as $don) {
            if ($montant_restant_a_prendre <= 0) {
                break;
            }

            $quantite_restante_don = $donModel->getRemainQuantity($don['id_don']);
            $montant_a_prendre = min($quantite_restante_don, $montant_restant_a_prendre);

            if ($montant_a_prendre <= 0) {
                continue;
            }

            $consommation[] = [
                'id_don' => $don['id_don'],
                'montant_disponible' => $quantite_restante_don,
                'montant_utilise' => $montant_a_prendre,
                'reste_apres' => $quantite_restante_don - $montant_a_prendre
            ];

            $montant_restant_a_prendre -= $montant_a_prendre;
        }

        $besoin = $besoinModel->getBesoinById($achat['id_besoin']);

        $result = [
            'success' => true,
            'consommation' => $consommation,
            'montant_total_achat' => $achat['montant_total']
        ];

        if ($besoin) {
            $result['don_achat'] = [
                'type' => $besoin['id_type'],
                'quantite' => $achat['quantite_achetee']
            ];

            $result['nouvelle_quantite_restante_besoin'] = max(0, $besoin['quantite_restante'] - $achat['quantite_achetee']);
        }

        return $result;
    }
}
