<?php

namespace MonologReader\Controller;

use MonologReader\Log\Reader;

!defined('MONOLOG_READER') && die(0);

/**
 * Class LogsController
 */
class LogsController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $request = $this->request;
        $id = $request->get('id');
        $currentPage = (int) $request->get('page', 1);
        $limit = $request->get('limit', 100);
        $logConfigs = $this->getConfigManager()->loadLogs();

        if (!isset($logConfigs[$id])) {
            return $this->redirectRoute(PageNotFoundController::class);
        }

        $logConfig = $logConfigs[$id];

        if (!file_exists($logConfig['path'])) {
            $reader = [];
        } else {
            $reader = $this->getReader($logConfig['path']);
        }

        $count = count($reader);
        $maxPage = (int) ceil($count / $limit);
        $maxPage = 0 === $maxPage ? 1 : $maxPage;
        $currentPage = $currentPage > $maxPage ? $maxPage : $currentPage;
        $indexEnd = $currentPage === $maxPage ? 0 : $count - $currentPage * $limit;
        $indexStart = $currentPage === $maxPage ? $count % $limit - 1 : $indexEnd + $limit - 1;

        $viewData = [
            'id' => $id,
            'reader' => $reader,
            'currentPage' => $currentPage,
            'maxPage' => $maxPage,
            'indexStart' => $indexStart,
            'indexEnd' => $indexEnd,
            'pages' => $this->getPages($currentPage, $maxPage, 10),
            'limit' => $limit,
            'total' => count($reader),
            'logConfig' => $logConfig,
        ];

        return $this->render('logs', $viewData);
    }

    /**
     * @param string $file Log file path
     *
     * @return Reader
     */
    private function getReader($file)
    {
        return new Reader($file);
    }

    /**
     * @param int $currentPage
     * @param int $maxPage
     * @param int $amount
     *
     * @return array
     */
    private function getPages($currentPage, $maxPage, $amount = 10)
    {
        $middle = intval($amount / 2);
        $start = $currentPage <= $middle ? 1 : $currentPage - $middle;
        $end = $start + $amount - 1;

        if ($end > $maxPage) {
            $end = $maxPage;
            $start = $maxPage - $amount + 1;
            $start = $start < 1 ? 1 : $start;
        }

        return range($start, $end);
    }
}
