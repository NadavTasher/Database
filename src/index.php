<?php

/**
 * Copyright (c) 2019 Nadav Tasher
 * https://github.com/NadavTasher/Storage/
 **/

include_once __DIR__ . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "Base.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "Storage.php";

Base::handle(function ($action, $parameters) {
    return Storage::handle($action, $parameters);
});