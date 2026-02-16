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
                    <table>
                        <tr>
                            <th>Besoin</th>
                            <th>Quantite</th>
                            <th>Quantite don</th>
                        </tr>
                        <tr>
                            <td><?= $ville["nom_type"] ?></td>
                            <td><?= $ville["quantite_besoin"] ?></td>
                            <td><?= $ville["quantite_don_attribue"] ?></td>
                        </tr>
                    </table>
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