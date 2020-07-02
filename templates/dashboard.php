<?php

use MonologReader\Controller\LogsController;
use MonologReader\Controller\EditLogConfigController;

!defined('MONOLOG_READER') && die(0);

/** @var \MonologReader\HttpFoundation\Request $request */
?>

<div class="container-fluid">
    <?php
    $alterClass = 'my-3';
    require __DIR__ . '/flash-messages.php';
    ?>

    <div class="row my-3">
    <?php foreach ($logConfigs as $index => $logConfig) { ?>
        <div class="col-sm-4 col-lg-3">
            <div class="card border-dark mb-3">
                <div class="card-header bg-dark text-white text-center">
                    <?php echo $logConfig['name']; ?>
                </div>
                <div class="card-body" style="height: 7em;">
                    <?php echo $logConfig['path']; ?>
                </div>
                <div class="card-footer bg-transparent border-dark">
                    <div class="row">
                        <div class="col-6">
                            <a href="<?php echo $request->generateUrl(LogsController::class, ['id' => $index]); ?>"
                                type="button" class="btn btn-outline-dark btn-block">
                                View
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo $request->generateUrl(EditLogConfigController::class, ['id' => $index]); ?>"
                               type="button" class="btn btn-outline-dark btn-block">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>
