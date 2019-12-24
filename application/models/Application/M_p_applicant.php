<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_p_applicant extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_applicant($data_insert){
        if(!empty($data_insert['date_of_birth'])):
            db_set_date('date_of_birth',$data_insert['date_of_birth']);
            unset($data_insert['date_of_birth']);
        endif;
        db_insert('p_applicant',$data_insert);
        return get_insert_id('p_applicant');
    }

    function update_applicant($data_update,$id){
        if(!empty($data_update['date_of_birth'])):
            db_set_date('date_of_birth',$data_update['date_of_birth']);
            unset($data_update['date_of_birth']);
        endif;
        db_where('applicant_id',$id);
        db_update('p_applicant',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }
}