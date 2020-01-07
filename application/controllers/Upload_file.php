<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 09/12/2018
 * Time: 12:16 PM
 */
class Upload_file extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        load_model('File/M_file_upload_temp', 'm_file_upload_temp');
    }

    function _remap($method)
    {
        $array = array(
            'insert_temp',
            'remove_temp'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : '';
    }

    function insert_temp(){

        if (!file_exists(FILE_UPLOAD_TEMP)):
            mkdir(FILE_UPLOAD_TEMP, 0777, true);
        endif;
//
        $error_upload = array();
        $if_have_error = 0;

        $upload_pic_name    = input_data('upload_name');
        $name               = $upload_pic_name;
        $session_id         = input_data('form_session');
        $module_type        = input_data('file_module_type');

        $data['full_path']      = '';
        $data['error_remark']   = '';
        $data['id_upload']      = '';

        $config['upload_path'] 	    = "./".FILE_UPLOAD_TEMP. "/" ;
        $config['allowed_types']    = 'gif|jpg|png|jpeg|JPG|JPEG|PNG|GIF|txt|TXT';
        $config['max_size']         = '15120';
        $config['max_width']        = '4000';
        $config['max_height']       = '4000';
        $config['encrypt_name']	    = false;
        $config['overwrite']        = false;
        $config['file_name']        = $upload_pic_name.'_'.date('YmdHis');
        $filename = $_FILES[$name]['name'];

        $ext = pathinfo($filename,PATHINFO_EXTENSION);

        $this->load->library('upload', $config);

        $result_upload = $this->upload->do_upload($name);

        if (!$result_upload):
            $error = $this->upload->display_errors();
            $data['error_remark']   =  $error;
//            $error_upload[] = $data_err;
            $if_have_error = 1;
        else:
            $info_upload = $this->upload->data();

            $data_insert['file_name']       = $info_upload['file_name'];
            $data_insert['file_type']       = $ext;
            $data_insert['session_id']      = $session_id;
            $data_insert['input_name']      = $upload_pic_name;
            $data_insert['module_type']     = $module_type;
            $data_insert['image_type']      = image_type($upload_pic_name);

            $id_upload = $this->m_file_upload_temp->insert_file_upload($data_insert);

            $data['full_path']      = $info_upload['full_path'];
            $data['display_path']   =  "/".FILE_UPLOAD_TEMP.$info_upload['file_name'] ;
            $data['id_upload']      =  $id_upload ;

//            // check EXIF and autorotate if needed
//            $full_path =  $config['upload_path'].$info_upload['file_name'];
//            $this->load->library('image_autorotate');
//            $this->image_autorotate->autorotate($full_path);
//            $this->image_lib->clear();
//
//            $config_resize['image_library']     = 'gd2';
//            $config_resize['source_image']	    = "./".PROJECT_CONTENT_BASED_UPLOAD_PATH. "/".$info_upload['file_name'] ;
//            $config_resize['create_thumb']      = FALSE;
//            $config_resize['maintain_ratio']    = TRUE;
//            $config_resize['quality']           = '80';
//            $config_resize['width']	            = 800;
//            $config_resize['height']	        = 800;
//
//            $this->image_lib->initialize($config_resize);

//            if (!$this->image_lib->resize()):
//                echo $this->image_lib->display_errors();
//            else:
//                $this->image_lib->clear();
//
//                $data_insert['project_id']  = $project_id;
//                $data_insert['image_name']  = $info_upload['file_name'];
//                $data_insert['remark']      = input_data('remark_image_'.$i);
//                $data_insert['date_effected'] = display_date(input_data('date_image_'.$i),'Y-m-d');
//                $data_insert['dt_added']    = timenow();
//                $data_insert['user_id']     = $this->curuser['user_id'];
//
//                $this->m_progress->insert_image_project($data_insert);
//            endif;
        endif;

        $data['status_upload']  = $if_have_error;
        echo json_encode($data);
//        echo $if_have_error;
    }

    public function remove_temp(){
        $upload_name    = input_data('upload_name');
        $id             = input_data('id');

        $data_delete    = $this->m_file_upload_temp->get_file_by_id($id);

        unlink(FILE_UPLOAD_TEMP.$data_delete['FILE_NAME']);
        $delete         = $this->m_file_upload_temp->delete_by_id($id);
        echo $delete;
//        unlink('file_upload/temp/passport_pic_20181209160455.PNG');
    }
}