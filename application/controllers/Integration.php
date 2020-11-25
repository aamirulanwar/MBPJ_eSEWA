<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Integration extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_library('Audit_trail_lib');

        $this->load->model('Bill/M_bill_master', 'm_bill_master');
        $this->load->model('Bill/M_bill_item', 'm_bill_item');
        $this->load->model('Integration/M_b_int_kewangan', 'm_b_int_kewangan');
    }

    function _remap($method)
    {
        $array = array(
            'test_sistem_kewangan',
            'pushBillGeneratedToSP',
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->test_sistem_kewangan();
    }

    function test_sistem_kewangan()
    {
        $data['link_1']     = 'TEST INTEGRATION';
        $data['link_2']     = 'SISTEM KEWANGAN';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $data_search["BILL_MONTH"] = date('m');
        $data_search["BILL_YEAR"] = date('Y');
        $data_search["BILL_CATEGORY"] = "B";

        $data["bill_record"] = $this->m_bill_master->getTotalBillGeneratedByKodKategory( $data_search["BILL_MONTH"], $data_search["BILL_YEAR"]  );
        $data["total_bill"] = count( $this->m_bill_master->get( $data_search ) );

        
        templates('/integration/v_integrasi_kewangan',$data);
    }

    function pushBillGeneratedToSP()
    {
        $month = date('m');
        $year = date('Y');

        $bill_generated = $this->m_bill_master->getTotalBillGeneratedByKodKategory( $month, $year );
        
        $total_bill_generated = 0;
        $total_bill_amount = 0;
        foreach ( $bill_generated as $bill ) 
        {
            # code...
            $total_bill_generated = $total_bill_generated + $bill["TOTAL_BILL_COUNT"];
            $total_bill_amount = $total_bill_amount + $bill["TOTAL_BILL_AMOUNT"];
        }

        $data_insert["TARIKH"] = date('d/m/Y');
        $data_insert["PERAKAUNAN"] = "91502"; // change code from 91505 to 91502
        $data_insert["AMAUN"] = $total_bill_amount;
        $data_insert["SAH"] = "N";
        $data_insert["TARIKH_SAH"] = null;
        $data_insert["SIRI"] = date('m')."-".date('Y');

        $this->m_b_int_kewangan->insert($data_insert);
    }
}