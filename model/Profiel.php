<?php

namespace model;

/**
 * Profiel model
 * 
 * @author Lisheng Ye
 */
class Profiel extends User
{

    /**
     *
     * @var boolean $isadmin
     * @var boolean $ismember
     * @var array $access 
     */
    protected $isadmin;
    protected $ismember;
    protected $access;

    /**
     * Constructor
     * 
     * @return Profiel
     */
    public function __construct()
    {
        parent::__construct();
        $this->ismember = false;
        $this->isadmin = false;
        $this->access = array();
        if ($this->loginCheck() === true) {
            $this->fetchUserAccess();
        }
    }

    /**
     * Fetch user access
     * 
     */
    private function fetchUserAccess()
    {
        switch ($this->getGroupname()) {
            case 'Admin':
                $this->isadmin = true;
                $this->access = array("add", "edit", "remove", "search");
                break;
            case 'Member':
                $this->ismember = true;
                $this->access = array("view", "email", "search");
                break;
        }
    }

    /**
     * Show access value
     * 
     * @return string $string
     */
    public function showAccess()
    {
        $string = "";
        if (empty($this->access) === false) {
            foreach ($this->access as $value) {
                $string .= ' - ' . $value;
            }
        } else {
            $string .= "No access!!";
        }

        return $string;
    }

}
