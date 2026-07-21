<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?= base_url('/pourcentage/add') ?>" method="post">
        <input type="hidden" name="id" value="<?= session()->get('client_id') ?>">
        <p>combien le pourcentage a epargner:<input type="number" id="pourcentage" name="pourcentage"></p>
        <input type="submit" value="Valider">
    </form>
</body>
</html>