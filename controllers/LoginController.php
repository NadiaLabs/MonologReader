<?php !defined('MONOLOG_READER') && die();

/**
 * Class LoginController
 */
class LoginController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function run(Request $request)
    {
        $session = $request->getSession();

        if ($this->isLoggedIn($session)) {
            return $this->redirectController(IndexController::class);
        }

        if (!$this->hasConfig('security')) {
            return $this->redirectController(SecurityController::class);
        }

        if ($request->isMethod('POST')) {
            return $this->processLogin($request);
        }

        $viewData = ['error' => ''];

        if ($session->has('error')) {
            $viewData['error'] = $session->get('error');

            $session->remove('error');
        }

        return $this->render($viewData);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    private function processLogin(Request $request)
    {
        $session = $request->getSession();
        $password = $this->getConfig('security');
        $inputPassword = $request->get('password');

        if (empty($inputPassword) || !password_verify($inputPassword, $password)) {
            $session->set('error', 'Password invalid!');

            return $this->redirectController(LoginController::class);
        }

        $refererUrl = $session->get(self::SESSION_REFERER_URL);

        $session->remove(self::SESSION_REFERER_URL);
        $session->set(self::SESSION_LOGGED_IN, 1);

        if (empty($refererUrl)) {
            return $this->redirectController(IndexController::class);
        }

        return $this->redirect($refererUrl);
    }
}
