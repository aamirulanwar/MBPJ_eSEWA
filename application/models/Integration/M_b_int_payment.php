<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_b_int_payment extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert($data_insert_payment){
        db_set_date_time('dt_added',timenow());
        db_insert('b_int_payment',$data_insert_payment);
        return true;
    }

    function get($data_search)
    {
    	db_select('b_int_payment.*');
        db_select("to_char(dt_added,'dd/mm/yyyy') as custom_dt_added");
        db_from('b_int_payment');

        if ( isset($data_search["ACCOUNT_NUMBER"]) && $data_search["ACCOUNT_NUMBER"] != "" )
        {
        	db_where('ACCOUNT_NUMBER',$data_search["ACCOUNT_NUMBER"]);
        }

        if ( isset($data_search["PROCESS_STATUS"]) && $data_search["PROCESS_STATUS"] != "" )
        {
            db_where('PROCESS_STATUS',$data_search["PROCESS_STATUS"]);
        }
        
        $sql = db_get('');
        if($sql):
            return $sql->result_array();
        endif;
    }

    function update($data_condition,$data_update)
    {
        if ( isset($data_condition["ACCOUNT_NUMBER"]) && $data_condition["ACCOUNT_NUMBER"] != "" )
        {
            db_where('ACCOUNT_NUMBER',$data_condition["ACCOUNT_NUMBER"]);
        }

        if ( isset($data_condition["TR_CODE"]) && $data_condition["TR_CODE"] != "" )
        {
            db_where('TR_CODE',$data_condition["TR_CODE"]);
        }

        if ( isset($data_condition["TR_CODE_NEW"]) && $data_condition["TR_CODE_NEW"] != "" )
        {
            db_where('TR_CODE_NEW',$data_condition["TR_CODE_NEW"]);
        }

        if ( isset($data_condition["PROCESS_STATUS"]) && $data_condition["PROCESS_STATUS"] != "" )
        {
            db_where('PROCESS_STATUS',$data_condition["PROCESS_STATUS"]);
        }

        db_update('b_int_payment',$data_update);
        return true;
    }
}
/* End of file modules/login/controllers/m_department.php */

