<?php

namespace MonologReader\Controller;

use MonologReader\Config\ConfigManager;
use MonologReader\HttpFoundation\RedirectResponse;
use MonologReader\HttpFoundation\Request;
use MonologReader\HttpFoundation\Response;
use MonologReader\HttpFoundation\Session;

!defined('MONOLOG_READER') && die(0);

/**
 * Class LoginController
 */
class LoginController extends AbstractController
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $request = $this->request;
        $session = $request->getSession();

        if ($session->isLoggedIn()) {
            return new RedirectResponse($request->generateUrl(DashboardController::class));
        }

        $configManager = new ConfigManager();
        $encryptedPassword = $configManager->loadEncryptedPassword();

        if (empty($encryptedPassword)) {
            return new RedirectResponse($request->generateUrl(SetupLoginPasswordController::class));
        }

        if ($request->isMethod('POST')) {
            return $this->login();
        }

        return $this->render('login');
    }

    /**
     * @return Response
     */
    private function login()
    {
        $request = $this->request;
        $session = $request->getSession();
        $encodedPassword = (new ConfigManager())->loadEncryptedPassword();
        $inputPassword = $request->get('password');

        if (empty($inputPassword) || !password_verify($inputPassword, $encodedPassword)) {
            $session->set('error', 'Password invalid!');

            return new RedirectResponse($request->generateUrl(LoginController::class));
        }

        $session->set(Session::SESSION_IS_LOGGED_IN, true);

        $redirectUrl = $session->getFlash(Session::SESSION_REDIRECT_URL);

        if (!empty($redirectUrl)) {
            return new RedirectResponse($redirectUrl);
        }

        return new RedirectResponse($request->generateUrl(DashboardController::class));
    }
}
