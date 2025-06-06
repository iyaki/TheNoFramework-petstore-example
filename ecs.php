<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
    ])

    // add sets - group of rules
   ->withPreparedSets(
        psr12: true,
        common: true,
        strict: true,
        cleanCode: true
    )
;
