<?php !defined('MONOLOG_READER') && die(0);
/** @var IndexController $this */
?>

<?php foreach ($viewData['logConfigs'] as $key => $config) { ?>
    <a href="<?php echo $this->generateUrl(LogsController::class, ['key' => $key]); ?>">
        <?php echo $key; ?>
    </a>
<?php } ?>
