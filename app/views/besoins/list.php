<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des besoins</title>
</head>
<body>
    <?php if(isset($besoins) && !empty($besoins)): ?>
        <table>
            <thead>
                <tr>
                    <th>Région</th>
                    <th>Ville</th>
                    <th>Type de besoin</th>
                    <th>Quantité</th>
                    <th>Quantité restante</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($besoins as $besoin): ?>
                    <tr>
                        <td><?= $besoin['nom_region'] ?></td>
                        <td><?= $besoin['nom_ville'] ?></td>
                        <td><?= $besoin['nom_type'] ?></td>
                        <td><?= $besoin['quantite'] ?></td>
                        <td><?= $besoin['quantite_restante'] ?></td>
                        <td><?= $besoin['date'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun besoin enregistré.</p>
    <?php endif; ?>
</body>
</html>