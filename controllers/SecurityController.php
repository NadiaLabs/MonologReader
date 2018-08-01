<?php !defined('MONOLOG_READER') && die();

/**
 * Class SecurityController
 */
class SecurityController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function run(Request $request)
    {
        if ($this->hasConfig('security')) {
            return $this->redirectController(LoginController::class);
        }

        if ($request->isMethod('POST')) {
            return $this->processCreatePassword($request);
        }

        $session = $request->getSession();
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
    private function processCreatePassword(Request $request)
    {
        $session = $request->getSession();
        $password = $request->get('password');
        $passwordRepeat = $request->get('password-repeat');
        $result = $this->checkPasswordRule($password, $passwordRepeat);

        if ($result !== true) {
            $session->set('error', $result);

            return $this->redirectController(SecurityController::class);
        }

        $encryptPassword = $this->encryptPassword($password);

        $this->generateConfigFile('security', $encryptPassword);

        return $this->redirectController(LoginController::class);
    }

    /**
     * @param string $password
     * @param string $passwordRepeat
     *
     * @return bool|string
     */
    private function checkPasswordRule($password, $passwordRepeat)
    {
        if (empty($password) || empty($passwordRepeat)) {
            return 'Password cannot be empty!';
        }

        if (!preg_match('/[a-zA-Z0-9]{8,32}/', $password)) {
            return 'Password should only contain alphabets and numbers!';
        }

        if (strlen($password) < 8) {
            return 'Password length should at least 8 characters!';
        }

        if (strlen($password) > 32) {
            return 'Password length should less than 32 characters!';
        }

        if ($password !== $passwordRepeat) {
            return 'Repeat password is not the same!';
        }

        return true;
    }
}
