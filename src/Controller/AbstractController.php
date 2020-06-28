<?php

namespace MonologReader\Controller;

use MonologReader\Config\ConfigManager;
use MonologReader\HttpFoundation\RedirectResponse;
use MonologReader\HttpFoundation\Request;
use MonologReader\HttpFoundation\Response;
use MonologReader\Templating\PhpEngine;

!defined('MONOLOG_READER') && die(0);

/**
 * Class AbstractController
 */
abstract class AbstractController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * AbstractController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Run controller action
     *
     * @return Response
     */
    abstract public function run();

    /**
     * @param array $data
     *
     * @return Response
     */
    protected function json(array $data)
    {
        return new Response(json_encode($data), 200, ['Content-Type' => 'application/json; charset=utf-8']);
    }

    /**
     * Render a PHP template
     *
     * @param string $view The view file path (without ".php" suffix) in "templates" directory,
     *                     e.g. "foo/bar" for "templates/foo/bar.php"
     *                          "dashboard" for "templates/dashboard.php"
     * @param array $parameters Parameters for rendering with view file
     *
     * @return Response
     */
    protected function render($view, array $parameters = [])
    {
        static $engine;

        if (!$engine instanceof PhpEngine) {
            $globalParameters = [
                'isLoggedIn' => $this->request->getSession()->isLoggedIn(),
                'request' => $this->request,
            ];

            $engine = new PhpEngine($globalParameters);
        }

        return new Response($engine->render($view, $parameters));
    }

    /**
     * @param string $controllerClassName
     * @param array $parameters
     *
     * @return RedirectResponse
     */
    protected function redirectRoute($controllerClassName, array $parameters = [])
    {
        $url = $this->request->generateUrl($controllerClassName, $parameters);

        return new RedirectResponse($url);
    }

    /**
     * @return ConfigManager
     */
    protected function getConfigManager()
    {
        static $manager;

        if (!$manager instanceof ConfigManager) {
            $manager = new ConfigManager();
        }

        return $manager;
    }
}
