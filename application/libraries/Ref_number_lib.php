<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

/*
 * Please refer https://pencil.atlassian.net/wiki/display/PCL/Audit+trail+log to get more details
 */

/**
 * Description of audit
 *
 * @author Shahrul
 */
class Ref_number_lib {
    
    private $ci;
    private $year;
    
    public function __construct()
    {
        $this->ci   = & get_instance();
        $this->year = date('Y');
        load_model('Application/M_p_ref_number', 'm_p_ref_number');
        load_model('Account/M_acc_running_number', 'm_acc_running_number');
    }
    
    function get_ref($type=REF_NUMBER_TYPE_APPLICATION,$type_code='MM',$year=''){
        if (empty($year)):
            $date = date('Y-m-d');
        endif;
        $this->year = date('Y',strtotime($date));
        if($type==REF_NUMBER_TYPE_APPLICATION):
            $cur_running_number = $this->generate_application_ref_number($type_code);
            $ref_number = $type_code.$this->year.sprintf_format($cur_running_number,5);
            return $ref_number;
        elseif ($type==REF_NUMBER_TYPE_ACCOUNT):
            $cur_number = $this->ci->m_acc_running_number->get_cur_running_number(1);

            $data_update['CUR_RUNNING_NUMBER']  = $cur_number['CUR_RUNNING_NUMBER']+1;
            $this->ci->m_acc_running_number->update($data_update,1);

            $ref_number = 'B11'.sprintf('%07d', $cur_number['CUR_RUNNING_NUMBER']);
            return $ref_number;
        endif;
    }

    function generate_application_ref_number($type_code){

        $cur_ref = $this->ci->m_p_ref_number->get_cur_running_number($this->year,$type_code);
        if(empty($cur_ref)):
            $data_insert['year']            = $this->year;
            $data_insert['running_number']  = 1;
            $data_insert['TYPE_CODE']       = $type_code;
            $this->ci->m_p_ref_number->insert($data_insert);
            return 1;
        else:
            $data_update['running_number']  = $cur_ref['RUNNING_NUMBER']+1;
            $update_result = $this->ci->m_p_ref_number->update($data_update,$this->year,$type_code);
            if($update_result):
                return $data_update['running_number'];
            endif;
        endif;
    }
}
