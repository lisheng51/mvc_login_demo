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
            unset($_SESSION["$session"]);
        } else {
            session_destroy();
        }
    }

    /**
     * Give the session a new id, call this after login
     * 
     * @return void
     */
    public static function regenerate()
    {
        session_regenerate_id(true);
    }

    /**
     * Get csrf token for forms
     * 
     * @return string
     */
    public static function token()
    {
        if (empty($_SESSION["csrf_token"]) === true) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }
        return $_SESSION["csrf_token"];
    }

    /**
     * Check csrf token from post data
     * 
     * @return boolean
     */
    public static function checkToken()
    {
        if (isset($_POST["token"]) === false || is_string($_POST["token"]) === false) {
            return false;
        }
        return hash_equals(self::token(), $_POST["token"]);
    }

}
