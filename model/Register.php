<?php

namespace model;

/**
 * Register model
 *
 * @author Lisheng Ye
 */
class Register extends Model
{

    /**
     *
     * @var array
     */
    private $arr_data = array();

    /**
     * Check post data
     *
     * @return string error type, empty when ok
     */
    public function checkData()
    {
        $fields = array("username", "password", "firstname", "surname");
        foreach ($fields as $field) {
            $this->arr_data[$field] = isset($_POST[$field]) ? (string) $_POST[$field] : "";
        }
        $this->arr_data["username"] = trim($this->arr_data["username"]);
        $this->arr_data["firstname"] = mb_substr(trim($this->arr_data["firstname"]), 0, 100);
        $this->arr_data["surname"] = mb_substr(trim($this->arr_data["surname"]), 0, 100);

        if ($this->arr_data["username"] === "" || $this->arr_data["password"] === "") {
            return "emptyckdata";
        }
        if (mb_strlen($this->arr_data["username"]) > 50) {
            return "invalid";
        }
        if ($this->usernameExists() === true) {
            return "doubledata";
        }
        return "";
    }

    /**
     * Check if username is used
     *
     * @return boolean
     */
    private function usernameExists()
    {
        $sql_fetch = "
            SELECT
                uid
            FROM
                user_login
            WHERE
                lower(username) = lower(:username)
            LIMIT
                1
        ";
        $obj_result = $this->obj_db->prepare($sql_fetch);
        $obj_result->bindValue("username", $this->arr_data["username"], \PDO::PARAM_STR);
        $obj_result->execute();
        return $obj_result->fetch(\PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Insert new user, group 1 is member
     *
     * @return boolean
     */
    public function insertData()
    {
        $sql_login = "
            INSERT INTO
                user_login (username, password)
            VALUES
                (:username, :password)
            RETURNING
                uid
        ";
        $sql_user = "
            INSERT INTO
                users (uid, firstname, surname, email, gender, \"group\")
            VALUES
                (:uid, :firstname, :surname, '', '', 1)
        ";
        try {
            $this->obj_db->beginTransaction();

            $obj_result = $this->obj_db->prepare($sql_login);
            $obj_result->bindValue("username", $this->arr_data["username"], \PDO::PARAM_STR);
            $obj_result->bindValue("password", password_hash($this->arr_data["password"], PASSWORD_DEFAULT), \PDO::PARAM_STR);
            $obj_result->execute();
            $uid = (int) $obj_result->fetchColumn();

            $obj_result = $this->obj_db->prepare($sql_user);
            $obj_result->bindValue("uid", $uid, \PDO::PARAM_INT);
            $obj_result->bindValue("firstname", $this->arr_data["firstname"], \PDO::PARAM_STR);
            $obj_result->bindValue("surname", $this->arr_data["surname"], \PDO::PARAM_STR);
            $obj_result->execute();

            $this->obj_db->commit();
            return true;
        } catch (\PDOException $e) {
            if ($this->obj_db->inTransaction() === true) {
                $this->obj_db->rollBack();
            }
            return false;
        }
    }

}
