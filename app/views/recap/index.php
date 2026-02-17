<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif - Besoins</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/theme.css">
</head>
<body>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Récapitulatif des besoins</h1>
            <p class="page-subtitle">Suivi global des besoins satisfaits et restants.</p>
        </div>

        <div class="page-card">
            <div class="recap-grid">
                <div class="recap-card">
                    <div class="recap-label">Besoins totaux</div>
                    <div class="recap-value" id="totalBesoins">
                        <?= $recap['total_besoins'] ?? 0 ?>
                    </div>
                </div>
                <div class="recap-card">
                    <div class="recap-label">Besoins satisfaits</div>
                    <div class="recap-value" id="totalSatisfait">
                        <?= $recap['total_satisfait'] ?? 0 ?>
                    </div>
                </div>
                <div class="recap-card">
                    <div class="recap-label">Besoins restants</div>
                    <div class="recap-value" id="totalRestant">
                        <?= $recap['total_restant'] ?? 0 ?>
                    </div>
                </div>
            </div>

            <div class="page-actions">
                <button class="btn btn-primary" id="refreshRecap" type="button">Actualiser</button>
                <span class="muted" id="recapStatus">Données à jour.</span>
            </div>
        </div>

        <div class="page-footer">
            <a class="back-button" href="<?= BASE_URL ?>/">Retour</a>
        </div>
    </div>

    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Basculer le theme">
        <span id="themeIcon" class="bi bi-sun-fill"></span>
        <span id="themeText">Mode clair</span>
    </button>

    <script src="<?= BASE_URL ?>/public/assets/js/theme.js"></script>
    <script id="recapScript" src="<?= BASE_URL ?>/public/assets/js/recap.js" data-base-url="<?= BASE_URL ?>"></script>
</body>
</html>
