<?php

class Config
{
    public static function DB_NAME() {
        return Config::get_env("DB_NAME", "handball_management_system");
    }
    public static function DB_PORT() {
        return Config::get_env("DB_PORT", 25060);
    }
    public static function DB_USER() {
        return Config::get_env("DB_USER", 'doadmin');
    }
    public static function DB_PASSWORD() {
        return Config::get_env("DB_PASSWORD", '');
    }
    public static function DB_HOST() {
        return Config::get_env("DB_HOST", 'db-mysql-nyc3-47797-do-user-31160656-0.l.db.ondigitalocean.com');
    }
    public static function JWT_SECRET() {
        return Config::get_env("JWT_SECRET", 'memo');
    }
    public static function get_env($name, $default){
        return isset($_ENV[$name]) && trim($_ENV[$name]) != "" ? $_ENV[$name] : $default;
    }
}

// Set the reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));




