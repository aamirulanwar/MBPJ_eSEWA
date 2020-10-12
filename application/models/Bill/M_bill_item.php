<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_bill_item extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get($data_search)
    {
        db_select('i.*');
        db_select("to_char(m.dt_added, 'yyyy-mm-dd') as dt_added",false);
        db_from('b_item i');

        if (isset($data_search["ITEM_ID"]) && having_value($data_search['ITEM_ID']) )
        {
            db_where('i.ITEM_ID',$data_search['ITEM_ID']);
        }

        if ( isset($data_search["BILL_ID"]) && $data_search["BILL_ID"] != "" )
        {
            db_where('i.BILL_ID',$data_search['BILL_ID']);
        }

        if ( isset($data_search["ACCOUNT_ID"]) && $data_search["ACCOUNT_ID"] != "" )
        {
            db_where('i.ACCOUNT_ID',$data_search['ACCOUNT_ID']);
        }
        
        if ( isset($data_search["BILL_CATEGORY"]) && $data_search["BILL_CATEGORY"] != "" )
        {
            db_where('i.BILL_CATEGORY',$data_search['BILL_CATEGORY']);
        }

        if (isset($data_search["TR_CODE"]) && having_value($data_search['TR_CODE']) )
        {
            db_where('i.TR_CODE',$data_search['TR_CODE']);
        }

        $sql = db_get('');

        if($sql)
        {
            return $sql->result_array();
        }
    }

    function delete($data_delete)
    {
        $ci =& get_instance();

        if (isset($data_search["ITEM_ID"]) && having_value($data_delete['ITEM_ID']) )
        {
            db_where('ITEM_ID',$data_delete['ITEM_ID']);
        }

        if ( isset($data_delete["BILL_ID"]) && $data_delete["BILL_ID"] != "" )
        {
            db_where('BILL_ID',$data_delete['BILL_ID']);
        }

        if ( isset($data_delete["ACCOUNT_ID"]) && $data_delete["ACCOUNT_ID"] != "" )
        {
            db_where('ACCOUNT_ID',$data_delete['ACCOUNT_ID']);
        }

        if ( isset($data_delete["BILL_CATEGORY"]) && $data_delete["BILL_CATEGORY"] != "" )
        {
            db_where('BILL_CATEGORY',$data_delete['BILL_CATEGORY']);
        }

        if ( isset($data_delete["DISPLAY_STATUS"]) && $data_delete["DISPLAY_STATUS"] != "" )
        {
            db_where('DISPLAY_STATUS',$data_delete['DISPLAY_STATUS']);
        }

        $ci->db->delete('B_ITEM');
        return true;
    }

    function get_bill_item($id){
        db_select('i.*');
        db_select("to_char(i.dt_added, 'yyyy') as dt_added",false);
        db_from('b_item i');
        db_join('admin.mctrancode t','i.tr_code=t.mct_trcodenew');
        db_where('i.bill_id',$id);
        db_where('t.mct_mdcode','B');
        db_order('t.mct_priort');
        $sql = db_get('');
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_bill_item_by_searchKey($data_search)
    {
        db_select('i.*');
        db_select("to_char(i.dt_added, 'yyyy') as dt_added",false);
        db_select("i.dt_added as ORIGINAL_DT_ADDED",false);
        db_from('b_item i');
        db_join('admin.mctrancode t','i.tr_code=t.mct_trcodenew');

        if (isset($data_search["BILL_ID"]) && having_value($data_search['BILL_ID']) )
        {
            db_where('i.BILL_ID',$data_search['BILL_ID']);
        }

        if (isset($data_search["ITEM_ID"]) && having_value($data_search['ITEM_ID']) )
        {
            db_where('i.ITEM_ID',$data_search['ITEM_ID']);
        }

        if (isset($data_search["TR_CODE"]) && having_value($data_search['TR_CODE']) )
        {
            db_where('i.TR_CODE',$data_search['TR_CODE']);
        }

        if (isset($data_search["BILL_CATEGORY"]) && having_value($data_search['BILL_CATEGORY']) )
        {
            db_where('i.BILL_CATEGORY',$data_search['BILL_CATEGORY']);
        }

        db_where('t.mct_mdcode','B');
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

    function insert_bill_item($data_insert)
    {
        if ( !isset($data_insert["DT_ADDED"]) || $data_insert["DT_ADDED"] == "" )
        {
            db_set_date_time('dt_added',timenow());
        }
        db_insert('B_ITEM ',$data_insert);
        return get_insert_id('B_ITEM');
    }

    function report_rental_gst($data_search=array()){
        db_select('i.*,m.*');
        db_select('i.amount as amount');
        db_select("to_char(m.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_bill",false);
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_where('tr_gst_status',1);
        db_where("(i.tr_code NOT LIKE '12%') ");

        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("i.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;

        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("m.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;

        db_where("(i.item_desc NOT LIKE '%LEPAS%') ");
        db_where('m.bill_year >= 2015');
        db_where('i.amount > 0');
        db_where('m.account_id',$data_search['account_id']);
        db_order('i.dt_added','asc');
        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function report_rental_gst_prv($data_search=array()){

        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'B' THEN AMOUNT END) as BILL");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'R' THEN AMOUNT END) as RESIT");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' and substr(i.TR_CODE,0,1)=2 THEN AMOUNT END) as JOURNAL_R");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' and substr(i.TR_CODE,0,1)=1 THEN AMOUNT END) as JOURNAL_B");
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_where('tr_gst_status',1);
        db_where("(i.tr_code NOT LIKE '12%') ");

        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("i.dt_added < to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        else:
            db_where("i.dt_added < to_date('".date('d-M-y',strtotime('2013-01-01'))."')");
        endif;

        db_where("(i.item_desc NOT LIKE '%LEPAS%') ");
        db_where('m.bill_year >= 2015');
        db_where('i.amount > 0');
        db_where('m.account_id',$data_search['account_id']);
        $sql = db_get('');
        if($sql):
            return $sql->row_array('');
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

    function report_rental_gst_simple($data_search=array()){
        db_select('count(i.ITEM_ID) as total_item,acc.CATEGORY_ID');
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
        db_where("(i.tr_code NOT LIKE '12%') ");
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

    function report_rental_gst_simple_prv($data_search,$category_id){
        db_select('count(i.ITEM_ID) as total_item,acc.CATEGORY_ID');
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
        db_where("(i.tr_code NOT LIKE '12%') ");
        db_where('tr_gst_status',1);

        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("m.dt_added < to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        else:
            db_where("m.dt_added < to_date('".date('d-M-y',strtotime('2013-01-01'))."')");
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
        db_where('acc.CATEGORY_ID',$category_id);
        db_group("acc.CATEGORY_ID,acc.TYPE_ID,c.CATEGORY_NAME,T.TYPE_NAME");
        $sql = db_get('');

        if($sql):
            return $sql->row_array('');
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
        db_select('(select journal_code from a_journal where a_journal.journal_id = i.journal_id) as journal_code');
        db_select('acc.ACCOUNT_NUMBER');
        db_select('usr.name');
        db_select("to_char(i.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_bill",false);
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_join('acc_account acc','acc.account_id = m.account_id');
        db_join('acc_user usr','acc.user_id = usr.user_id');
        db_where("(i.tr_code NOT LIKE '12%') ");
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
        db_order('i.ITEM_ID');
        $sql = db_get('');
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
        db_order('acc.account_id,i.ITEM_ID');
        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function get_bill_by_search($data_search=array()){
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

    function update_bill_item($item_id,$data_update)
    {
        db_where('item_id',$item_id);
        db_update('b_item',$data_update);
        return true;
    }

    function rekodTransaksi($data_search=array())
    {
        db_select('i.ITEM_ID');
        db_select('i.TR_CODE');
        db_select('i.ITEM_DESC');
        db_select('i.ACCOUNT_ID');
        db_select('a.ACCOUNT_NUMBER');
        db_select('i.BILL_CATEGORY');
        db_select('m.BILL_CATEGORY as master_bill_category');
        db_select('i.AMOUNT');
        db_select('m.BILL_NUMBER');
        db_select(" case when i.BILL_CATEGORY = 'J' then (select bill_number from b_journal_temp where id = i.b_journal_id) else m.BILL_NUMBER end as reference_no");
        db_select("to_char(i.DT_ADDED,'dd/mm/yyyy') as TKH_BIL");
        db_from('b_item i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_join('acc_account a','a.ACCOUNT_ID = m.ACCOUNT_ID');
        db_where("i.display_status != 'X'");

        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("i.dt_added >= to_date('".$data_search['date_start']."','DD-MM-YYYY')-1");
        endif;
        if(isset($data_search['tr_code']) && having_value($data_search['tr_code'])):
            db_where('i.tr_code',$data_search['tr_code']);
        endif;
        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("i.dt_added <= to_date('".$data_search['date_end']."','DD-MM-YYYY')+1");
        endif;
        if(isset($data_search['account_id']) && having_value($data_search['account_id'])):
            db_where('m.account_id',$data_search['account_id']);
        endif;
        if(isset($data_search['order_by']) && having_value($data_search['order_by'])):
            db_order($data_search['order_by']);
        else:
            db_order("TKH_BIL");
        endif;
        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function rekodTransaksiInfo($account_id)
    {
        if ( !empty($account_id) )
        {
            db_select('acc_account.account_id');
            db_select('acc_account.account_number');
            db_select('acc_user.name');
            db_select('acc_account.estimation_rental_charge');
            db_select('acc_account.status_acc');
            db_select('a_asset.asset_add');
            db_select('a_category.category_name');
            db_select('a_category.address');
            db_from('acc_account');
            db_join('a_asset','acc_account.asset_id = a_asset.asset_id');
            db_join('a_category','acc_account.category_id = a_category.category_id');
            db_join('acc_user','acc_account.user_id = acc_user.user_id');
            db_where('acc_account.account_id',$account_id);
            $sql = db_get('');
            if($sql):
                return $sql->result_array('');
            endif;
        }
        else
        {
            return array();
        }
    }

    function getNoticeChargeTransaction($account_id,$month,$year)
    {
        if ( !empty($account_id) )
        {
            db_select("b_master.account_id");
            db_select("b_master.bill_month");
            db_select("b_master.bill_year");
            db_select("b_item.ITEM_ID");
            db_select("b_item.TR_CODE");
            db_select("b_item.ITEM_DESC");
            db_select("b_item.REMARK");
            db_from("b_item");
            db_join("b_master","b_master.bill_id = b_item.bill_id");
            db_where("b_master.account_id",$account_id);
            db_where("b_master.bill_month",$month);
            db_where("b_master.bill_year",$year);
            db_where("b_item.TR_CODE in ('11110020')");
            $sql = db_get('');
            if($sql):
                return $sql->result_array('');
            endif;
        }
        else
        {
            return array();
        }
    }

    function getBillItemTotalAmount($master_bill_id)
    {
        db_select('nvl(sum(b_item.amount),0) as total_amount');
        db_from('b_item');
        db_where('bill_id',$master_bill_id);

        $sql = db_get('');
        if($sql)
        {
            return $sql->row_array('');
        }
        else
        {
            return array();
        }
    }

    function deleteLODCharge($item_id)
    {
        $ci =& get_instance();
        $ci->db->where('ITEM_ID', $item_id);
        $ci->db->delete('B_ITEM'); 
        return true;
    }
    
    /*   
    function getPreviousBillCharges( $bill_type = 1, $account_id=-1,$bill_month=0,$bill_year=0,$tr_code_new=0 )
    {
        db_select('tr_code');
        db_select('sum(amount) as total_amount');
        db_from('b_item');
        db_where(' account_id',$account_id);
        db_where(" substr(tr_code,0,2) = '11' ");

        if ( $tr_code_new != 0 && isset($tr_code_new) )
        {
            db_where(' tr_code',$tr_code_new);
        }

        if ( $bill_type == 1 )
        {
            // db_where(" bill_id in (select bill_id from b_master where account_id = ".$account_id." and bill_year = ".$bill_year." and bill_month < ".$bill_month." ) ");
            db_where(" bill_id not in (select bill_id from b_master where bill_type = 1 and account_id = ".$account_id." and bill_year = ".$bill_year." and bill_month = ".$bill_month." ) ");
        }
        else if ( $bill_type == 2 )
        {
            db_where(" bill_id < (select max(bill_id) bill_id from b_master where bill_type = 2 and account_id = ".$account_id." ) ");
        }
        else if ( $bill_type == 3 ) // This bill type is generated once only
        {
            db_where(" bill_id in (select bill_id from b_master where account_id = ".$account_id." and bill_year = ".$bill_year." and bill_month < ".$bill_month." ) ");
        }       
        
        db_group(" tr_code,bill_category");
        $sql = db_get('');

        if( $sql && $tr_code_new != 0 && isset($tr_code_new) )
        {
            return $sql->row_array('');
        }
        else if( $sql )
        {
            return $sql->result_array('');
        }
        else
        {
            return array();
        }
    }

    function getOutstandingBillChargesCarriedForwardInNewYear( $bill_type = 1, $account_id=-1,$bill_month=1,$bill_year=0 )
    {
        db_select('tr_code');
        db_select('sum(amount) as total_amount');
        db_from('b_item');
        db_where(' account_id',$account_id);
        db_where(" substr(tr_code,0,2) = '12' ");

        if ( $bill_type == 1 )
        {
            // db_where(" bill_id in (select bill_id from b_master where account_id = ".$account_id." and bill_year = ".$bill_year." and bill_month < ".$bill_month." ) ");
            db_where(" bill_id not in (select bill_id from b_master where bill_type = 1 and account_id = ".$account_id." and bill_year = ".$bill_year." and bill_month = ".$bill_month." ) ");
        }
        else if ( $bill_type == 2 )
        {
            db_where(" bill_id not in (select max(bill_id) bill_id from b_master where bill_type = 2 and account_id = ".$account_id." ) ");
        }
        else if ( $bill_type == 3 ) // This bill type is generated once only
        {
            db_where(" bill_id in (select bill_id from b_master where account_id = ".$account_id." and bill_year = ".$bill_year." and bill_month < ".$bill_month." ) ");
        }

        db_group(" tr_code,bill_category");
        $sql = db_get('');

        if( $sql )
        {
            return $sql->result_array('');
        }
        else
        {
            return array();
        }
    }
    
    function getPreviousBillPayment( $bill_type = 1, $account_id=-1,$bill_month=0,$bill_year=0,$tr_code_new=0 )
    {
        db_select('tr_code');
        db_select('sum(amount) as total_amount');
        db_from('b_item');
        db_where(' account_id',$account_id);

        if ( strlen($tr_code_new) == 5)
        {
            db_where(' tr_code_old',$tr_code_new);
            db_where(" substr(tr_code_old,0,1) = '2' ");           
        }
        else if ( strlen($tr_code_new) > 5)
        {
            db_where(' tr_code',$tr_code_new);            
            db_where(" substr(tr_code,0,1) = '2' ");
        }


        if ( $bill_type == 1 )
        {
            // db_where(" bill_id in (select bill_id from b_master where account_id = ".$account_id." and bill_year = ".$bill_year." and bill_month < ".$bill_month." ) ");
            db_where(" bill_id not in (select bill_id from b_master where bill_type = 1 and account_id = ".$account_id." and bill_year = ".$bill_year." and bill_month = ".$bill_month." ) ");
        }
        else if ( $bill_type == 2 )
        {
            db_where(" bill_id < (select max(bill_id) bill_id from b_master where account_id = ".$account_id." ) ");
        }

        db_group(" tr_code,bill_category");
        $sql = db_get('');

        if($sql)
        {
            return $sql->row_array('');
        }
        else
        {
            return array();
        }
    }
    
    function getPreviousBillTransaction($account_id=-1,$bill_month=0,$bill_year=0)
    {
        db_select('tr_code');
        db_select('sum(amount) as total_amount');
        db_from('b_item');
        db_where(' account_id',$account_id);
        db_where(" substr(tr_code,0,2) in ('11','21') ");
        db_where(" bill_id in (select bill_id from b_master where account_id = ".$account_id." and bill_year = ".$bill_year." and bill_month < ".$bill_month." ) ");
        db_group(" tr_code,bill_category");
        $sql = db_get('');

        if($sql)
        {
            return $sql->result_array('');
        }
        else
        {
            return array();
        }
    }
    */

    /* *************** EXTREME NOTICE / ALERT *************** */
    /*  Please edit this function "groupAmountByYearTrcode($account_id)" carefully
     *  Any Changes has HIGH PROBABILTY to make bill generation to calculate wrongly 
     *  and make generated bill is invalid
     */
    /* *************** BE WARNED *************** */
    function groupAmountByYearTrcode($account_id)
    {
        db_select('sum(a.amount) as total_amount');
        db_select('b.account_id');
        db_select('b.bill_year');
        db_select('a.tr_code');
        db_select('a.tr_code_old');
        db_select('a.BILL_CATEGORY');
        db_from('b_item a,b_master b');
        db_where('a.bill_id = b.bill_id');
        db_where('a.account_id = b.account_id ');
        db_where('b.account_id',$account_id);
        db_group("b.bill_year,b.account_id,a.tr_code_old,a.tr_code,a.BILL_CATEGORY");
        db_order('b.BILL_YEAR', 'asc');
        db_order('a.BILL_CATEGORY', 'asc');
        db_order('a.TR_CODE_OLD', 'desc');
        $sql = db_get('');

        if($sql)
        {
            return $sql->result_array('');
        }
        else
        {
            return array();
        }
    }
}
