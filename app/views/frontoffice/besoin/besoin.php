<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisis des besoins </title>
</head>
<body>

<h2>Saisir les besoins</h2>
    <form action="<?= BASE_URL ?>/" method="post">
        <label for="ville">Ville:</label>
            <select id="ville" name="ville">
                <?php foreach ($ville as $value): ?>
                <option value="<?= $value ?>"><?= $value ?></option>
                <?php endforeach; ?>
            </select>
            
            <br>
        <label for="type_besoin">Type de besoin:</label>
            <select id="type_besoin" name="type_besoin">
                <?php foreach ($type_besoin as $value): ?>
                <option value="<?= $value ?>"><?= $value ?></option>
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