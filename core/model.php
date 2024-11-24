<?php
// require_once 'core/imodel.php';  
class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database(); 
    }

    function query($query)
    {
        return $this->db->connect()->query($query);
    }

    function prepare($query)
    {
        return $this->db->connect()->prepare($query);
    }
}
