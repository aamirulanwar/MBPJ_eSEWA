<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_department extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_department_layer_1(){
        db_select('*');
        db_from('department');
        db_where('dept_level',DEPARTMENT_LEVEL_1);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('department_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_department_layer_2($dept_id){
        db_select('*');
        db_from('department');
        db_where('parent_id',$dept_id);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('department_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function insert_department($data_insert){
        db_insert('department',$data_insert);
        return get_insert_id('department');
    }

    function get_dept_details($dept_id){
        db_select('*');
        db_from('department');
        db_where('department_id',$dept_id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_department($data_update,$dept_id){
        db_where('department_id',$dept_id);
        db_update('department',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function delete_department($delete_id,$data_update){
        db_where('department_id',$delete_id);
        db_update('department',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function get_department_layer_1_active(){
        db_select('*');
        db_from('department');
        db_where('dept_level',DEPARTMENT_LEVEL_1);
        db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('seq');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_department_layer_2_active($dept_id){
        db_select('*');
        db_from('department');
        db_where('parent_id',$dept_id);
        db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_order('department_name');
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function department_list(){
        $get_department_layer_1 = $this->get_department_layer_1_active();
        $department_arr = array();
        foreach($get_department_layer_1 as $department):
            $department_arr[] = $department;
            $child_department = $this->get_department_layer_2_active($department['department_id']);
            if($child_department):
                foreach($child_department as $child_dept):
                    $department_arr[] = $child_dept;
                endforeach;
            endif;
        endforeach;

        return $department_arr;
    }
}
/* End of file modules/login/controllers/m_department.php */

