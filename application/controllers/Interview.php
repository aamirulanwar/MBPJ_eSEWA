<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Interview extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_model('Asset/M_a_type', 'm_a_type');
        load_model('Interview/M_p_interview', 'm_p_interview');
        load_model('Interview/M_p_interview_application', 'm_p_interview_application');
        load_model('Application/M_p_application', 'm_p_application');
    }

    function _remap($method)
    {
        $array = array(
            'interview_list',
            'add_interview',
            'delete_interview',
            'interview_details',
            'excel_interview_application',
            'doc_interview_rating',
            'doc_panel_review'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->form();
    }
    
    function interview_list(){
        $this->auth->restrict_access($this->curuser,array(4005));

        $data['link_1']     = 'Temuduga';
        $data['link_2']     = 'Senarai Temuduga';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Temuduga';

        $search_segment = uri_segment(3);
        $total = $this->m_p_interview->count_interview();
        $links          = '/interview/interview';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_p_interview->get_interview($per_page,$search_segment);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('interview/v_list_interview',$data);
    }

    function add_interview(){
        $this->auth->restrict_access($this->curuser,array(4006));

        $data['link_1']     = 'Permohonan Sewaan';
        $data['link_2']     = 'Temuduga Pemohon';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $data['asset_type']         = $this->m_a_type->get_a_type_active();

        $data_search['ref_start']       = input_data('ref_number_start');
        $data_search['ref_end']         = input_data('ref_number_end');
        $data_search['type_id']         = input_data('type_id');
        $data_search['approval']        = STATUS_APPLICATION_NEW;
        $data_search['status_agree']    = STATUS_AGREE_DEFAULT;

        if(empty($data_search['type_id'])):
            $data['data_list']          = array();
            $data['interview_title']    = '';
        else:
            $data_list                  = $this->m_p_application->get_application_by_search($data_search);
            $data['data_list']          = $data_list;
            $type                       = $this->m_a_type->get_a_type_details(input_data('type_id'));
            $data['interview_title']    = 'TEMUDUGA BAGI '.strtoupper($type['TYPE_NAME']);
        endif;

        $type_submit = input_data('submit');

        if($type_submit=='submit'):
            validation_rules('interview_name','<strong>tajuk</strong>','required');
            validation_rules('date_interview','<strong>Tarikh temuduga</strong>','required');
        elseif ($type_submit=='search'):
            validation_rules('type_id','<strong>jenis sewaan</strong>','required');
        endif;

        if(validation_run()==false):
            templates('/interview/v_interview_add',$data);
        else:
            if($type_submit=='submit'):
                $list_application = input_data('application_id');
                if($list_application):
                    $data_insert['interview_name']  = strtoupper(input_data('interview_name'));
                    $data_insert['date_interview']  = input_data('date_interview');
                    $data_insert['type_id']         = input_data('type_id');

                    $interview_id = $this->m_p_interview->insert_interview($data_insert);

                    foreach ($list_application as $app_id):
                        $data_insert_interview_application['interview_id']   = $interview_id;
                        $data_insert_interview_application['application_id'] = $app_id;

                        $application_id = $this->m_p_interview_application->insert_interview_application($data_insert_interview_application);
                    endforeach;

                    if($interview_id>0 && $application_id>0):
                        set_notify('notify_msg',TEXT_SAVE_RECORD);
                    else:
                        set_notify('notify_msg',TEXT_SAVE_UNSUCCESSFUL,2);
                    endif;
                    redirect('/interview/interview_list');
                else:
                    set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL);
                    redirect('/interview/add_interview');
                endif;
            elseif ($type_submit=='search'):

            endif;
            templates('/interview/v_interview_add',$data);

        endif;
    }

    function interview_details(){
        $this->auth->restrict_access($this->curuser,array(4007));

        $data['link_1']     = 'Permohonan Sewaan';
        $data['link_2']     = 'Temuduga Pemohon';
        $data['link_3']     = 'Maklumat Temuduga';
        $data['pagetitle']  = 'Maklumat Temuduga';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $interview_application_list = $this->m_p_interview->get_interview_details($id,'');

        $data['interview_application']  = $interview_application_list;

        templates('/interview/v_interview_details',$data);
    }

    function delete_interview(){
        if(is_ajax()):
            $delete_id = input_data('delete_id');

            $data_update['soft_delete'] = SOFT_DELETE_TRUE;
            $delete = $this->m_p_interview->update_interview($data_update,$delete_id);
            if($delete):
                echo '<span style="color: green">'.TEXT_DELETE_RECORD.'</span>';
            else:
                echo '<span style="color: red">'.TEXT_DELETE_UNSUCCESSFUL.'</span>';
            endif;
        endif;
    }

    function excel_interview_application(){
        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $interview_application_list = $this->m_p_interview->get_interview_details($id);

        $data['interview_application']  = $interview_application_list;

        header("Content-Type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=senarai_kehadiran_temuduga_".date('d-M-Y').".xls");
        $data_print = load_view('/interview/v_head_excel',$data,true);
        $data_print2 = load_view('/interview/v_interview_excel',$data,true);
        echo $data_print.$data_print2;
        //templates('/interview/v_interview_excel',$data);
    }

    function doc_interview_rating(){
        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        load_library('Generate_word');
        $this->generate_word->word_document($id,DOC_INTERVIEW_RATING);
    }

    function doc_panel_review(){
        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        load_library('Generate_word');
        $this->generate_word->word_document($id,DOC_PANEL_REVIEW);
    }
}