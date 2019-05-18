<?php !defined('MONOLOG_READER') && die(0); ?>

<div class="container-fluid">
    <h2 class="my-3">
        Logs for <?php echo $viewData['selectedLogKey']; ?>
        <a href="/?c=edit-log-config&key=<?php echo urlencode($viewData['selectedLogKey']); ?>" class="btn btn-sm">
            Edit
        </a>
        <a href="/?c=delete-log-config&key=<?php echo urlencode($viewData['selectedLogKey']); ?>" class="btn btn-sm text-danger"
           onclick="return confirm('Will delete this log config, are you sure?');">
            Delete
        </a>
    </h2>

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
    <?php for ($i = $viewData['indexStart']; $i >= $viewData['indexEnd']; --$i) {
        $log = $viewData['reader'][$i];
        if (empty($log)) {
            continue;
        }
    ?>
        <tr>
            <td><?php echo $viewData['total'] - $i; ?></td>
            <td><?php echo $log['date']->format('Y-m-d H:i:s'); ?></td>
            <td><?php echo htmlentities($log['logger']); ?></td>
            <td><?php echo htmlentities($log['level']); ?></td>
            <td><?php echo htmlentities($log['message']); ?></td>
            <td>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#contextModal<?php echo $i; ?>">
                    Context
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#extraModal<?php echo $i; ?>">
                    Extra
                </button>

                <!-- Modal -->
                <div class="modal fade codemirror-json" tabindex="-1" role="dialog" aria-hidden="true" id="contextModal<?php echo $i; ?>">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Context</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <textarea><?php echo json_encode($log['context'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); ?></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade codemirror-json" tabindex="-1" role="dialog" aria-hidden="true" id="extraModal<?php echo $i; ?>">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Extra</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <textarea><?php echo json_encode($log['extra'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); ?></textarea>
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
            <li class="page-item <?php echo $viewData['currentPage'] === 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo $viewData['pageUrlPrefix'].'&page=1'; ?>" tabindex="-1">
                    First
                </a>
            </li>
            <li class="page-item <?php echo $viewData['currentPage'] === 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo $viewData['pageUrlPrefix'].'&page='.($viewData['currentPage'] - 1); ?>" tabindex="-1">
                    Previous
                </a>
            </li>

        <?php
        for ($i = 0, $length = count($viewData['pages']); $i < $length; ++$i) {
            $page = $viewData['pages'][$i];
        ?>
            <li class="page-item <?php echo $viewData['currentPage'] === $page ? 'active' : ''; ?>">
                <a class="page-link" href="<?php echo $viewData['pageUrlPrefix'].'&page='.$page; ?>">
                    <?php echo $page; ?>
                </a>
            </li>
        <?php } ?>

            <li class="page-item <?php echo $viewData['currentPage'] === $viewData['maxPage'] ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo $viewData['pageUrlPrefix'].'&page='.($viewData['currentPage'] + 1); ?>" tabindex="-1">
                    Next
                </a>
            </li>
            <li class="page-item <?php echo $viewData['currentPage'] === $viewData['maxPage'] ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo $viewData['pageUrlPrefix'].'&page='.$viewData['maxPage']; ?>" tabindex="-1">
                    Last
                </a>
            </li>
        </ul>
    </nav>
</div>
