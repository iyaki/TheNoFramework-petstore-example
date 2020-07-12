<?php

// require __DIR__.'/../vendor/autoload.php';

// (function() {

//     function getFiles(string $dir, &$results = []) {
//         $files = scandir($dir);
//         foreach ($files as $file) {
//             $path = realpath($dir.DIRECTORY_SEPARATOR.$file);
//             if (!is_dir($path)) {
//                 $results[] = $path;
//             } else if ($file !== '.' && $file !== '..') {
//                 getFiles($path, $results);
//                 $results[] = $path;
//             }
//         }
//         return $results;
//     }

//     $filesToPreload = [...getFiles(__DIR__.'/../public')];
//     foreach ($filesToPreload as $fileToPreload) {
//         require/*_once*/ $fileToPreload;
//     }

// })();

$autoload = require __DIR__.'/../vendor/autoload.php';

$classesToPreload = [
    'TheNoFramework\ApplicationWrapper',
    'Psr\Http\Server\RequestHandlerInterface',
];

foreach ($classesToPreload as $classToPreload) {
    require $autoload->findFile($classToPreload);
}
