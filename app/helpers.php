<?php

function ifCurrentRoute($routeName, $class = 'active')
{
    $path = str_after(route($routeName, [], false), '/');
    return Request::is($path) ? $class : '';
}
