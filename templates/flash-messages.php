<?php

!defined('MONOLOG_READER') && die(0);

$session = $request->getSession();
$successMessages = $session->getFlash('success');
$errorMessages = $session->getFlash('error');
?>

<?php if (!empty($errorMessages) || !empty($successMessages)) { ?>
<div class="<?php echo empty($alterClass) ? 'my-1' : $alterClass; ?>">
    <?php if (!empty($errorMessages)) { ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $errorMessages; ?>
    </div>
    <?php } ?>
    <?php if (!empty($successMessages)) { ?>
    <div class="alert alert-success" role="alert">
        <?php echo $successMessages; ?>
    </div>
    <?php } ?>
</div>
<?php } ?>
