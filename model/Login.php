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
        $username = isset($_POST["username"]) ? trim((string) $_POST["username"]) : "";
        $password = isset($_POST["password"]) ? (string) $_POST["password"] : "";

        if ($password === "" || $username === "") {
            return false;
        }

        $this->username = $username;
        $this->password = $password;

        return true;
    }

    /**
     * Fetch user data, return empty array when username or password is wrong
     *
     * @return array
     */
    public function fetchData()
    {
        $sql_fetch = "
            SELECT
                uid,
                password
            FROM
                user_login
            WHERE
                lower(username) = lower(:username)
            LIMIT
                1
        ";
        try {
            $obj_result = $this->obj_db->prepare($sql_fetch);
            $obj_result->bindValue("username", $this->username, \PDO::PARAM_STR);
            $obj_result->execute();
            $arr_user = $obj_result->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return array();
        }

        if (empty($arr_user) === true || $this->verifyPassword($arr_user) === false) {
            return array();
        }
        return $arr_user;
    }

    /**
     * Verify password, old md5 password is upgraded to password_hash
     *
     * @param array $arr_user
     * @return boolean
     */
    private function verifyPassword($arr_user)
    {
        $stored = (string) $arr_user["password"];

        if (preg_match('/^[a-f0-9]{32}$/', $stored) === 1) {
            if (hash_equals($stored, md5($this->password)) === false) {
                return false;
            }
            $this->updatePassword($arr_user["uid"], $this->password);
            return true;
        }

        if (password_verify($this->password, $stored) === false) {
            return false;
        }
        if (password_needs_rehash($stored, PASSWORD_DEFAULT) === true) {
            $this->updatePassword($arr_user["uid"], $this->password);
        }
        return true;
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
        $uid = (int) $arr_user["uid"];
        $hash = bin2hex(random_bytes(32));

        if ($this->updateHash($uid, $hash) === true) {
            \core\Session::regenerate();
            \core\Session::set($this->loginsession, $uid . "_" . $hash);
            return true;
        }

        return false;
    }

    /**
     * Get uid from loginsession
     *
     * @return integer
     */
    public function getSessionUid()
    {
        $session = \core\Session::get($this->loginsession);
        if (is_string($session) === false || strpos($session, "_") === false) {
            return 0;
        }
        list ($uid) = explode("_", $session, 2);
        return (int) $uid;
    }

    /**
     * Check loginsession
     *
     * @return boolean
     */
    public function checkSession()
    {
        $session = \core\Session::get($this->loginsession);
        if (is_string($session) === false || strpos($session, "_") === false) {
            return false;
        }
        list ($uid, $hash) = explode("_", $session, 2);
        $uid = (int) $uid;
        if ($uid <= 0 || $hash === "") {
            return false;
        }
        $sql_fetch = "
            SELECT
                hash
            FROM
                user_login
            WHERE
                uid = :uid
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
        if (empty($arr_result["hash"]) === true) {
            return false;
        }
        return hash_equals($arr_result["hash"], $hash);
    }

    /**
     * Logout, remove loginhash and session
     *
     * @return void
     */
    public function logout()
    {
        $uid = $this->getSessionUid();
        if ($uid > 0 && $this->checkSession() === true) {
            $this->updateHash($uid, null);
        }
        \core\Session::remove($this->loginsession);
        \core\Session::regenerate();
    }

    /**
     * Update user loginhash
     *
     * @param interger $uid
     * @param string|null $hash
     * @return boolean
     */
    private function updateHash($uid, $hash)
    {
        if ($uid <= 0) {
            return false;
        }

        $sql_update = "
            UPDATE
                user_login
            SET
                hash= :hash
            WHERE
                uid = :uid
        ";

        try {
            $obj_result = $this->obj_db->prepare($sql_update);
            $obj_result->bindValue("uid", $uid, \PDO::PARAM_INT);
            $obj_result->bindValue("hash", $hash, $hash === null ? \PDO::PARAM_NULL : \PDO::PARAM_STR);
            $obj_result->execute();
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Update user password with password_hash
     *
     * @param interger $uid
     * @param string $password
     * @return boolean
     */
    private function updatePassword($uid, $password)
    {
        $sql_update = "
            UPDATE
                user_login
            SET
                password= :password
            WHERE
                uid = :uid
        ";

        try {
            $obj_result = $this->obj_db->prepare($sql_update);
            $obj_result->bindValue("uid", (int) $uid, \PDO::PARAM_INT);
            $obj_result->bindValue("password", password_hash($password, PASSWORD_DEFAULT), \PDO::PARAM_STR);
            $obj_result->execute();
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

}
