<?php

namespace core;

/**
 * LisPDO connection
 * 
 * @author Lisheng Ye
 * @version 1.0
 */
class LisPDO
{

    /**
     *
     * @var object $db 
     * @var boolean $isConnected 
     */
    private static $db;
    private static $isConnected;

    /**
     * Constructor
     * 
     * @return LisPDO
     */
    private function __construct()
    {
        self::$isConnected = true;
        $str_setting = "pgsql:host=" . DATABASE_HOST . ";port=" . DATABASE_PORT . ";dbname=" . DATABASE_NAME;
        try {
            self::$db = new \PDO($str_setting, DATABASE_USER, DATABASE_PASS);
            self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$db->exec("SET client_encoding TO 'UTF8'");
        } catch (\PDOException $e) {
            self::$isConnected = false;
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Disconnect the database
     */
    public static function disConnect()
    {
        self::$db = null;
        self::$isConnected = false;
    }

    /**
     * Get connection to database
     * 
     * @return obj $db
     */
    public static function connection()
    {
        if (empty(self::$db) === true) {
            new self();
        }
        return self::$db;
    }

}
