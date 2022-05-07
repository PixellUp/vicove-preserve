<?php

if (!function_exists('env')) {
    /**
     * Get Env value from key
     *
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    function env($key, $default = null)
    {
        Dotenv\Dotenv::createMutable(__DIR__ . '/../../')->load();
        return $_SERVER[strtoupper($key)] ?: $default;
    }
}

if (!function_exists('now')) {
    /**
     * Return current datetime
     *
     * @return string
     */
    function now(): string
    {
        return (new DateTime('NOW', new DateTimeZone('Europe/Sofia')))->format('Y-m-d H:i:s');
    }
}

if (!function_exists('mb_ucfirst')) {
    /**
     * @param string $string
     * @param string $encoding
     *
     * @return string
     */
    function mb_ucfirst(string $string, string $encoding = 'UTF-8'): string
    {
        return mb_convert_case($string, MB_CASE_TITLE, $encoding);
    }
}
