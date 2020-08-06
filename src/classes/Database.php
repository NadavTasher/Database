<?php

/**
 * Copyright (c) 2020 Nadav Tasher
 * https://github.com/NadavTasher/Database/
 **/

/**
 * Database API for application databases.
 */
class Database
{

    // Database root
    private const ROOT = "/opt";

    /**
     * Handles database requests with action and parameters.
     * @param string $action Action
     * @param array $parameters Parameters
     * @return mixed Result
     */
    public static function handle($action, $parameters)
    {
        // Make sure the request has a token parameter
        if (!isset($parameters->token) || empty($parameters->token))
            throw new Error("Missing token parameter");

        // Make sure the token is rightfully signed
        $authorized = Token::validate($parameters->token);

        // Create a request scope
        $requested = array();

        // Check whether the scope parameter is set
        if (isset($parameters->scope) && !empty($parameters->scope)) {
            // Push scope parameter to requested scope
            array_push($requested, $parameters->scope);

            // Check whether the table parameter is set
            if (isset($parameters->table) && !empty($parameters->table)) {
                // Push table parameter to requested scope
                array_push($requested, $parameters->table);

                // Check whether the entry parameter is set
                if (isset($parameters->entry) && !empty($parameters->entry)) {
                    // Push entry parameter to requested scope
                    array_push($requested, $parameters->entry);
                }
            }
        }

        // Make sure the requested scope is within the control scope of the token
        if (count($authorized) > count($requested))
            throw new Error("Unauthorized token scope");

        foreach ($authorized as $index => $value)
            if ($requested[$index] !== $value)
                throw new Error("Unauthorized token scope");

        // Assemble a path from the requested scope
        $path = self::ROOT;

        // Loop over children and append to path
        foreach ($requested as $scope) {
            // Make sure the parent path exists before appending the child path
            if (!file_exists($path))
                throw new Error("Scope does not exist");

            // Append child to path
            $path .= DIRECTORY_SEPARATOR . bin2hex($scope);
        }

        // Check whether the requested action is "check"
        if ($action === "check") {
            // Check whether the path exists
            return file_exists($path);
        }

        // Check whether the requested action is "insert"
        if ($action === "insert") {
            // Make sure the path does not exist
            if (file_exists($path))
                throw new Error("Scope already exists");

            // Create the path
            self::touch($path);

            // Issue and return a token
            return Token::issue($requested);
        }

        // Check whether the requested action is "remove"
        if ($action === "remove") {
            // Make sure the path exists
            if (!file_exists($path))
                throw new Error("Scope does not exist");

            // Create the path
            self::unlink($path);

            // Return success
            return true;
        }

        // Make sure the requested scope exists before allowing keystore access
        if (!file_exists($path))
            throw new Error("Scope does not exist");

        // Make sure the requested scope points to an entry before allowing keystore access
        if (count($requested) === 3) {
            // Make sure the request has a key parameter
            if (!isset($parameters->key) || empty($parameters->key))
                throw new Error("Missing key parameter");

            // Make sure the request has a value parameter
            if (!isset($parameters->value) || empty($parameters->value))
                throw new Error("Missing value parameter");

            // Extend the path
            $path .= DIRECTORY_SEPARATOR . bin2hex($parameters->key);

            // Check whether the requested action is "read"
            if ($action === "read") {
                // Check whether the path exists
                if (!file_exists($path))
                    // Return fallback value
                    return $parameters->value;

                // Read path and return
                return file_get_contents($path);
            }

            // Check whether the requested action is "write"
            if ($action === "write") {
                // Write path
                file_put_contents($path, $parameters->value);

                // Return success
                return true;
            }
        }

        throw new Error("Unknown action");
    }

    /**
     * Makes directories.
     * @param string $path Path
     */
    private static function touch($path)
    {
        // Create directory
        mkdir($path);
    }

    /**
     * Removes directories.
     * @param string $path Path
     */
    private static function unlink($path)
    {
        // Check whether the path is a directory
        if (is_file($path)) {
            // Unlink file
            unlink($path);
        } else {
            // List child paths
            $paths = scandir($path);

            // Slice child paths
            $paths = array_slice($paths, 2);

            // Unlink child paths
            foreach ($paths as $value)
                self::unlink($value);

            // Remove directory
            rmdir($path);
        }
    }
}