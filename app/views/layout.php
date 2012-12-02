<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title><?= $title?></title>
    <?= output_css() ?>
    <?= output_js() ?>
</head>
<body>
    <?= render ('header'); ?>
    <div class="container">
        <?= render_body($data); ?>
        <?= render('footer'); ?>
    </div>
</body>
</html>