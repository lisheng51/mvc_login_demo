<?php

namespace core;

/**
 * Session
 * 
 * @author Lisheng Ye
 * @version 1.0
 */
class Session
{

    /**
     * Get session
     * 
     * @return string
     */
    public static function get($value)
    {
        if (isset($_SESSION["$value"]) === true) {
            return $_SESSION["$value"];
        }
        return null;
    }

    /**
     * Set session
     * 
     * @return void
     */
    public static function set($session, $value)
    {
        if (empty($value) === false) {
            $_SESSION["$session"] = $value;
        }
        return;
    }
    
    /**
     * Remove session
     * 
     * @param type string
     */
    public static function remove($session = null)
    {
        if (empty($session) === false) {
            $_SESSION["$session"] = null;
        } else {
            session_destroy();
        }
    }

}
