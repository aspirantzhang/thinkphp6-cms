<?php

if (!function_exists('success')) {
    function success(string $message = '', array $data = [], int $code = 200, array $header = [])
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => $code,
            'header' => $header,
        ];
    }
}

if (!function_exists('error')) {
    function error(string $message = '', array $data = [], int $code = 200, array $header = [])
    {
        return [
            'success' => false,
            'message' => $message,
            'data' => $data,
            'code' => $code,
            'header' => $header,
        ];
    }
}

if (!function_exists('missingRequiredValues')) {
    /**
     * Check if the array is missing specified keys or those key's value are empty.
     *
     * E.g. checkRequiredValues($haystack, ['foo', 'bar']).
     *
     * @return string|bool returns the first missing key name, or false for no missing
     */
    function missingRequiredValues(array $array, array $requiredKeys = [])
    {
        foreach ($requiredKeys as $key) {
            if ((($array[$key] ?? false) && !empty($array[$key])) === false) {
                return $key;
            }
        }

        return false;
    }
}
