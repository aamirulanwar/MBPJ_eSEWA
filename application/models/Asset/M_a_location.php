<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_a_location extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_a_location($per_page,$search_segment){
        db_select('*');
        db_from('a_location');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_a_location(){
        db_select('*');
        db_from('a_location');
        return db_count_results();
    }

    function insert_a_location($data_insert){
        db_insert('a_location',$data_insert);
        return get_insert_id('a_location');
    }

    function get_a_location_details($id){
        db_select('*');
        db_from('a_location');
        db_where('location_id',$id);
        $sql = db_get('');
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_asset_location($data_update,$id){
        db_where('location_id',$id);
        db_update('a_location',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function get_a_location_active(){
        db_select('*');
        db_from('a_location');
        db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('location_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}