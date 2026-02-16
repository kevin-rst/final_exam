<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisis des besoins </title>
</head>

<body>

    <h2>Saisir les besoins</h2>
    <form action="<?= BASE_URL ?>/besoins/showForm" method="post">
        <label for="ville">Ville:</label>
        <select id="ville" name="ville">
            <?php foreach ($villes as $value): ?>
                <option value="<?= $value['id_ville'] ?>"><?= $value['nom'] ?></option>
            <?php endforeach; ?>
        </select>

        <br>
        <label for="type_besoin">Type de besoin:</label>
        <select id="type_besoin" name="type_besoin">
            <?php foreach ($types as $value): ?>
                <option value="<?= $value['id_type'] ?>"><?= $value['nom'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="quantite">Quantité:</label>
        <input type="number" id="quantite" name="quantite">
        <input type="date" id="date" name="date">
        <br>
        <input type="submit" value="Valider">
    </form>
</body>

</html>