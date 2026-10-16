<?php

namespace controller;

/**
 * Logout controller
 *
 * @author Lisheng Ye
 */
class Logout
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
     * Execute, only logout with post and valid token
     */
    public function execute()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && \core\Session::checkToken() === true) {
            $this->obj_login->logout();
            header("location: /login");
        } else {
            header("location: /profiel");
        }
        exit();
    }

}
