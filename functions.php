<?php

// global helper functions

if (! function_exists('count_leading_spaces')) {
    function count_leading_spaces($str) {
        if (mb_ereg('^\p{Zs}+', $str, $regs) === false)
            return 0;
        return mb_strlen($regs[0]);
    }
}

if (! function_exists('get_app_env')) {
    function get_app_env(): string {
        // TODO: returns the value in environment file
        if ((isset($GLOBALS['is_test']) and $GLOBALS['is_test'])) {
            return APP_ENV_TEST;
        }
        $env = getenv('APP_ENV');

        return empty($env)? APP_ENV_DEV: $env;
    }
}
