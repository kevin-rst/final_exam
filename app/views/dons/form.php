<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisis des besoins </title>
</head>

<body>

    <h2>Saisir les dons a donner aux sinistrés</h2>
    <form action="<?= BASE_URL ?>/dons/create" method="post">
        <label for="type">Type du don</label>
        <select id="type" name="type">
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