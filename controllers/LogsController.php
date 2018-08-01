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

        $viewData = [
            'reader' => $reader,
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
}
