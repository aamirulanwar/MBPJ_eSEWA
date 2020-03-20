<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_a_rental_use  extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_a_rental_use($per_page,$search_segment){
        db_select('*');
        db_from('a_rental_use');
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('rental_use_name');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_a_rental_use(){
        db_select('*');
        db_from('a_rental_use');
        db_where('soft_delete',SOFT_DELETE_FALSE);
        return db_count_results();
    }

    function insert_a_rental_use($data_insert){
        db_insert('a_rental_use',$data_insert);
        $user_id = get_insert_id('a_rental_use');
          $data_audit_trail['log_id']                  = 6010;
          $data_audit_trail['remark']                  = "Tambah Kegunaan Sewaan";
          $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
          $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
          $data_audit_trail['refer_id']                = $user_id; //refer to db_where
          $this->audit_trail_lib->add($data_audit_trail);
        return $user_id;
    }

    function get_a_rental_use_details($id){
        db_select('*');
        db_from('a_rental_use');
        db_where('rental_use_id',$id);
        $sql = db_get('');
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_a_rental_use($data_update,$id){
        db_where('rental_use_id',$id);
        db_update('a_rental_use',$data_update);
        if ($data_update["soft_delete"] == 1)
        {  // code...
          if(db_affected_rows()!=0):
                $data_audit_trail['log_id']                  = 6012;
                $data_audit_trail['remark']                  = "Padam Kegunaan Sewaan";
                $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                $data_audit_trail['refer_id']                = $id; //refer to db_where
                $this->audit_trail_lib->add($data_audit_trail);
              return true;
          else:
              return false;
          endif;
        }
        else
        { // code...
          if(db_affected_rows()!=0):
                $data_audit_trail['log_id']                  = 6011;
                $data_audit_trail['remark']                  = "Kemaskini Kegunaan Sewaan";
                $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                $data_audit_trail['refer_id']                = $id; //refer to db_where
                $this->audit_trail_lib->add($data_audit_trail);
              return true;
          else:
              return false;
          endif;
        }
    }

    function get_a_rental_use_active(){
        db_select('*');
        db_from('a_rental_use');
        db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('rental_use_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}
