<?php !defined('MONOLOG_READER') && die();

/**
 * Class PageNotFoundController
 */
class PageNotFoundController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function run(Request $request)
    {
        return new Response('Page not found!', 404);
    }
}
