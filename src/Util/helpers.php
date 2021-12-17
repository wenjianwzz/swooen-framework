<?php
use Illuminate\Support\Str;

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

function echo_memory_usage($prefix='') {
    $mem_usage = memory_get_usage();
    if ($mem_usage < 1024) {
        echo $prefix . ' ' . $mem_usage." B" . PHP_EOL;
    } elseif ($mem_usage < 1024*1024) {
        echo $prefix . ' ' . round($mem_usage/1024,3)." KiB" . PHP_EOL;
    } elseif ($mem_usage < 1024*1024*1024) {
        echo $prefix . ' ' . round($mem_usage/1024/1024,3)." MiB" . PHP_EOL;
    } else {
        echo $prefix . ' ' . round($mem_usage/1024/1024/1024, 3)."GiB" . PHP_EOL;
    }
}