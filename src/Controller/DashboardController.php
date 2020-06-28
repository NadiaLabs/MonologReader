<?php

namespace MonologReader\Controller;

!defined('MONOLOG_READER') && die(0);

/**
 * Class DashboardController
 */
class DashboardController extends AbstractController
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        $viewData = [
            'logConfigs' => $this->getConfigManager()->loadLogs(),
            'request' => $this->request,
        ];

        return $this->render('dashboard', $viewData);
    }
}
