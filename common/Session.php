<?php !defined('MONOLOG_READER') && die();

/**
 * Class Session
 */
class Session
{
    /**
     * @var bool
     */
    private $isStarted = false;

    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_register_shutdown();
    }

    /**
     * Get a session value
     *
     * @param string $name
     * @param null   $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (!$this->isStarted()) {
            return $default;
        }

        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return $default;
    }

    /**
     * Set a session value
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($name, $value)
    {
        if (!$this->isStarted()) {
            return $this;
        }

        $_SESSION[$name] = $value;

        return $this;
    }

    /**
     * Remove a session value
     *
     * @param string $name
     *
     * @return $this
     */
    public function remove($name)
    {
        if (!$this->isStarted()) {
            return $this;
        }

        unset($_SESSION[$name]);

        return $this;
    }

    /**
     * Check a session key is exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        if (!$this->isStarted()) {
            return false;
        }

        return array_key_exists($name, $_SESSION);
    }

    /**
     * Start a session
     *
     * @return $this
     */
    public function start()
    {
        if (PHP_SESSION_NONE === session_status()) {
            session_start();

            $this->isStarted = true;
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
        return $this->isStarted;
    }
}
