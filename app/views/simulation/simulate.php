<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/nav.php'; ?>

    <h2>Choisissez la méthode dont vous voulez dispatcher les dons</h2>
        <form action="<?= BASE_URL ?>/dons/dispatch" method="post">
            <label>
                <input type="radio" name="method" value="date_saisie">Date
            </label>
            <label>
                <input type="radio" name="method" value="qte_min">Quantite
            </label>
            <label>
                <input type="radio" name="method" value="proportion">Proportion
            </label>

            <button type="submit" >Valider</button>
        </form>


    <?php include __DIR__ . '/../includes/footer.php'; ?>       
</body>
</html>