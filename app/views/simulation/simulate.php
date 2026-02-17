<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/theme.css">
</head>
<body>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Dispatch des dons</h1>
            <p class="page-subtitle">Choisissez la methode de distribution des dons.</p>
        </div>

        <div class="page-card">
            <h2 class="section-title">Methode de dispatch</h2>
            <form action="<?= BASE_URL ?>/dons/dispatch" method="post" class="form-grid">
                <div class="form-row">
                    <label>
                        <input type="radio" name="method" value="date_saisie"> Date
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <input type="radio" name="method" value="qte_min"> Quantite
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <input type="radio" name="method" value="proportion"> Proportion
                    </label>
                </div>

                <div class="form-actions">
                    <input type="submit" value="Valider">
                </div>
            </form>
        </div>

        <div class="page-footer">
            <a class="back-button" href="<?= BASE_URL ?>/">Retour</a>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-content">
            <span>Simulation urgence - Dispatch des dons</span>
            <div class="footer-links">
                <a href="<?= BASE_URL ?>">ETU004079</a>
                <a href="<?= BASE_URL ?>/besoins/showForm">ETU004170</a>
                <a href="<?= BASE_URL ?>/ville/details">ETU004360</a>
            </div>
        </div>
    </footer>

    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Basculer le theme">
        <span id="themeIcon" class="bi bi-sun-fill"></span>
        <span id="themeText">Mode clair</span>
    </button>

    <script src="<?= BASE_URL ?>/public/assets/js/theme.js"></script>
</body>
</html>