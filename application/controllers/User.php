<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */
class User extends CI_Controller
{
    public $curuser;

    function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_model('User/M_users','m_users');
        load_model('Department/M_department','m_dept');
        load_model('Access/M_access_file_parent','m_access_file_parent');
        load_model('User/M_user_group','m_user_group');
    }

    function _remap($method)
    {
        $array = array('user_list','add_user','edit_user','delete_user','reset_password');
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->user_list();
    }

    function user_list(){
        $this->auth->restrict_access($this->curuser,array(2009));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Akaun Pengguna';
        $data['link_3']     = 'Senarai Akaun Pengguna';
        $data['pagetitle']  = 'Senarai Akaun Pengguna';

        $search_segment = uri_segment(3);
        $data_input = $this->input->post();
        if(!empty($data_input)):
            $this->session->set_userdata('arr_filter_user',$this->input->post());
            $data_search = get_session('arr_filter_user');
        else:
            $arr_filter_user_session = get_session('arr_filter_user');
            if(!empty($arr_filter_user_session)):
                $data_search = get_session('arr_filter_user');
            else:
                $data_search['search'] = '';
            endif;
        endif;

        $data['data_search'] = $data_search;
        $total = $this->m_users->count_user($data_search);
        $links          = '/user/user_list';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $user_list              = $this->m_users->get_user_list($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['user_list']      = $user_list;

        templates('/user/v_user',$data);
    }

    function add_user(){
        $this->auth->restrict_access($this->curuser,array(2010));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Akaun Pengguna';
        $data['link_3']     = 'Tambah Akaun Pengguna';
        $data['pagetitle']  = '';

        $file_access_parent         = $this->m_access_file_parent->get_access_file_parent();
        $data['file_access_parent'] = $file_access_parent;

        $data['list_user_group']    = $this->m_user_group->get_active_user_group();

        $get_department_layer_1 = $this->m_dept->get_department_layer_1();
        $department_arr = array();
        foreach($get_department_layer_1 as $department):
            $department_arr[] = $department;
            $child_department = $this->m_dept->get_department_layer_2($department['DEPARTMENT_ID']);
            if($child_department):
                foreach($child_department as $child_dept):
                    $department_arr[] = $child_dept;
                endforeach;
            endif;
        endforeach;

        $data['department_arr'] = $department_arr;

        validation_rules('name','<strong>nama</strong>','required');
        validation_rules('user_group','<strong>kumpulan pengguna</strong>','required');
        validation_rules('department','<strong>jabatan/bahagian</strong>','required');
        validation_rules('email_address','<strong>Id pengguna (emel)</strong>','required|valid_email|is_unique[USERS.USER_EMAIL]');
        validation_rules('mobile_number','<strong>no. telefon</strong>','required');

        if(validation_run()==false):
            templates('/user/v_user_add',$data);
        else:
            $data_insert['user_name']               = ucwords(input_data('name'));
            $data_insert['user_group_id']           = input_data('user_group');
            //            $data_insert['user_role']       = input_data('user_role');
            $data_insert['department_id']           = input_data('department');
            //            $data_insert['pos_id']          = input_data('position');
            $data_insert['user_email']              = input_data('email_address');
            //            $data_insert['assign_to_csr']   = input_data('assign_to_csr');
            $data_insert['user_nobimbit']           = input_data('mobile_number');
            //            $data_insert['dt_added']                = timenow();
            //            $data_insert['last_dt_update_password'] = timenow();
            $id_user                                = $this->m_users->insert_user($data_insert);
            if(is_numeric($id_user)):
                $password                       = sha1(DEFAULT_PASSWORD.$id_user);
                $data_update['profile_version'] = $password;
                $update_status                  = $this->m_users->update_user($data_update,$id_user);
                if($update_status):
                    set_notify('add_user',TEXT_SAVE_RECORD);
                else:
                    set_notify('add_user',TEXT_SAVE_UNSUCCESSFUL,2);
                endif;
                redirect('/user/add_user/');
            endif;
        endif;
    }

    function edit_user(){
        $this->auth->restrict_access($this->curuser,array(2011));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Akaun Pengguna';
        $data['link_3']     = 'Kemaskini Akaun Pengguna';
        $data['pagetitle']  = '';

        $user_id = urlDecrypt(uri_segment(3));
        if(!is_numeric($user_id)):
            return false;
        endif;

        $user_details = $this->m_users->get_user_details($user_id);
        if(!$user_details):
            return false;
        endif;

        $data['user_details']       = $user_details;
        $file_access_parent         = $this->m_access_file_parent->get_access_file_parent();
        $data['file_access_parent'] = $file_access_parent;

        $data['list_user_group']    = $this->m_user_group->get_active_user_group();

        //        $data['list_gred']          = $this->m_user->get_active_gred();
        //        $data['list_position']      = $this->m_user->get_active_position();

        $get_department_layer_1 = $this->m_dept->get_department_layer_1();
        $department_arr = array();
        foreach($get_department_layer_1 as $department):
            $department_arr[] = $department;
            $child_department = $this->m_dept->get_department_layer_2($department['DEPARTMENT_ID']);
            if($child_department):
                foreach($child_department as $child_dept):
                    $department_arr[] = $child_dept;
                endforeach;
            endif;
        endforeach;

        $data['department_arr'] = $department_arr;

        validation_rules('name','<strong>name</strong>','required');
        validation_rules('user_group','<strong>user group</strong>','required');
        validation_rules('department','<strong>department / section</strong>','required');
        //        validation_rules('position','<strong>position</strong>','required');
        validation_rules('status','<strong>status</strong>');
        //        validation_rules('user_role','<strong>user role</strong>','required');
        //        validation_rules('assign_to_csr','<strong>user role</strong>','required');

        if(input_data('email_address')!=$user_details['USER_EMAIL']):
            validation_rules('email_address','<strong>Id pengguna (emel)</strong>','required|valid_email|is_unique[USERS.USER_EMAIL]');
        else:
            validation_rules('email_address','<strong>Id pengguna (emel)</strong>','required|valid_email');
        endif;
        validation_rules('mobile_number','<strong>phone number</strong>','required');

        if(validation_run()==false):
            templates('/user/v_user_edit',$data);
        else:
            $data_update['user_name']       = ucwords(input_data('name'));
            $data_update['user_group_id']   = input_data('user_group');
            //            $data_update['user_role']       = input_data('user_role');
            $data_update['department_id']   = input_data('department');
            //            $data_update['pos_id']          = input_data('position');
            $data_update['user_email']      = input_data('email_address');
            $data_update['user_nobimbit']   = input_data('mobile_number');
            $data_update['active']          = (input_data('status'))?STATUS_ACTIVE:STATUS_INACTIVE;
            //            $data_update['assign_to_csr']   = input_data('assign_to_csr');


            $update_status                  = $this->m_users->update_user($data_update,$user_id);
            if($update_status>0):
                set_notify('edit_user',TEXT_UPDATE_RECORD);
            else:
                set_notify('edit_user',TEXT_UPDATE_UNSUCCESSFUL,3);
            endif;
            redirect('/user/edit_user/'.uri_segment(3));
        endif;

    }

    function check_format_notel($str){
        if(!empty($str)):
            $status = check_mobile_number($str);
            if($status==false):
                $this->form_validation->set_message('check_format_notel', 'Wrong format');
                return FALSE;
            else:
                return true;
            endif;
        endif;
    }

    function delete_user(){
        if(is_ajax()):
            $delete_id = input_data('delete_id');

            $data_update['soft_delete'] = SOFT_DELETE_TRUE;
            $delete = $this->m_users->update_user($data_update,$delete_id);
            if($delete):
                set_notify('user',TEXT_DELETE_RECORD,1);
                echo TEXT_DELETE_RECORD;
            else:
                set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                echo TEXT_DELETE_UNSUCCESSFUL;
            endif;
        endif;
    }

    function reset_password(){
        if(is_ajax()):
            $user_id = input_data('user_id');

            $data_update['profile_version'] = sha1(DEFAULT_PASSWORD.$user_id);
            $update_status                  = $this->m_users->update_user($data_update,$user_id);
            if($update_status):
                // echo 1;
                return true;
            else:
                // echo "<pre>";
                // var_dump($data_update);
                return false;
            endif;
        endif;
    }

    function gred_user()
    {
        $this->auth->restrict_access($this->curuser,array(2013));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Pengguna';
        $data['link_3']     = 'Gred Jawatan';
        $data['pagetitle']  = 'Senarai Gred Pengguna';

        $data['gred_list']          = $this->m_users->get_gred_list();
        $data['total_result']       = count($data['gred_list']);
        templates('/gred/v_gred_user',$data);
    }

    function add_gred_user()
    {
        $this->auth->restrict_access($this->curuser,array(2014));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Pengguna';
        $data['link_3']     = 'Tambah Gred Pengguna';
        $data['pagetitle']  = '';

        $data['gred_group_list'] = $this->m_users->get_gred_group();

        validation_rules('name','<strong>nama gred pengguna</strong>','required');
        validation_rules('gred_group','<strong>gred kumpulan</strong>','required');

        if(validation_run()==false):
            templates('/gred/v_gred_user_add',$data);
        else:
            $data_insert['gred_desc']       = strtoupper(input_data('name'));
            $data_insert['gred_group_id']   = input_data('gred_group');
            $id_user_group = $this->m_users->insert_gred_user($data_insert);
            if(is_numeric($id_user_group)):
                set_notify('add_gred_user','Data telah berjaya disimpan');
            else:
                set_notify('add_gred_user','Data tidak berjaya disimpan',2);
            endif;
            redirect('/user/add_gred_user/');
        endif;
    }

    function edit_gred_user()
    {
        $this->auth->restrict_access($this->curuser,array(2015));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Pengguna';
        $data['link_3']     = 'Kemaskini Gred Pengguna';
        $data['pagetitle']  = '';

        $gred_id = uri_segment(3);
        if(!is_numeric($gred_id)):
            return false;
        endif;

        $user_gred_details = $this->m_users->get_gred_details($gred_id);
        if(!$user_gred_details):
            return false;
        endif;

        $data['user_gred_details']  = $user_gred_details;
        $data['gred_group_list']    = $this->m_users->get_gred_group();

        validation_rules('name','<strong>nama gred pengguna</strong>','required');
        validation_rules('gred_group','<strong>gred kumpulan</strong>','required');
        $this->form_validation->set_rules('status','status');

        if(validation_run()==false):
            templates('/gred/v_gred_user_edit',$data);
        else:
            $data_update['gred_desc']       = strtoupper(input_data('name'));
            $data_update['gred_group_id']   = input_data('gred_group');
            $data_update['active']          = (input_data('status'))?STATUS_ACTIVE:STATUS_INACTIVE;

            $update_gred = $this->m_users->update_gred_user($data_update,$gred_id);
            if($update_gred):
                set_notify('edit_gred_user','Data telah berjaya dikemaskini');
            else:
                set_notify('edit_gred_user','Tiada perubahan pada data',3);
            endif;
            redirect('/user/edit_gred_user/'.$gred_id);
        endif;
    }

    function delete_gred_user(){
        if(is_ajax()):
            $delete_id = input_data('delete_id');
            $check_gred_user = $this->m_users->check_gred_user($delete_id);
            if($check_gred_user):
                $delete = $this->m_users->delete_gred_user($delete_id);
                if($delete):
                    set_notify('gred','Data telah berjaya dipadam',1);
                else:
                    set_notify('gred','Data tidak berjaya dipadam',2);
                endif;
            else:
                set_notify('gred','Data tidak boleh dipadam kerana terdapat pengguna yang menggunakan gred ini.',2);
            endif;
            echo 1;
        endif;
    }

    function position()
    {
        $this->auth->restrict_access($this->curuser,array(2009));

        $data['link_1']     = 'Administrator';
        $data['link_2']     = 'User';
        $data['link_3']     = 'Position';
        $data['pagetitle']  = 'Position List';

        $data['position_list']          = $this->m_users->get_position_list();
        $data['total_result']       = count($data['position_list']);
        templates('/position/v_position',$data);
    }

    function add_position()
    {
        $this->auth->restrict_access($this->curuser,array(2010));

        $data['link_1']     = 'Administrator';
        $data['link_2']     = 'User';
        $data['link_3']     = 'New Position';
        $data['pagetitle']  = '';

        validation_rules('name','<strong>position name</strong>','required');

        if(validation_run()==false):
            templates('/position/v_position_add',$data);
        else:
            $data_insert['pos_name']       = upper_case_first_char(input_data('name'));

            $id_post = $this->m_users->insert_position($data_insert);
            if(is_numeric($id_post)):
                set_notify('add_position',TEXT_SAVE_RECORD);
            else:
                set_notify('add_position',TEXT_SAVE_UNSUCCESSFUL,2);
            endif;
            redirect('/user/add_position/');
        endif;
    }

    function edit_position()
    {
        $this->auth->restrict_access($this->curuser,array(2011));

        $data['link_1']     = 'Administrator';
        $data['link_2']     = 'User';
        $data['link_3']     = 'Edit Position';
        $data['pagetitle']  = '';

        $post_id = uri_segment(3);
        if(!is_numeric($post_id)):
            return false;
        endif;

        $user_position_details = $this->m_users->get_position_details($post_id);
        if(!$user_position_details):
            return false;
        endif;

        $data['user_position_details'] = $user_position_details;

        validation_rules('name','<strong>position name</strong>','required');
        $this->form_validation->set_rules('status','status');

        if(validation_run()==false):
            templates('/position/v_position_edit',$data);
        else:
            $data_update['pos_name']    = upper_case_first_char(input_data('name'));
            $data_update['active']      = (input_data('status'))?STATUS_ACTIVE:STATUS_INACTIVE;

            $status_update = $this->m_users->edit_position($data_update,$post_id);
            if($status_update):
                set_notify('edit_position',TEXT_UPDATE_RECORD);
            else:
                set_notify('edit_position',TEXT_UPDATE_UNSUCCESSFUL,3);
            endif;
            redirect('/user/edit_position/'.$post_id);
        endif;
    }

    function delete_position(){
        if(is_ajax()):
            $delete_id = input_data('delete_id');

        $data_update['soft_delete'] = SOFT_DELETE_TRUE;
            $delete = $this->m_users->edit_position($data_update,$delete_id);
            if($delete):
                echo '<span style="color: green">'.TEXT_DELETE_RECORD.'</span>';
            else:
                echo '<span style="color: red">'.TEXT_DELETE_UNSUCCESSFUL.'</span>';
            endif;
        endif;
    }
}
/* End of file modules/login/controllers/department.php */

