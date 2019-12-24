<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */

class Department extends CI_Controller
{
    public $curuser;

    function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_model('department/M_department','m_dept');
    }

    function _remap($method)
    {
        $array = array('add_department','edit_department','delete_department','delete_faq');
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function index(){
        $this->auth->restrict_access($this->curuser,array(2001));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Jabatan / Bahagian';
        $data['link_3']     = 'Senarai Jabatan/Bahagian';
        $data['pagetitle']  = 'Senarai Jabatan/Bahagian';

//        $search_segment = uri_segment(3);
//        $total = $this->m_p_application->count_application();
//        $links          = '/rental_application/application';
//        $uri_segment    = 3;
//        $per_page       = 20;
//        paging_config($links,$total,$per_page,$uri_segment);
//
//        $data_list              = $this->m_p_application->get_application($per_page,$search_segment,$data_search);
//        $data['total_result']   = $total;
//        $data['data_list']      = $data_list;


        $get_department_layer_1 = $this->m_dept->get_department_layer_1();
        $department_arr = array();
        foreach($get_department_layer_1 as $department):
            $department_arr[] = $department;
            $child_department = $this->m_dept->get_department_layer_2($department['DEPARTMENT_ID']);
            if($child_department):
                foreach($child_department as $child_dept):
                    $department_arr[] = $child_dept;
                endforeach;
            endif;
        endforeach;

        $data['department_arr'] = $department_arr;
        $total_dept = count($data['department_arr']);
        $data['total_dept']     = $total_dept;
        templates('/department/v_department',$data);
    }

    function add_department(){
        $this->auth->restrict_access($this->curuser,array(2002));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Jabatan / Bahagian';
        $data['link_3']     = 'Tambah Jabatan/Bahagian';
        $data['pagetitle']  = '';

        $get_department_layer_1 = $this->m_dept->get_department_layer_1();
        $data['get_department_layer_1'] = $get_department_layer_1;

        validation_rules('name','<strong>nama</strong>','required');
//        if(input_data('group_email')==GROUP_EMAIL_YES):
//            validation_rules('email','<strong>email</strong>','required|valid_email');
//        endif;
        if(validation_run()==false):
            templates('/department/v_department_add',$data);
        else:
            $data_insert['department_name']     = strtoupper(input_data('name'));
            $data_insert['dept_level']          = input_data('level');
            
            if(input_data('level')==DEPARTMENT_LEVEL_2):
                $data_insert['parent_id']       = input_data('department_parent');
            else:
                $data_insert['parent_id']       = 0;
            endif;

//            if(input_data('group_email')==GROUP_EMAIL_YES):
//                $data_insert['email']           = input_data('email');
//            else:
//                $data_insert['email']           = '';
//            endif;

            $insert_id = $this->m_dept->insert_department($data_insert);
            if(is_numeric($insert_id)):
                set_notify('add_department',TEXT_SAVE_RECORD);
            else:
                set_notify('add_department',TEXT_SAVE_UNSUCCESSFUL,2);
            endif;
            redirect('/department/add_department/');
        endif;
    }

    function edit_department(){
        $this->auth->restrict_access($this->curuser,array(2003));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Jabatan / Bahagian';
        $data['link_3']     = 'Kemaskini Jabatan/Bahagian';
        $data['pagetitle']  = '';

        $dept_id = urlDecrypt(uri_segment(3));
        if(!is_numeric($dept_id)):
            return false;
        endif;

        $get_dept_details = $this->m_dept->get_dept_details($dept_id);
        if(!$get_dept_details):
            return false;
        endif;

        $get_department_layer_1 = $this->m_dept->get_department_layer_1();
        $data['get_department_layer_1'] = $get_department_layer_1;

        $data['get_dept_details'] = $get_dept_details;
        validation_rules('name','<strong>nama</strong>','required');
        validation_rules('status','<strong>status</strong>');

        if(validation_run()==false):
            templates('/department/v_department_edit',$data);
        else:
            $data_update['department_name']     = strtoupper(input_data('name'));
            $data_update['dept_level']          = input_data('level');
            $data_update['active']              = (input_data('status'))?STATUS_ACTIVE:STATUS_INACTIVE;

            if(input_data('level')==DEPARTMENT_LEVEL_2):
                $data_update['parent_id']   = input_data('department_parent');
            else:
                $data_update['parent_id']   = 0;
            endif;

            $update_status = $this->m_dept->update_department($data_update,$dept_id);
            if($update_status):
                set_notify('edit_department',TEXT_UPDATE_RECORD);
            else:
                set_notify('edit_department',TEXT_UPDATE_UNSUCCESSFUL,2);
            endif;
            redirect('/department/edit_department/'.uri_segment(3));
        endif;
    }

    function delete_department(){
        if(is_ajax()):
            $delete_id      = input_data('delete_id');
            $child_data     = $this->m_dept->get_department_layer_2($delete_id);
            if($child_data):
                set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                echo TEXT_DELETE_UNSUCCESSFUL;
            else:
                $data_update['soft_delete'] = SOFT_DELETE_TRUE;
                $delete = $this->m_dept->delete_department($delete_id,$data_update);
                if($delete):
                    set_notify('user',TEXT_DELETE_RECORD,1);
                    echo TEXT_DELETE_RECORD;
                else:
                    set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                    echo TEXT_DELETE_UNSUCCESSFUL;
                endif;
            endif;
        endif;
    }
}
/* End of file modules/login/controllers/department.php */

