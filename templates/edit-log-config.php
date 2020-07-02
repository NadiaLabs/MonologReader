<?php

use MonologReader\Controller\DeleteLogConfigController;
use MonologReader\Templating\Html;

!defined('MONOLOG_READER') && die(0);

?>

<div class="container-fluid">
    <div class="offset-xl-2 col-xl-8">
        <form method="post">
            <div class="card border-dark my-5">
                <div class="card-header bg-transparent text-center border-dark">
                    <h2 class="mb-0"><?php echo Html::escape($title); ?></h2>
                </div>
                <div class="card-body">
                    <?php require __DIR__ . '/flash-messages.php'; ?>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" aria-describedby="name" required
                               value="<?php echo Html::escape($logConfig['name']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="path">Log File Path</label>
                        <input type="text" class="form-control" id="path" name="path" aria-describedby="path" required
                               value="<?php echo Html::escape($logConfig['path']); ?>" />
                    </div>
                </div>
                <div class="card-footer bg-transparent border-dark">
                    <input type="hidden" name="id" value="<?php echo $logConfig['id']; ?>" />

                    <div class="row">
                        <div class="<?php echo empty($logConfig['id']) ? 'col-12' : 'col-10'; ?>">
                            <button class="btn btn-primary btn-block">
                                <?php echo $submitText; ?>
                            </button>
                        </div>
                        <?php if (!empty($logConfig['id'])) { ?>
                        <div class="col-2">
                            <a href="<?php echo $request->generateUrl(DeleteLogConfigController::class, ['id' => $logConfig['id']]); ?>"
                               class="btn btn-danger btn-block"
                               onclick="return confirm('Are you sure to delete this log config?');">
                                Delete
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
