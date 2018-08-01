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
        return $this->render();
    }
}
