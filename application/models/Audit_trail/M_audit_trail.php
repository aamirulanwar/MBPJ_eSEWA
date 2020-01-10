<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_audit_trail extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_audit_trail_list($per_page,$search_segment,$data_search){
        db_select('a.*,u.*,d.*');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_added",false);
        db_from('audit_trail a');
        db_join('users u','u.user_id = a.user_id','left');
        db_join('department d','d.department_id = u.department_id','LEFT');
        db_where('u.user_id > 0');

        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("a.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;

        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("a.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;

        if(isset($data_search['user_id']) && having_value($data_search['user_id'])):
            db_where("a.user_id",$data_search['user_id']);
        endif;
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_audit_trail($data_search){
        db_select('a.*,u.*,d.*');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_added",false);
        db_from('audit_trail a');
        db_join('users u','u.user_id = a.user_id','left');
        db_join('department d','d.department_id = u.department_id','LEFT');
        db_where('u.user_id > 0');
        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("a.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;

        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("a.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;

        if(isset($data_search['user_id']) && having_value($data_search['user_id'])):
            db_where("a.user_id",$data_search['user_id']);
        endif;
        return db_count_results();
    }

    function count_notice_out_current_month(){
        db_select('*');
        db_from('audit_trail');
        db_where('log_id',3004);
        db_where("extract(year from dt_added) = ".date('Y')."");
        db_where("extract(month from dt_added) = ".date('m')."");
        return db_count_results();
    }
}
/* End of file modules/login/controllers/m_department.php */

