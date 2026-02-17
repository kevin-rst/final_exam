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
            <p class="page-subtitle">Affichage de tous les achats simulés.</p>
        </div>

        <div class="page-card">
            <?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
            <?php if (!empty($_SESSION['error_message'])): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['simulation_result'])): ?>
                <?php $sim = $_SESSION['simulation_result']; ?>
                <div class="modal" id="simModal" role="dialog" aria-modal="true" aria-labelledby="simTitle">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="simTitle">Résultat de la simulation</h3>
                            <button class="modal-close" id="simClose" aria-label="Fermer">&times;</button>
                        </div>
                        <div class="modal-body">
                            <?php if (empty($sim['success']) || $sim['success'] === false): ?>
                                <div class="alert alert-error"><?php echo htmlspecialchars($sim['message'] ?? 'Simulation: erreur'); ?></div>
                            <?php else: ?>
                                <p style="margin:0 0 0.5rem;"><strong>Montant total:</strong> <?= htmlspecialchars($sim['montant_total_achat']) ?></p>
                                <ul style="margin:0 0 0.5rem 1.2rem;">
                                    <?php foreach ($sim['consommation'] as $c): ?>
                                        <li>Don #<?= htmlspecialchars($c['id_don']) ?> — utilisera <?= htmlspecialchars($c['montant_utilise']) ?> (reste: <?= htmlspecialchars($c['reste_apres']) ?>)</li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if (!empty($sim['don_achat'])): ?>
                                    <p style="margin:0.25rem 0;"><strong>Don créé par l'achat:</strong> quantité <?= htmlspecialchars($sim['don_achat']['quantite']) ?></p>
                                    <p style="margin:0.25rem 0;"><strong>Quantité restante du besoin après l'achat:</strong> <?= htmlspecialchars($sim['nouvelle_quantite_restante_besoin']) ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- simulation modal script will be loaded from assets/js/simulate-modal.js -->
                <?php unset($_SESSION['simulation_result']); ?>
            <?php endif; ?>
            <?php if (!empty($villes)): ?>
                <form method="get" action="<?= BASE_URL ?>/achats/list" style="margin-bottom:1rem; display:flex; gap:0.5rem; align-items:center;">
                    <label for="ville">Filtrer par ville:</label>
                    <select name="ville" id="ville">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($villes as $v): ?>
                            <option value="<?= $v['id_ville'] ?>" <?= (isset($ville_selected) && $ville_selected == $v['id_ville']) ? 'selected' : '' ?>><?= htmlspecialchars($v['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary btn-sm" type="submit">Filtrer</button>
                    <?php if (!empty($ville_selected)): ?>
                        <a class="btn btn-outline btn-sm" href="<?= BASE_URL ?>/achats/list">Réinitialiser</a>
                    <?php endif; ?>
                </form>

                <?php
                    $totalAchats = isset($achats) ? count($achats) : 0;
                    $totalMontant = 0;
                    $totalQuantiteAchetee = 0;
                    if (!empty($achats)) {
                        foreach ($achats as $achat) {
                            $totalMontant += (float) $achat['montant_total'];
                            $totalQuantiteAchetee += (float) $achat['quantite_achetee'];
                        }
                    }
                ?>
                <div class="table-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h11A1.5 1.5 0 0 1 15 2.5v9a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 11.5v-9zM2.5 2a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-11z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Achats listés</div>
                            <div class="stat-value"><?= $totalAchats ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M0 2a2 2 0 0 1 2-2h5a2 2 0 0 1 2 2v2h2.5A1.5 1.5 0 0 1 13 5.5V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm2 0a.5.5 0 0 0-.5.5V4h7V2.5A.5.5 0 0 0 8 2H2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Quantité achetée</div>
                            <div class="stat-value"><?= $totalQuantiteAchetee ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 0a4 4 0 0 0-4 4v2H2.5A1.5 1.5 0 0 0 1 7.5V13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7.5A1.5 1.5 0 0 0 13.5 6H12V4a4 4 0 0 0-4-4zm-2 6V4a2 2 0 1 1 4 0v2H6z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Montant total</div>
                            <div class="stat-value"><?= number_format($totalMontant, 2, '.', ' ') ?></div>
                        </div>
                    </div>
                </div>
                <div class="table-scroll">
                    <table class="table-list">
                        <thead>
                            <tr>
                                <th>ID Achat</th>
                                <th>Ville</th>
                                <th>Type de besoin</th>
                                <th class="cell-number">Quantité besoin</th>
                                <th class="cell-number">Quantité restante</th>
                                <th class="cell-number">Quantité achetée</th>
                                <th class="cell-number">Prix Unitaire</th>
                                <th class="cell-number">Montant Sous-total</th>
                                <th class="cell-number">Frais</th>
                                <th class="cell-number">Montant Total</th>
                                <th>Statut</th>
                                <th class="cell-date">Date Achat</th>
                                <th class="cell-action">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($achats as $achat): ?>
                                <tr>
                                    <td><?= $achat['id_achat'] ?></td>
                                    <td><?= $achat['nom_ville'] ?></td>
                                    <td><?= $achat['nom_type'] ?></td>
                                    <td class="cell-number"><?= $achat['quantite'] ?></td>
                                    <td class="cell-number"><?= $achat['quantite_restante'] ?></td>
                                    <td class="cell-number"><?= $achat['quantite_achetee'] ?></td>
                                    <td class="cell-number"><?= $achat['prix_unitaire'] ?></td>
                                    <td class="cell-number"><?= $achat['montant_sous_total'] ?></td>
                                    <td class="cell-number"><?= $achat['montant_frais'] ?></td>
                                    <td class="cell-number"><?= $achat['montant_total'] ?></td>
                                    <td><?= $achat['statut'] ?></td>
                                    <td class="cell-date"><?= $achat['date_achat'] ?></td>
                                    <td class="cell-action">
                                        <div class="table-actions">
                                            <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/achats/validate/<?= $achat['id_achat'] ?>">Valider</a>
                                            <a class="btn btn-muted btn-sm" href="<?= BASE_URL ?>/achats/simulate/<?= $achat['id_achat'] ?>">Simuler</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">Aucun achat trouvé.</div>
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

    <script src="<?= BASE_URL ?>/public/assets/js/theme.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/simulate-modal.js"></script>
</body>

</html>