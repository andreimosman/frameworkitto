<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frameworkitto Sample</title>
    <script>var baseURL="<?= $BASE_URL; ?>"</script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $BASE_URL; ?>/assets/css/style.css?<?= $ASSETS_CACHE_ID ?>">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" integrity="sha512-RdSPYh1WA6BF0RhpisYJVYkOyTzK4HwofJ3Q7ivt/jkpW6Vc8AurL1R+4AUcvn9IwEKAPm/fk7qFZW3OuiUDeg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.13.1/underscore-umd-min.min.js" integrity="sha512-+kmUFYAww86SUXALNm4MuqYezJlxT29D+O5KUyvU7gpu4M0dlo74cd6FlSjyd3DoiG9H0KAWsp6ewYFDgKzvuw==" crossorigin="anonymous"></script>

    <?php foreach($customStyles["header"] as $style): ?>
        <link rel="stylesheet" href="<?= $style ?>?<?= $ASSETS_CACHE_ID ?>">
    <?php endforeach; ?>

    <?php foreach($customScripts["header"] as $script): ?>
        <script src="<?= $script ?>?<?= $ASSETS_CACHE_ID ?>"></script>
    <?php endforeach; ?>


</head>
<body>

<?= $_NAVIGATION_BAR_; ?>


<div class="container content-area">
