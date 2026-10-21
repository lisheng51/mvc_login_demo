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
            $this->arr_user = $this->fetchData($obj_login->getSessionUid());
            return empty($this->arr_user) === false;
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
        return (isset($this->arr_user["group"]) === true && $this->arr_user["group"] == 2) ? "Admin" : "Member";
    }

    /**
     * Get group url
     *
     * @return string
     */
    public function getGroupUrl() {
        return ($this->getGroupname() === "Admin") ? "/admin" : "/member";
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
        if ($uid <= 0) {
            return array();
        }
        $sql_fetch = "
            SELECT
                *
            FROM
               users
            WHERE
              uid = :uid
        ";
        try {
            $obj_result = $this->obj_db->prepare($sql_fetch);
            $obj_result->bindValue("uid", $uid, \PDO::PARAM_INT);
            $obj_result->execute();
            $arr_user = $obj_result->fetch(\PDO::FETCH_ASSOC);
            return ($arr_user === false) ? array() : $arr_user;
        } catch (\PDOException $e) {
            return array();
        }
    }

    /**
     * Update user data from post
     *
     * @return string error type, empty when saved
     */
    public function updateData() {
        if (empty($this->arr_user) === true) {
            return "error";
        }
        $firstname = isset($_POST["firstname"]) ? trim((string) $_POST["firstname"]) : "";
        $surname = isset($_POST["surname"]) ? trim((string) $_POST["surname"]) : "";
        $email = isset($_POST["email"]) ? trim((string) $_POST["email"]) : "";
        $gender = isset($_POST["gender"]) ? (string) $_POST["gender"] : "";

        if ($email !== "" && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return "email";
        }
        if (in_array($gender, array("", "male", "female"), true) === false) {
            $gender = "";
        }

        $sql_update = "
            UPDATE
                users
            SET
                firstname = :firstname,
                surname = :surname,
                email = :email,
                gender = :gender
            WHERE
                uid = :uid
        ";
        try {
            $obj_result = $this->obj_db->prepare($sql_update);
            $obj_result->bindValue("firstname", mb_substr($firstname, 0, 100), \PDO::PARAM_STR);
            $obj_result->bindValue("surname", mb_substr($surname, 0, 100), \PDO::PARAM_STR);
            $obj_result->bindValue("email", mb_substr($email, 0, 255), \PDO::PARAM_STR);
            $obj_result->bindValue("gender", $gender, \PDO::PARAM_STR);
            $obj_result->bindValue("uid", (int) $this->arr_user["uid"], \PDO::PARAM_INT);
            $obj_result->execute();
        } catch (\PDOException $e) {
            return "error";
        }
        $this->arr_user = $this->fetchData($this->arr_user["uid"]);
        return "";
    }
}
