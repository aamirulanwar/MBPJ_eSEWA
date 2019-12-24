<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_a_type extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_a_type($per_page=0,$search_segment=''){
        db_select('*');
        db_from('a_type');
        db_order('type_name');
        db_order('form_type','asc');
        db_where('soft_delete',SOFT_DELETE_FALSE);
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_a_type(){
        db_select('*');
        db_from('a_type');
        db_where('soft_delete',SOFT_DELETE_FALSE);
        return db_count_results();
    }

    function insert_a_type($data_insert){
        db_insert('a_type',$data_insert);
        return get_insert_id('a_type');
    }

    function get_a_type_details($id){
        db_select('*');
        db_from('a_type');
        db_where('type_id',$id);
        $sql = db_get('');
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_asset_type($data_update,$id){
        db_where('type_id',$id);
        db_update('a_type',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function get_a_type_active(){
        db_select('*');
        db_from('a_type');
        db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('type_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_a_type_application(){
        db_select('*');
        db_from('a_type');
//        db_where('form_type != 0');
        db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('form_type, type_name','desc');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}