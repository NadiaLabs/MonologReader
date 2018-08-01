<?php !defined('MONOLOG_READER') && die(0);

/**
 * Class LogoutController
 */
class LogoutController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function run(Request $request)
    {
        $session = $request->getSession();

        if ($this->isLoggedIn($session)) {
            $session->remove(self::SESSION_LOGGED_IN);
            $session->destroy();
        }

        return $this->redirectController(LoginController::class);
    }
}
