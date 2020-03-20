<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_user_group extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        load_library('Audit_trail_lib');
    }

    function insert_user_group($data_user_group){
        db_insert('user_group',$data_user_group);
        $user_id = get_insert_id('user_group');

          $data_audit_trail['log_id']                  = 5006;
          $data_audit_trail['remark']                  = "Tambah Kumpulan Pengguna";
          $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
          $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
          $data_audit_trail['refer_id']                = $user_id; //refer to db_where
          $this->audit_trail_lib->add($data_audit_trail);

        return $user_id;
    }

    function get_user_group_list(){
        db_select('*');
        db_from('user_group u');
//        db_join('access_level a','u.access_level = a.lvl_id');
        db_order('u.user_group_desc');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_active_user_group(){
        db_select('*');
        db_from('user_group');
        db_where('active',STATUS_ACTIVE);
        db_order('user_group_desc');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_ugroup_details($ugroup_id){
        db_select('*');
        db_from('user_group');
        db_where('user_group_id',$ugroup_id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_user_group($update_user_group,$ugroup_id){
        db_where('user_group_id',$ugroup_id);
        db_update('user_group',$update_user_group);
        if(db_affected_rows()!=0):
            $data_audit_trail['log_id']                  = 5005;
            $data_audit_trail['remark']                  = "Kemaskini Kumpulan Pengguna";
            $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
            $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
            $data_audit_trail['refer_id']                = $ugroup_id; //refer to db_where
            $this->audit_trail_lib->add($data_audit_trail);
            return true;
        else:
            return false;
        endif;
    }

    function delete_user_group($delete_id){
        db_where('user_group_id',$delete_id);
        $sql = db_delete('user_group');
        if($sql):
            $data_audit_trail['log_id']                  = 5006;
            $data_audit_trail['remark']                  = "Padam Kumpulan Pengguna";
            $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
            $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
            $data_audit_trail['refer_id']                = $delete_id; //refer to db_where
            $this->audit_trail_lib->add($data_audit_trail);
            return true;
        else:
            return false;
        endif;
    }
}
/* End of file modules/login/controllers/m_pentadbir.php */
