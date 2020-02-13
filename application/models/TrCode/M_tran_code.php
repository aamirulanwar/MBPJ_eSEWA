<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_tran_code extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->admin_trcode = $this->load->database('admin',TRUE);
    }

    function get_tr_code_list($data_search = array()){
        $this->admin_trcode->select('*');
        $this->admin_trcode->from('MCTRANCODE');
		$this->admin_trcode->where('MCT_MDCODE','B');
        if(isset($data_search['tr_id']) && having_value($data_search['tr_id'])):
            $this->admin_trcode->where('tr_id',$data_search['tr_id']);
        endif;
        if(isset($data_search['TR_TYPE']) && having_value($data_search['TR_TYPE'])):
            $this->admin_trcode->where('TR_TYPE',$data_search['TR_TYPE']);
        endif;
        if(isset($data_search['AUTO_GENERATE']) && having_value($data_search['TR_TYPE'])):
            $this->admin_trcode->where('AUTO_GENERATE',$data_search['AUTO_GENERATE']);
        endif;
        if(isset($data_search['MCT_TSTATS']) && having_value($data_search['MCT_TSTATS'])):
            $this->admin_trcode->where('MCT_TSTATS',$data_search['MCT_TSTATS']);
        endif;
        $this->admin_trcode->where("MCT_TRCODENEW is not null");
        $this->admin_trcode->order_by('MCT_TRCODENEW');
        $sql = $this->admin_trcode->get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_tr_code($data_search = array()){
        $this->admin_trcode->select('*');
        $this->admin_trcode->from('MCTRANCODE');
        if(isset($data_search['MCT_TRCODENEW']) && having_value($data_search['MCT_TRCODENEW'])):
            $this->admin_trcode->where('MCT_TRCODENEW',$data_search['MCT_TRCODENEW']);
        endif;
        if(isset($data_search['TR_TYPE']) && having_value($data_search['TR_TYPE'])):
            $this->admin_trcode->where('TR_TYPE',$data_search['TR_TYPE']);
        endif;
        if(isset($data_search['AUTO_GENERATE']) && having_value($data_search['TR_TYPE'])):
            $this->admin_trcode->where('AUTO_GENERATE',$data_search['AUTO_GENERATE']);
        endif;
        if(isset($data_search['MCT_TSTATS']) && having_value($data_search['MCT_TSTATS'])):
            $this->admin_trcode->where('MCT_TSTATS',$data_search['MCT_TSTATS']);
        endif;
        $sql = $this->admin_trcode->get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_tr_code_list_journal($data_search = array()){
        $this->admin_trcode->select('*');
        $this->admin_trcode->from('MCTRANCODE');
        $this->admin_trcode->where('MCT_MDCODE','B');
        // if(isset($data_search['tr_id']) && having_value($data_search['tr_id'])):
        //     $this->admin_trcode->where('tr_id',$data_search['tr_id']);
        // endif;
        // if(isset($data_search['TR_TYPE']) && having_value($data_search['TR_TYPE'])):
        //     $this->admin_trcode->where('TR_TYPE',$data_search['TR_TYPE']);
        // endif;
        // if(isset($data_search['AUTO_GENERATE']) && having_value($data_search['TR_TYPE'])):
        //     $this->admin_trcode->where('AUTO_GENERATE',$data_search['AUTO_GENERATE']);
        // endif;
        // if(isset($data_search['MCT_TSTATS']) && having_value($data_search['MCT_TSTATS'])):
        //     $this->admin_trcode->where('MCT_TSTATS',$data_search['MCT_TSTATS']);
        // endif;
        // $this->admin_trcode->where("MCT_TRCODENEW is not null");
        $this->admin_trcode->order_by('MCT_TRCODENEW');
        $sql = $this->admin_trcode->get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}
/* End of file modules/login/controllers/m_department.php */

