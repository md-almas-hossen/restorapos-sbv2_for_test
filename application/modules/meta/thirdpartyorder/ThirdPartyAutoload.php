<?php

class ThirdPartyAutoload
{
    public static function init($class)
    {
        // project-specific namespace prefix
        $prefix = 'Meta\\ThirdPartyOrder\\';

        $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

        // does the class use the namespace prefix?
        $len = strlen($prefix);

        // get the relative class name
        $originalClass = substr($class, $len);

        $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $originalClass) . '.php';
        // if the file exists, require it

        if (file_exists($file)) {
            require $file;
        }
    }
}
