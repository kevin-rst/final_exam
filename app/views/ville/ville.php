<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/theme.css">
</head>
<body>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Details des villes</h1>
            <p class="page-subtitle">Suivez les besoins et les dons attribues par ville.</p>
        </div>

        <?php if ( isset($details) ) {
            foreach ( $details as $ville ) { ?>
                <div class="page-card" style="margin-bottom: 1.5rem;">
                    <h2 class="section-title"><?= $ville['nom_ville'] ?></h2>
                    <?php
                        $totalBesoin = (int) ($ville['quantite_besoin'] ?? 0);
                        $totalDon = (int) ($ville['quantite_don_attribue'] ?? 0);
                        $restant = max($totalBesoin - $totalDon, 0);
                    ?>
                    <div class="table-stats">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <svg viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793V2zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="stat-label">Besoin</div>
                                <div class="stat-value"><?= $totalBesoin ?></div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <svg viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M2.5 2A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2h-11zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5z"/>
                                    <path d="M7.646 1.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 2.707V5.5a.5.5 0 0 1-1 0V2.707L6.354 3.854a.5.5 0 1 1-.708-.708l2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="stat-label">Don attribué</div>
                                <div class="stat-value"><?= $totalDon ?></div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <svg viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM4.5 8a.5.5 0 0 1 .5-.5h3V4.5a.5.5 0 0 1 1 0V8a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="stat-label">Reste à couvrir</div>
                                <div class="stat-value"><?= $restant ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="table-scroll">
                        <table class="table-list">
                            <tr>
                                <th>Besoin</th>
                                <th class="cell-number">Quantite</th>
                                <th class="cell-number">Quantite don</th>
                            </tr>
                            <tr>
                                <td><?= $ville["nom_type"] ?></td>
                                <td class="cell-number"><?= $ville["quantite_besoin"] ?></td>
                                <td class="cell-number"><?= $ville["quantite_don_attribue"] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php }
        } ?>

        <div class="page-footer">
            <a class="back-button" href="<?= BASE_URL ?>/">Retour</a>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-content">
            <span>Simulation urgence - Details des villes</span>
            <div class="footer-links">
                <a href="<?= BASE_URL ?>">ETU004079</a>
                <a href="<?= BASE_URL ?>/besoins/showForm">ETU004170</a>
                <a href="<?= BASE_URL ?>/dons/showForm">ETU004360</a>
            </div>
        </div>
    </footer>

    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Basculer le theme">
        <span id="themeIcon" class="bi bi-sun-fill"></span>
        <span id="themeText">Mode clair</span>
    </button>

    <script src="<?= BASE_URL ?>/public/assets/js/theme.js" nonce="<?= Flight::app()->get('csp_nonce') ?>"></script>
</body>
</html>