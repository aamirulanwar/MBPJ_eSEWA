<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_a_asset extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_a_asset($per_page,$search_segment,$data_search=array()){
        db_select('*');
        db_join('a_category c','c.category_id = a.category_id');
        db_from('a_asset a');
        if(isset($data_search['search']) && having_value($data_search['search'])):
            db_where("upper(c.category_name) like '%".trim($data_search['search'])."%' or upper(a.asset_name) like '%".trim($data_search['search'])."%'");
        endif;
//        if(isset($data_search['search']) && having_value($data_search['search'])):
//            db_where("upper(a.asset_name) like '%".trim($data_search['search'])."%'");
//        endif;
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_a_asset($data_search=array()){
        db_select('*');
        db_join('a_category c','c.category_id = a.category_id');
        db_from('a_asset a');
        if(isset($data_search['search']) && having_value($data_search['search'])):
            db_where("upper(c.category_name) like '%".trim($data_search['search'])."%'");
        endif;
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        return db_count_results();
    }

    function insert_a_asset($data_insert){
        db_insert('a_asset',$data_insert);
        return get_insert_id('a_asset');
    }

    function get_a_asset_by_id($asset_id){
        db_select('a.*,c.*');
        db_select('a.active as active');
        db_join('a_category c','c.category_id = a.category_id');
        db_from('a_asset a');
        db_where('asset_id',$asset_id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_a_asset($data_update,$id){
        db_where('asset_id',$id);
        db_update('a_asset',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function update_a_asset_by_category_id($data_update,$id){
        db_where('category_id',$id);
        db_update('a_asset',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function get_a_asset_active_by_category_id($category_id){
        db_where('category_id',$category_id);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_where('active',STATUS_ACTIVE);
        db_from('a_asset');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_a_asset_active_available_by_category_id($category_id){
        db_where('category_id',$category_id);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_where('active',STATUS_ACTIVE);
        db_where('rental_status',RENTAL_STATUS_NO);
        db_from('a_asset');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_asset_aging_report($asset_code){

    }
}