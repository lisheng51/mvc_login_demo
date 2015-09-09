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

        if ($this->obj_login->checkSession() === true) {
            header("location: /profiel");
            exit();
        }
    }

    /**
     * Execute
     */
    public function execute()
    {
        if (isset($_POST['submit']) === true) {
            $this->checkData();
            $arr_user = $this->obj_login->fetchData();

            if (empty($arr_user) === true) {
                return $this->showForm(\core\ErrorMsg::user("invalid"));
                exit();
            }

            if ($this->obj_login->checkHash($arr_user) === true) {
                header("location: /profiel");
                exit();
            }
        } else {
            $this->showForm();
        }
    }

    /**
     * Check post data
     *
     * @return string
     */
    private function checkData()
    {
        if ($this->obj_login->checkData() === false) {
            return $this->showForm(\core\ErrorMsg::user("emptyckdata"));
        }
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
        $temp->set("form", $form->parse());
        return $temp->execute();
    }

}
