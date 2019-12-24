<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_access_file_parent extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_access_file_parent(){
        db_select('*');
        db_from('access_file_parent');
        db_where('active',1);
        db_order('parent_id');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}