<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Audit_trail extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_model('audit_trail/M_audit_trail','m_audit_trail');
        load_model('user/M_users','m_users');
    }

    function _remap($method)
    {
        $array = array();
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function index(){
        $this->auth->restrict_access($this->curuser,array(2013));

        $data['link_1']     = 'Audit Trail';
        $data['link_2']     = 'Senarai Audit Trail';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Audit Trail';

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_audit_trail');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_audit_trail',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['user_id']     = '';
                $data_search['date_start']  = '';
                $data_search['date_end']    = '';
            endif;
        endif;

        $data_user              = $this->m_users->get_user_list_all();
        $data['data_search']    = $data_search;
        $data['data_user']      = $data_user;

        $search_segment = uri_segment(2);
        $total = $this->m_audit_trail->count_audit_trail($data_search);
        $links          = '/audit_trail';
        $uri_segment    = 2;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_audit_trail->get_audit_trail_list($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('audit_trail/v_audit_trail',$data);
    }
}
/* End of file modules/login/controllers/administrator.php */

