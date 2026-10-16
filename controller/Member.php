<?php

namespace controller;

/**
 * Member controller, for admin and member user
 *
 * @author Lisheng Ye
 */
class Member
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

        $temp = new \core\Template("member");
        $temp->set("name", $this->obj_profiel->arr_user["firstname"] . " " . $this->obj_profiel->arr_user["surname"]);
        $temp->set("token", \core\Session::token());
        return $temp->execute();
    }

}
