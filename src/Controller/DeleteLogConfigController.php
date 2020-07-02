<?php

namespace MonologReader\Controller;

!defined('MONOLOG_READER') && die(0);

/**
 * Class DeleteLogConfigController
 */
class DeleteLogConfigController extends AbstractController
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = $this->request->get('id');
        $configManager = $this->getConfigManager();
        $logConfigs = $configManager->loadLogs();

        if (empty($logConfigs[$id])) {
            return $this->redirectRoute(DashboardController::class);
        }

        $deletedLogConfig = $logConfigs[$id];

        unset($logConfigs[$id]);

        $configManager->updateLogs($logConfigs);

        $this->request->getSession()->set(
            'success',
            sprintf('Delete log "%s" successfully!', $deletedLogConfig['name'])
        );

        return $this->redirectRoute(DashboardController::class);
    }
}
