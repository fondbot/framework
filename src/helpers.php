<?php

declare(strict_types=1);

if (!function_exists('env')) {
    /**
     * Get environment variable value.
     *
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? null;

        if ($value === null) {
            return $default;
        }

        switch (mb_strtolower($value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'null':
                return null;
        }

        return $value;
    }
}
