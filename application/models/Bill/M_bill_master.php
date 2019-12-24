<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_bill_master extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_bill_history_list($per_page,$search_segment,$data_search=array()){
        db_select('m.*');
        db_select('a.*');
        db_select("to_char(m.dt_added, 'yyyy-mm-dd') as dt_added",false);
        db_from('b_master m');
        db_join('acc_account a','a.account_id=m.account_id');
        if(isset($data_search['account_id']) && having_value($data_search['account_id'])):
            db_where('m.account_id',$data_search['account_id']);
        endif;
        if(isset($data_search['bill_category']) && having_value($data_search['bill_category'])):
            db_where('m.bill_category',$data_search['bill_category']);
        endif;
        if(isset($data_search['bill_number']) && having_value($data_search['bill_number'])):
            db_where('m.bill_number',$data_search['bill_number']);
        endif;
        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("m.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;
        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("m.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;
        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
            db_where("a.account_number like '%".$data_search['account_number']."%'");
        endif;
        db_order('bill_year','desc');
        db_order('bill_month','desc');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_bill_history ($data_search=array()){
        db_select('*');
        if(isset($data_search['account_id']) && having_value($data_search['account_id'])):
            db_where('m.account_id',$data_search['account_id']);
        endif;
        if(isset($data_search['bill_category']) && having_value($data_search['bill_category'])):
            db_where('bill_category',$data_search['bill_category']);
        endif;
        if(isset($data_search['bill_number']) && having_value($data_search['bill_number'])):
            db_where('m.bill_number',$data_search['bill_number']);
        endif;
        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("m.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;
        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("m.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;
        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
            db_where("a.account_number",$data_search['account_number']);
        endif;
        db_join('acc_account a','a.account_id=m.account_id');
        db_from('b_master m');
        return db_count_results();
    }

    function get_bill_master($id){
        db_select('m.*,a.*');
        db_select("to_char(m.dt_added, 'yyyy-mm-dd') as dt_added",false);
        db_join('acc_account a','a.account_id=m.account_id');
        db_from('b_master m');
        db_where('m.bill_id',$id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_highest_oustanding_bill($per_page,$search_segment,$id,$data_search=array()){
        db_select('*');
        db_from('b_master m');
        db_join('acc_account a','a.account_id=m.account_id');
        db_join('acc_user u','u.user_id=a.user_id');
        db_join('a_category c','c.category_id=a.category_id');
        db_where('m.bill_category','B');
        db_where('m.bill_month',1);
        db_where('m.bill_year',2019);
        db_order('m.total_amount','desc');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }
}

