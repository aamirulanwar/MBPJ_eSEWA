<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_users extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function check_username($str)
    {
        $username = $str;
        $username = trim_strtolower($username);

        db_where('user_email',$username);
        if(db_count_results('users')==0):
            validation_message('_external','Wrong <strong>email address</strong>');
            return false;
        endif;
    }

    function check_password($str)
    {
        $pass = $str;
        $username = trim_strtolower(input_data('username'));
        $ttl2 = '';

        db_where('user_email',$username);
        $sql = db_get('users',1);

        if($sql->num_rows()!=0):
            $sql = $sql->result();
            $user_id = $sql[0]->user_id;

            db_where('user_email',$username);
            db_where('profile_version',sha1(($pass).$user_id));

            $ttl2 = db_count_results('users');
        endif;

        if($ttl2==0):
            validation_message('_external', 'Wrong <strong>password</strong>');
            return false;
        endif;
    }

    function _get_user_id(){

        $username = trim_strtolower(input_data('username'));
        $ttl = 0;
        $ttl2 = 0;
        db_where('user_email',$username);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_where('active',STATUS_ACTIVE);
        $sql = db_get('users',1);

        if($sql->num_rows()!=0):
            $ttl = 1;
            $sql = $sql->result();
            $user_id = $sql[0]->user_id;

            db_where('user_email',$username);
            db_where('profile_version',sha1((input_data('password')).$user_id));
            
            $ttl2 = db_count_results('users');
        endif;

        if($ttl!=0 && $ttl2!=0):
            return $user_id;
        else:
            return false;
        endif;
    }

    function process_login(){
        $user_id = $this->_get_user_id();

        if(!$user_id) return false;
        #begin set login to this user
        return $user_id;
    }

    function get_user_list($per_page,$search_segment,$data_search=array()){
        db_select('u.*,ug.*,d.*,ug.*,u.active as active');
        db_from('users u');
//        db_join('position_lvl p','u.pos_id = p.pos_id','LEFT');
        db_join('user_group ug','ug.user_group_id = u.user_group_id');
        db_join('department d','d.department_id = u.department_id','LEFT');
        db_where('user_admin',0);
        db_where('u.soft_delete',SOFT_DELETE_FALSE);
        if(isset($data_search['search']) && having_value($data_search['search'])):
            db_where("lower(u.user_name) like lower('%".$data_search['search']."%')",'',false);
        endif;
        db_order('u.user_name');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_user($data_search=array()){
        db_select('*');
        db_from('users u');
//        db_join('position_lvl p','u.pos_id = p.pos_id');
        db_join('user_group ug','ug.user_group_id = u.user_group_id');
        db_join('department d','d.department_id = u.department_id');
        db_where('user_admin',0);
        db_where('u.soft_delete',SOFT_DELETE_FALSE);
        if(isset($data_search['search']) && having_value($data_search['search'])):
            db_where("lower(u.user_name) like lower('%".$data_search['search']."%')",'',false);
        endif;
        return db_count_results();
    }

    function insert_user($data_insert){
        db_set_date_time('dt_added',timenow());
//        db_set_date_time('last_dt_update_password',timenow());
        db_insert('users',$data_insert);

        return get_insert_id('users');
    }

    function update_user($data_update,$id_user){
        if(isset($data_update['last_dt_update_password'])):
            db_set_date_time('last_dt_update_password',timenow());
            unset($data_update['last_dt_update_password']);
        endif;
        db_where('user_id',$id_user);
        db_update('users',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function get_user_details($user_id){
        db_select('u.*,ug.*,d.*,u.active as user_active');
        db_from('users u');
//        db_join('position_lvl p','u.pos_id = p.pos_id');
        db_join('user_group ug','ug.user_group_id = u.user_group_id');
        db_join('department d','d.department_id = u.department_id');
        db_where('user_id',$user_id);
        $sql = db_get('');
        if($sql):
            return $sql->row_array();
        endif;
    }

    function validate_password($data_pass,$teacher_id){
        db_where('user_id',$teacher_id);
        db_where('profile_version',$data_pass);
        $sql =  db_count_results('users');
        if($sql==1):
            return true;
        else:
            return false;
        endif;
    }

    function update_password($data_update,$user_id){
        db_set_date_time('last_dt_update_password',timenow());
        db_where('user_id',$user_id);
        $sql = db_update('users',$data_update);
        if($sql):
            return true;
        else:
            return false;
        endif;
    }

    function get_user_list_all($data_search=array()){
        db_select('u.*,ug.*,d.*,ug.*,u.active as active');
        db_from('users u');
//        db_join('position_lvl p','u.pos_id = p.pos_id','LEFT');
        db_join('user_group ug','ug.user_group_id = u.user_group_id');
        db_join('department d','d.department_id = u.department_id','LEFT');
        db_where('user_admin',0);
        if(isset($data_search['search']) && having_value($data_search['search'])):
            db_where("lower(u.user_name) like lower('%".$data_search['search']."%')",'',false);
        endif;
        db_order('u.user_name');
        $sql = db_get('');
        if($sql):
            return $sql->result_array();
        endif;
    }

}
/* End of file modules/login/controllers/m_pentadbir.php */

