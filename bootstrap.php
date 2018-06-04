<?php

$autoloadFile = realpath(__DIR__.'/../../autoload.php');

if (!file_exists($autoloadFile)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

require_once $autoloadFile;
