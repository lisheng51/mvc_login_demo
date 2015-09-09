<?php

namespace core;

/**
 * Error message
 * 
 * @author Lisheng Ye
 * @version 1.0
 */
class ErrorMsg
{

    public static function user($type)
    {
        switch ($type) {
            case 'emptyckdata':
                return "Username or password is empty!";
                break;
            case 'doubledata':
                return "Username is used!";
                break;
            case 'error':
                return "Can not insert!";
                break;
            case 'invalid':
                return "username or password is invalid!";
                break;
            default:
                return "no define type";
                break;
        }
        return;
    }

    public static function admin($num) {
        switch ($num) {
            case 1:
                $str_msg = "Wrong group";
                break;
            case 2:
                $str_msg = "Access_module is empty";
                break;
            default:
                $str_msg = "no define number";
                break;
        }
        return "Lis Error($num): " . $msg;
    }
    
    public static function module($type)
    {
        switch ($type) {
            case 'noaccess':
                return "access is not comlete!";
                break;
            default:
                return "no define type";
                break;
        }
        return;
    }
}
