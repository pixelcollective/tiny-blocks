<?php

namespace TinyPixel;

spl_autoload_register(function ($className) {
    if (strpos($className, __NAMESPACE__) !== 0) {
        return;
    }

    $relativeClassName = array_slice(explode('\\', $className), 2);

    $file = __DIR__ . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $relativeClassName) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});
