<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_p_application extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_application($data_insert){
        if(!empty($data_insert['date_application'])):
            db_set_date('date_application',$data_insert['date_application']);
            unset($data_insert['date_application']);
        endif;

        db_set_date_time('dt_added',timenow());
        db_insert('p_application',$data_insert);
        return get_insert_id('p_application');
    }

    function update_application($data_update,$id){
        if(!empty($data_update['date_meeting'])):
            db_set_date('date_meeting',$data_update['date_meeting']);
            unset($data_update['date_meeting']);
        endif;

        if(!empty($data_update['date_agree'])):
            db_set_date('date_agree',$data_update['date_agree']);
            unset($data_update['date_agree']);
        endif;

        if(!empty($data_update['dt_create_account'])):
            db_set_date_time('dt_create_account',$data_update['dt_create_account']);
            unset($data_update['dt_create_account']);
        endif;

        if(!empty($data_update['date_start'])):
            db_set_date('date_start',$data_update['date_start']);
            unset($data_update['date_start']);
        endif;

        if(!empty($data_update['date_end'])):
            db_set_date('date_end',$data_update['date_end']);
            unset($data_update['date_end']);
        endif;

        db_where('application_id',$id);
        db_update('p_application',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function get_application($per_page,$search_segment,$data_search=array()){
        db_select('a.*,applicant.*,t.*,c.*,r.*');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_added",false);
        db_select('a.remark as remark');
        db_join('p_applicant applicant','applicant.applicant_id = a.applicant_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left  ');
        db_from('p_application a');
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        if(isset($data_search['status_application']) && having_value($data_search['status_application'])):
            db_where('a.status_application',$data_search['status_application']);
        endif;
        if(isset($data_search['status_agree']) && having_value($data_search['status_agree'])):
            db_where('a.status_agree',$data_search['status_agree']);
        endif;
        if(isset($data_search['status_create_account']) && having_value($data_search['status_create_account'])):
            db_where('a.status_create_account',$data_search['status_create_account']);
        endif;
        if(isset($data_search['ref_number']) && having_value($data_search['ref_number'])):
        //db_where('((a.ref_number = "'.$data_search['ref_number'].'") or (applicant.ic_number = "'.$data_search['ref_number'].'") or (name like "%'.$data_search['ref_number'].'%"))');
            db_where("((a.ref_number = '".$data_search['ref_number']."') or (applicant.ic_number = '".$data_search['ref_number']."') or (upper(name) like '%".strtoupper($data_search['ref_number'])."%'))");
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('a.type_id',$data_search['type_id']);
        endif;
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_application($data_search=array()){
        db_select('a.*,applicant.*,t.*,c.*,r.*');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_added",false);
        db_select('a.remark as remark');
        db_join('p_applicant applicant','applicant.applicant_id = a.applicant_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left  ');
        db_from('p_application a');
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        if(isset($data_search['status_application']) && having_value($data_search['status_application'])):
            db_where('a.status_application',$data_search['status_application']);
        endif;
        if(isset($data_search['status_agree']) && having_value($data_search['status_agree'])):
            db_where('a.status_agree',$data_search['status_agree']);
        endif;
        if(isset($data_search['status_create_account']) && having_value($data_search['status_create_account'])):
            db_where('a.status_create_account',$data_search['status_create_account']);
        endif;
        if(isset($data_search['ref_number']) && having_value($data_search['ref_number'])):
        //db_where('a.ref_number',$data_search['ref_number']);
            db_where("((a.ref_number = '".$data_search['ref_number']."') or (applicant.ic_number = '".$data_search['ref_number']."') or (upper(name) like '%".strtoupper($data_search['ref_number'])."%'))");
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('a.type_id',$data_search['type_id']);
        endif;
        return db_count_results();
    }

    function get_application_details($id)
    {
        db_select('a.*,applicant.*,t.*,c.*,r.*,asset.asset_name');
        db_select('a.rental_fee as rental_fee');
        db_select('a.bill_type as bill_type');
        db_select('applicant.address_1 as applicant_address');
        db_select('a.remark as application_remark');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_added",false);
        db_select("nvl(to_char(a.date_start, 'yyyy-mm-dd hh24:mi:ss'),'') as date_start",false);
        db_select("nvl(to_char(a.date_end, 'yyyy-mm-dd hh24:mi:ss'),'') as date_end",false);
        db_select("to_char(applicant.date_of_birth, 'dd-mm-yyyy') as date_of_birth",false);
        db_select("nvl(to_char(applicant.starting_of_service_date, 'dd-mm-yyyy'),'') as starting_of_service_date",false);
        db_join('p_applicant applicant','applicant.applicant_id = a.applicant_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_asset asset','a.asset_id = asset.asset_id','left');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left');
        db_from('p_application a');
        db_where('application_id',$id);
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    //this function for agreement document

    function get_application_agreement_details($id){
        db_select('a.*,applicant.*,t.*,c.*,r.*,asset.asset_name');
        db_select('a.rental_fee as rental_fee');
        db_select('a.bill_type as bill_type');
        db_select('applicant.address_1 as applicant_address');
        db_select('a.remark as application_remark');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_added",false);
        db_select("nvl(to_char(a.date_start, 'yyyy-mm-dd hh24:mi:ss'),'') as date_start",false);
        db_select("nvl(to_char(a.date_end, 'yyyy-mm-dd hh24:mi:ss'),'') as date_end",false);
        db_select("to_char(applicant.date_of_birth, 'dd-mm-yyyy') as date_of_birth",false);
        db_select("nvl(to_char(applicant.starting_of_service_date, 'dd-mm-yyyy'),'') as starting_of_service_date",false);
        db_join('p_applicant applicant','applicant.applicant_id = a.applicant_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_asset asset','a.asset_id = asset.asset_id','left');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left');
        db_from('p_application a');
        db_where('a.account_id',$id);
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }


    function get_application_by_search($data_search=array()){
        db_select('a.*,applicant.*,t.*,c.*,r.*');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_added",false);
        db_select('a.remark as remark');
        db_join('p_applicant applicant','applicant.applicant_id = a.applicant_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left');
        db_from('p_application a');
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        if(isset($data_search['ref_start']) && having_value($data_search['ref_start'])):
            db_where("a.ref_number >= '".$data_search['ref_start']."'");
        endif;
        if(isset($data_search['ref_end']) && having_value($data_search['ref_end'])):
            db_where("a.ref_number <= '".$data_search['ref_end']."'");
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            if($data_search['type_id'] > 0 )
            {
              db_where('a.type_id',$data_search['type_id']);
            }
        endif;
        if(isset($data_search['approval']) && having_value($data_search['approval'])):
            if(is_numeric($data_search['approval'])):
                db_where('a.status_application',$data_search['approval']);
            else:
                db_where('(a.status_application = '.STATUS_APPLICATION_NEW.' or a.status_application = '.STATUS_APPLICATION_KIV.')');
            endif;
        endif;

        if(isset($data_search['status_agree']) && having_value($data_search['status_agree'])):
            db_where('a.status_agree',$data_search['status_agree']);
        endif;

        $sql = db_get('');
        if($sql):
            return $sql->result_array();
        endif;
    }

    function application_report_dashboard($data_search){
        //db_select('a.*,applicant.*,t.*,c.*,r.*');
        db_select('count(a.application_id) as count_application');
        db_select('count(case when a.status_application = '.STATUS_APPLICATION_NEW.' then a.application_id end) as new_application');
        db_select('count(case when a.status_application = '.STATUS_APPLICATION_APPROVED.' then a.application_id end) as approved_application');
        db_select('count(case when a.status_application = '.STATUS_APPLICATION_REJECTED.' then a.application_id end) as rejected_application');
        db_select('count(case when a.status_application = '.STATUS_APPLICATION_KIV.' then a.application_id end) as kiv_application');
        db_select('count(case when a.status_agree = '.STATUS_AGREE_ACCEPTED.' then a.application_id end) as setuju_terima');
        // db_select('count(case when a.status_agree = '.STATUS_AGREE_ACCEPTED.' and status_create_account = '.STATUS_CREATE_ACCOUNT_NO.' then a.application_id end) as not_registered');
        db_select('count(case when a.status_application = '.STATUS_APPLICATION_APPROVED.' and status_create_account = '.STATUS_CREATE_ACCOUNT_NO.' then a.application_id end) as not_registered');
        db_join('p_applicant applicant','applicant.applicant_id = a.applicant_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left  ');
        db_from('p_application a');
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function application_report_dashboard_by_type($data_search){
        db_select('count(a.application_id) as count_application , t.type_name');
        db_join('p_applicant applicant','applicant.applicant_id = a.applicant_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left  ');
        db_from('p_application a');
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        db_where("extract(year from a.dt_added) = ".date('Y')."");
        db_group('t.type_name');
        //db_group("to_char(a.dt_added, 'YYYY-MM')");
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function application_status_report_dashboard_by_type($data_search){
        db_select("to_char(a.dt_added, 'yyyy-mm') as dt_added",false);
        db_select("to_char(a.dt_added, 'MONTH') as month",false);
        db_select("to_char(a.dt_added, 'mm') as month_number",false);
        db_select('count(case when a.status_application = '.STATUS_APPLICATION_NEW.' then a.application_id end) as new_application');
        db_select('count(case when a.status_application = '.STATUS_APPLICATION_APPROVED.' then a.application_id end) as approved_application');
        db_select('count(case when a.status_application = '.STATUS_APPLICATION_REJECTED.' then a.application_id end) as rejected_application');
        db_select('count(case when a.status_application = '.STATUS_APPLICATION_KIV.' then a.application_id end) as kiv_application');
        db_join('p_applicant applicant','applicant.applicant_id = a.applicant_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left  ');
        db_from('p_application a');
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        db_where("extract(year from a.dt_added) = ".date('Y')."");
        db_group("to_char(a.dt_added, 'yyyy-mm'),to_char(a.dt_added, 'MONTH'),to_char(a.dt_added, 'mm')");
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function application_offered_by_type($data_search){
        db_select('count(case when a.status_application = '.STATUS_APPLICATION_APPROVED.' then a.application_id end) as approved_application');
        db_select('count(case when a.status_agree = '.STATUS_AGREE_ACCEPTED.' then a.application_id end) as setuju_terima');
        db_select('t.type_name');
        db_join('p_applicant applicant','applicant.applicant_id = a.applicant_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left  ');
        db_from('p_application a');
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        db_where("extract(year from a.dt_added) = ".date('Y')."");
        db_group('t.type_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}
