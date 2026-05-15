<?php

return [
    'proxy' => env('TRUST_PROXY', true),
    'proxies' => '*',
    'headers' => Illuminate\Http\Request::HEADER_X_FORWARDED_FOR
        | Illuminate\Http\Request::HEADER_X_FORWARDED_HOST
        | Illuminate\Http\Request::HEADER_X_FORWARDED_PORT
        | Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO,
];
