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
        $str_setting = "mysql:host=" . DATABASE_HOST . ";dbname=" . DATABASE_NAME . ";port=" . DATABASE_PORT . ", " . DATABASE_USER . ", " . DATABASE_PASS . "";
        try {
            self::$db = new \PDO($str_setting);
            self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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
