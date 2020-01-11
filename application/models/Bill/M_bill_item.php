<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_bill_item extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_bill_item($id){
        db_select('i.*');
        db_select("to_char(i.dt_added, 'yyyy') as dt_added",false);
        db_from('b_item i');
        db_join('admin.mctrancode t','i.tr_code=t.mct_trcodenew');
        db_where('i.bill_id',$id);
        db_order('t.mct_priort');
        $sql = db_get('');
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_bill_item($tr_id,$category){
        db_select('i.*');
        db_select("to_char(i.dt_added, 'yyyy') as dt_added",false);
        db_from('b_item i');
        db_where('tr_id',$tr_id);
        db_where('bill_category',$category);
        return db_count_results();
    }

    function get_bill_amount_by_tr_id($tr_id,$bill_category='',$category_id='',$dt_start,$dt_end){
        db_select('sum(i.amount) as amount');
        db_from('b_item i');
        db_join('b_master m','i.bill_id=m.bill_id');
        db_join('acc_account a','a.account_id=m.account_id');
        db_where('i.tr_id',$tr_id);
        if(!empty($bill_category)):
            db_where('i.bill_category',$bill_category);
        endif;
        if(!empty($category_id)):
            db_where('a.category_id',$category_id);
        endif;
        if(!empty($dt_start)):
            db_where("to_char(i.dt_added, 'dd-mm-yyyy') >='".$dt_start."'");
        endif;
        if(!empty($dt_end)):
            db_where("to_char(i.dt_added, 'dd-mm-yyyy') <='".$dt_end."'");
        endif;
        $sql = db_get('');
        if($sql):
            return $sql->row_array('');
        endif;
    }

    function get_bill_amount_by_tr_code($tr_code,$bill_category='',$category_id='',$dt_start,$dt_end){
        db_select('sum(i.amount) as amount');
        db_from('b_item i');
        db_join('b_master m','i.bill_id=m.bill_id');
        db_join('acc_account a','a.account_id=m.account_id');
        db_where('i.tr_code',$tr_code);
        if(!empty($bill_category)):
            db_where('i.bill_category',$bill_category);
        endif;
        if(!empty($category_id)):
            db_where('a.category_id',$category_id);
        endif;
        if(!empty($dt_start)):
            db_where("to_char(i.dt_added, 'dd-mm-yyyy') >='".$dt_start."'");
        endif;
        if(!empty($dt_end)):
            db_where("to_char(i.dt_added, 'dd-mm-yyyy') <='".$dt_end."'");
        endif;
        $sql = db_get('');
        if($sql):
            return $sql->row_array('');
        endif;
    }

    function count_bill_history($id,$data_search=array()){
        db_select('*');
        db_where('account_id',$id);
        if(isset($data_search['bill_category']) && having_value($data_search['bill_category'])):
            db_where('bill_category',$data_search['bill_category']);
        endif;
        db_from('b_master');
        return db_count_results();
    }

    function insert_bill_item($data_insert){
        db_set_date_time('dt_added',timenow());
        db_insert('B_ITEM ',$data_insert);
        return get_insert_id('B_ITEM');
    }

    function report_rental_gst($data_search=array()){
        db_select('i.*,m.*');
        db_select('i.amount as amount');
        db_select("to_char(m.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_bill",false);
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
//        db_join('acc_account acc','acc.account_id = m.account_id');
//        db_join('acc_user usr','acc.user_id = usr.user_id');
        db_where('tr_gst_status',1);

        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("m.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;

        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("m.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;
//        db_where('m.bill_month >= 4');
        db_where("(i.item_desc NOT LIKE '%LEPAS%') ");
        db_where('m.bill_year >= 2015');
        db_where('i.amount > 0');
        db_where('m.account_id',$data_search['account_id']);
        db_order('m.dt_added','asc');
        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function get_item_jurnal($data_search=array()){
        db_select('i.*,m.*');
        db_select('i.amount as amount');
        db_select("to_char(m.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_bill",false);
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_where('m.bill_month',$data_search['bill_month']);
        db_where('m.bill_year',$data_search['bill_year']);
        db_where('i.bill_category','J');
        db_where('i.tr_code',$data_search['tr_code']);
        db_where('m.account_id',$data_search['account_id']);
        if(isset($data_search['not_equal_last_year']) && having_value($data_search['not_equal_last_year'])):
            db_where("item_desc NOT LIKE '%LEPAS%'");
        endif;
        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function get_item_bill($data_search=array()){
        db_select('i.*,m.*');
        db_select('i.amount as amount');
        db_select("to_char(m.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_bill",false);
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_where('m.bill_month',$data_search['bill_month']);
        db_where('m.bill_year',$data_search['bill_year']);
        db_where('i.bill_category','B');
        db_where('i.tr_code',$data_search['tr_code']);
        db_where('m.account_id',$data_search['account_id']);
        if(isset($data_search['not_equal_last_year']) && having_value($data_search['not_equal_last_year'])):
            db_where("item_desc NOT LIKE '%LEPAS%'");
        endif;
        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

//    function report_rental_gst_simple($data_search=array()){
//        db_select('i.*,m.*,t.type_name,c.category_name,c.category_code');
//        db_select('i.amount as amount');
//        db_select("to_char(m.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_bill",false);
//        db_from('b_item i');
//        db_join('b_master m','m.bill_id = i.bill_id');
//        db_join('acc_account acc','acc.account_id = m.account_id');
//        db_join('acc_user usr','acc.user_id = usr.user_id');
//        db_join('a_type t','t.type_id = acc.type_id');
//        db_join('a_category c','c.category_id = acc.category_id');
//        db_where('tr_gst_status',1);
//
//        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
//            db_where("m.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
//        endif;
//
//        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
//            db_where("m.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
//        endif;
//
//        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
//            db_where("acc.type_id",$data_search['type_id']);
//        endif;
//        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
//            db_where("acc.category_id",$data_search['category_id']);
//        endif;
//        db_where('m.bill_year >= 2015');
//        db_where('i.amount > 0');
//        db_order('m.dt_added','asc');
//        $sql = db_get('');
//        if($sql):
//            return $sql->result_array('');
//        endif;
//    }

    function report_rental_gst_simple($data_search=array()){
        db_select('count(i.ITEM_ID) as total_item,');
        db_select('c.CATEGORY_NAME');
        db_select('t.TYPE_NAME');
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'B' THEN AMOUNT END) as BILL");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'R' THEN AMOUNT END) as RESIT");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' and substr(i.TR_CODE,0,1)=2 THEN AMOUNT END) as JOURNAL_R");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' and substr(i.TR_CODE,0,1)=1 THEN AMOUNT END) as JOURNAL_B");

        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_join('acc_account acc','acc.account_id = m.account_id');
        db_join('a_type t','t.type_id = acc.type_id');
        db_join('a_category c','c.category_id = acc.category_id');
        db_where("(i.item_desc NOT LIKE '%LEPAS%') ");
        db_where('tr_gst_status',1);

        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("m.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;

        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("m.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;

        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where("acc.type_id",$data_search['type_id']);
        endif;
        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
            db_where("acc.category_id",$data_search['category_id']);
        endif;
        if(isset($data_search['acc_status']) && having_value($data_search['acc_status'])):
            db_where('acc.status_acc',$data_search['acc_status']);
        endif;
        db_where('m.bill_year >= 2015');
        db_where('i.amount > 0');
        db_group("acc.CATEGORY_ID,acc.TYPE_ID,c.CATEGORY_NAME,T.TYPE_NAME");
        $sql = db_get('');

        if($sql):
            return $sql->result_array('');
        endif;
    }

    function report_code_gl($data_search=array()){
        db_select('count(i.item_id) as total');
        db_select('i.TR_CODE');
        db_select('i.ITEM_DESC');
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'R' THEN AMOUNT END) as RESIT");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'B' THEN AMOUNT END) as BILL");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' and substr(i.TR_CODE,0,1)=2 THEN AMOUNT END) as JOURNAL_R");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' and substr(i.TR_CODE,0,1)=1 THEN AMOUNT END) as JOURNAL_B");
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_join('acc_account acc','acc.account_id = m.account_id');
        db_where('i.TR_CODE IS NOT NULL ');

        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("m.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;

        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("m.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;

        if(isset($data_search['acc_status']) && having_value($data_search['acc_status'])):
            db_where('acc.status_acc',$data_search['acc_status']);
        endif;

        if(isset($data_search['tr_code']) && having_value($data_search['tr_code'])):
            db_where('i.tr_code',$data_search['tr_code']);
        endif;

        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('acc.type_id',$data_search['type_id']);
        endif;

        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
            db_where('acc.category_id',$data_search['category_id']);
        endif;

        db_group("i.TR_CODE,i.ITEM_DESC");
        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function report_highest_overdue($data_search=array()){
        db_select('A.ACCOUNT_ID');
        db_select("NVL(SUM(CASE WHEN TO_CHAR(i.DT_ADDED, 'YYYY') = to_char(sysdate, 'YYYY') THEN (i.AMOUNT-i.TOTAL_PAID+(i.TOTAL_JOURNAL)) END),0) as TAHUN_SEMASA");
        db_select("NVL(SUM(CASE WHEN TO_CHAR(i.DT_ADDED, 'YYYY') < to_char(sysdate, 'YYYY') THEN (i.AMOUNT-i.TOTAL_PAID+(i.TOTAL_JOURNAL)) END),0) as TAHUN_LEPAS");
        db_select("NVL(SUM(CASE WHEN TO_CHAR(i.DT_ADDED, 'YYYY') = to_char(sysdate, 'YYYY') THEN (i.AMOUNT-i.TOTAL_PAID+(i.TOTAL_JOURNAL)) END),0) +NVL(SUM(CASE WHEN TO_CHAR(i.DT_ADDED, 'YYYY') < to_char(sysdate, 'YYYY') THEN (i.AMOUNT-i.TOTAL_PAID+(i.TOTAL_JOURNAL)) END),0) as JUMLAH_TUNGGAKAN");
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_join('acc_account a','a.account_id = m.account_id');
        db_where("substr(i.ITEM_DESC,0,6)='SEWAAN'");
        db_where("(i.AMOUNT-i.TOTAL_PAID+(i.TOTAL_JOURNAL)) != 0");

        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("m.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;
        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("m.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;
        if(isset($data_search['acc_status']) && having_value($data_search['acc_status'])):
            db_where('a.status_acc',$data_search['acc_status']);
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where("a.type_id",$data_search['type_id']);
        endif;
        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
            db_where("a.category_id",$data_search['category_id']);
        endif;
        db_group('A.ACCOUNT_ID');
        if(isset($data_search['order_by']) && having_value($data_search['order_by'])):
            if($data_search['order_by']==1):
                db_order('JUMLAH_TUNGGAKAN','DESC');
            endif;
        endif;
        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function record_transaction($data_search=array()){
        db_select('m.*');
        db_select('i.*');
        db_select("to_char(i.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_bill",false);
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("i.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;
        if(isset($data_search['tr_code']) && having_value($data_search['tr_code'])):
            db_where('i.tr_code',$data_search['tr_code']);
        endif;
        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("i.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;
        if(isset($data_search['account_id']) && having_value($data_search['account_id'])):
            db_where('m.account_id',$data_search['account_id']);
        endif;
        if(isset($data_search['order_by']) && having_value($data_search['order_by'])):
            db_order($data_search['order_by']);
        endif;
        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function get_item_amount_not_equal($acc_id,$date_calculate){
        db_select('i.*');
        db_select('i.amount+(i.total_journal) as amount');
        db_select("to_char(i.dt_added, 'yyyy-mm-dd') as dt_added",false);
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_where('m.account_id',$acc_id);
        db_where('i.amount+(i.total_journal) > i.total_paid');
        db_where('i.bill_category','B');
        db_where('i.PREV_YEAR_OUTSTANDING','0');
        db_where("i.dt_added <= to_date('".date('d-M-y',strtotime($date_calculate))."')");
        $sql = db_get('');
//        echo last_query();
//        exit;
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function get_item_amount_not_equal_by_category($category_id,$date_calculate){
        db_select('i.*');
        db_select('i.amount+(i.total_journal) as amount');
        db_select("to_char(i.dt_added, 'yyyy-mm-dd') as dt_added",false);
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_join('acc_account acc','m.account_id = acc.account_id');
        db_where('acc.category_id',$category_id);
        db_where('i.amount+(i.total_journal) > i.total_paid');
        db_where('i.bill_category','B');
        db_where('i.PREV_YEAR_OUTSTANDING','0');
        db_where("i.dt_added <= to_date('".date('d-M-y',strtotime($date_calculate))."')");
        $sql = db_get('');
//        echo last_query();
//        exit;
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function get_bill_by_search($data_search=array()){
//        db_select('sum(i.amount) as amount');
        if(isset($data_search['bill_category']) && having_value($data_search['bill_category'])):
            db_select("SUM(CASE WHEN i.BILL_CATEGORY = '".$data_search['bill_category']."' THEN AMOUNT END) as TUNGGAKAN");
        endif;
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' AND i.amount >0 THEN AMOUNT END) as DEBIT");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' AND i.amount <0 THEN AMOUNT END) as KREDIT");
        db_from('b_item i');
        db_join('b_master m','i.bill_id=m.bill_id');
        db_join('acc_account a','a.account_id=m.account_id');
        if(isset($data_search['tr_code']) && having_value($data_search['tr_code'])):
            db_where('i.tr_code',$data_search['tr_code']);
        endif;
        if(isset($data_search['tr_code_like']) && having_value($data_search['tr_code_like'])):
            db_where("i.tr_code LIKE '".$data_search['tr_code_like']."%'");
        endif;
        if(isset($data_search['tr_code_start']) && having_value($data_search['tr_code_start'])):
            db_where("i.tr_code >= '".$data_search['tr_code_start']."'");
        endif;
        if(isset($data_search['tr_code_end']) && having_value($data_search['tr_code_end'])):
            db_where("i.tr_code <= '".$data_search['tr_code_end']."'");
        endif;
        if(isset($data_search['account_id']) && having_value($data_search['account_id'])):
            db_where('m.account_id',$data_search['account_id']);
        endif;
        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
            db_where("a.category_id",$data_search['category_id']);
        endif;
        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("i.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;
        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("i.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;

//        if(!empty($bill_category)):
//            db_where('i.bill_category',$bill_category);
//        endif;
//        if(!empty($category_id)):
//            db_where('a.category_id',$category_id);
//        endif;
//        if(!empty($dt_start)):
//            db_where("to_char(i.dt_added, 'dd-mm-yyyy') >='".$dt_start."'");
//        endif;
//        if(!empty($dt_end)):
//            db_where("to_char(i.dt_added, 'dd-mm-yyyy') <='".$dt_end."'");
//        endif;
        $sql = db_get('');
        if($sql):
            return $sql->row_array('');
        endif;
    }

    function get_lebihan_current_year($account_id=0){
        db_select('*');
        db_from('b_item i');
        db_join('b_master m','i.bill_id=m.bill_id');
        db_where('m.account_id',$account_id);
        db_where('tr_code',TR_CODE_LEBIHAN);
        $sql = db_get('');
        if($sql):
            return $sql->row_array('');
        endif;
    }

    function get_tunggakan_to_new_year($account_id=0,$tr_code,$year){
        db_select('*');
        db_from('b_item i');
        db_join('b_master m','i.bill_id=m.bill_id');
        db_where('m.account_id',$account_id);
        db_where('i.tr_code',$tr_code);
        db_where('m.BILL_YEAR',$year);
        $sql = db_get('');
        if($sql):
            return $sql->row_array('');
        endif;
    }

    function update_bill_item($item_id,$data_update){
        db_where('item_id',$item_id);
        db_update('b_item',$data_update);
        return true;
    }
}

