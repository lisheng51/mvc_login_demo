<?php

namespace controller;

/**
 * Register controller
 *
 * @author Lisheng Ye
 */
class Register
{

    /**
     *
     * @var object
     */
    private $obj_register;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->obj_register = new \model\Register();
    }

    /**
     * Execute
     */
    public function execute()
    {
        $obj_login = new \model\Login();
        if ($obj_login->checkSession() === true) {
            header("location: /profiel");
            exit();
        }

        if (isset($_POST['submit']) === false) {
            return $this->showForm();
        }

        if (\core\Session::checkToken() === false) {
            return $this->showForm(\core\ErrorMsg::user("token"));
        }

        $error = $this->obj_register->checkData();
        if ($error !== "") {
            return $this->showForm(\core\ErrorMsg::user($error));
        }

        if ($this->obj_register->insertData() === true) {
            header("location: /login");
            exit();
        }

        return $this->showForm(\core\ErrorMsg::user("error"));
    }

    /**
     * Show register form
     *
     * @param string $message
     * @return string
     */
    private function showForm($message = null)
    {
        $temp = new \core\Template("index");
        $form = new \core\Template("form/register");
        $form->set("message", $message);
        $form->set("token", \core\Session::token());
        $temp->setRaw("form", $form->parse());
        return $temp->execute();
    }

}
