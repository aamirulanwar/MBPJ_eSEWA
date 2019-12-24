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
}
/* End of file modules/login/controllers/m_department.php */

