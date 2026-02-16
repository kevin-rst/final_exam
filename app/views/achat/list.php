<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des achats</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/theme.css">
</head>

<body>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Liste des achats</h1>
            <p class="page-subtitle">Affichage de tous les achats validés et annulés (hors simulations).</p>
        </div>

        <div class="page-card">
            <?php if (count($achats) > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID Achat</th>
                            <th>Ville</th>
                            <th>Type de besoin</th>
                            <th>Quantité besoin</th>
                            <th>Quantité restante</th>
                            <th>Quantité achetée</th>
                            <th>Prix Unitaire</th>
                            <th>Montant Sous-total</th>
                            <th>Frais</th>
                            <th>Montant Total</th>
                            <th>Statut</th>
                            <th>Date Achat</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($achats as $achat): ?>
                            <tr>
                                <td><?= $achat['id_achat'] ?></td>
                                <td><?= $achat['nom_ville'] ?></td>
                                <td><?= $achat['nom_type'] ?></td>
                                <td><?= $achat['quantite'] ?></td>
                                <td><?= $achat['quantite_restante'] ?></td>
                                <td><?= $achat['quantite_achetee'] ?></td>
                                <td><?= $achat['prix_unitaire'] ?></td>
                                <td><?= $achat['montant_sous_total'] ?></td>
                                <td><?= $achat['montant_frais'] ?></td>
                                <td><?= $achat['montant_total'] ?></td>
                                <td><?= $achat['statut'] ?></td>
                                <td><?= $achat['date_achat'] ?></td>
                                <td><a href="<?= BASE_URL ?>/achats/validate/<?= $achat['id_achat'] ?>">Valider</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun achat trouvé.</p>
            <?php endif; ?>

            <br>
            <a href="<?= BASE_URL ?>/">Retour</a>
        </div>
    </div>

    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Basculer le theme">
        <span id="themeIcon" class="bi bi-sun-fill"></span>
        <span id="themeText">Mode clair</span>
    </button>

    <script src="<?= BASE_URL ?>/public/assets/js/theme.js"></script>
</body>

</html>