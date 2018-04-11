<?php
namespace App;

class Config
{
    public static  $host, $email, $pass, $dateFormat, $maxEmails, $shown;
    private static $path = "../config.json", $configs;
    
    public static function read(){
        $file = fopen(Config::$path, "r");
        Config::$configs = json_decode(fread($file, filesize(Config::$path)));

        Config::$host = Config::$configs->host != '' ? Config::$configs->host : '{imap.gmail.com:993/imap/ssl}INBOX';
        Config::$email = Config::$configs->email != '' ? Config::$configs->email : '';
        Config::$pass = Config::$configs->password != '' ? Config::$configs->password : '';
        Config::$dateFormat = Config::$configs->dateFormat != '' ? Config::$configs->dateFormat : 'd/m/Y';
        Config::$maxEmails = isset(Config::$configs->maxEmails) ? Config::$configs->maxEmails : 20;
        Config::$shown = isset(Config::$configs->shown) ? Config::$configs->shown : 50;

        fclose($file);
    }

    public static function getVar(String $var, String $default = ''){
        if(isset(Config::$configs) && isset(Config::$configs->$var))
            return Config::$configs->$var;

        return $default;
    }
}