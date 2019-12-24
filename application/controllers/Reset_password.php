<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reset_password extends CI_Controller
{
    public $curuser;

    function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
//        $this->auth->loginonly($this->curuser);
        load_model('User/M_users','m_users');
    }

    function _remap($method)
    {
        $array = array('user_list','add_user','edit_user');
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function index()
    {
        if(empty($this->curuser)):
            redirect('/login');
        endif;

        $data['link_1']     = 'Tukar Kata Laluan';
        $data['link_2']     = '';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        validation_rules('password_1','<strong>kata laluan</strong>','required|min_length[6]');
        validation_rules('password_2','<strong>kata laluan</strong>','required|matches[password_1]
');

        if(validation_run()==false):
            templates('/reset_password/v_reset_password',$data);
        else:
            $data_update['profile_version']         = sha1((input_data('password_1').$this->curuser['USER_ID']));
            $data_update['last_dt_update_password'] = timenow();
            $update_status                          = $this->m_users->update_user($data_update,$this->curuser['USER_ID']);

            if($update_status>0):
                set_notify('notify',TEXT_UPDATE_RECORD);
            else:
                set_notify('notify',TEXT_UPDATE_UNSUCCESSFUL,3);
            endif;
            redirect('/dashboard');
        endif;
    }
}
/* End of file modules/login/controllers/department.php */

