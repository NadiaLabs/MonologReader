<?php !defined('MONOLOG_READER') && die(0);

/**
 * Class LogsController
 */
class LogsController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function run(Request $request)
    {
        $currentPage = (int) $request->get('page', 1);
        $limit = $request->get('limit', 100);
        $key = $request->get('key');
        $logConfigs = $this->getConfig('logs');

        if (!isset($logConfigs[$key])) {
            throw new RuntimeException('The log "'.$key.'" is not exists!');
        }

        if (!file_exists($logConfigs[$key]['path'])) {
            $reader = [];
        } else {
            $reader = $this->getReader($logConfigs[$key]['path']);
        }

        $count = count($reader);
        $maxPage = (int) ceil($count / $limit);
        $maxPage = 0 === $maxPage ? 1 : $maxPage;
        $currentPage = $currentPage > $maxPage ? $maxPage : $currentPage;
        $indexEnd = $currentPage === $maxPage ? 0 : $count - $currentPage * $limit;
        $indexStart = $currentPage === $maxPage ? $count % $limit - 1 : $indexEnd + $limit - 1;

        $query = [
            'key' => $key,
            'limit' => $limit,
        ];

        $viewData = [
            'reader' => $reader,
            'currentPage' => $currentPage,
            'maxPage' => $maxPage,
            'indexStart' => $indexStart,
            'indexEnd' => $indexEnd,
            'pageUrlPrefix' => '/?c=logs&'.http_build_query($query),
            'selectedLogKey' => $key,
            'pages' => $this->getPages($currentPage, $maxPage, 10),
            'total' => count($reader),
        ];

        return $this->render($viewData);
    }

    /**
     * @param string $file Log file path
     *
     * @return MonologReader
     */
    private function getReader($file)
    {
        return new MonologReader($file);
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
