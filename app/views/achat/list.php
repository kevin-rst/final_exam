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

                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Achat</th>
                                <th>Ville</th>
                                <th>Type de besoin</th>
                                <th>Quantité besoin</th>
                                <th>Quantité restante</th>
                                <th>Quantité achetée</th>
                                <th>Prix Unitaire</th>
                                <th>Montant Sous-total</th>
                                <th>Frais</th>
                                <th>Montant Total</th>
                                <th>Statut</th>
                                <th>Date Achat</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($achats as $achat): ?>
                                <tr>
                                    <td><?= $achat['id_achat'] ?></td>
                                    <td><?= $achat['nom_ville'] ?></td>
                                    <td><?= $achat['nom_type'] ?></td>
                                    <td><?= $achat['quantite'] ?></td>
                                    <td><?= $achat['quantite_restante'] ?></td>
                                    <td><?= $achat['quantite_achetee'] ?></td>
                                    <td><?= $achat['prix_unitaire'] ?></td>
                                    <td><?= $achat['montant_sous_total'] ?></td>
                                    <td><?= $achat['montant_frais'] ?></td>
                                    <td><?= $achat['montant_total'] ?></td>
                                    <td><?= $achat['statut'] ?></td>
                                    <td><?= $achat['date_achat'] ?></td>
                                    <td>
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