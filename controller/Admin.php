<?php

namespace controller;

/**
 * Admin controller, only for admin user
 *
 * @author Lisheng Ye
 */
class Admin
{

    /**
     *
     * @var object
     */
    private $obj_profiel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->obj_profiel = new \model\Profiel();
    }

    /**
     * Execute
     */
    public function execute()
    {
        if (empty($this->obj_profiel->arr_user) === true) {
            header("location: /login");
            exit();
        }
        if ($this->obj_profiel->isAdmin() === false) {
            http_response_code(403);
            echo htmlspecialchars(\core\ErrorMsg::module("noaccess"), ENT_QUOTES, 'UTF-8');
            return;
        }

        $temp = new \core\Template("admin");
        $temp->set("name", $this->obj_profiel->arr_user["firstname"] . " " . $this->obj_profiel->arr_user["surname"]);
        $temp->set("accessmodule", $this->obj_profiel->showAccess());
        $temp->set("token", \core\Session::token());
        return $temp->execute();
    }

}
