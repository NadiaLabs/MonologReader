<?php !defined('MONOLOG_READER') && die(0);

/**
 * Class IndexController
 */
class IndexController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function run(Request $request)
    {
        $viewData = [
            'logConfigs' => $this->getConfig('logs'),
        ];

        return $this->render($viewData);
    }
}
