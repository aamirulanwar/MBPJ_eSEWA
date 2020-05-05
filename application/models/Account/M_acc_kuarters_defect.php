<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_acc_kuarters_defect extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function count_account_defect($data_search=array()){
        db_select('a.*,acc.*,t.*,c.*,r.*,ak.*');
        db_select("to_char(ak.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_join('acc_account acc','acc.account_id = ak.account_id');
        db_join('acc_user a','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = acc.type_id');
        db_join('a_category c','c.category_id = acc.category_id');
        db_join('a_rental_use r','r.rental_use_id = acc.rental_use_id','LEFT');
        db_from('acc_kuarters_defect ak');
        if(isset($data_search['search']) && having_value($data_search['search'])):
            db_where("(acc.account_number like '%".$data_search['search']."%') or (a.ic_number like '%".$data_search['search']."%') or (a.name like '%".$data_search['search']."%')");
        endif;
        return db_count_results();
    }

    function get_account_defect($per_page='',$search_segment='',$data_search=array()){
        db_select('a.*,acc.*,t.*,c.*,r.*,ak.*');
        db_select("to_char(ak.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_join('acc_account acc','acc.account_id = ak.account_id');
        db_join('acc_user a','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = acc.type_id');
        db_join('a_category c','c.category_id = acc.category_id');
        db_join('a_rental_use r','r.rental_use_id = acc.rental_use_id','LEFT');
        db_from('acc_kuarters_defect ak');
        if(isset($data_search['search']) && having_value($data_search['search'])):
            db_where("(acc.account_number like '%".$data_search['search']."%') or (a.ic_number like '%".$data_search['search']."%') or (a.name like '%".$data_search['search']."%')");
        endif;
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function insert_kuarters_defect($data_insert){
        if(!empty($data_update['date_added'])):
            db_set_date('date_added',$data_update['date_added']);
            unset($data_update['date_added']);
        endif;
        db_set_date_time('dt_added',timenow());
        db_insert('ACC_KUARTERS_DEFECT',$data_insert);
    //  $user_id = get_insert_id('a_rental_use');
          $data_audit_trail['log_id']                  = 6016;
          $data_audit_trail['remark']                  = "Tambah Laporan Kerosakan Kuarters";
          $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
          $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
          $data_audit_trail['refer_id']                = ''; //refer to db_where
          $this->audit_trail_lib->add($data_audit_trail);
        return true;
    }

    function get_account_defect_by_id($id){
        db_select('a.*,acc.*,t.*,c.*,r.*,ak.*');
        db_select("to_char(ak.date_added, 'yyyy-mm-dd hh24:mi:ss') as date_added",false);
        db_join('acc_account acc','acc.account_id = ak.account_id');
        db_join('acc_user a','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = acc.type_id');
        db_join('a_category c','c.category_id = acc.category_id');
        db_join('a_rental_use r','r.rental_use_id = acc.rental_use_id','LEFT');
        db_from('acc_kuarters_defect ak');
        db_where("ak.ACC_KUARTERS_DEFECT_ID",$id);
        $sql = db_get('');
        if($sql):
            return $sql->row_array();
        endif;
    }

    function delete_kuarters($id){
        db_where('ACC_KUARTERS_DEFECT_ID',$id);
        db_delete('ACC_KUARTERS_DEFECT');
          $data_audit_trail['log_id']                   = 6017; 
          $data_audit_trail['remark']                   = "Padam Laporan Kerosakan Kuarters";
          $data_audit_trail['status']                   = PROCESS_STATUS_SUCCEED;
          $data_audit_trail['user_id']                  = $this->curuser['USER_ID'];
          $data_audit_trail['refer_id']                 = ''; //refer to db_where
          $this->audit_trail_lib->add($data_audit_trail);
        return true;
    }
}
