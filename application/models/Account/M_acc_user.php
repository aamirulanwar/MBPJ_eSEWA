<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_acc_user extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_user($data_insert){
        db_set_date_time('dt_added',timenow());
        db_set_date_time('dt_update',timenow());
        if(!empty($data_insert['date_of_birth'])):
            db_set_date('date_of_birth',$data_insert['date_of_birth']);
            unset($data_insert['date_of_birth']);
        endif;
        if(!empty($data_insert['starting_of_service_date'])):
            db_set_date('starting_of_service_date',$data_insert['starting_of_service_date']);
            unset($data_insert['starting_of_service_date']);
        endif;
        db_insert('acc_user',$data_insert);
        return get_insert_id('acc_user');
    }

    function get_acc_user_details($data_search=array()){
        if(isset($data_search['ic_number']) && !empty($data_search['ic_number'])):
            db_where('ic_number',$data_search['ic_number']);
        endif;

        if(isset($data_search['company_registration_number']) && !empty($data_search['company_registration_number'])):
            db_where('company_registration_number',$data_search['company_registration_number']);
        endif;

        if(isset($data_search['user_id']) && !empty($data_search['user_id'])):
            db_where('user_id',$data_search['user_id']);
        endif;

        db_from('acc_user');
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_user($data_update,$user_id){
        db_where('user_id',$user_id);
        db_set_date_time('dt_update',timenow());
        if(!empty($data_update['date_of_birth'])):
            db_set_date('date_of_birth',$data_update['date_of_birth']);
            unset($data_update['date_of_birth']);
        endif;
        if(!empty($data_update['starting_of_service_date'])):
            db_set_date('starting_of_service_date',$data_update['starting_of_service_date']);
            unset($data_update['starting_of_service_date']);
        endif;
        db_update('acc_user',$data_update);

        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function get_account_base_on_ic($ic){
        db_select('a.account_id,a.bill_type,c.TRCODE_CATEGORY');
//        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_join('acc_account a','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
//        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','LEFT');
        db_from('acc_user acc');
        db_where('IC_NUMBER',$ic);
        db_where('t.type_id',8);
        db_order('a.account_id','desc');
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }
}
