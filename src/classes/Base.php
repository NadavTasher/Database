<?php

/**
 * Copyright (c) 2019 Nadav Tasher
 * https://github.com/NadavTasher/Template/
 **/

/**
 * Base API for general utilities.
 */
class Base
{
    /**
     * Handles API calls by handing them over to the callback.
     * @param callable $callback Callback to handle the request
     */
    public static function handle($callback)
    {
        // Initialize the response
        $result = new stdClass();
        // Initialize the action
        if (count($_GET) > 0) {
            // Get the action
            $requestAction = array_key_first($_GET);
            // Parse the parameters
            $requestParameters = new stdClass();
            // Loop over GET parameters
            foreach ($_GET as $name => $value) {
                if (is_string($value))
                    $requestParameters->$name = $value;
            }
            // Loop over POST parameters
            foreach ($_POST as $name => $value) {
                if (is_string($value))
                    $requestParameters->$name = $value;
            }
            // Unset the action
            unset($requestParameters->$requestAction);
            // Execute the call
            try {
                $result->result = $callback($requestAction, $requestParameters);
                $result->status = true;
            } catch (Error $error) {
                $result->result = $error->getMessage();
                $result->status = false;
            }
        }
        // Change the content type
        header("Content-Type: application/json");
        // Echo response
        echo json_encode($result);
    }

    /**
     * Creates random strings.
     * @param int $length Length
     * @return string String
     */
    public static function random($length = 0)
    {
        if ($length > 0) {
            return str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz")[0] . self::random($length - 1);
        }
        return "";
    }
}