<?php

declare(strict_types=1);

(function () {
    if (! \file_exists(__DIR__ . '/../vendor/autoload.php')) {
        if (\basename($_SERVER["SCRIPT_FILENAME"], '.php') !== 'composer') {
            \trigger_error('composer autoload file not found. Please run `composer install`');
        }

        return;
    }

    require __DIR__ . '/../vendor/autoload.php';
})();
