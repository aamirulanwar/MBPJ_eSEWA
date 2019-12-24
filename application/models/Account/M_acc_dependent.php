<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_acc_dependent extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_dependent($data_insert){
        db_insert('acc_dependent',$data_insert);
        return get_insert_id('acc_dependent');
    }

    function get_dependent_by_applicant_id($applicant_id){
        db_where('applicant_id',$applicant_id);
        db_from('acc_dependent');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_dependent_by_acc_id($acc_id){
        db_where('acc_id',$acc_id);
        db_from('acc_dependent');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_dependent_by_applicant_id($applicant_id,$relationship=''){
        db_where('applicant_id',$applicant_id);
        db_from('acc_dependent');
        if($relationship!=''):
            db_where('LOWER(relationship)',$relationship);
        endif;
        return db_count_results();
    }

    function update_dependent_by_applicant_id($data_update,$id){
        db_where('APPLICANT_ID',$id);
        db_update('acc_dependent',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

}