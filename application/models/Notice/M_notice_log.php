<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 18/12/2019
 * Time: 11:24 PM
 */

class M_notice_log extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_notice_level($account_id,$level){
        db_select('n.*');
        db_select("to_char(n.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_added",false);
        db_from('notice_log n');
        db_where('n.notice_level',$level);
        db_where('n.account_id',$account_id);
        db_order('n.dt_added','desc');
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_log_notice($acc_id){
        db_select('*');
        db_from('notice_log');
        db_where('account_id',$acc_id);
        db_order('dt_added','desc');
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }
}