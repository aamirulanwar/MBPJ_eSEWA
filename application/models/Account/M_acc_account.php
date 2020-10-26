<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_acc_account extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get($data_search)
    {
        db_select('acc_account.*');
        db_from('acc_account');

        if ( isset($data_search["ACCOUNT_ID"]) && $data_search["ACCOUNT_ID"] != "" )
        {
            db_where('ACCOUNT_ID',$data_search['ACCOUNT_ID']);
        }

        if ( isset($data_search["ACCOUNT_NUMBER"]) && $data_search["ACCOUNT_NUMBER"] != "" )
        {
            db_where('ACCOUNT_NUMBER',$data_search['ACCOUNT_NUMBER']);
        }

        if ( isset($data_search["USER_ID"]) && $data_search["USER_ID"] != "" )
        {
            db_where('USER_ID',$data_search['USER_ID']);
        }

        $sql = db_get('');

        if($sql)
        {
            return $sql->result_array();
        }
    }

    function insert_account($data_insert){
        if(!empty($data_update['date_start'])):
            db_set_date('date_start',$data_update['date_start']);
            unset($data_update['date_start']);
        endif;


        if(!empty($data_update['date_end'])):
            db_set_date('date_end',$data_update['date_end']);
            unset($data_update['date_end']);
        endif;


        db_set_date_time('dt_added',timenow());
        db_insert('acc_account',$data_insert);
        return get_insert_id('acc_account');
    }

    function update_account($data_update,$id){

        if(!empty($data_update['date_start'])):
            db_set_date('date_start',$data_update['date_start']);
            unset($data_update['date_start']);
        endif;

        if(!empty($data_update['date_end'])):
            db_set_date('date_end',$data_update['date_end']);
            unset($data_update['date_end']);
        endif;

        if(!empty($data_update['first_date_start'])):
            db_set_date('first_date_start',$data_update['first_date_start']);
            unset($data_update['first_date_start']);
        endif;

        if(!empty($data_update['first_date_end'])):
            db_set_date('first_date_end',$data_update['first_date_end']);
            unset($data_update['first_date_end']);
        endif;


        db_where('account_id',$id);
        db_update('acc_account',$data_update);

        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function update_a_acc_account_by_asset_id($data_update,$id){
        db_where('asset_id',$id);
        db_update('acc_account',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function count_account($data_search=array()){
        db_select('a.*,acc.*,t.*,c.*,r.*');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
        db_from('acc_account a');
        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
            db_where("a.account_number like '%".$data_search['account_number']."%'");
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('a.type_id',$data_search['type_id']);
        endif;
        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
            db_where('a.category_id',$data_search['category_id']);
        endif;
        if(isset($data_search['almost_expired']) && having_value($data_search['almost_expired'])):
           // db_where("extract(year from a.date_end) = ".date('Y')."");
           // db_where("extract(month from a.date_get_accountend) = ".date('m')."");
            if($data_search['almost_expired']==1):
                db_where("A.DATE_END IS NOT NULL");
                db_where('a.STATUS_ACC',STATUS_ACCOUNT_ACTIVE);
                db_where("A.DATE_END > TO_DATE('".date('Y-m-d')."','YYYY-MM-DD')");
                db_where("( A.DATE_END -TO_DATE('".date('Y-m-d')."','YYYY-MM-DD')) <= 60");
            elseif ($data_search['almost_expired']==2):
                db_where('a.STATUS_ACC',STATUS_ACCOUNT_ACTIVE);
            elseif ($data_search['almost_expired']==3):
                db_where('a.STATUS_ACC',STATUS_ACCOUNT_NONACTIVE);
            endif;
        endif;
        if(isset($data_search['name']) && having_value($data_search['name'])):
            db_where("acc.name like '%".$data_search['name']."%'");
        endif;
        if(isset($data_search['ic_number_company_reg']) && having_value($data_search['ic_number_company_reg'])):
            db_where("(acc.ic_number like '%".$data_search['ic_number_company_reg']."%' or acc.company_registration_number like '%".$data_search['ic_number_company_reg']."%')");
        endif;
        if(isset($data_search['acc_start']) && having_value($data_search['acc_start'])):
            db_where("a.account_number >= '".$data_search['acc_start']."'");
        endif;
        if(isset($data_search['acc_end']) && having_value($data_search['acc_end'])):
            db_where("a.account_number <= '".$data_search['acc_end']."'");
        endif;
        return db_count_results();
    }

    function get_account($per_page='',$search_segment='',$data_search=array()){
        db_select('a.*,acc.*,t.*,c.*,r.*');
        db_select('(select TOTAL_COUNT from C_DOCUMENT_LOG where C_DOCUMENT_LOG.DOCUMENT_NAME = '."'DOC_SIGNATURE'".' and C_DOCUMENT_LOG.account_id = a.account_id ) as TOTAL_COUNT');
        db_select(  "to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
        db_from('acc_account a');
        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
            db_where("a.account_number like '%".$data_search['account_number']."%'");
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('a.type_id',$data_search['type_id']);
        endif;
        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
            db_where('a.category_id',$data_search['category_id']);
        endif;
        if(isset($data_search['almost_expired']) && having_value($data_search['almost_expired'])):
            // db_where("extract(year from a.date_end) = ".date('Y')."");
            // db_where("extract(month from a.date_end) = ".date('m')."");
            if($data_search['almost_expired']==1):
                db_where("A.DATE_END IS NOT NULL");
                db_where('a.STATUS_ACC',STATUS_ACCOUNT_ACTIVE);
                db_where("A.DATE_END > TO_DATE('".date('Y-m-d')."','YYYY-MM-DD')");
                db_where("( A.DATE_END -TO_DATE('".date('Y-m-d')."','YYYY-MM-DD')) <= 60");
            elseif ($data_search['almost_expired']==2):
                db_where('a.STATUS_ACC',STATUS_ACCOUNT_ACTIVE);
            elseif ($data_search['almost_expired']==3):
                db_where('a.STATUS_ACC',STATUS_ACCOUNT_NONACTIVE);
            endif;
        endif;
        if(isset($data_search['name']) && having_value($data_search['name'])):
            db_where("acc.name like '%".$data_search['name']."%'");
        endif;
        if(isset($data_search['status_bill']) && having_value($data_search['status_bill'])):
            db_where('a.STATUS_BILL',STATUS_BILL_ACTIVE);
        endif;
        if(isset($data_search['ic_number_company_reg']) && having_value($data_search['ic_number_company_reg'])):
            db_where("(acc.ic_number like '%".$data_search['ic_number_company_reg']."%' or acc.company_registration_number like '%".$data_search['ic_number_company_reg']."%')");
        endif;
        if(isset($data_search['acc_start']) && having_value($data_search['acc_start'])):
            db_where("a.account_number >= '".$data_search['acc_start']."'");
        endif;
        if(isset($data_search['acc_end']) && having_value($data_search['acc_end'])):
            db_where("a.account_number <= '".$data_search['acc_end']."'");
        endif;
        db_order('a.account_number');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_account_details($id){
        db_select('a.*,acc.*,t.*,c.*,r.*,asset.asset_name,asset.asset_add');
        db_select('a.bill_type as bill_type');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_select("to_char(acc.date_of_birth, 'dd-mm-yyyy') as date_of_birth",false);
        db_select('acc.address as user_address');
        db_select('acc.department_id as department_id');
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_asset asset','a.asset_id=asset.asset_id','left');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left');
        db_from('acc_account a');
        db_where('account_id',$id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_account_details_by_accountNo($account_number){
        db_select('a.*,acc.*,t.*,c.*,r.*,asset.asset_name,asset.asset_add');
        db_select('a.bill_type as bill_type');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_select("to_char(acc.date_of_birth, 'dd-mm-yyyy') as date_of_birth",false);
        db_select('acc.address as user_address');
        db_select('acc.department_id as department_id');
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_asset asset','a.asset_id=asset.asset_id','left');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left');
        db_from('acc_account a');
        db_where('ACCOUNT_NUMBER',$account_number);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function count_account_outstanding($data_search=array()){
        db_select('a.*,acc.*,t.*,c.*,r.*');
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
        db_from('acc_account a');
        db_where('a.notice_level != 0');
        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
            // db_where('a.account_number',$data_search['account_number']);
            db_where("a.account_number like '%".$data_search['account_number']."%'");
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('a.type_id',$data_search['type_id']);
        endif;
        if(isset($data_search['name']) && having_value($data_search['name'])):
            db_where("acc.name like '%".$data_search['name']."%'");
        endif;
        if(isset($data_search['ic_number']) && having_value($data_search['ic_number'])):
            db_where("acc.ic_number like '%".$data_search['ic_number']."%'");
        endif;
        return db_count_results();
    }

    function get_account_outstanding($per_page,$search_segment,$data_search=array()){
        db_select('a.*,acc.*,t.*,c.*,r.*');
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
        db_from('acc_account a');
        db_where('a.notice_level != 0');
        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
            // db_where('a.account_number',$data_search['account_number']);
        db_where("a.account_number like '%".$data_search['account_number']."%'");
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('a.type_id',$data_search['type_id']);
        endif;
        if(isset($data_search['ic_number']) && having_value($data_search['ic_number'])):
            db_where("acc.ic_number like '%".str_replace('-','',$data_search['ic_number'])."%'");
        endif;
        if(isset($data_search['name']) && having_value($data_search['name'])):
            db_where("acc.name like '%".$data_search['name']."%'");
        endif;
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_account_by_search($data_search=array()){
        db_select('a.*,acc.*,t.*,c.*,r.*');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_select("to_char(a.date_start, 'dd-mm-yyyy') as date_start",false);
        db_select("to_char(a.date_end, 'dd-mm-yyyy') as date_end",false);
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
        db_from('acc_account a');
        if(isset($data_search['status_acc']) && having_value($data_search['status_acc'])):
            db_where('a.status_acc',$data_search['status_acc']);
        endif;
        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
            db_where('a.category_id',$data_search['category_id']);
        endif;
        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
            db_where('a.account_number',$data_search['account_number']);
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('a.type_id',$data_search['type_id']);
        endif;
        if(isset($data_search['name']) && having_value($data_search['name'])):
            db_where("acc.name like '%".$data_search['name']."%'");
        endif;
        if(isset($data_search['ic_number_company_reg']) && having_value($data_search['ic_number_company_reg'])):
           // db_where('(acc.ic_number like "%'.$data_search['ic_number_company_reg'].'%" or acc.company_registration_number like "%'.$data_search['ic_number_company_reg'].'%")');
            db_where("(acc.ic_number like '%".$data_search['ic_number_company_reg']."%' or acc.company_registration_number like '%".$data_search['ic_number_company_reg']."%')");

        endif;
        if(isset($data_search['acc_start']) && having_value($data_search['acc_start'])):
            db_where("a.account_number >= '".$data_search['acc_start']."'");
        endif;
        if(isset($data_search['acc_end']) && having_value($data_search['acc_end'])):
            db_where("a.account_number <= '".$data_search['acc_end']."'");
        endif;
        $sql = db_get('');
        if($sql):
            return $sql->result_array();
        endif;
    }

    function acc_with_bill_gst($data_search){
        db_select('acc.account_id');
        db_join('b_master m','m.account_id = acc.account_id');
        db_join('b_item i','i.bill_id = m.bill_id');
        db_join('a_type t','t.type_id = acc.type_id');
        db_join('a_category c','c.category_id = acc.category_id');
        db_from('acc_account acc');
        // db_where('m.bill_month >= 4');
        db_where('m.bill_year >= 2015');
        db_where('i.tr_gst_status',1);
        // db_where('i.amount > 0');
        // db_where('acc.account_id',6262);
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where("acc.type_id",$data_search['type_id']);
        endif;
        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
            db_where("acc.category_id",$data_search['category_id']);
        endif;
        if(isset($data_search['status_acc']) && having_value($data_search['status_acc'])):
            db_where('acc.status_acc',$data_search['status_acc']);
        endif;
        db_group('acc.account_id');
        $sql = db_get('');
        if($sql):
            return $sql->result_array();
        endif;
    }

    function account_report_dashboard($per_page='',$search_segment='',$data_search=array()){
        // db_select('a.*,acc.*,t.*,c.*,r.*');
        // db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_select('count(account_id) as total_acc');
        db_select('t.type_name');
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
        db_from('acc_account a');
        db_group('t.type_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    public function count_expiry_acc(){
        db_select('*');
        db_from('acc_account a');
        db_where('STATUS_ACC',STATUS_ACCOUNT_ACTIVE);
        // db_where("extract(year from a.date_end) = ".date('Y')."");
        // db_where("extract(month from a.date_end) = ".date('m')."");
        db_where("A.DATE_END IS NOT NULL");
        db_where("A.DATE_END > TO_DATE('".date('Y-m-d')."','YYYY-MM-DD')");
        db_where("( A.DATE_END - TO_DATE('".date('Y-m-d')."','YYYY-MM-DD')) <= 60");
        return db_count_results();
    }

    public function get_account_aging_report($category_id,$acc_status){
        db_select('a.*,ass.*,au.*');
        db_from('acc_account a');
        db_join('a_asset ass','a.asset_id=ass.asset_id','LEFT');
        db_join('acc_user au','au.user_id=a.user_id');
        db_where('a.category_id',$category_id);
        if(isset($acc_status) && having_value($acc_status)):
            db_where('a.status_acc',$acc_status);
        endif;
        db_order('ass.asset_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    public function get_account_aging_by_type($type_id,$acc_status){
        db_select('a.*,ass.*,au.*');
        db_from('acc_account a');
        db_join('a_asset ass','a.asset_id=ass.asset_id');
        db_join('acc_user au','au.user_id=a.user_id');
        db_where('a.type_id',$type_id);
        if(isset($acc_status) && having_value($acc_status)):
            db_where('a.status_acc',$acc_status);
        endif;
        db_order('ass.asset_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_account_cron($data_search=array()){
        db_select('a.account_id,a.bill_type');
        // db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        // db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
        db_from('acc_account a');
        if(isset($data_search['status_bill']) && having_value($data_search['status_bill'])):
            db_where('a.STATUS_BILL',STATUS_BILL_ACTIVE);
        endif;
        if(isset($data_search['last_acc_id']) && having_value($data_search['last_acc_id'])):
            db_where('a.ACCOUNT_ID > '.$data_search['last_acc_id'].'');
        endif;
        if(isset($data_search['not_added_this_year']) && having_value($data_search['not_added_this_year'])):
            db_where("extract(year from a.dt_added) < ".date('Y')."");
        endif;
        db_where("a.type_id NOT IN (6,8)");
        db_order('a.account_id','asc');
        $sql = db_get('');
        if($sql):
            return $sql->result_array();
        endif;
    }

    public function get_account_kuarters(){
        db_select('a.*,ass.*,au.*');
        db_from('acc_account a');
        db_join('a_asset ass','a.asset_id=ass.asset_id');
        db_join('acc_user au','au.user_id=a.user_id');
        db_where('a.type_id',8);
        db_order('ass.asset_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    // public function sewaan_hartanah($year,$month){
    public function sewaan_hartanah($data_search){
        db_select ('a.type_id');
        db_select ('b.type_name');
        db_select ('count(account_id) as bil');
        db_select ("EXTRACT(MONTH FROM A.DT_SIGNATURE) AS BULAN");
        db_from('acc_account A');
        db_join('a_type B','A.type_id = B.type_id');
        db_where("A.TYPE_ID IN (1,2,3,4,5,9)");
        // db_where("EXTRACT(YEAR FROM A.DATE_START) = ",$year);
        if(isset($data_search['year']) && having_value($data_search['year'])):
            db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) = ".$data_search['year']."");
        endif;
        // db_where("EXTRACT(MONTH FROM A.DATE_START) = ",$month);
        db_group('A.type_id,b.type_name,EXTRACT(YEAR FROM A.DT_SIGNATURE),EXTRACT(MONTH FROM A.DT_SIGNATURE)');
        db_order('TYPE_ID');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    public function sewaan_papaniklan($data_search){
        db_select ('A.BILLBOARD_TYPE');
        db_select ('COUNT(account_id) as bil');
        db_select ("EXTRACT(MONTH FROM A.DT_SIGNATURE)AS BULAN");
        db_from('acc_account A');
        if(isset($data_search['year']) && having_value($data_search['year'])):
            db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) =".$data_search['year']."");
        endif;
        db_where("A.TYPE_ID = 6");
        db_where("A.BILLBOARD_TYPE in (1,2)");
        // db_where("EXTRACT(MONTH FROM A.DT_SIGNATURE) in (1,2,3,4,5,6,7,8,9,10,12)");
        db_group('A.BILLBOARD_TYPE,EXTRACT(YEAR FROM A.DT_SIGNATURE),EXTRACT(MONTH FROM A.DT_SIGNATURE)');
        db_order('A.BILLBOARD_TYPE');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    public function perjanjian_kutipan($data_search){
        db_select ('COUNT(A.ACCOUNT_ID) as bil');
        // db_select ('SUM(A.RENTAL_CHARGE) AS RM');
        db_select ("EXTRACT(YEAR FROM A.DT_SIGNATURE)AS YEAR");
        // db_select ('A.TYPE_ID');
        db_from('acc_account A');
        // if(isset($data_search['year']) && having_value($data_search['year'])):
        //     db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) IN(".$data_search['year'].",".$data_search['year2'].",".$data_search['year3'].")");
        // endif;

        if(isset($data_search['year']) && having_value($data_search['year'])):
            db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) >= ".$data_search['year']."");
        endif;

        if(isset($data_search['year2']) && having_value($data_search['year2'])):
            db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) <= ".$data_search['year2']."");
        endif;
        db_where("A.TYPE_ID IN (1,2,3,4,5,9)");
        //db_where("A.BILLBOARD_TYPE in (1,2)");
        // db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) =2017");
        db_group('EXTRACT(YEAR FROM A.DT_SIGNATURE)');
        db_order('YEAR');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    public function count_perjanjian_kutipan( $data_search )
    {
        db_select ('A.ACCOUNT_ID');
        db_from('acc_account A');
        db_where("A.TYPE_ID IN (1,2,3,4,5,9)");

        if( isset($data_search['year']) && having_value($data_search['year']) )
        {
            db_where(" EXTRACT(YEAR FROM A.DT_SIGNATURE) = ".$data_search['year']."");
        }

        $sql = db_count_results();

        if ( empty($sql) )
        {
            return 0;
        }
        else
        {
            // return $sql->row_array();
            return $sql;
        }
    }

    public function perjanjian_kutipan_billboard($data_search){
        db_select ('COUNT(A.ACCOUNT_ID) as bil');
        // db_select ('SUM(A.RENTAL_CHARGE) AS RM');
        db_select ("EXTRACT(YEAR FROM A.DT_SIGNATURE)AS YEAR");
        // db_select ('A.TYPE_ID');
        db_from('acc_account A');
        // if(isset($data_search['year']) && having_value($data_search['year'])):
        //     db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) IN(".$data_search['year'].",".$data_search['year2'].",".$data_search['year3'].")");
        // endif;

        if(isset($data_search['year']) && having_value($data_search['year'])):
            db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) >= ".$data_search['year']."");
        endif;

        if(isset($data_search['year2']) && having_value($data_search['year2'])):
            db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) <= ".$data_search['year2']."");
        endif;
        db_where("A.TYPE_ID = 6");
        //db_where("A.BILLBOARD_TYPE in (1,2)");
        // db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) =2017");
        db_group('EXTRACT(YEAR FROM A.DT_SIGNATURE)');
        db_order('YEAR');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
      endif;
    }

    public function count_perjanjian_kutipan_billboard( $data_search )
    {
        db_select ('A.ACCOUNT_ID');
        db_from('acc_account A');
        db_where("A.TYPE_ID = 6");

        if( isset($data_search['year']) && having_value($data_search['year']) )
        {
            db_where(" EXTRACT(YEAR FROM A.DT_SIGNATURE) = ".$data_search['year']."");
        }

        $sql = db_count_results();

        if ( empty($sql) )
        {
            return 0;
        }
        else
        {
            // return $sql->row_array();
            return $sql;
        }
    }

    function getAccountNoticeLevel($account_id)
    {
        db_select ('notice_level');
        db_from('acc_account');
        db_where("account_id",$account_id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    public function hasil($data_search){
        db_select ('COUNT(A.ACCOUNT_ID) as bil');
        // db_select ('SUM(B.AMOUNT) AS RM');
        db_select ("EXTRACT(MONTH FROM A.DT_SIGNATURE) AS BULAN");
        db_select ('A.TYPE_ID');
        // db_from('B_ITEM B');
        db_from('ACC_ACCOUNT A');
        // db_join('B_ITEM B','B.ACCOUNT_ID = A.ACCOUNT_ID');
        if(isset($data_search['year']) && having_value($data_search['year'])):
            db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) = ".$data_search['year']."");
        endif;
        // db_where("A.TYPE_ID = 6");
        // db_where("B.BILL_CATEGORY = 'R'");
        // db_where("B.GST_TYPE = 1");
        // db_where("EXTRACT(YEAR FROM B.DT_ADDED) =2015");
        db_group('EXTRACT(MONTH FROM A.DT_SIGNATURE),A.TYPE_ID');
        db_order('BULAN');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
      endif;
    }

    function getAccountDetailOnlyById($account_id)
    {
        db_select('*');
        db_from('acc_account');
        db_where('account_id',$account_id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function getAllAccounts($accountNumber='')
    {
        db_select('account_id');
        db_select('account_number');
        db_select('user_id');
        if ($accountNumber != "")
        {
            db_where("account_number like '%$accountNumber%'");
        }
        db_limit(30);
        db_from('acc_account');
        // db_order('account_id');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
    public function iso($data_search){
        db_select ('COUNT(A.ACCOUNT_ID) as bil');
        db_select ('A.TYPE_ID');
        db_from('ACC_ACCOUNT A');
        if(isset($data_search['year']) && having_value($data_search['year'])):
            db_where("EXTRACT(YEAR FROM A.DT_SIGNATURE) = ".$data_search['year']."");
        endif;
        db_group('A.TYPE_ID');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
      endif;
    }
}