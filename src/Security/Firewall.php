<?php

namespace MonologReader\Security;

use MonologReader\Controller\LoginController;
use MonologReader\Controller\SetupLoginPasswordController;
use MonologReader\HttpFoundation\Request;

!defined('MONOLOG_READER') && die(0);

/**
 * Class Firewall
 */
class Firewall
{
    /**
     * Check user is granted to access current page
     *
     * @param Request $request
     *
     * @return bool
     */
    public function isGranted(Request $request)
    {
        $anonymousControllerClassNames = $this->getAnonymousControllerClassNames();
        $controllerClassName = $request->getControllerClassName();

        if ($request->getSession()->isLoggedIn() || in_array($controllerClassName, $anonymousControllerClassNames)) {
            return true;
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function getAnonymousControllerClassNames()
    {
        return [
            LoginController::class,
            SetupLoginPasswordController::class,
        ];
    }
}
