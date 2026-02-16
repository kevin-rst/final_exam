<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisis des besoins </title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/theme.css">
</head>

<body>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Saisir les besoins</h1>
            <p class="page-subtitle">Déclarez les besoins urgents des villes sinistrées.</p>
        </div>

        <div class="page-card">
            <h2 class="section-title">Formulaire</h2>
            <form action="<?= BASE_URL ?>/besoins/create" method="post" class="form-grid">
                <div class="form-row">
                    <label for="ville">Ville</label>
                    <select id="ville" name="ville">
            <?php foreach ($villes as $value): ?>
                <option value="<?= $value['id_ville'] ?>"><?= $value['nom'] ?></option>
            <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label for="type_besoin">Type de besoin</label>
                    <select id="type_besoin" name="type_besoin">
            <?php foreach ($types as $value): ?>
                <option value="<?= $value['id_type'] ?>"><?= $value['nom'] ?></option>
            <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label for="quantite">Quantite</label>
                    <input type="number" id="quantite" name="quantite" min="0">
                </div>

                <div class="form-row">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date">
                </div>

                <div class="form-actions">
                    <input type="submit" value="Valider">
                </div>
            </form>
        </div>

        <div class="page-footer">
            <a class="back-button" href="<?= BASE_URL ?>">Retour</a>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-content">
            <span>Simulation urgence - Formulaire besoins</span>
            <div class="footer-links">
                <a href="<?= BASE_URL ?>">ETU004079</a>
                <a href="<?= BASE_URL ?>/dons/showForm">ETU004170</a>
                <a href="<?= BASE_URL ?>/ville/details">ETU004360</a>
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