<?php

namespace MonologReader\HttpFoundation;

!defined('MONOLOG_READER') && die(0);

/**
 * Class StreamResponse
 */
class StreamResponse extends Response
{
    protected $callback;

    /**
     * StreamResponse constructor.
     *
     * @param callable $callback
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct($callback, $statusCode = 200, array $headers = [])
    {
        $this->callback = $callback;

        parent::__construct('', $statusCode, $headers);
    }

    /**
     * Sends HTTP headers and content.
     *
     * @return $this
     */
    public function send()
    {
        call_user_func($this->callback);

        return $this;
    }
}
