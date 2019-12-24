<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_access_level extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_access_level(){
        $sql = db_get('access_level');
        if($sql):
            return $sql->result_array();
        endif;
    }
}