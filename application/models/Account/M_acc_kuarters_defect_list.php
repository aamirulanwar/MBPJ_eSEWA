<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_acc_kuarters_defect_list extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_kuarters_defect_list($data_insert){
        db_set_date_time('dt_added',timenow());
        db_insert('ACC_KUARTERS_DEFECT_LIST',$data_insert);
          $data_audit_trail['log_id']                  = 6018;
          $data_audit_trail['remark']                  = "Tambah Senarai Kerosakan Kuarters";
          $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
          $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
          $data_audit_trail['refer_id']                = ''; //refer to db_where
          $this->audit_trail_lib->add($data_audit_trail);
        return true;
    }

    function get_list_defect($id){
        db_from('ACC_KUARTERS_DEFECT_LIST');
        db_where("ACC_KUARTERS_DEFECT_ID",$id);
        $sql = db_get('');
        if($sql):
            return $sql->result_array();
        endif;
    }

    function delete_item($id){
        db_where('ID_LIST',$id);
        db_delete('ACC_KUARTERS_DEFECT_LIST');
          $data_audit_trail['log_id']                  = 6019;
          $data_audit_trail['remark']                  = "Padam Senarai Kerosakan Kuarters";
          $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
          $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
          $data_audit_trail['refer_id']                = ''; //refer to db_where
          $this->audit_trail_lib->add($data_audit_trail);
        return true;
    }

    function delete_item_by_parent_id($id){
        db_where('ACC_KUARTERS_DEFECT_ID',$id);
        db_delete('ACC_KUARTERS_DEFECT_LIST');
        return true;
    }
}
