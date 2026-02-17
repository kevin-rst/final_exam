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
                <?php
                    $totalBesoins = count($besoins);
                    $totalQuantite = 0;
                    $totalRestante = 0;
                    foreach ($besoins as $besoin) {
                        $totalQuantite += (int) $besoin['quantite'];
                        $totalRestante += (int) $besoin['quantite_restante'];
                    }
                ?>
                <div class="table-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793V2zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Besoins enregistrés</div>
                            <div class="stat-value"><?= $totalBesoins ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M2 1h12a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm1 2v2h10V3H3zm0 4v6h4V7H3zm6 0v6h4V7H9z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Quantité totale</div>
                            <div class="stat-value"><?= $totalQuantite ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM4.5 8a.5.5 0 0 1 .5-.5h3V4.5a.5.5 0 0 1 1 0V8a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Quantité restante</div>
                            <div class="stat-value"><?= $totalRestante ?></div>
                        </div>
                    </div>
                </div>
                <div class="table-scroll">
                    <table class="table-list">
                    <thead>
                        <tr>
                            <th>Région</th>
                            <th>Ville</th>
                            <th>Type de besoin</th>
                            <th class="cell-number">Quantité</th>
                            <th class="cell-number">Quantité restante</th>
                            <th class="cell-date">Date</th>
                            <th class="cell-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($besoins as $besoin): ?>
                            <tr>
                                <td><?= $besoin['nom_region'] ?></td>
                                <td><?= $besoin['nom_ville'] ?></td>
                                <td><?= $besoin['nom_type'] ?></td>
                                <td class="cell-number"><?= $besoin['quantite'] ?></td>
                                <td class="cell-number"><?= $besoin['quantite_restante'] ?></td>
                                <td class="cell-date"><?= $besoin['date_saisie'] ?></td>
                                <td class="cell-action">
                                    <div class="table-actions">
                                        <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/besoins/buy/<?= $besoin['id_besoin'] ?>">Acheter</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    </table>
                </div>
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