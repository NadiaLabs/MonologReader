<?php !defined('MONOLOG_READER') && die(0);

/**
 * Class DeleteLogConfigController
 */
class DeleteLogConfigController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function run(Request $request)
    {
        $key = $request->get('key');
        $logConfigs = $this->getConfig('logs');

        if (empty($logConfigs[$key])) {
            return $this->redirectController(IndexController::class);
        }

        unset($logConfigs[$key]);

        $this->writeConfigFile('logs', $logConfigs);

        return $this->redirectController(IndexController::class);
    }
}
