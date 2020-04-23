<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_file_gallery extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert($data_insert){
        db_set_date_time('dt_added',timenow());
        db_insert('file_gallery',$data_insert);
        return get_insert_id('file_gallery');
    }

    function get_file($ref_id,$file_type){
        db_select('*');
        db_from('file_gallery');
        db_where('ref_id',$ref_id);
        db_where('file_type',$file_type);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_file_by_asset_id($asset_id){
        db_select('*');
        db_from('file_gallery');
        db_where('module_type',FILE_MODULE_TYPE_ASSET);
        db_where('ref_id',$asset_id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_all_file_list($ref_id,$file_type){
        db_select('*');
        db_from('file_gallery');
        db_where('ref_id',$ref_id);
        db_where('file_type',$file_type);
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
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

