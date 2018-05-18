<?php

namespace Prominado\Redirect;

interface Constant
{
    const HTTP_CODES = [
        '301' => '301 Moved Permanently',
        '300' => '300 Multiple Choices',
        '302' => '302 Moved Temporarily',
        '303' => '303 See Other',
        '304' => '304 Not Modified',
        '305' => '305 Use Proxy',
        '307' => '307 Temporary Redirect',
        '308' => '308 Permanent Redirect',
    ];
}