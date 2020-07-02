<?php

use MonologReader\Controller\CreateLogConfigController;
use MonologReader\Controller\DashboardController;
use MonologReader\Controller\LogoutController;

!defined('MONOLOG_READER') && die(0);

/** @var \MonologReader\HttpFoundation\Request $request */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Monolog Reader</title>
    <link rel="stylesheet" href="assets/build/index.css" />
</head>
<body style="min-width: 400px;">

<nav class="navbar navbar-expand navbar-dark bg-dark">
    <span class="navbar-adjust-block"></span>
    <a class="navbar-brand m-auto" href="<?php echo $request->generateUrl(DashboardController::class) ?>">
        MonologReader
    </a>
<?php if ($isLoggedIn) { ?>
    <a href="<?php echo $request->generateUrl(CreateLogConfigController::class); ?>"
       class="btn btn-outline-light mr-2">
        Add New Log
    </a>
    <a href="<?php echo $request->generateUrl(LogoutController::class); ?>"
       class="btn btn-outline-light">
        Logout
    </a>
<?php } ?>
</nav>

<?php echo $mainContents; ?>

<script type="text/javascript" src="assets/build/index.js"></script>

</body>
</html>
