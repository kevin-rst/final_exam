<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des besoins</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/theme.css">
</head>
<body>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Liste des besoins</h1>
            <p class="page-subtitle">Consultez les besoins déclarés et lancez un achat.</p>
        </div>

        <div class="page-card">
            <?php if(isset($besoins) && !empty($besoins)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Région</th>
                            <th>Ville</th>
                            <th>Type de besoin</th>
                            <th>Quantité</th>
                            <th>Quantité restante</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($besoins as $besoin): ?>
                            <tr>
                                <td><?= $besoin['nom_region'] ?></td>
                                <td><?= $besoin['nom_ville'] ?></td>
                                <td><?= $besoin['nom_type'] ?></td>
                                <td><?= $besoin['quantite'] ?></td>
                                <td><?= $besoin['quantite_restante'] ?></td>
                                <td><?= $besoin['date_saisie'] ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/besoins/buy/<?= $besoin['id_besoin'] ?>">Acheter</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">Aucun besoin enregistré.</div>
            <?php endif; ?>
        </div>

        <div class="page-footer">
            <a class="back-button" href="<?= BASE_URL ?>/">Retour</a>
        </div>
    </div>

    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Basculer le theme">
        <span id="themeIcon" class="bi bi-sun-fill"></span>
        <span id="themeText">Mode clair</span>
    </button>

    <script src="<?= BASE_URL ?>/public/assets/js/theme.js" nonce="<?= Flight::app()->get('csp_nonce') ?>"></script>
</body>
</html>