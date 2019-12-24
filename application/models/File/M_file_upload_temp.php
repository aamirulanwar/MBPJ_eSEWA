<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_file_upload_temp extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_file_upload($data_insert){
        db_set_date_time('dt_added',timenow());
        db_insert('file_upload_temp',$data_insert);
        return get_insert_id('file_upload_temp');
    }

    function get_file_by_session_id_input_name($session_id,$input_name){
        db_select('*');
        db_from('file_upload_temp');
        db_where('session_id',$session_id);
        db_where('input_name',$input_name);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_file_by_session_id($session_id){
        db_select('*');
        db_from('file_upload_temp');
        db_where('session_id',$session_id);
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_file_by_id($id){
        db_select('*');
        db_from('file_upload_temp');
        db_where('id',$id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function delete_by_id($id){
        db_where('id',$id);
        $sql = db_delete('file_upload_temp');
        return $sql;
    }
}
/* End of file modules/login/controllers/m_department.php */

