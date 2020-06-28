<?php

namespace MonologReader;

use MonologReader\Controller\AbstractController;
use MonologReader\Controller\LoginController;
use MonologReader\Controller\PageNotFoundController;
use MonologReader\DependencyInjection\Container;
use MonologReader\HttpFoundation\RedirectResponse;
use MonologReader\HttpFoundation\Request;
use MonologReader\HttpFoundation\Response;
use MonologReader\HttpFoundation\Session;
use MonologReader\Security\Firewall;

!defined('MONOLOG_READER') && die(0);

/**
 * Class Kernel
 */
class Kernel
{
    /**
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function handleRequest(Request $request)
    {
        $controllerClassName = $request->getControllerClassName();

        if (!class_exists($controllerClassName)) {
            $controller = new PageNotFoundController($request);

            return $controller->run($request);
        }

        $firewall = new Firewall();

        // When user is not login, redirect to login page.
        // Remember current URL to session, then redirect to current URL after logging in
        if (!$firewall->isGranted($request)) {
            if (!empty($_SERVER['HTTP_REFERER'])) {
                $request->getSession()->set(Session::SESSION_REDIRECT_URL, $_SERVER['HTTP_REFERER']);
            }

            return new RedirectResponse($request->generateUrl(LoginController::class));
        }

        /** @var AbstractController $controller */
        $controller = new $controllerClassName($request);

        return $controller->run();
    }
}
