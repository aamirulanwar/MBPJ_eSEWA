<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_group extends CI_Controller
{
    public $curuser;

    function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_model('User/M_user_group','m_user_group');
        load_model('Access/M_access_file_parent','m_access_file_parent');
        load_model('Access/M_access_file','m_access_file');
        load_model('Access/M_access_level','m_access_level');
    }

    function _remap($method)
    {
        $array = array('add_user_group','edit_user_group','delete_user_group');
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function index(){
        $this->auth->restrict_access($this->curuser,array(2005));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Kumpulan Pengguna';
        $data['link_3']     = 'Senarai Kumpulan Pengguna';
        $data['pagetitle']  = 'Senarai Kumpulan Pengguna';

        $user_group_list            = $this->m_user_group->get_user_group_list();
        $total_result               = count($user_group_list) ;
        $data['user_group_list']    = $user_group_list;
        $data['total_result']       = $total_result;

        templates('/user_group/v_user_group',$data);
    }

    function add_user_group(){
        $this->auth->restrict_access($this->curuser,array(2006));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Kumpulan Pengguna';
        $data['link_3']     = 'Tambah Kumpulan Pengguna';
        $data['pagetitle']  = '';

        $file_access_parent = $this->m_access_file_parent->get_access_file_parent();
        $data['file_access_parent'] = $file_access_parent;

        $data['list_level']         = $this->m_access_level->get_access_level();

        validation_rules('name','<strong>nama</strong>','required');
        validation_rules('access[]','<strong>access</strong>','required');
//        validation_rules('level','<strong>access level</strong>','required');

        if(validation_run()==false):
            templates('/user_group/v_user_group_add',$data);
        else:
            $access = input_data('access');
            $get_parent_access = array();
            if(isset($access)):
                if(!empty($access)):
                    $get_parent_access = $this->m_access_file->get_parent_access(implode(',',input_data('access')));
                endif;
                $data_user_group['file_access']         = json_encode(input_data('access'));
            endif;

            $data_user_group['user_group_desc']     = ucwords(input_data('name'));
//            $data_user_group['access_level']      = input_data('level');

            #GET PARENT_ID ACCESS FIL
            $parent_file_access = array();
            foreach (input_data('access') as $access_file):
                $access_file_data = $this->m_access_file->get_parent_access($access_file);
                $parent_file_access[] = $access_file_data['PARENT_ID'];
            endforeach;
            $parent_file_access_unique = array_unique($parent_file_access);

            $parent_file_access_unique_new = array();
            if($parent_file_access_unique):
                foreach ($parent_file_access_unique as $row):
                    $parent_file_access_unique_new[] = $row;
                endforeach;
            endif;

            $data_user_group['parent_file_access']  = json_encode($parent_file_access_unique_new);

            $id_user_group = $this->m_user_group->insert_user_group($data_user_group);

            if(is_numeric($id_user_group)):
                set_notify('add_user_group',TEXT_SAVE_RECORD);
            else:
                set_notify('add_user_group',TEXT_SAVE_UNSUCCESSFUL,2);
            endif;
            redirect('/user_group/add_user_group/');
        endif;
    }

    function edit_user_group(){
        $this->auth->restrict_access($this->curuser,array(2007));

        $data['link_1']     = 'Pentadbir';
        $data['link_2']     = 'Kumpulan Pengguna';
        $data['link_3']     = 'Kemaskini Kumpulan Pengguna';
        $data['pagetitle']  = '';

        $ugroup_id = uri_segment(3);
        if(!is_numeric(uri_segment(3))):
            return false;
        endif;

        $user_group_details = $this->m_user_group->get_ugroup_details($ugroup_id);
        if(!$user_group_details):
            return false;
        endif;

        $data['user_group_details'] = $user_group_details;
        $data['list_level']         = $this->m_access_level->get_access_level();

        $file_access_parent         = $this->m_access_file_parent->get_access_file_parent();
        $data['file_access_parent'] = $file_access_parent;

        $file_access_arr            = json_decode($user_group_details['FILE_ACCESS']);

        if(empty($file_access_arr )):
            $file_access_arr  = array();
        endif;

        $data['file_access_arr'] = $file_access_arr;

//        $user_file_access['user_file_access'] = $this->m_user->get_access_group();
//        pre(input_data('access'));
//        exit;

        validation_rules('name','<strong>nama</strong>','required');
        validation_rules('access[]','<strong>access</strong>','required');
//        validation_rules('level','<strong>access level</strong>','required');
//                pre(input_data('access'));
//        exit;

        $this->form_validation->set_rules('status','status');

        if(validation_run()==false):
            templates('/user_group/v_user_group_edit',$data);
        else:
            $get_parent_access = $this->m_access_file->get_parent_access(implode(',',input_data('access')));

            $update_user_group['user_group_desc']   = ucwords(input_data('name'));
            $update_user_group['active']            = (input_data('status'))?STATUS_ACTIVE:STATUS_INACTIVE;
//            $update_user_group['access_level']      = input_data('level');
            $update_user_group['file_access']       = json_encode(input_data('access'));

            #GET PARENT_ID ACCESS FIL
            $parent_file_access = array();
            foreach (input_data('access') as $access_file):
                $access_file_data = $this->m_access_file->get_parent_access($access_file);
                $parent_file_access[] = $access_file_data['PARENT_ID'];
            endforeach;
            $parent_file_access_unique = array_unique($parent_file_access);

            $parent_file_access_unique_new = array();
            if($parent_file_access_unique):
                foreach ($parent_file_access_unique as $row):
                    $parent_file_access_unique_new[] = $row;
                endforeach;
            endif;
            $update_user_group['parent_file_access']= json_encode($parent_file_access_unique_new);

            $id_user_group = $this->m_user_group->update_user_group($update_user_group,$ugroup_id);

            if($id_user_group):
                set_notify('edit_user_group',TEXT_UPDATE_RECORD);
            else:
                set_notify('edit_user_group',TEXT_UPDATE_UNSUCCESSFUL,3);
            endif;
            redirect('/user_group/edit_user_group/'.$ugroup_id);
        endif;
    }

    function delete_user_group(){
        if(is_ajax()):
            $delete_id = input_data('delete_id');

//            $check_available_user = $this->m_user->check_available_user($delete_id);
//            if($check_available_user):
                $delete = $this->m_user_group->delete_user_group($delete_id);
                if($delete):
                    set_notify('user',TEXT_DELETE_RECORD,1);
                    echo TEXT_DELETE_RECORD;
                else:
                    set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                    echo TEXT_DELETE_UNSUCCESSFUL;
                endif;
//            else:
//            endif;
//            echo 1;
        endif;
    }
}
/* End of file modules/login/controllers/department.php */

