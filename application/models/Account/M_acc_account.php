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
//            db_where("extract(year from a.date_end) = ".date('Y')."");
//            db_where("extract(month from a.date_get_accountend) = ".date('m')."");
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
//            db_where("extract(year from a.date_end) = ".date('Y')."");
//            db_where("extract(month from a.date_end) = ".date('m')."");
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
//            db_where('(acc.ic_number like "%'.$data_search['ic_number_company_reg'].'%" or acc.company_registration_number like "%'.$data_search['ic_number_company_reg'].'%")');
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

    function count_account_outstanding($data_search=array()){
        db_select('a.*,acc.*,t.*,c.*,r.*');
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
        db_from('acc_account a');
        db_where('a.notice_level != 0');
        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
            db_where('a.account_number',$data_search['account_number']);
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
            db_where('a.account_number',$data_search['account_number']);
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
//            db_where('(acc.ic_number like "%'.$data_search['ic_number_company_reg'].'%" or acc.company_registration_number like "%'.$data_search['ic_number_company_reg'].'%")');
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
//        db_where('m.bill_month >= 4');
        db_where('m.bill_year >= 2015');
        db_where('i.tr_gst_status',1);
//        db_where('i.amount > 0');
//        db_where('acc.account_id',6262);
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
//        db_select('a.*,acc.*,t.*,c.*,r.*');
//        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_select('count(account_id) as total_acc');
        db_select('t.type_name');
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
        db_from('acc_account a');
//        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
//            db_where('a.account_number',$data_search['account_number']);
//        endif;
//        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
//            db_where('a.type_id',$data_search['type_id']);
//        endif;
//        if(isset($data_search['name']) && having_value($data_search['name'])):
//            db_where("acc.name like '%".$data_search['name']."%'");
//        endif;
//        if(isset($data_search['ic_number_company_reg']) && having_value($data_search['ic_number_company_reg'])):
////            db_where('(acc.ic_number like "%'.$data_search['ic_number_company_reg'].'%" or acc.company_registration_number like "%'.$data_search['ic_number_company_reg'].'%")');
//            db_where("(acc.ic_number like '%".$data_search['ic_number_company_reg']."%' or acc.company_registration_number like '%".$data_search['ic_number_company_reg']."%')");
//
//        endif;
//        if(isset($data_search['acc_start']) && having_value($data_search['acc_start'])):
//            db_where("a.account_number >= '".$data_search['acc_start']."'");
//        endif;
//        if(isset($data_search['acc_end']) && having_value($data_search['acc_end'])):
//            db_where("a.account_number <= '".$data_search['acc_end']."'");
//        endif;
//        db_order('a.account_number');
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
        db_where("extract(year from a.date_end) = ".date('Y')."");
        db_where("extract(month from a.date_end) = ".date('m')."");
        return db_count_results();
    }

    public function get_account_aging_report($category_id,$acc_status){
        db_select('a.*,ass.*,au.*');
        db_from('acc_account a');
        db_join('a_asset ass','a.asset_id=ass.asset_id');
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
//        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
//        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
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
}