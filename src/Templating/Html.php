<?php

namespace MonologReader\Templating;

!defined('MONOLOG_READER') && die(0);

/**
 * Class Html
 */
class Html
{
    /**
     * @param string $text
     *
     * @return string
     */
    public static function escape($text)
    {
        return htmlentities($text);
    }
}
