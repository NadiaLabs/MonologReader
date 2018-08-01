<?php

function camelize($string, $delimiter = '-')
{
    return str_replace('-', '', ucwords($string, $delimiter));
}

function uncamelize($string, $delimiter = '-')
{
    return strtolower(preg_replace('/[A-Z]/', $delimiter.'\\0', lcfirst($string)));
}
