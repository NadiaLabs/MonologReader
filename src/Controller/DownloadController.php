<?php

namespace MonologReader\Controller;

use MonologReader\HttpFoundation\StreamResponse;

!defined('MONOLOG_READER') && die(0);

/**
 * Class DownloadController
 */
class DownloadController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $request = $this->request;
        $id = $request->get('id');
        $logConfigs = $this->getConfigManager()->loadLogs();

        if (empty($logConfigs[$id])) {
            $request->getSession()->set('error', 'Log config is not exist!');

            return $this->redirectRoute(DashboardController::class);
        }

        $logConfig = $logConfigs[$id];

        return new StreamResponse(function () use ($logConfig) {
            header('Content-Type: text/plain');

            $stream = fopen('php://output', 'w');
            $fs = fopen($logConfig['path'], 'r');

            while (!feof($fs)) {
                $line = fgets($fs);

                fputs($stream, $line);
            }

            fclose($fs);
            fclose($stream);
        });
    }
}
