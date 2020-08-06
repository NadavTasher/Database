<?php

/**
 * Copyright (c) 2019 Nadav Tasher
 * https://github.com/NadavTasher/Storage/
 **/

include_once __DIR__ . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "Base.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "Database.php";

const PASSWORD_VARIABLE = "DATABASE_PASSWORD";

Base::handle(function ($action, $parameters) {
    // Check password environment variable
    if (getenv(PASSWORD_VARIABLE))
        if (!isset($parameters->password) || getenv(PASSWORD_VARIABLE) !== $parameters->password)
            throw new Error("Invalid password parameter");

    // Handle database requests
    return Database::handle($action, $parameters);
});