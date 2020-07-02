<?php

namespace MonologReader\HttpFoundation;

!defined('MONOLOG_READER') && die(0);

/**
 * Class RedirectResponse
 */
class RedirectResponse extends Response
{
    /**
     * Response constructor.
     *
     * @param string $url
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct($url, $statusCode = 302, array $headers = [])
    {
        $headers = array_merge($headers, ['Location' => $url]);

        parent::__construct('', $statusCode, $headers);
    }
}
