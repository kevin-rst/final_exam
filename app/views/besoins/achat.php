<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achat-Besoin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/theme.css">
</head>
<body>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Acheter un besoin</h1>
            <p class="page-subtitle">Saisissez la quantité à acheter pour ce besoin.</p>
        </div>

        <div class="page-card">
            <h2 class="section-title">Formulaire d'achat</h2>
            <form action="<?= BASE_URL ?>/achats/buy" method="post" class="form-grid">
                <div class="form-row">
                    <label for="quantite">Quantité à acheter</label>
                    <input type="number" id="quantite" name="quantite" min="1" required>
                </div>

                <input type="hidden" name="id_besoin" value="<?= $id_besoin ?>">

                <div class="form-actions">
                    <input type="submit" value="Acheter">
                </div>
            </form>
        </div>

        <div class="page-footer">
            <a class="back-button" href="<?= BASE_URL ?>/besoins/list">Retour</a>
        </div>
    </div>

    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Basculer le theme">
        <span id="themeIcon" class="bi bi-sun-fill"></span>
        <span id="themeText">Mode clair</span>
    </button>

    <script src="<?= BASE_URL ?>/public/assets/js/theme.js" nonce="<?= Flight::app()->get('csp_nonce') ?>"></script>
</body>
</html>