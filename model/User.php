<?php

namespace model;

/**
 * User model
 *
 * @author Lisheng Ye
 */
class User extends Model
{

    /**
     *
     * @var array 
     */
    public $arr_user = array();
    
    /**
     * Check login status
     * 
     * @return boolean
     */
    protected function loginCheck() {
        $obj_login = new \model\Login();
        if ($obj_login->checkSession() === true) {
            list ($uid, $hash) = explode("_", \core\Session::get($obj_login->loginsession));
            $this->arr_user = $this->fetchData($uid);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get user group
     * 
     * @return string
     */
    public function getGroupname() {
        return ($this->arr_user["group"] == 2) ? "Admin" : "Member";
    }
    
    /**
     * Get group url
     * 
     * @return string
     */
    public function getGroupUrl() {
        return ($this->arr_user["group"] == 2) ? "admin.php" : "member.php";
    }

    
    /**
     * Fetch user data
     * 
     * @param integer $uid
     * 
     * @return array $value
     */
    public function fetchData($uid) {
        $uid = (int) $uid;
        if ($uid < 0) {
            return array();
        }
        $sql_fetch = "
            SELECT 
                *
            FROM
               `users` 
            WHERE
              `uid` = :uid
        ";
        try {
            $obj_result = $this->obj_db->prepare($sql_fetch);
            $obj_result->bindValue("uid", $uid, \PDO::PARAM_INT);
            $obj_result->execute();
            return $obj_result->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return array();
        }
    }
}
