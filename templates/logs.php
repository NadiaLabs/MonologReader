<?php

use MonologReader\Controller\DashboardController;
use MonologReader\Controller\DownloadController;
use MonologReader\Controller\EditLogConfigController;
use MonologReader\Controller\LogsController;
use MonologReader\Templating\Html;

!defined('MONOLOG_READER') && die(0);

/** @var \MonologReader\HttpFoundation\Request $request */

$currentFirstPage = current($pages);
$currentLastPage = end($pages);
reset($pages);

$generatePageUrl = function ($page) use ($id, $limit, $request) {
    $parameters = [
        'id' => $id,
        'page' => $page,
        'limit' => $limit
    ];

    return $request->generateUrl(LogsController::class, $parameters);
};
$messageProcessor = function ($message) {
    // Add new line before '#0', '#1', ...
    if (false !== strpos($message, ' #0 ')) {
        $number = 0;
        $searches = [];
        $replaces = [];

        while (1) {
            if (false !== strpos($message, ' #' . ($number + 1) . ' ')) {
                ++$number;
            } else {
                break;
            }
        }

        for ($i = 0; $i <= $number; ++$i) {
            $search = ' #' . $i . ' ';
            $searches[] = $search;
            $replaces[] = ' <br> ' . $search;
        }

        $message = str_replace($searches, $replaces, $message);
    }

    return $message;
};
?>

<div class="container-fluid">
    <div class="my-3 d-flex">
        <div class="h2 mr-auto mb-0 ">
            Logs for <strong>"<?php echo Html::escape($logConfig['name']); ?>"</strong>
        </div>

        <div>
            <a href="<?php echo $request->generateUrl(EditLogConfigController::class, ['id' => $logConfig['id']]); ?>"
                class="btn btn-outline-dark">
                Edit
            </a>
            <a href="<?php echo $request->generateUrl(DownloadController::class, ['id' => $logConfig['id']]); ?>"
               class="btn btn-outline-success" target="_blank" download="<?php echo basename($logConfig['path']);?>">
                Download
            </a>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="thead-dark">
        <tr>
            <th style="width: 50px;">Index</th>
            <th style="width: 170px;">DateTime</th>
            <th>Logger</th>
            <th>Level</th>
            <th>Message</th>
            <th style="width: 160px;">Extra Information</th>
        </tr>
        </thead>
        <tbody>
    <?php for ($i = $indexStart; $i >= $indexEnd; --$i) {
        $log = $reader[$i];
        if (empty($log)) {
            continue;
        }
    ?>
        <tr>
            <td><?php echo $total - $i; ?></td>
            <td><?php echo $log['date']->format('Y-m-d H:i:s'); ?></td>
            <td><?php echo Html::escape($log['logger']); ?></td>
            <td><?php echo Html::escape($log['level']); ?></td>
            <td><?php echo $messageProcessor(Html::escape($log['message'])); ?></td>
            <td>
                <button type="button" class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target="#context-modal<?php echo $i; ?>">
                    Context
                </button>
                <button type="button" class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target="#extra-modal<?php echo $i; ?>">
                    Extra
                </button>

                <!-- Modal -->
                <div class="modal fade codemirror-json" tabindex="-1" role="dialog" aria-hidden="true" id="context-modal<?php echo $i; ?>">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Context</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <textarea><?php echo json_encode($log['context'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_LINE_TERMINATORS); ?></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade codemirror-json" tabindex="-1" role="dialog" aria-hidden="true" id="extra-modal<?php echo $i; ?>">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Extra</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <textarea><?php echo json_encode($log['extra'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_LINE_TERMINATORS); ?></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    <?php } ?>
        </tbody>
    </table>

    <nav aria-label="Pagination">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $currentPage === 1 ? 'disabled' : ''; ?>">
                <a href="<?php echo $generatePageUrl(1); ?>" class="page-link" tabindex="-1">
                    First
                </a>
            </li>
            <li class="page-item <?php echo $currentPage === 1 ? 'disabled' : ''; ?>">
                <a href="<?php echo $generatePageUrl($currentPage - 1); ?>" class="page-link" tabindex="-1">
                    Previous
                </a>
            </li>

        <?php if ($currentFirstPage !== 1) { ?>
            <li class="page-item">
                <a href="<?php echo $generatePageUrl($currentFirstPage - 1); ?>" class="page-link" tabindex="-1">
                    ...
                </a>
            </li>
        <?php } ?>

        <?php
        for ($i = 0, $length = count($pages); $i < $length; ++$i) {
            $page = $pages[$i];
        ?>
            <li class="page-item <?php echo $currentPage === $page ? 'active' : ''; ?>">
                <a href="<?php echo $generatePageUrl($page); ?>" class="page-link" tabindex="-1">
                    <?php echo $page; ?>
                </a>
            </li>
        <?php } ?>

        <?php if ($currentLastPage !== $maxPage) { ?>
            <li class="page-item">
                <a href="<?php echo $generatePageUrl($currentLastPage + 1); ?>" class="page-link" tabindex="-1">
                    ...
                </a>
            </li>
        <?php } ?>

            <li class="page-item <?php echo $currentPage === $maxPage ? 'disabled' : ''; ?>">
                <a href="<?php echo $generatePageUrl($currentPage + 1); ?>" class="page-link" tabindex="-1">
                    Next
                </a>
            </li>
            <li class="page-item <?php echo $currentPage === $maxPage ? 'disabled' : ''; ?>">
                <a href="<?php echo $generatePageUrl($maxPage); ?>" class="page-link" tabindex="-1">
                    Last
                </a>
            </li>
            <li class="page-item disabled">
                <a href="#" class="page-link">
                    <?php echo $total; ?> lines
                </a>
            </li>
        </ul>
    </nav>
</div>
