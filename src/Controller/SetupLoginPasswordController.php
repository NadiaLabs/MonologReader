<?php

namespace MonologReader\Controller;

use MonologReader\Config\ConfigManager;
use MonologReader\HttpFoundation\Response;

!defined('MONOLOG_READER') && die(0);

/**
 * Class SetupLoginPasswordController
 */
class SetupLoginPasswordController extends AbstractController
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $request = $this->request;
        $configManager = new ConfigManager();
        $encodedPassword = $configManager->loadEncryptedPassword();

        if (!empty($encodedPassword)) {
            return $this->redirectRoute(DashboardController::class);
        }

        if ($request->isMethod('POST')) {
            return $this->save();
        }

        return $this->render('setup-login-password');
    }

    /**
     * @return Response
     */
    private function save()
    {
        $request = $this->request;
        $session = $request->getSession();
        $password = $request->get('password');
        $passwordRepeat = $request->get('password-repeat');
        $result = $this->validate($password, $passwordRepeat);

        if (true !== $result) {
            $session->set('error', $result);

            return $this->redirectRoute(SetupLoginPasswordController::class);
        }

        $encryptedPassword = $this->encryptPassword($password);

        $this->getConfigManager()->updateEncryptedPassword($encryptedPassword);

        $session->set('success', 'Create password successfully! You can login MonologReader now!');

        return $this->redirectRoute(LoginController::class);
    }

    /**
     * Validation
     *
     * @param string $password
     * @param string $passwordRepeat
     *
     * @return bool|string
     */
    private function validate($password, $passwordRepeat)
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

    /**
     * Encrypt password
     *
     * @param string $password
     *
     * @return string
     */
    private function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }
}
