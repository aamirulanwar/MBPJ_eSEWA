<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller
{
    #public $curuser;
    function __construct()
    {
        parent::__construct();
        $this->sec_key = 'data_';
        $this->user = $this->auth->userdata();

        load_library('Audit_trail_lib');

    }

    function _remap($method)
    {
        $array = array();
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function index()
    {
        $data_audit_trail['log_id']                  = 1002;
        $data_audit_trail['remark']                  = '';
        $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
        $data_audit_trail['user_id']                 = $this->user['USER_ID'];
        $data_audit_trail['refer_id']                = 0;
        $this->audit_trail_lib->add($data_audit_trail);

        delete_cookie($this->sec_key.'A');
        delete_cookie($this->sec_key.'B');
        delete_cookie($this->sec_key.'C');
        delete_cookie($this->sec_key.'D');
        delete_cookie('demi_waktu');
        $this->load->library('session');
        $this->session->sess_destroy();

        redirect('/login/');
    }
}
/* End of file modules/logout/controllers/logout.php */

