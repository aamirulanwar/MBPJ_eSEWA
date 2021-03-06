<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_a_category extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_a_category($per_page,$search_segment,$data_search=array()){
        db_select('a.*,t.*,l.*');
        db_select('a.active as active');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_location l','l.location_id = a.location_id','left');
        db_from('a_category a');
        if(isset($data_search['search']) && having_value($data_search['search'])):
            db_where("upper(a.category_name) like '%".trim(strtoupper($data_search['search']))."%' or upper(a.category_code) like '%".trim(strtoupper($data_search['search']))."%'");
        endif;
        if(isset($data_search['type_name']) && having_value($data_search['type_name'])):
            db_where("upper(t.type_name) like '%".trim(strtoupper($data_search['type_name']))."%'");
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where("a.type_id",$data_search['type_id']);
        endif;
        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
            db_where("a.category_id",$data_search['category_id']);
        endif;
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        db_order('category_code');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_a_category($data_search=array()){
        db_select('*');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_location l','l.location_id = a.location_id','left');
        db_from('a_category a');
        if(isset($data_search['search']) && having_value($data_search['search'])):
            db_where("upper(a.category_name) like '%".trim(strtoupper($data_search['search']))."%'");
        endif;
        if(isset($data_search['type_name']) && having_value($data_search['type_name'])):
            db_where("upper(t.type_name) like '%".trim(strtoupper($data_search['type_name']))."%'");
        endif;
        if(isset($data_search['type_id']) && having_value($data_search['type_id'])):
            db_where('t.type_id',$data_search['type_id']);
        endif;
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        return db_count_results();
    }

    function insert_a_category($data_insert){
        db_insert('a_category',$data_insert);
        $user_id = get_insert_id('a_category');
          $data_audit_trail['log_id']                  = 6004;
          $data_audit_trail['remark']                  = "Tambah Kod Kategori";
          $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
          $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
          $data_audit_trail['refer_id']                = $user_id; //refer to db_where
          $this->audit_trail_lib->add($data_audit_trail);
        return $user_id;
    }

    function get_a_category_details($id){
        db_select('*');
        db_from('a_category');
        db_where('category_id',$id);
        $sql = db_get('');
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_a_category($data_update,$id)
    {
        db_where('category_id',$id);
        db_update('a_category',$data_update);

        if ( isset( $data_update["soft_delete"] ) )
        {
            if ($data_update["soft_delete"] == 1)
            {
                if( db_affected_rows() != 0 )
                {
                    $data_audit_trail['log_id']                  = 6006;
                    $data_audit_trail['remark']                  = "Padam Kot Kategori";
                    $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                    $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                    $data_audit_trail['refer_id']                = $id; //refer to db_where
                    $this->audit_trail_lib->add($data_audit_trail);
                    return true;
                }
                else if( db_affected_rows() == 0 )
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            if( db_affected_rows() != 0 )
            {
                $data_audit_trail['log_id']                  = 6005;
                $data_audit_trail['remark']                  = "Kemaskini Kot Kategori";
                $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                $data_audit_trail['refer_id']                = $id; //refer to db_where
                $this->audit_trail_lib->add($data_audit_trail);
                return true;
            }
            else if( db_affected_rows() == 0 )
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    function get_a_category_active(){
        db_select('*');
        db_from('a_category a');
        db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('category_code');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_a_category_all(){
        db_select('*');
        db_from('a_category a');
        // db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('type_id');
        db_order('category_code');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_a_category_all_active(){
        db_select('*');
        db_from('a_category a');
        db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        // db_order('type_id');
        db_order('category_code');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_data_category_by_type($type_id){
        db_select('*');
        db_from('a_category a');
        // db_join('tr_code tr','tr.mct_trcode_new=a.trcode_category');
        db_where('a.active',STATUS_ACTIVE);
        db_where('a.soft_delete',SOFT_DELETE_FALSE);
        db_where('a.type_id',$type_id);
        db_order('a.category_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

}
