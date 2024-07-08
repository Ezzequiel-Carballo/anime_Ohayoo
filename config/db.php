<?php

class Database {
    private static $dbHost = '193.203.175.35';
    private static $dbUser = 'u924030861_Zekken';
    private static $dbPass = 'Zekken_9139';
    private static $dbName = 'u924030861_animeohayoo';

    public static function conexion() {
        $db = new mysqli(self::$dbHost, self::$dbUser, self::$dbPass, self::$dbName);

        // Check connection
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        return $db;
    }

    public static function conectar() {
        try {
            $conex = new PDO(
                'mysql:host=' . self::$dbHost . ';dbname=' . self::$dbName,
                self::$dbUser,
                self::$dbPass,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
            return $conex;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
