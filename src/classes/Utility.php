<?php

/**
 * Copyright (c) 2019 Nadav Tasher
 * https://github.com/NadavTasher/Template/
 **/

/**
 * Storage API for file management.
 */
class Utility
{
    // Path root
    public const ROOT = "/opt";

    // Path delimiter
    public const DELIMITER = ":";

    /**
     * Creates writable paths.
     * @param string $path Path
     * @param string $base Base directory
     * @return string Path
     */
    public static function path($path, $base = self::ROOT)
    {
        // Split name
        $split = explode(self::DELIMITER, $path, 2);
        // Check if we have to create a sub-path
        if (count($split) > 1) {
            // Append first path to the base
            $base = $base . DIRECTORY_SEPARATOR . $split[0];
            // Make sure the path exists
            if (!file_exists($base)) {
                mkdir($base);
            }
            // Return the path
            return self::path($split[1], realpath($base));
        }
        // Return the last path
        return $base . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * Removes paths recursively.
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
                Utility::remove($value);
            }
            // Remove directory
            rmdir($path);
        }
    }

    /**
     * Inserts paths.
     * @param string $path Path
     * @param mixed $contents Contents
     */
    public static function insert($path, $contents = null)
    {
        // Check whether contents are null
        if ($contents === null) {
            // Write contents to file
            file_put_contents($path, $contents);
        } else {
            // Create directory
            mkdir($path);
        }
    }

}