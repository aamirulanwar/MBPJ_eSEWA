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
    	db_select('*');
        db_from('b_int_payment');

        if ( isset($data_search["ACCOUNT_NUMBER"]) && $data_search["ACCOUNT_NUMBER"] != "" )
        {
        	db_where('ACCOUNT_NUMBER',$data_search["ACCOUNT_NUMBER"]);
        }
        
        $sql = db_get('');
        if($sql):
            return $sql->result_array();
        endif;
    }
}
/* End of file modules/login/controllers/m_department.php */

