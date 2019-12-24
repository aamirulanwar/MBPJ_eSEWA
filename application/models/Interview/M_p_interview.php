<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_p_interview extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_interview($per_page,$search_segment,$data_search=array()){
        db_join('a_type t','t.type_id = i.type_id');
        db_from('p_interview i');
        db_where('i.soft_delete',SOFT_DELETE_FALSE);
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('i.type_id',$data_search['type_id']);
        endif;
        db_order('i.date_interview','desc');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_interview_by_id($id){
        db_join('a_type t','t.type_id = i.type_id');
        db_from('p_interview i');
        db_where('i.soft_delete',SOFT_DELETE_FALSE);
        db_where('i.interview_id',$id);
        db_order('i.date_interview','desc');
        $sql = db_get('');
        if($sql):
            return $sql->row_array();
        endif;
    }

    function count_interview($data_search=array()){
        db_join('a_type t','t.type_id = i.type_id');
        db_from('p_interview i');
        db_where('i.soft_delete',SOFT_DELETE_FALSE);
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('i.type_id',$data_search['type_id']);
        endif;
        return db_count_results();
    }

    function insert_interview($data_insert){
        if(!empty($data_insert['date_interview'])):
            db_set_date('date_interview',$data_insert['date_interview']);
            unset($data_insert['date_interview']);
        endif;
        db_set_date_time('dt_added',timenow());
        db_insert('p_interview',$data_insert);
        return get_insert_id('p_interview');
    }

    function update_interview($data_update,$interview_id){
        db_where('interview_id',$interview_id);
        db_update('p_interview',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function get_interview_details($interview_id,$category_id=''){
        db_select('i.*, applicant.*, a.*, c.*, ru.*, asset.asset_name');
        db_select("to_char(i.date_interview, 'yyyy-mm-dd hh24:mi:ss') as date_interview",false);
        db_join('p_interview_application ia','i.interview_id = ia.interview_id');
        db_join('p_application a','ia.application_id = a.application_id');
        db_join('p_applicant applicant','applicant.applicant_id = a.applicant_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use ru','ru.rental_use_id = a.rental_use_id');
        db_join('a_asset asset','a.asset_id = asset.asset_id','left');
        db_from('p_interview i');
        db_where('i.interview_id',$interview_id);
        db_where('i.soft_delete',SOFT_DELETE_FALSE);
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        if($category_id!=''):
            db_where('c.category_id',$category_id);
        endif;
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}