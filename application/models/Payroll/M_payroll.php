<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_payroll extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->payroll = $this->load->database('payroll',TRUE);
    }

    function get_payroll_new($data_search = array()){
        $this->payroll->select('*');
        $this->payroll->from('PAYROLL.SEWA_STAGING');
		$this->payroll->where('TBACA','N');
		$this->payroll->order_by('TKH_HANTAR');
        $sql = $this->payroll->get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function update_payroll_process($data,$data_update){
        $this->payroll->where('BULAN',$data['BULAN']);
        $this->payroll->where('TAHUN',$data['TAHUN']);
        $this->payroll->where('NAMA',$data['NAMA']);
        $this->payroll->where('NO_PEKERJA',$data['NO_PEKERJA']);
        $this->payroll->where('NO_KP',$data['NO_KP']);
        $this->payroll->where('AMAUN',$data['AMAUN']);
        $this->payroll->where('TKH_HANTAR',$data['TKH_HANTAR']);
        $this->payroll->where('TBACA',$data['TBACA']);
        $this->payroll->update('PAYROLL.SEWA_STAGING',$data_update);
        return true;
    }
}
/* End of file modules/login/controllers/m_department.php */

