<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_library('Audit_trail_lib');

        load_model('User/M_users', 'm_users');
    }

    function _remap($method)
    {
        $array = array('validate_password','update_password');
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function index()
    {
        $data['link_1'] = 'Profil';
        $data['link_2'] = '';
        $data['link_3'] = '';
        $data['pagetitle'] = '';

        $user_id = $this->curuser['USER_ID'];

        $user_details = $this->m_users->get_user_details($user_id);
        if(!$user_details):
            return false;
        endif;

        $data['user_details']       = $user_details;

        validation_rules('user_name','<strong>nama</strong>','required');

        if(input_data('user_email')!=$user_details['USER_EMAIL']):
            validation_rules('user_email','<strong>alamat emel</strong>','required|valid_email|is_unique[USERS.USER_EMAIL]');
        else:
            validation_rules('user_email','<strong>alamat emel</strong>','valid_email');
        endif;
        validation_rules('user_nobimbit','<strong>nombor telefon</strong>','callback_check_format_notel');
        if(validation_run()==false):
            templates('profile/v_profile',$data);
        else:
            $data_update['user_name']       = ucwords(input_data('user_name'));
            $data_update['user_email']      = input_data('user_email');
            $data_update['user_nobimbit']   = clean_phone_number(input_data('user_nobimbit'));

            $update_status                  = $this->m_users->update_user($data_update,$user_id);
            if($update_status):
                set_notify('notify_msg',TEXT_UPDATE_RECORD);
            else:
                set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL,3);
            endif;
            redirect('/profile/');
        endif;
    }

    function check_format_notel($str){
        if(!empty($str)):
            $status = check_mobile_number($str);
            if($status==false):
                $this->form_validation->set_message('check_format_notel', 'Sila masukkan format <strong>nombor telefon</strong> yang betul');
                return FALSE;
            else:
                return true;
            endif;
        endif;
    }

    function validate_password(){
        if(is_ajax()):
            $data_type  = input_data('type');
            $data_pass  = sha1(trim(input_data('cur_pass').$this->curuser['USER_ID']));
            $user_id    = $this->curuser['USER_ID'];
            if($data_type=='current_password'):
                $check_password = $this->m_users->validate_password($data_pass,$user_id);
                if($check_password):
                    echo 1;
                else:
                    echo 0;
                endif;
            endif;
        endif;
    }

    function update_password(){
        if(is_ajax()):
            $data_pass = sha1(trim(input_data('new_pass').$this->curuser['USER_ID']));
            $user_id = $this->curuser['USER_ID'];

            $data_update['profile_version'] = $data_pass;
            $update_password = $this->m_users->update_password($data_update,$user_id);
            if($update_password):
                echo 1;
            else:
                echo 0;
            endif;
        endif;
    }
}