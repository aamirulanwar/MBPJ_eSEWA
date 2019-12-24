<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_tr_code extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_tr_code($data_search = array()){
        db_select('*');
        db_from('tr_code');
        if(isset($data_search['tr_id']) && having_value($data_search['tr_id'])):
            db_where('tr_id',$data_search['tr_id']);
        endif;
        if(isset($data_search['TR_TYPE']) && having_value($data_search['TR_TYPE'])):
            db_where('TR_TYPE',$data_search['TR_TYPE']);
        endif;
        if(isset($data_search['AUTO_GENERATE']) && having_value($data_search['TR_TYPE'])):
            db_where('AUTO_GENERATE',$data_search['AUTO_GENERATE']);
        endif;
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_tr_code_list($data_search = array()){
        db_select('*');
        db_from('tr_code');
        if(isset($data_search['tr_id']) && having_value($data_search['tr_id'])):
            db_where('tr_id',$data_search['tr_id']);
        endif;
        if(isset($data_search['TR_TYPE']) && having_value($data_search['TR_TYPE'])):
            db_where('TR_TYPE',$data_search['TR_TYPE']);
        endif;
        if(isset($data_search['AUTO_GENERATE']) && having_value($data_search['TR_TYPE'])):
            db_where('AUTO_GENERATE',$data_search['AUTO_GENERATE']);
        endif;
        db_order('MCT_TRCODE_NEW');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_gl_code(){
        db_from('tr_code');
        db_where('mct_tstats','B');
        db_where('tr_type',1);
        db_where('acc_mct_glcrdt is not null');
        db_order('priority','asc');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

//    function get_file_by_session_id_input_name($session_id,$input_name){
//        db_select('*');
//        db_from('file_upload_temp');
//        db_where('session_id',$session_id);
//        db_where('input_name',$input_name);
//        $sql = db_get();
//        if($sql):
//            return $sql->row_array();
//        endif;
//    }
//
//    function get_file_by_session_id($session_id){
//        db_select('*');
//        db_from('file_upload_temp');
//        db_where('session_id',$session_id);
//        $sql = db_get();
//        if($sql):
//            return $sql->result_array();
//        endif;
//    }
//
//    function get_file_by_id($id){
//        db_select('*');
//        db_from('file_upload_temp');
//        db_where('id',$id);
//        $sql = db_get();
//        if($sql):
//            return $sql->row_array();
//        endif;
//    }
//
//    function delete_by_id($id){
//        db_where('id',$id);
//        $sql = db_delete('file_upload_temp');
//        return $sql;
//    }
}
/* End of file modules/login/controllers/m_department.php */

