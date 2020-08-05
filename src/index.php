<?php

/**
 * Copyright (c) 2019 Nadav Tasher
 * https://github.com/NadavTasher/Storage/
 **/

include_once __DIR__ . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "Base.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "Utility.php";

Base::handle(function ($action, $parameters) {
    // Make sure the root path exists
    if (!file_exists(Utility::ROOT))
        mkdir(Utility::ROOT);

    // Check scope parameter
    if (!isset($parameters->scope))
        throw new Error("Missing scope parameter");

    // Create the path for the scope
    $scope = Utility::path(bin2hex($parameters->scope));

    if ($action === "insertScope") {
        // Make sure the scope does not exist
        if (file_exists($scope))
            throw new Error("Scope already exists");

        // Create the scope
        Utility::insert($scope);

        // Return success
        return null;
    }

    if ($action === "removeScope") {
        // Make sure the scope exists
        if (!file_exists($scope))
            throw new Error("Scope does not exist");

        // Create the scope
        Utility::remove($scope);

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
    if (!isset($parameters->table))
        throw new Error("Missing table parameter");

    // Create the path for the table
    $table = Utility::path(bin2hex($parameters->table), $scope);

    if ($action === "insertTable") {
        // Make sure the table does not exist
        if (file_exists($table))
            throw new Error("Table already exists");

        // Create the table
        Utility::insert($table);

        // Return success
        return null;
    }

    if ($action === "removeTable") {
        // Make sure the table exists
        if (!file_exists($table))
            throw new Error("Table does not exist");

        // Create the table
        Utility::remove($table);

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
    if (!isset($parameters->entry))
        throw new Error("Missing entry parameter");

    // Create the path for the entry
    $entry = Utility::path(bin2hex($parameters->entry), $table);

    if ($action === "insertEntry") {
        // Make sure the entry does not exist
        if (file_exists($entry))
            throw new Error("Entry already exists");

        // Create the entry
        Utility::insert($entry);

        // Return success
        return null;
    }

    if ($action === "removeEntry") {
        // Make sure the entry exists
        if (!file_exists($entry))
            throw new Error("Entry does not exist");

        // Create the entry
        Utility::remove($entry);

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
    if (!isset($parameters->cell))
        throw new Error("Missing cell parameter");

    // Create the path for the cell
    $cell = Utility::path(bin2hex($parameters->cell), $entry);

    if ($action === "insertCell") {
        // Make sure the cell does not exist
        if (file_exists($cell))
            throw new Error("Cell already exists");

        // Create the cell
        Utility::insert($cell, "null");

        // Return success
        return null;
    }

    if ($action === "removeCell") {
        // Make sure the cell exists
        if (!file_exists($cell))
            throw new Error("Cell does not exist");

        // Create the cell
        Utility::remove($cell);

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
});