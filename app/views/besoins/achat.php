<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achat-Besoin</title>
</head>
<body>
    <h1>Acheter un besoin</h1>

    <form action="<?= BASE_URL ?>/besoins/buy" method="post">
        <label for="quantite">Quantité à acheter:</label>
        <input type="number" id="quantite" name="quantite" min="1" required>

        <input type="hidden" name="id_besoin" value="<?= $id_besoin ?>">
        
        <input type="submit" value="Acheter">
    </form>
</body>
</html>