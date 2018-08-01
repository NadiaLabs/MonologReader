<?php !defined('MONOLOG_READER') && die(0);
/** @var LogsController $this */
?>

<div class="container-fluid table-responsive">
    <table class="table table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>DateTime</th>
            <th>Logger</th>
            <th>Level</th>
            <th>Message</th>
            <th>Extra Information</th>
        </tr>
        </thead>
        <tbody>
    <?php for ($i = count($viewData['reader']) - 1; $i >= 0; --$i) {
        $log = $viewData['reader'][$i];
        if (empty($log)) {
            continue;
        }
    ?>
        <tr>
            <td><?php echo $log['date']->format('Y-m-d H:i:s'); ?></td>
            <td><?php echo $log['logger']; ?></td>
            <td><?php echo $log['level']; ?></td>
            <td><?php echo $log['message']; ?></td>
            <td>
                context, extra
            </td>
        </tr>
    <?php } ?>
        </tbody>
    </table>
</div>
