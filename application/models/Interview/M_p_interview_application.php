<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_p_interview_application extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_interview_application($data_insert){
        db_insert('p_interview_application',$data_insert);
        return get_insert_id('p_interview_application');
    }

    function get_category_by_interview($type_id){
        db_select('c.category_id, c.category_name, COUNT(*) as total_application');
        db_from('p_interview_application ia');
        db_join('p_interview i','i.interview_id = ia.interview_id');
        db_join('p_application a','ia.application_id = a.application_id');
        db_join('a_type t', 'i.type_id = t.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_where('i.interview_id',$type_id);
        db_where('i.soft_delete',SOFT_DELETE_FALSE);
        db_where('c.active',STATUS_ACTIVE);
        db_where('c.soft_delete',SOFT_DELETE_FALSE);
        db_group('c.category_id, c.category_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}