<?php

/**
 * Copyright (c) 2019 Nadav Tasher
 * https://github.com/NadavTasher/Template/
 **/

/**
 * Database API for application databases.
 */
class Database
{

    // Database root
    private const ROOT = "/opt";

    /**
     * Handles actions passed with parameters.
     * @param string $action Action
     * @param object $parameters Parameters
     * @return mixed Result
     */
    public static function handle($action, $parameters)
    {
        // Check scope parameter
        if (!isset($parameters->scope) || empty($parameters->scope))
            throw new Error("Invalid scope parameter");

        // Create the path for the scope
        $scope = self::child($parameters->scope, self::ROOT);

        if ($action === "insertScope") {
            // Make sure the scope does not exist
            if (file_exists($scope))
                throw new Error("Scope already exists");

            // Create the scope
            self::insert($scope);

            // Return success
            return null;
        }

        if ($action === "removeScope") {
            // Make sure the scope exists
            if (!file_exists($scope))
                throw new Error("Scope does not exist");

            // Create the scope
            self::remove($scope);

            // Return success
            return null;
        }

        if ($action === "checkScope") {
            // Check if scope exists
            return file_exists($scope);
        }

        // Make sure the scope exists
        if (!file_exists($scope))
            throw new Error("Scope does not exist");

        // Check table parameter
        if (!isset($parameters->table) || empty($parameters->table))
            throw new Error("Invalid table parameter");

        // Create the path for the table
        $table = self::child($parameters->table, $scope);

        if ($action === "insertTable") {
            // Make sure the table does not exist
            if (file_exists($table))
                throw new Error("Table already exists");

            // Create the table
            self::insert($table);

            // Return success
            return null;
        }

        if ($action === "removeTable") {
            // Make sure the table exists
            if (!file_exists($table))
                throw new Error("Table does not exist");

            // Create the table
            self::remove($table);

            // Return success
            return null;
        }

        if ($action === "checkTable") {
            // Check if table exists
            return file_exists($table);
        }

        // Make sure the table exists
        if (!file_exists($table))
            throw new Error("Table does not exist");

        // Check entry parameter
        if (!isset($parameters->entry) || empty($parameters->entry))
            throw new Error("Invalid entry parameter");

        // Create the path for the entry
        $entry = self::child($parameters->entry, $table);

        if ($action === "insertEntry") {
            // Make sure the entry does not exist
            if (file_exists($entry))
                throw new Error("Entry already exists");

            // Create the entry
            self::insert($entry);

            // Return success
            return null;
        }

        if ($action === "removeEntry") {
            // Make sure the entry exists
            if (!file_exists($entry))
                throw new Error("Entry does not exist");

            // Create the entry
            self::remove($entry);

            // Return success
            return null;
        }

        if ($action === "checkEntry") {
            // Check if entry exists
            return file_exists($entry);
        }

        // Make sure the entry exists
        if (!file_exists($entry))
            throw new Error("Entry does not exist");

        // Check cell parameter
        if (!isset($parameters->cell) || empty($parameters->cell))
            throw new Error("Invalid cell parameter");

        // Create the path for the cell
        $cell = self::child($parameters->cell, $entry);

        if ($action === "insertCell") {
            // Make sure the cell does not exist
            if (file_exists($cell))
                throw new Error("Cell already exists");

            // Create the cell
            touch($cell);

            // Return success
            return null;
        }

        if ($action === "removeCell") {
            // Make sure the cell exists
            if (!file_exists($cell))
                throw new Error("Cell does not exist");

            // Create the cell
            unlink($cell);

            // Return success
            return null;
        }

        if ($action === "checkCell") {
            // Check if cell exists
            return file_exists($cell);
        }

        // Make sure the cell exists
        if (!file_exists($cell))
            throw new Error("Cell does not exist");

        if ($action === "readCell") {
            // Read cell and return
            return file_get_contents($cell);
        }

        if ($action === "writeCell") {
            // Check value parameter
            if (!isset($parameters->value))
                throw new Error("Missing value parameter");

            // Write cell
            file_put_contents($cell, $parameters->value);

            // Return success
            return null;
        }

        // Throw unknown error
        throw new Error("Unknown action");
    }

    /**
     * Returns a child path for a given base path.
     * @param string $child Child path
     * @param string $parent Parent path
     * @return string Path
     */
    public static function child($child, $parent)
    {
        // Return combined path
        return $parent . DIRECTORY_SEPARATOR . bin2hex($child);
    }

    /**
     * Creates directories.
     * @param string $path Path
     */
    public static function insert($path)
    {
        // Create directory
        mkdir($path);
    }

    /**
     * Removes directories.
     * @param string $path Path
     */
    public static function remove($path)
    {
        // Check whether the path is a directory
        if (is_file($path)) {
            // Unlink file
            unlink($path);
        } else {
            // List subdirectories
            foreach (array_slice(scandir($path), 2) as $value) {
                self::remove($value);
            }
            // Remove directory
            rmdir($path);
        }
    }

}