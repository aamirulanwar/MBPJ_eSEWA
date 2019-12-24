<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_a_tenant_type extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_a_tenant_type($per_page,$search_segment){
        db_select('*');
        db_from('a_tenant_type');
        db_where('soft_delete',SOFT_DELETE_FALSE);
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_a_tenant_type(){
        db_select('*');
        db_from('a_tenant_type');
        db_where('soft_delete',SOFT_DELETE_FALSE);
        return db_count_results();
    }

    function insert_a_tenant_type($data_insert){
        db_insert('a_tenant_type',$data_insert);
        return get_insert_id('a_tenant_type');
    }

    function get_a_tenant_type_details($id){
        db_select('*');
        db_from('a_tenant_type');
        db_where('tenant_type_id',$id);
        $sql = db_get('');
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_a_tenant_type($data_update,$id){
        db_where('tenant_type_id',$id);
        db_update('a_tenant_type',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }
}