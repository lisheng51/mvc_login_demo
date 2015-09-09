<?php

namespace controller;

/**
 * Profiel controller
 *
 * @author Lisheng Ye
 */
class Profiel
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
        if (empty($this->obj_profiel->arr_user) === false) {
            $this->show();
        } else {
            header("location: /login");
            exit();
        }
    }

    /**
     * Show profiel page
     * 
     * @return string
     */
    public function show()
    {
        $getgendervalue = ($this->obj_profiel->arr_user["gender"] == 'male') ? 'selected' : '';
        $getgendervalue2 = ($this->obj_profiel->arr_user["gender"] == 'female') ? 'selected' : '';

        $temp = new \core\Template("profiel");

        $temp->set("getgendervalue", $getgendervalue);
        $temp->set("getgendervalue2", $getgendervalue2);
        $temp->set("email", $this->obj_profiel->arr_user["email"]);
        $temp->set("surname", $this->obj_profiel->arr_user["surname"]);
        $temp->set("firstname", $this->obj_profiel->arr_user["firstname"]);
        $temp->set("groupurl", $this->obj_profiel->getGroupUrl());
        $temp->set("accesslist", $this->obj_profiel->showAccess());
        $temp->set("groupname", $this->obj_profiel->getGroupname());

        return $temp->execute();
    }

}
