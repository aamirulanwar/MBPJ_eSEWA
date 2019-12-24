<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_a_rental_use  extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_a_rental_use($per_page,$search_segment){
        db_select('*');
        db_from('a_rental_use');
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('rental_use_name');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_a_rental_use(){
        db_select('*');
        db_from('a_rental_use');
        db_where('soft_delete',SOFT_DELETE_FALSE);
        return db_count_results();
    }

    function insert_a_rental_use($data_insert){
        db_insert('a_rental_use',$data_insert);
        return get_insert_id('a_rental_use');
    }

    function get_a_rental_use_details($id){
        db_select('*');
        db_from('a_rental_use');
        db_where('rental_use_id',$id);
        $sql = db_get('');
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_a_rental_use($data_update,$id){
        db_where('rental_use_id',$id);
        db_update('a_rental_use',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function get_a_rental_use_active(){
        db_select('*');
        db_from('a_rental_use');
        db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('rental_use_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}