<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_model('M_login','lg');
    }

    function _remap($method)
    {
        $array = array(
            'new_application',
            'not_registered',
            'almost_expired'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function index(){
        $this->auth->restrict_access($this->curuser,array(1001));

        $data['link_1']     = '';
        $data['link_2']     = '';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        templates('/dashboard/v_dashboard',$data);
    }

    function new_application(){
        $data_search['ref_number']              = '';
        $data_search['type_id']                 = '';
        $data_search['status_application']      = STATUS_APPLICATION_NEW;
        $data_search['status_create_account']   = '';

        $this->session->set_userdata('arr_filter_application',$data_search);
        redirect('/rental_application/application');
    }

    function not_registered(){
        $data_search['ref_number']              = '';
        $data_search['type_id']                 = '';
        $data_search['status_application']      = STATUS_APPLICATION_APPROVED;
        $data_search['status_create_account']   = 0;

        $this->session->set_userdata('arr_filter_application',$data_search);
        redirect('/rental_application/application');
    }

    function almost_expired(){
        $data_search['account_number']      = '';
        $data_search['type_id']             = '';
        $data_search['almost_expired']      = 1;

        $this->session->set_userdata('arr_filter_acc',$data_search);
        redirect('/account/account_list');
    }
}
/* End of file modules/login/controllers/administrator.php */

