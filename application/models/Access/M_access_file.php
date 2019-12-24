<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_access_file extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_child_access($parent_id){
        db_select('*');
        db_from('access_file');
        db_where('active',STATUS_ACTIVE);
        db_where('parent_id',$parent_id);
        db_order('seq');
        db_order('access_id');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_parent_access($access_array){
        db_select('parent_id');
        db_from('access_file');
        db_where('access_id in ('.$access_array.')');
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }
}