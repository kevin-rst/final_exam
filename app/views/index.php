<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Simulation Villes Sinistrées</title>
    <!-- Bootstrap 5 CSS local -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/theme.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/index.css">
</head>
<body class="home-page">
    <div class="main-container">
        <div class="page-layout">
            <aside class="sidebar">
                <div class="sidebar-card">
                    <div class="sidebar-brand">
                        <div class="brand-mark">BN</div>
                        <div class="brand-text">
                            <span class="brand-title">BNGRC</span>
                            <span class="brand-subtitle">Plateforme urgence</span>
                        </div>
                    </div>
                    <h2>Accès rapide</h2>
                    <nav class="sidebar-links" aria-label="Liens rapides">
                        <a href="<?= BASE_URL ?>/besoins/showForm" class="quick-link">Saisir les besoins</a>
                        <a href="<?= BASE_URL ?>/besoins/list" class="quick-link">Liste des besoins</a>
                        <a href="<?= BASE_URL ?>/dons/showForm" class="quick-link">Saisir les dons</a>
                        <a href="<?= BASE_URL ?>/dons/dispatch" class="quick-link">Simuler dispatch</a>
                        <a href="<?= BASE_URL ?>/ville/details" class="quick-link">Voir les détails des villes</a>
                        <a href="<?= BASE_URL ?>/achats/list" class="quick-link">Liste des achats</a>
                        <a href="<?= BASE_URL ?>/recap" class="quick-link">Récapitulatif</a>
                    </nav>
                </div>
            </aside>

            <div class="content-area">
                <!-- En-tête -->
                <div class="page-header">
                    <h1>SIMULATION URGENCE</h1>
                    <p>Plateforme de coordination des dons pour villes sinistrées</p>
                </div>

                <!-- Cartes de simulation -->
                <div class="simulation-grid">
                    <!-- Carte Besoins -->
                    <div class="simulation-card">
                        <div class="card-icon">
                            <svg viewBox="0 0 16 16">
                                <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793V2zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/>
                            </svg>
                        </div>
                        <h3>Besoins des villes</h3>
                        <p>Centralisez les besoins urgents et suivez l'évolution des demandes par ville.</p>
                        <div class="card-actions">
                            <a href="<?= BASE_URL ?>/besoins/showForm" class="card-action">Saisir les besoins</a>
                            <a href="<?= BASE_URL ?>/besoins/list" class="card-action">Liste des besoins</a>
                        </div>
                    </div>

                    <!-- Carte Dons -->
                    <div class="simulation-card">
                        <div class="card-icon">
                            <svg viewBox="0 0 16 16">
                                <path d="M2.5 2A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2h-11zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5z"/>
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 2.707V5.5a.5.5 0 0 1-1 0V2.707L6.354 3.854a.5.5 0 1 1-.708-.708l2-2z"/>
                            </svg>
                        </div>
                        <h3>Dons disponibles</h3>
                        <p>Enregistrez les dons et lancez la simulation d'attribution vers les villes prioritaires.</p>
                        <div class="card-actions">
                            <a href="<?= BASE_URL ?>/dons/showForm" class="card-action">Saisir les dons</a>
                            <a href="<?= BASE_URL ?>/dons/dispatch" class="card-action">Simuler dispatch</a>
                        </div>
                    </div>

                    <!-- Carte Villes -->
                    <div class="simulation-card">
                        <div class="card-icon">
                            <svg viewBox="0 0 16 16">
                                <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM2.04 4.326c.325 1.329 2.532 2.54 4.781 2.991.888.18 1.701.325 2.177.416.636.12 1.071.311 1.267.565.156.202.18.366.165.485-.02.164-.115.367-.373.61-.296.28-.77.488-1.448.555-.662.066-1.455-.01-2.403-.223-.917-.206-1.938-.605-2.993-1.165l.064-.305a30.71 30.71 0 0 0-.352-1.707c.868.457 1.786.81 2.66 1.027.977.242 1.87.33 2.594.282.365-.025.673-.08.913-.154.146-.045.271-.104.367-.179.023-.018.044-.037.062-.057.012-.014.023-.028.032-.043.006-.01.01-.02.013-.03.003-.01.005-.02.005-.03 0-.01-.002-.02-.005-.03-.004-.01-.008-.02-.015-.03-.013-.02-.04-.04-.084-.059-.082-.036-.214-.063-.38-.085-.34-.044-.825-.07-1.423-.058-.602.013-1.297.066-2.082.185-1.469.222-3.19.821-4.628 1.747l-.01.006C2.496 10.07 2 8.957 2 8c0-1.357.46-2.614 1.23-3.628l.81-.046z"/>
                            </svg>
                        </div>
                        <h3>Détails des villes</h3>
                        <p>Suivez la situation de chaque ville : besoins, dons reçus et urgences en cours.</p>
                        <div class="card-actions">
                            <a href="<?= BASE_URL ?>/ville/details" class="card-action">Voir les détails</a>
                        </div>
                    </div>

                    <!-- Carte Suivi -->
                    <div class="simulation-card">
                        <div class="card-icon">
                            <svg viewBox="0 0 16 16">
                                <path d="M2 1.5A1.5 1.5 0 0 1 3.5 0h9A1.5 1.5 0 0 1 14 1.5v13A1.5 1.5 0 0 1 12.5 16h-9A1.5 1.5 0 0 1 2 14.5v-13zM3.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-9z"/>
                                <path d="M5 4.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                        </div>
                        <h3>Suivi &amp; récap</h3>
                        <p>Consultez les achats validés et le récapitulatif global des besoins satisfaits.</p>
                        <div class="card-actions">
                            <a href="<?= BASE_URL ?>/achats/list" class="card-action">Liste des achats</a>
                            <a href="<?= BASE_URL ?>/recap" class="card-action">Récapitulatif</a>
                        </div>
                    </div>
                </div>

            <!-- Section d'information -->
            <div class="info-section">
            <div class="info-header">
                <div class="info-icon">
                    <svg viewBox="0 0 16 16">
                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                    </svg>
                </div>
                <h2>Simulation d'urgence</h2>
            </div>
            <p class="text-muted">Cette plateforme permet de simuler la coordination des dons vers les villes sinistrées. Les données saisies sont utilisées pour tester différents scénarios d'attribution et optimiser la répartition des ressources.</p>
            
            <!-- Statistiques simulées -->
            <div class="info-stats">
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Actions disponibles</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Simulation en continu</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Données fictives</div>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-content">
            <span>Simulation urgence - Coordination des dons</span>
            <div class="footer-links">
                <a href="#">ETU004079</a>
                <a href="#">ETU004170</a>
                <a href="#">ETU004360</a>
            </div>
        </div>
    </footer>

    <!-- Bouton mode sombre/clair -->
    <button class="theme-toggle" id="themeToggle">
        <span id="themeIcon" class="bi bi-sun-fill"></span>
        <span id="themeText">Mode clair</span>
    </button>

    <script src="<?= BASE_URL ?>/public/assets/js/theme.js"></script>
</body>
</html>