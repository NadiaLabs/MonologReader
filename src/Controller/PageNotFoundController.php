<?php

namespace MonologReader\Controller;

use MonologReader\HttpFoundation\Response;

!defined('MONOLOG_READER') && die(0);

/**
 * Class PageNotFoundController
 */
class PageNotFoundController extends AbstractController
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        return new Response('Page not found!', 404);
    }
}
