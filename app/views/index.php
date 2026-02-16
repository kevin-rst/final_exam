<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <h1>DASHBOARD</h1>

    <?php if ( isset($details) ) {
        foreach ( $details as $ville ) { ?>
            <p><?= $ville['nom_ville'] ?></p>

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
        <?php }
    } ?>

    <a href="<?= BASE_URL ?>/besoins/showForm">Saisir les besoins</a>
    <a href="<?= BASE_URL ?>/dons/showForm">Saisir les dons</a>
    <a href="<?= BASE_URL ?>/dons/dispatch">Simuler dispatch</a>
</body>
</html>