<?php !defined('MONOLOG_READER') && die(0);

function camelize($string, $delimiter = '-')
{
    return str_replace('-', '', ucwords($string, $delimiter));
}

function uncamelize($string, $delimiter = '-')
{
    return strtolower(preg_replace('/[A-Z]/', $delimiter.'\\0', lcfirst($string)));
}
