<?php !defined('MONOLOG_READER') && die();

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
