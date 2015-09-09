<?php

namespace model;

/**
 * Model global
 *
 * @author Lisheng Ye
 */
class Model
{
    /**
     *
     * @var object
     */
    protected $obj_db;
    
    /**
     * Constructor
     * 
     * @return Model
     */
    public function __construct()
    {
        $this->obj_db = \core\LisPDO::connection();
    }
    
}