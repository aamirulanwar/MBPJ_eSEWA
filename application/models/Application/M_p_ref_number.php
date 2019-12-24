<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_p_ref_number extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function update($data_update,$year,$type_code){
        db_where('year',$year);
        db_where('type_code',$type_code);
        db_update('p_ref_number',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function get_cur_running_number($year,$type_code){
        db_where('year',$year);
        db_where('type_code',$type_code);
        db_from('p_ref_number');
        $sql = db_get('');
        if($sql):
            return $sql->row_array();
        endif;
    }

    function insert($data_insert){
        db_insert('p_ref_number',$data_insert);
        return true;//get_insert_id('p_ref_number');
    }
}