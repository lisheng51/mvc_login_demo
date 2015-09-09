<?php

namespace core;

/**
 * Function global
 * 
 * @author Lisheng Ye
 * @version 1.0
 */
class LisFunction
{

    /**
     * Get browser
     * 
     * @return string
     */
    public static function getBrowser()
    {
        return $_SERVER['HTTP_USER_AGENT'];
        //$arr_brower = get_browser(null, true);
        //return $arr_brower["browser"];
    }  
}
