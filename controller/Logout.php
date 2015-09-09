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
     * Execute
     */
    public function execute()
    {
        \core\Session::remove($this->obj_login->loginsession);
        header("location: /login");
        exit();
    }

}
