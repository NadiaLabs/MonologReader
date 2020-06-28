<?php

namespace MonologReader\Controller;

!defined('MONOLOG_READER') && die(0);

/**
 * Class LogoutController
 */
class LogoutController extends AbstractController
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $session = $this->request->getSession();

        if ($session->isLoggedIn()) {
            $session->logout();
        }

        return $this->redirectRoute(LoginController::class);
    }
}
