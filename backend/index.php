<?php

/**
 * Copyright (c) 2019 Nadav Tasher
 * https://github.com/NadavTasher/Storage/
 **/

include_once __DIR__ . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "Base.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "Token.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "Database.php";

const VARIABLE = "DATABASE_PASSWORD";

Base::handle(function ($action, $parameters) {
    // Check whether the action requires a master token creation
    if ($action === "issue") {
        // Make sure all parameters are present
        if (!isset($parameters->password) || empty($parameters->password))
            throw new Error("Missing password parameter");

        // Check whether the password matches the stored password
        if ($parameters->password !== getenv(VARIABLE))
            throw new Error("Invalid password parameter");

        // Generate a new master with one year of access
        return Token::issue([], 60 * 60 * 24 * 365);
    }

    // Handle database logic requests
    return Database::handle($action, $parameters);
});