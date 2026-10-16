<?php

namespace controller;

/**
 * Login controller
 *
 * @author Lisheng Ye
 */
class Login
{

    /**
     *
     * @var object
     */
    private $obj_login;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->obj_login = new \model\Login();
    }

    /**
     * Execute
     */
    public function execute()
    {
        if ($this->obj_login->checkSession() === true) {
            header("location: /profiel");
            exit();
        }

        if (isset($_POST['submit']) === false) {
            return $this->showForm();
        }

        if (\core\Session::checkToken() === false) {
            return $this->showForm(\core\ErrorMsg::user("token"));
        }

        if ($this->obj_login->checkData() === false) {
            return $this->showForm(\core\ErrorMsg::user("emptyckdata"));
        }

        $arr_user = $this->obj_login->fetchData();
        if (empty($arr_user) === true) {
            return $this->showForm(\core\ErrorMsg::user("invalid"));
        }

        if ($this->obj_login->makeHash($arr_user) === true) {
            header("location: /profiel");
            exit();
        }

        return $this->showForm(\core\ErrorMsg::user("error"));
    }

    /**
     * Show login form
     *
     * @param string $message
     * @return string
     */
    private function showForm($message = null)
    {
        $temp = new \core\Template("index");
        $form = new \core\Template("form/login");
        $form->set("message", $message);
        $form->set("token", \core\Session::token());
        $temp->setRaw("form", $form->parse());
        return $temp->execute();
    }

}
