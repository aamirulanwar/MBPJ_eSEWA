<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */

class M_login extends ci_model
{
    function __construct()
    {
        parent::__construct();
    }

    function check_username($str)
    {
        $username = $str;
        $username = trim_strtolower($username);

        if(empty($username)):
            validation_message('_external', 'Sila masukkan <strong>ID Pengguna</strong>');
            // validation_message('_external', 'The <strong>email address</strong> field is required.');
            return false;
        endif;

        db_where('user_email',$username);
        if(db_count_results('users')==0):
            validation_message('_external','Sila masukkan <strong>ID Pengguna</strong> yang betul');
            // validation_message('_external','Wrong <strong>email address</strong>');
            return false;
        endif;
    }

    function check_password($str)
    {
        $pass = $str;
        $username = trim_strtolower(input_data('username'));
        $ttl2 = '';

        if(empty($pass)):
            validation_message('_external', 'Sila masukkan <strong>Katalaluan</strong>');
            // validation_message('_external', 'The <strong>password</strong> field is required.');
            return false;
        endif;

        db_where('user_email',$username);
        $sql = db_get('users',1);
        $user_id = 0;
        if($sql->num_rows()!=0):
            $sql = $sql->result();
            $user_id = $sql[0]->USER_ID;

            db_where('user_email',$username);
            db_where('profile_version',sha1(($pass).$user_id));

            $ttl2 = db_count_results('users');
        endif;

        if($ttl2==0):
            validation_message('_external', 'Sila masukkan <strong>Katalaluan</strong> yang betul');
            // validation_message('_external', 'Wrong <strong>password</strong>');

            load_library('audit_trail_lib');
            $post_arr = $this->input->post();
            unset($post_arr['password']);

            $data_insert['log_id']                  = 1001;
            $data_insert['remark']                  = $post_arr;
            $data_insert['status']                  = PROCESS_STATUS_FAILED;
            $data_insert['user_id']                 = $user_id;

            $this->audit_trail_lib->add($data_insert);
            return false;
        endif;
    }

    function _get_user_id()
    {
        $username   = trim_strtolower(input_data('username'));
        $user_id    = 0;
        $ttl = 0;
        $ttl2 = 0;
        db_where('user_email',$username);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_where('active',STATUS_ACTIVE);
        $sql = db_get('users',1);

        if($sql->num_rows()!=0):
            $ttl = 1;
            $sql = $sql->result();
            $user_id = $sql[0]->USER_ID;

            db_where('user_email',$username);
            db_where('profile_version',sha1((input_data('password')).$user_id));
            
            $ttl2 = db_count_results('users');
        endif;

        if($ttl!=0 && $ttl2!=0):
            return $user_id;
        else:
            load_library('audit_trail_lib');
            $post_arr = $this->input->post();
            unset($post_arr['password']);

            $data_insert['log_id']                  = 1001;
            $data_insert['remark']                  = $post_arr;
            $data_insert['status']                  = PROCESS_STATUS_FAILED;
            $data_insert['user_id']                 = 0;

            $this->audit_trail_lib->add($data_insert);
            return false;
        endif;
    }

    function process_login()
    {
        $user_id = $this->_get_user_id();

        if(!$user_id) return false;
        #begin set login to this user
        load_library('audit_trail_lib');
        $post_arr = $this->input->post();
        unset($post_arr['password']);
        $data_insert['log_id']                  = 1001;
        $data_insert['remark']                  = $post_arr;
        $data_insert['status']                  = PROCESS_STATUS_SUCCEED;
        $data_insert['user_id']                 = $user_id;

        $this->audit_trail_lib->add($data_insert);
        return $user_id;
    }
}
/* End of file modules/login/controllers/m_pentadbir.php */

