<?php

class Autoloader {

    public static $autoloadPaths = [];

    public function registerAutoloadPath($namespace,$path) {
        self::$autoloadPaths[$namespace] = $path;
    }

    public static function autoload($name) {

        $basePath = isset($_SERVER["SCRIPT_FILENAME"]) ? dirname($_SERVER["SCRIPT_FILENAME"]) : $_SERVER['argv'][0];
        if( !$basePath ) $basePath = '.';
        $fileName = $basePath . "/" . $name . ".php";
        $fileName = str_replace('\\',"/",$fileName);
        if( file_exists($fileName) ) return require_once($fileName);
    }

}

spl_autoload_register(function($name){
    return Autoloader::autoload($name);
});
