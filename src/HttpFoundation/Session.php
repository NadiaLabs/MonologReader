<?php

namespace MonologReader\HttpFoundation;

!defined('MONOLOG_READER') && die(0);

/**
 * Class Session
 */
class Session
{
    /**
     * @var string
     */
    const SESSION_REDIRECT_URL = 'redirect_url';
    /**
     * @var string
     */
    const SESSION_IS_LOGGED_IN = 'is_logged_in';

    /**
     * @var string Session key prefix
     */
    private $prefix;

    /**
     * @var bool
     */
    private $started = false;

    /**
     * Session constructor.
     *
     * @param string $prefix Session key prefix
     */
    public function __construct($prefix = 'monolog-reader:')
    {
        $this->prefix = $prefix;

        session_register_shutdown();
    }

    /**
     * Get a session value
     *
     * @param string $key     The session key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->start();

        $key = $this->getSessionKey($key);

        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return $default;
    }

    /**
     * Set a session value
     *
     * @param string $key   The session key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->start();

        $key = $this->getSessionKey($key);

        $_SESSION[$key] = $value;

        return $this;
    }

    /**
     * Remove a session value
     *
     * @param string $key The session key
     *
     * @return $this
     */
    public function remove($key)
    {
        $this->start();

        $key = $this->getSessionKey($key);

        unset($_SESSION[$key]);

        return $this;
    }

    /**
     * Check a session key is exists
     *
     * @param string $key The session key
     *
     * @return bool
     */
    public function has($key)
    {
        $this->start();

        $key = $this->getSessionKey($key);

        return array_key_exists($key, $_SESSION);
    }

    /**
     * Get flash session data, and delete it.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function getFlash($key, $default = null)
    {
        if ($this->has($key)) {
            $value = $this->get($key);

            $this->remove($key);

            return $value;
        }

        return $default;
    }

    /**
     * Start a session
     *
     * @return $this
     */
    public function start()
    {
        if (!$this->isStarted() && PHP_SESSION_NONE === session_status()) {
            session_set_cookie_params(86400 * 30);
            session_start();

            $this->started = true;
        }

        return $this;
    }

    /**
     * Destroy a session
     *
     * @return bool
     */
    public function destroy()
    {
        return session_destroy();
    }

    /**
     * @return bool
     */
    public function isStarted()
    {
        return $this->started;
    }

    /**
     * Check user is logged-in or not
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->has(self::SESSION_IS_LOGGED_IN) && $this->get(self::SESSION_IS_LOGGED_IN);
    }

    /**
     * Logout and remove login session
     *
     * @return $this
     */
    public function logout()
    {
        $this->remove(self::SESSION_IS_LOGGED_IN);
        $this->destroy();

        return $this;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getSessionKey($name)
    {
        return $this->prefix . $name;
    }
}
