<?php

namespace model;

/**
 * Login model
 *
 * @author Lisheng Ye
 */
class Login extends Model
{

    /**
     *
     * @var string
     */
    private $username;

    /**
     *
     * @var string 
     */
    private $password;

    /**
     *
     * @var string 
     */
    public $loginsession = 'login_user_session';

    /**
     * Check if empty post data
     * 
     * @return boolean
     */
    public function checkData()
    {
        $username = strip_tags($_POST["username"]);
        $password = strip_tags($_POST["password"]);

        if (empty($password) === true || empty($username) === true) {
            return false;
        }

        $this->username = $username;
        $this->password = md5($password);

        return true;
    }

    /**
     * Fetch user data
     * 
     * @return array
     */
    public function fetchData()
    {
        $sql_fetch = "
            SELECT 
                `uid`,
                `password`
            FROM
                `user_login` 
            WHERE
                `username` = :username
            AND
                `password` = :password 
            LIMIT 
                1
        ";
        try {
            $obj_result = $this->obj_db->prepare($sql_fetch);
            $obj_result->bindValue("username", $this->username, \PDO::PARAM_STR);
            $obj_result->bindValue("password", $this->password, \PDO::PARAM_STR);
            $obj_result->execute();
            return $obj_result->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return array();
        }
    }

    /**
     * Make loginhash
     * 
     * @param array $arr_user
     * @return boolean
     */
    public function makeHash($arr_user)
    {
        if (empty($arr_user) === true) {
            return false;
        }
        $uid = $arr_user["uid"];
        $hash = md5($uid . $_SERVER['REMOTE_ADDR'] . $this->password . \core\LisFunction::getBrowser());

        if ($this->updateHash($uid, $hash) === true) {
            \core\Session::set($this->loginsession, $uid . "_" . $hash);
            return true;
        }

        return false;
    }

    /**
     * Check loginsession
     * 
     * @return boolean
     */
    public function checkSession()
    {
        if (\core\Session::get($this->loginsession) === null) {
            return false;
        }
        list ($uid, $hash) = explode("_", \core\Session::get($this->loginsession));
        if ($uid < 0) {
            return false;
        }
        $sql_fetch = "
            SELECT 
                `hash`
            FROM
                `user_login` 
            WHERE
                `uid` = :uid
            LIMIT 
                1
        ";
        try {
            $obj_result = $this->obj_db->prepare($sql_fetch);
            $obj_result->bindValue("uid", $uid, \PDO::PARAM_INT);
            $obj_result->execute();
            $arr_result = $obj_result->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
        if ($arr_result['hash'] === $hash) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update user loginhash
     * 
     * @param interger $uid
     * @param string $hash
     * @return boolean
     */
    private function updateHash($uid, $hash)
    {
        if ($uid < 0 || empty($hash) === true) {
            return false;
        }

        $sql_update = "
            UPDATE 
                `user_login`
            SET
                `hash`= :hash
            WHERE
                `uid` = :uid
        ";

        try {
            $obj_result = $this->obj_db->prepare($sql_update);
            $obj_result->bindValue("uid", $uid, \PDO::PARAM_INT);
            $obj_result->bindValue("hash", $hash, \PDO::PARAM_STR);
            $obj_result->execute();
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

}
