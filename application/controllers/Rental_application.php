<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rental_application extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_library('Ref_number_lib');
        load_library('Audit_trail_lib');

        load_model('Asset/M_a_type', 'm_a_type');
        load_model('Asset/M_a_rental_use', 'm_a_rental_use');
        load_model('Asset/M_a_category', 'm_a_category');
        load_model('Interview/M_p_interview', 'm_p_interview');
        load_model('Interview/M_p_interview_application', 'm_p_interview_application');
        load_model('Application/M_p_applicant', 'm_p_applicant');
        load_model('Application/M_p_application', 'm_p_application');
        load_model('Account/M_acc_dependent', 'm_acc_dependent');
        load_model('File/M_file_upload_temp', 'm_file_upload_temp');
        load_model('File/M_file_gallery', 'm_file_gallery');
        load_model('Department/M_department','m_dept');
        load_model('Asset/M_a_asset','m_a_asset');

    }

    function _remap($method)
    {
        $array = array(
            'form',
            'get_data_category_by_type',
            'application_type',
            'application',
            'application_process',
            'create_acc',
            'interview',
            'application_interview',
            'interview_details',
            'delete_interview',
            'application_approval',
            'application_agree',
            'doc_offer_letter',
            'doc_acceptance_letter',
            'excel_interview_application',
            'get_asset_by_id'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->form();
    }

    function application_type()
    {
        $this->auth->restrict_access($this->curuser,array(4001));

        $data['link_1']     = 'Permohonan Sewaan';
        $data['link_2']     = 'Jenis Permohonan';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $data['asset_type']     = $this->m_a_type->get_a_type_application();

        templates('/rental_application/v_application_type',$data);
    }

    function form()
    {
        $data['link_1']     = 'Permohonan Sewaan';
        $data['link_2']     = 'Borang Permohonan';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $type_id = urlDecrypt(uri_segment(3));
        if(empty($type_id)):
            return false;
        endif;

        $data_asset_type = $this->m_a_type->get_a_type_details($type_id);
        if(empty($data_asset_type)):
            return false;
        endif;

        //$data_result['application_id']  = 0;
        //$data_result['applicant_id']    = 0;
        //$data_result['ref_number']      = '';

        if($data_asset_type['FORM_TYPE']==1):
            $data_result = $this->form_1($data_asset_type,$data);
        elseif ($data_asset_type['FORM_TYPE']==2):
            $data_result = $this->form_2($data_asset_type,$data);
        elseif ($data_asset_type['FORM_TYPE']==3):
            $data_result = $this->form_3($data_asset_type,$data);
        elseif ($data_asset_type['FORM_TYPE']==4):
            $data_result = $this->form_4($data_asset_type,$data);
        endif;

        if(isset($data_result['application_id']) && isset($data_result['applicant_id'])):
            if($data_result['application_id']>0 && $data_result['applicant_id']):

                $data_audit_trail['log_id']                  = 2001;
                $data_audit_trail['remark']                  = $this->input->post();
                $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                $data_audit_trail['refer_id']                = $data_result['application_id'];
                $this->audit_trail_lib->add($data_audit_trail);

                set_notify('notify_msg',TEXT_SAVE_RECORD.'. No. Rujukan : <strong>'.$data_result['ref_number'].'</strong>');
                redirect('/rental_application/form/'.uri_segment(3));
            else:
                set_notify('notify_msg',TEXT_SAVE_UNSUCCESSFUL,2);
                redirect('/rental_application/form/'.uri_segment(3));
            endif;
        endif;
    }

    private function form_1($data_asset_type,$data)
    {
        $form_session   = input_data('form_session');
        $random_number  = rand(1,1000);
        if(empty($form_session)):
            $form_session       = 'form_1'.'_'.$this->curuser['USER_ID'].'_'.date('YmdHis').'_'.$random_number;
        endif;

        $data['rental_use']     = $this->m_a_rental_use->get_a_rental_use_active();
        $data['category']       = $this->m_a_category->get_data_category_by_type($data_asset_type['TYPE_ID']);
        $data['asset_type']     = $data_asset_type;
        $data['form_session']   = $form_session;
        $data['ic_number_pic']  = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'ic_number_pic');
        $data['passport_pic']   = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'passport_pic');
        $data['ssm_pic']        = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'ssm_pic');

        validation_rules('date_application','<strong>tarikh permohonan</strong>','required');
        validation_rules('form_number','<strong>nombor borang</strong>','is_unique[P_APPLICATION.FORM_NUMBER]');
        validation_rules('name','<strong>nama permohonan</strong>','required');
        validation_rules('type_of_ic','<strong>jenis kad pengenalan</strong>','required');
        if(input_data('type_of_ic')==1):
            validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required|exact_length[12]');
        else:
            validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required|min_length[7]|max_length[8]');
        endif;
        //validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required|exact_length[12]');
        validation_rules('date_of_birth','<strong>tarikh lahir</strong>','required');
        validation_rules('address_1','<strong>alamat</strong>','required');
        validation_rules('address_3','<strong>bandar</strong>','required');
        validation_rules('postcode','<strong>poskod</strong>','required');
        validation_rules('address_state','<strong>negeri</strong>','required');
        validation_rules('mail_address_1','<strong>alamat surat menyurat</strong>','required');
        validation_rules('mail_address_3','<strong>bandar</strong>','required');
        validation_rules('mail_postcode','<strong>poskod</strong>','required');
        validation_rules('mail_state','<strong>negeri</strong>','required');
        validation_rules('race','<strong>bangsa</strong>','required');
        validation_rules('marital_status','<strong>status perkahwinan</strong>','required');
        validation_rules('home_phone_number','<strong>no. telefon rumah</strong>');
        validation_rules('mobile_phone_number','<strong>no. telefon bimbit</strong>','required');
        validation_rules('occupation_status','<strong>status pekerjaan</strong>','required');
        validation_rules('location_distance','<strong>jarak tempat tinggal</strong>','required');
        validation_rules('residence_duration','<strong>tempoh menetap</strong>','required');
        validation_rules('business_experience','<strong>pengalaman berniaga</strong>','required');
        validation_rules('category_id','<strong>lokasi yang dipohon</strong>','required');

        if($data_asset_type['TYPE_ID']==1):
            validation_rules('rental_use_id','<strong>jenis perniagaan</strong>','required');
        endif;

        validation_rules('ic_number_pic_status','<strong>salinan kad pengenalan</strong>','required');
        validation_rules('passport_pic_status','<strong>gambar passport</strong>','required');
        validation_rules('ssm_pic_status','<strong>salinan pendaftaran perniagaan (SSM)</strong>');

        if(input_data('rental_use_id')==RENTAL_USE_OTHERS):
            validation_rules('rental_use_remark','<strong>catatan jenis perniagaan</strong>','required');
        endif;

        if(input_data('occupation_status')==OCCUPATION_STATUS_WORKING):
            validation_rules('occupation','<strong>pekerjaan</strong>','required');
            validation_rules('total_earnings','<strong>jumlah pendapatan</strong>','required');
        endif;

        if(validation_run()==false):
            templates('/rental_application/form_1/v_form_1',$data);
        else:
            $application_id = 0;

            #insert applicant
            $data_insert['name']                = input_data('name');
            $data_insert['ic_number']           = trim(str_replace(array(' ', '-', '/'), '', input_data('ic_number')));
            $data_insert['date_of_birth']       = input_data('date_of_birth');
            $data_insert['address_1']           = input_data('address_1');
            $data_insert['address_2']           = input_data('address_2');
            $data_insert['address_3']           = input_data('address_3');
            $data_insert['postcode']            = input_data('postcode');
            $data_insert['address_state']       = input_data('address_state');
            $data_insert['mail_address_1']      = input_data('mail_address_1');
            $data_insert['mail_address_2']      = input_data('mail_address_2');
            $data_insert['mail_address_3']      = input_data('mail_address_3');
            $data_insert['mail_postcode']       = input_data('mail_postcode');
            $data_insert['mail_state']          = input_data('mail_state');
            $data_insert['location_distance']   = input_data('location_distance');
            $data_insert['residence_duration']  = input_data('residence_duration');
            $data_insert['race']                = input_data('race');
            $data_insert['marital_status']      = input_data('marital_status');
            $data_insert['home_phone_number']   = input_data('home_phone_number');
            $data_insert['mobile_phone_number'] = trim(str_replace(array(' ', '-', '/'), '', input_data('mobile_phone_number')));
            $data_insert['occupation_status']   = input_data('occupation_status');
            $data_insert['business_experience'] = input_data('business_experience');
            $data_insert['business_type']       = input_data('business_type');
            $data_insert['applicant_type']      = APPLICANT_TYPE_INDIVIDUAL;

            if(input_data('occupation_status')==OCCUPATION_STATUS_WORKING):
                $data_insert['occupation']      = ucfirst(input_data('occupation'));
                $data_insert['total_earnings']  = currencyToDouble(input_data('total_earnings'));
            endif;

            $applicant_id = $this->m_p_applicant->insert_applicant($data_insert);

            if($applicant_id>0):
                #insert dependent
                $dependent      = input_data('dependent_name[]');
                $relationship   = input_data('dependent_relationship[]');
                if(isset($dependent) && $dependent):
                    $no_dependent = 0;
                    foreach ($dependent as $row):
                        if(!empty($row)):
                            $data_dependent['name']         = $row;
                            $data_dependent['relationship'] = (isset($relationship[$no_dependent]))?$relationship[$no_dependent]:'';
                            $data_dependent['applicant_id'] = $applicant_id;

                            $this->m_acc_dependent->insert_dependent($data_dependent);
                        endif;
                        $no_dependent = $no_dependent+1;
                    endforeach;
                endif;

                #update_applicant
                $data_update_applicant['total_child'] = count_child($applicant_id,1);
                $this->m_p_applicant->update_applicant($data_update_applicant,$applicant_id);

                #insert application
                $data_insert_application['applicant_id']    = $applicant_id;
                $data_insert_application['date_application']= input_data('date_application');
                $data_insert_application['ref_number']      = strtoupper($this->ref_number_lib->get_ref(REF_NUMBER_TYPE_APPLICATION,$data_asset_type['TYPE_CODE']));
                $data_insert_application['form_number']     = strtoupper(input_data('form_number'));
                $data_insert_application['type_id']         = $data_asset_type['TYPE_ID'];
                $data_insert_application['category_id']     = input_data('category_id');
                if($data_asset_type['TYPE_ID']==1):
                    $data_insert_application['rental_use_id']   = input_data('rental_use_id');
                endif;
                $data_insert_application['bill_type']       = $data_asset_type['BILL_TYPE'];
                $data_insert_application['difference_rental_charge_type']= 1 ;


                if(input_data('rental_use_id')==RENTAL_USE_OTHERS):
                    $data_insert_application['rental_use_remark']   = input_data('rental_use_remark');
                endif;
                $application_id = $this->m_p_application->insert_application($data_insert_application);

                #insert file
                $form_session = $this->m_file_upload_temp->get_file_by_session_id($form_session);
                if($form_session):
                    foreach ($form_session as $row):
                        if (!file_exists(FILE_APPLICATION.$application_id.'/')):
                            mkdir(FILE_APPLICATION.$application_id.'/', 0777, true);
                        endif;

                        $data_file['filename']    = $row['FILE_NAME'];
                        $data_file['extension']   = $row['FILE_TYPE'];
                        $data_file['path']        = FILE_APPLICATION.$application_id.'/';
                        $data_file['ref_id']      = $application_id;
                        $data_file['file_type']   = $row['IMAGE_TYPE'];
                        $data_file['module_type'] = $row['MODULE_TYPE'];

                        copy(FILE_UPLOAD_TEMP.$row['FILE_NAME'], FILE_APPLICATION.$application_id.'/'.$row['FILE_NAME']);
                        $file_id = $this->m_file_gallery->insert($data_file);

                        unlink(FILE_UPLOAD_TEMP.$row['FILE_NAME']);
                        $delete         = $this->m_file_upload_temp->delete_by_id($row['ID']);
                    endforeach;
                endif;
            endif;
            //$ref_number         = 'REF'.$application_id;
            //
            //$data_update['ref_number'] = $ref_number;
            //$update_application = $this->m_p_application->update_application($data_update,$application_id);

            $data_return['application_id']  = $application_id;
            $data_return['applicant_id']    = $applicant_id;
            $data_return['ref_number']      = $data_insert_application['ref_number'];

            return $data_return;
        endif;
    }

    private function form_2($data_asset_type,$data)
    {

        $form_session   = input_data('form_session');
        $random_number  = rand(1,1000);
        if(empty($form_session)):
            $form_session       = 'form_2'.'_'.$this->curuser['USER_ID'].'_'.date('YmdHis').'_'.$random_number;
        endif;

        $data['rental_use']     = $this->m_a_rental_use->get_a_rental_use_active();
        $data['category']       = $this->m_a_category->get_data_category_by_type($data_asset_type['TYPE_ID']);
        $data['asset_type']     = $data_asset_type;
        $data['form_session']   = $form_session;

        $data['map_info']           = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'map_info');
        $data['location_plan_file'] = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'location_plan_file');
        $data['structure_plan']     = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'structure_plan');
        $data['app_ssm_file']       = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'app_ssm_file');
        $data['cost_validation']    = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'cost_validation');

        validation_rules('date_application','<strong>tarikh permohonan</strong>','required');
        validation_rules('form_number','<strong>nombor borang</strong>','is_unique[P_APPLICATION.FORM_NUMBER]');
        //validation_rules('ref_number','<strong>nombor Rujukan Permohonan</strong>','required|is_unique[P_APPLICATION.REF_NUMBER]');
        validation_rules('name','<strong>nama syarikat</strong>','required');
        validation_rules('company_registration_number','<strong>no. pendaftaran syarikat</strong>','required');
        validation_rules('address_1','<strong>alamat syarikat</strong>','required');
        validation_rules('address_3','<strong>bandar</strong>','required');
        validation_rules('postcode','<strong>poskod</strong>','required');
        validation_rules('address_state','<strong>alamat negeri</strong>','required');
        validation_rules('home_phone_number','<strong>no. telefon syarikat</strong>','required');
        validation_rules('fax_number','<strong>no. faks</strong>');
        validation_rules('director_name','<strong>nama pengarah/pegawai</strong>','required');
        validation_rules('mobile_phone_number','<strong>no. telefon pengarah/pegawai</strong>','required');
        validation_rules('category_id','<strong>lokasi yang dipohon</strong>','required');
        validation_rules('cost_billboard','<strong>kos binaan papan iklan</strong>','');
        validation_rules('cost_validation_status','<strong>lampiran pengesahan kos binaan oleh perunding</strong>','');
        validation_rules('total_income_a_year','<strong>jumlah pendapatan setahun</strong>','');
        validation_rules('structure_type_billboard','<strong>jenis struktur papan iklan</strong>','required');
        validation_rules('location_billboard','<strong>lokasi papan iklan</strong>','required');
        validation_rules('billboard','<strong>papan iklan</strong>','required');
        validation_rules('height_billboard','<strong>tinggi papan iklan</strong>','required');
        validation_rules('width_billboard','<strong>lebar papan iklan</strong>','required');
        validation_rules('area_billboard','<strong>keluasan tapak papan iklan</strong>','required');
        validation_rules('app_ssm_file_status','<strong>carian SSM</strong>','required');
        validation_rules('structure_plan_status','<strong>pelan struktur</strong>','required');
        validation_rules('location_plan_file_status','<strong>pelan lokasi<strukt></strukt>ur</strong>','required');
        //validation_rules('map_info_status','<strong>map info</strong>','required');

        if(input_data('rental_use_id')==RENTAL_USE_OTHERS):
            validation_rules('rental_use_remark','<strong>catatan jenis perniagaan</strong>','required');
        endif;

        if(validation_run()==false):
            templates('/rental_application/form_2/v_form_2',$data);
        else:

            $application_id = 0;
            #insert applicant
            $data_insert['name']                        = input_data('name');
            $data_insert['COMPANY_REGISTRATION_NUMBER'] = input_data('company_registration_number');
            $data_insert['address_1']                   = input_data('address_1');
            $data_insert['address_2']                   = input_data('address_2');
            $data_insert['address_3']                   = input_data('address_3');
            $data_insert['postcode']                    = input_data('postcode');
            $data_insert['home_phone_number']           = input_data('home_phone_number');
            $data_insert['fax_number']                  = input_data('fax_number');
            $data_insert['director_name']               = input_data('director_name');
            $data_insert['mobile_phone_number']         = input_data('mobile_phone_number');
            $data_insert['address_state']               = input_data('address_state');
            $data_insert['applicant_type']              = APPLICANT_TYPE_COMPANY;

            $applicant_id = $this->m_p_applicant->insert_applicant($data_insert);
            if($applicant_id>0):
                #insert application
                $data_insert_application['applicant_id']    = $applicant_id;
                $data_insert_application['date_application']= input_data('date_application');
                $data_insert_application['ref_number']      = strtoupper($this->ref_number_lib->get_ref(REF_NUMBER_TYPE_APPLICATION,$data_asset_type['TYPE_CODE']));
                $data_insert_application['form_number']     = strtoupper(input_data('form_number'));
                $data_insert_application['type_id']         = $data_asset_type['TYPE_ID'];
                $data_insert_application['category_id']     = input_data('category_id');
                $data_insert_application['cost_billboard']  = currencyToDouble(input_data('cost_billboard'));
                $data_insert_application['total_income_a_year']  = currencyToDouble(input_data('total_income_a_year'));
                $data_insert_application['structure_type_billboard']    = (input_data('structure_type_billboard'));
                $data_insert_application['location_billboard']          = (input_data('location_billboard'));
                $data_insert_application['billboard']       = (input_data('billboard'));
                $data_insert_application['height_billboard']= currencyToDouble(input_data('height_billboard'));
                $data_insert_application['width_billboard'] = currencyToDouble(input_data('width_billboard'));
                $data_insert_application['area_billboard']  = currencyToDouble(input_data('area_billboard'));
                $data_insert_application['bill_type']       = $data_asset_type['BILL_TYPE'];
                $data_insert_application['difference_rental_charge_type']= 1 ;

                $application_id = $this->m_p_application->insert_application($data_insert_application);

                #insert file
                $form_session = $this->m_file_upload_temp->get_file_by_session_id($form_session);
                if($form_session):
                    foreach ($form_session as $row):
                        if (!file_exists(FILE_APPLICATION.$application_id.'/')):
                            mkdir(FILE_APPLICATION.$application_id.'/', 0777, true);
                        endif;

                        $data_file['filename']    = $row['FILE_NAME'];
                        $data_file['extension']   = $row['FILE_TYPE'];
                        $data_file['path']        = FILE_APPLICATION.$application_id.'/';
                        $data_file['ref_id']      = $application_id;
                        $data_file['file_type']   = $row['IMAGE_TYPE'];
                        $data_file['module_type'] = $row['MODULE_TYPE'];

                        copy(FILE_UPLOAD_TEMP.$row['FILE_NAME'], FILE_APPLICATION.$application_id.'/'.$row['FILE_NAME']);
                        $file_id = $this->m_file_gallery->insert($data_file);

                        unlink(FILE_UPLOAD_TEMP.$row['FILE_NAME']);
                        $delete         = $this->m_file_upload_temp->delete_by_id($row['ID']);
                    endforeach;
                endif;
            endif;

            $data_return['application_id']  = $application_id;
            $data_return['applicant_id']    = $applicant_id;
            $data_return['ref_number']      = $data_insert_application['ref_number'];

            return $data_return;
        endif;
    }

    private function form_3($data_asset_type,$data)
    {

        $form_session   = input_data('form_session');
        $random_number  = rand(1,1000);
        if(empty($form_session)):
            $form_session       = 'form_3'.'_'.$this->curuser['USER_ID'].'_'.date('YmdHis').'_'.$random_number;
        endif;

        $data['rental_use']     = $this->m_a_rental_use->get_a_rental_use_active();
        $data['category']       = $this->m_a_category->get_data_category_by_type($data_asset_type['TYPE_ID']);
        $data['asset_type']     = $data_asset_type;
        $data['form_session']   = $form_session;

        $data['letter_application'] = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'letter_application_file');
        $data['ic_number_pic']      = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'ic_number_pic');
        $data['location_plan']      = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'location_plan_file');
        $data['photo_location']     = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'photo_location_file');
        $data['structure_plan']     = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'suggestion_structure_plan_file');
        $data['ssm']                = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'app_ssm_file');

        validation_rules('date_application','<strong>tarikh permohonan</strong>','required');
        validation_rules('form_number','<strong>nombor borang</strong>','is_unique[P_APPLICATION.FORM_NUMBER]');
        //validation_rules('ref_number','<strong>nombor Rujukan Permohonan</strong>','required|is_unique[P_APPLICATION.REF_NUMBER]');
        validation_rules('name','<strong>nama syarikat</strong>','required');
        validation_rules('company_registration_number','<strong>no. pendaftaran syarikat</strong>','required');
        validation_rules('address_1','<strong>alamat syarikat</strong>','required');
        validation_rules('address_3','<strong>bandar</strong>','required');
        validation_rules('postcode','<strong>poskod</strong>','required');
        validation_rules('address_state','<strong>alamat negeri</strong>','required');
        validation_rules('home_phone_number','<strong>no. telefon syarikat</strong>','required');
        validation_rules('fax_number','<strong>no. <fa></fa>ks</strong>');
        validation_rules('director_name','<strong>nama pengarah/pegawai</strong>','required');
        validation_rules('mobile_phone_number','<strong>no. telefon pengarah/pegawai</strong>','required');
        validation_rules('category_id','<strong>lokasi yang dipohon</strong>','required');
        validation_rules('area_site','<strong>keluasan</strong>','required');
        validation_rules('area_site_unit','<strong>keluasan</strong>','required');
        validation_rules('duration_use','<strong>cadangan tempoh caj penggunaan / penyelenggaraan</strong>','required');
        validation_rules('duration_use_unit','<strong></strong>','required');
        validation_rules('charge_use_in_a_month','<strong>cadangan caj penggunaan / penyelanggaraan sebulan</strong>','required');
        validation_rules('operation_use','<strong>cadangan operasi / kegunaan tapak / bangunan</strong>','required');
        validation_rules('structure_type_building','<strong>cadangan jenis struktur bangunan</strong>','required');
        validation_rules('letter_application_file_status','<strong>surat permohonan</strong>','required');
        validation_rules('ic_number_pic_status','<strong>salinan kad pengenalan</strong>','required');
        validation_rules('location_plan_file_status','<strong>pelan lokasi (Google map)</strong>','required');
        validation_rules('photo_location_file_status','<strong>foto lokasi</strong>','required');
        validation_rules('suggestion_structure_plan_file_status','<strong>salinan cadangan pelan struktur</strong>','required');
        validation_rules('app_ssm_file_status','<strong>carian SSM</strong>','required');

        if(input_data('rental_use_id')==RENTAL_USE_OTHERS):
            validation_rules('rental_use_remark','<strong>catatan jenis perniagaan</strong>','required');
        endif;

        if(validation_run()==false):
            templates('/rental_application/form_3/v_form_3',$data);
        else:
            $application_id = 0;

            #insert applicant
            $data_insert['name']                        = input_data('name');
            $data_insert['COMPANY_REGISTRATION_NUMBER'] = strtoupper(input_data('company_registration_number'));
            $data_insert['address_1']                   = input_data('address_1');
            $data_insert['address_2']                   = input_data('address_2');
            $data_insert['address_3']                   = input_data('address_3');
            $data_insert['postcode']                    = input_data('postcode');
            $data_insert['home_phone_number']           = input_data('home_phone_number');
            $data_insert['fax_number']                  = input_data('fax_number');
            $data_insert['director_name']               = input_data('director_name');
            $data_insert['mobile_phone_number']         = input_data('mobile_phone_number');
            $data_insert['address_state']               = input_data('address_state');
            $data_insert['applicant_type']              = APPLICANT_TYPE_COMPANY;

            $applicant_id = $this->m_p_applicant->insert_applicant($data_insert);

            $application_id = 0;
            if($applicant_id>0):
                #insert application
                $data_insert_application['applicant_id']    = $applicant_id;
                $data_insert_application['date_application']= input_data('date_application');
                $data_insert_application['ref_number']      = strtoupper($this->ref_number_lib->get_ref(REF_NUMBER_TYPE_APPLICATION,$data_asset_type['TYPE_CODE']));
                $data_insert_application['form_number']     = strtoupper(input_data('form_number'));
                $data_insert_application['type_id']         = $data_asset_type['TYPE_ID'];
                $data_insert_application['category_id']     = input_data('category_id');
                $data_insert_application['area_site']       = currencyToDouble(input_data('area_site'));
                $data_insert_application['area_site_unit']  = input_data('area_site_unit');
                $data_insert_application['duration_use']            = currencyToDouble(input_data('duration_use'));
                $data_insert_application['duration_use_unit']       = input_data('duration_use_unit');
                $data_insert_application['charge_use_in_a_month']   = currencyToDouble(input_data('charge_use_in_a_month'));
                $data_insert_application['operation_use']           = input_data('operation_use');
                $data_insert_application['structure_type_building'] = input_data('structure_type_building');
                $data_insert_application['bill_type']               = $data_asset_type['BILL_TYPE'];
                $data_insert_application['difference_rental_charge_type']= 1 ;

                $application_id = $this->m_p_application->insert_application($data_insert_application);

                #insert file
                $form_session = $this->m_file_upload_temp->get_file_by_session_id($form_session);
                if($form_session):
                    foreach ($form_session as $row):
                        if (!file_exists(FILE_APPLICATION.$application_id.'/')):
                            mkdir(FILE_APPLICATION.$application_id.'/', 0777, true);
                        endif;

                        $data_file['filename']    = $row['FILE_NAME'];
                        $data_file['extension']   = $row['FILE_TYPE'];
                        $data_file['path']        = FILE_APPLICATION.$application_id.'/';
                        $data_file['ref_id']      = $application_id;
                        $data_file['file_type']   = $row['IMAGE_TYPE'];
                        $data_file['module_type'] = $row['MODULE_TYPE'];

                        copy(FILE_UPLOAD_TEMP.$row['FILE_NAME'], FILE_APPLICATION.$application_id.'/'.$row['FILE_NAME']);
                        $file_id = $this->m_file_gallery->insert($data_file);

                        unlink(FILE_UPLOAD_TEMP.$row['FILE_NAME']);
                        $delete         = $this->m_file_upload_temp->delete_by_id($row['ID']);
                    endforeach;
                endif;
            endif;

            $data_return['application_id']  = $application_id;
            $data_return['applicant_id']    = $applicant_id;
            $data_return['ref_number']      = $data_insert_application['ref_number'];

            return $data_return;
        endif;
    }

    private function form_4($data_asset_type,$data)
    {
        $form_session   = input_data('form_session');
        $random_number  = rand(1,1000);
        if(empty($form_session)):
            $form_session       = 'form_4'.'_'.$this->curuser['USER_ID'].'_'.date('YmdHis').'_'.$random_number;
        endif;

        $data['rental_use']     = $this->m_a_rental_use->get_a_rental_use_active();
        $data['category']       = $this->m_a_category->get_data_category_by_type($data_asset_type['TYPE_ID']);
        $data['asset_type']     = $data_asset_type;
        $data['form_session']   = $form_session;
        $data['ic_number_pic']  = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'ic_number_pic');

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

        validation_rules('date_application','<strong>tarikh permohonan</strong>','required');
        //validation_rules('ref_number','<strong>nombor Rujukan Permohonan</strong>','required|is_unique[P_APPLICATION.REF_NUMBER]');
        validation_rules('form_number','<strong>nombor borang</strong>','is_unique[P_APPLICATION.FORM_NUMBER]');
        validation_rules('name','<strong>nama permohonan</strong>','required');
        validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required|exact_length[12]');
        validation_rules('date_of_birth','<strong>tarikh lahir</strong>','required');
        validation_rules('place_of_birth','<strong>tempat lahir</strong>','required');
        validation_rules('address_1','<strong>alamat kediaman</strong>','required');
        validation_rules('address_3','<strong>bandar</strong>','required');
        validation_rules('postcode','<strong>poskod</strong>','required');
        validation_rules('address_state','<strong>alamat negeri</strong>','required');
        validation_rules('residence_information','<strong>maklumat kediaman</strong>','required');
        validation_rules('position','<strong>jawatan</strong>','required');
        validation_rules('staff_number','<strong>no. kakitangan</strong>','required');
        validation_rules('department_id','<strong>bahagian/unit</strong>','required');
        validation_rules('starting_of_service_date','<strong>tarikh mula berkhidmat</strong>','required');
        validation_rules('home_phone_number','<strong>no. telefon rumah</strong>','');
        validation_rules('mobile_phone_number','<strong>no. telefon bimbit</strong>','required');
        validation_rules('ic_number_pic_status','<strong>salinan kad pengenalan</strong>','required');
        validation_rules('category_id','<strong>lokasi yang dipohon</strong>','required');

        if(input_data('rental_use_id')==RENTAL_USE_OTHERS):
            validation_rules('rental_use_remark','<strong>catatan jenis perniagaan</strong>','required');
        endif;

        if(validation_run()==false):
            templates('/rental_application/form_4/v_form_4',$data);
        else:

            #insert applicant
            $data_insert['name']                = input_data('name');
            $data_insert['ic_number']           = trim(str_replace(array(' ', '-', '/'), '', input_data('ic_number')));
            $data_insert['date_of_birth']       = input_data('date_of_birth');
            $data_insert['place_of_birth']      = input_data('place_of_birth');
            $data_insert['address_1']           = input_data('address_1');
            $data_insert['address_2']           = input_data('address_2');
            $data_insert['address_3']           = input_data('address_3');
            $data_insert['postcode']            = input_data('postcode');
            //$data_insert['mail_address']        = input_data('mail_address');
            $data_insert['residence_information']  = input_data('residence_information');
            $data_insert['position']            = input_data('position');
            $data_insert['staff_number']        = input_data('staff_number');
            $data_insert['department_id']       = input_data('department_id');
            $data_insert['starting_of_service_date'] = input_data('starting_of_service_date');
            $data_insert['home_phone_number']   = input_data('home_phone_number');
            $data_insert['address_state']       = input_data('address_state');
            $data_insert['mobile_phone_number'] = trim(str_replace(array(' ', '-', '/'), '', input_data('mobile_phone_number')));
            $data_insert['applicant_type']      = APPLICANT_TYPE_INDIVIDUAL;

            $applicant_id = $this->m_p_applicant->insert_applicant($data_insert);

            $application_id = 0;
            if($applicant_id>0):
                #insert dependent
                $dependent      = input_data('dependent_name[]');
                $relationship   = input_data('dependent_relationship[]');
                if(isset($dependent) && $dependent):
                    $no_dependent = 0;
                    foreach ($dependent as $row):
                        if(!empty($row)):
                            $data_dependent['name']         = $row;
                            $data_dependent['relationship'] = (isset($relationship[$no_dependent]))?$relationship[$no_dependent]:'';
                            $data_dependent['applicant_id'] = $applicant_id;

                            $this->m_acc_dependent->insert_dependent($data_dependent);
                        endif;
                        $no_dependent = $no_dependent+1;
                    endforeach;
                endif;

                #insert application
                $data_insert_application['applicant_id']    = $applicant_id;
                $data_insert_application['date_application']= input_data('date_application');
                $data_insert_application['ref_number']      = strtoupper($this->ref_number_lib->get_ref(REF_NUMBER_TYPE_APPLICATION,$data_asset_type['TYPE_CODE']));
                $data_insert_application['form_number']     = strtoupper(input_data('form_number'));
                $data_insert_application['type_id']         = $data_asset_type['TYPE_ID'];
                $data_insert_application['category_id']     = input_data('category_id');
                $data_insert_application['difference_rental_charge_type']= 1 ;

                $application_id = $this->m_p_application->insert_application($data_insert_application);

                #insert file
                $form_session = $this->m_file_upload_temp->get_file_by_session_id($form_session);
                if($form_session):
                    foreach ($form_session as $row):
                        if (!file_exists(FILE_APPLICATION.$application_id.'/')):
                            mkdir(FILE_APPLICATION.$application_id.'/', 0777, true);
                        endif;

                        $data_file['filename']    = $row['FILE_NAME'];
                        $data_file['extension']   = $row['FILE_TYPE'];
                        $data_file['path']        = FILE_APPLICATION.$application_id.'/';
                        $data_file['ref_id']      = $application_id;
                        $data_file['file_type']   = $row['IMAGE_TYPE'];
                        $data_file['module_type'] = $row['MODULE_TYPE'];

                        copy(FILE_UPLOAD_TEMP.$row['FILE_NAME'], FILE_APPLICATION.$application_id.'/'.$row['FILE_NAME']);
                        $file_id = $this->m_file_gallery->insert($data_file);

                        unlink(FILE_UPLOAD_TEMP.$row['FILE_NAME']);
                        $delete         = $this->m_file_upload_temp->delete_by_id($row['ID']);
                    endforeach;
                endif;
            endif;

            $data_return['application_id']  = $application_id;
            $data_return['applicant_id']    = $applicant_id;
            $data_return['ref_number']      = $data_insert_application['ref_number'];
            return $data_return;
        endif;
    }

    function application()
    {
        $this->auth->restrict_access($this->curuser,array(4002));

        $data['link_1']     = 'Permohonan Sewaan';
        $data['link_2']     = 'Senarai Permohonan';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Permohonan';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        //pre($post);
        $filter_session = get_session('arr_filter_application');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_application',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['ref_number']          = '';
                $data_search['type_id']             = '';
                $data_search['status_application']  = '';
                $data_search['status_create_account']  = '';
            endif;
        endif;

        $data['data_search'] = $data_search;

        //pre($data_search);
        //exit;


        $total = $this->m_p_application->count_application($data_search);
        $links          = '/rental_application/application';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment,$data_search);

        $data_list              = $this->m_p_application->get_application($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;
        $data['data_type']      = $this->m_a_type->get_a_type();

        templates('rental_application/v_list_application',$data);
    }

    function application_process()
    {
        $this->auth->restrict_access($this->curuser,array(4003));

        $data['link_1']     = 'Permohonan Sewaan';
        $data['link_2']     = '<a href="/rental_application/application">Senarai permohonan</a>';
        $data['link_3']     = 'Proses Permohonan';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $get_details = $this->m_p_application->get_application_details($id);
        if(!$get_details):
            return false;
        endif;
        //pre($get_details);
        $data['data_details']   = $get_details;
        $data['dependent']      = $this->m_acc_dependent->get_dependent_by_applicant_id($get_details['APPLICANT_ID']);
        $data['asset']          = $this->m_a_asset->get_a_asset_active_available_by_category_id($get_details['CATEGORY_ID']);
        $data['department']     = $this->m_dept->get_dept_details($get_details['DEPARTMENT_ID']);

        if($get_details['FORM_TYPE']==1):
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
            $data['passport_pic']   = $this->m_file_gallery->get_file($id,PERMOHONAN_GAMBAR_PASPORT);
            $data['ssm_pic']        = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM);
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
            $data['passport_pic']   = $this->m_file_gallery->get_file($id,PERMOHONAN_GAMBAR_PASPORT);

            validation_rules('name','<strong>nama permohonan</strong>','required');
            validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required|exact_length[12]');
            validation_rules('date_of_birth','<strong>tarikh lahir</strong>','required');
            validation_rules('address_1','<strong>alamat kediaman</strong>','required');
            validation_rules('address_3','<strong>bandar</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');
            validation_rules('mail_address_1','<strong>alamat surat menyurat</strong>','required');
            validation_rules('mail_address_3','<strong>bandar</strong>','required');
            validation_rules('mail_postcode','<strong>poskod</strong>','required');
            validation_rules('mail_state','<strong>alamat negeri</strong>','required');
            validation_rules('location_distance','<strong>jarak tempat tinggal</strong>','required');
            validation_rules('residence_duration','<strong>tempoh menetap</strong>','required');
            validation_rules('race','<strong>bangsa</strong>','required');
            validation_rules('marital_status','<strong>status perkahwinan</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon rumah</strong>');
            validation_rules('mobile_phone_number','<strong>no. telefon bimbit</strong>','required');
            validation_rules('occupation_status','<strong>status pekerjaan</strong>','required');
            validation_rules('business_experience','<strong>pengalaman berniaga</strong>','required');
            if(input_data('occupation_status')==OCCUPATION_STATUS_WORKING):
                validation_rules('total_earnings','<strong>jumlah pendapatan (RM)</strong>','required');
                validation_rules('occupation','<strong>pekerjaan</strong>','required');
            endif;
        elseif ($get_details['FORM_TYPE']==2):
            $data['map_info']           = $this->m_file_gallery->get_file($id,PERMOHONAN_MAP_INFO);
            $data['location_plan_file'] = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_LOKASI);
            $data['structure_plan']     = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_STRUKTUR);
            $data['app_ssm_file']       = $this->m_file_gallery->get_file($id,PERMOHONAN_CARIAN_SSM);
            $data['cost_validation']    = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_PENGESAHAN_KOS_BINAAN);

            validation_rules('cost_billboard','<strong>kos binaan papan iklan</strong>','required');
            validation_rules('structure_type_billboard','<strong>jenis struktur papan iklan</strong>','required');
            validation_rules('location_billboard','<strong>lokasi papan iklan</strong>','required');
            validation_rules('billboard','<strong>papan iklan</strong>','required');
            validation_rules('height_billboard','<strong>tinggi papan iklan</strong>','required');
            validation_rules('width_billboard','<strong>lebar papan iklan</strong>','required');
            validation_rules('area_billboard','<strong>keluasan tapak papan iklan</strong>','required');
            validation_rules('name','<strong>nama syarikat</strong>','required');
            validation_rules('company_registration_number','<strong>no. pendaftaran syarikat</strong>','required');
            validation_rules('address_1','<strong>alamat syarikat</strong>','required');
            validation_rules('address_3','<strong>bandar</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon syarikat</strong>','required');
            validation_rules('fax_number','<strong>no. faks</strong>');
            validation_rules('director_name','<strong>nama pengarah/pegawai</strong>','required');
            validation_rules('mobile_phone_number','<strong>no. telefon pengarah/pegawai</strong>','required');
            validation_rules('total_income_a_year','<strong>jumlah pendapatan setahun</strong>','required');

        elseif ($get_details['FORM_TYPE']==3):
            $data['letter_application'] = $this->m_file_gallery->get_file($id,PERMOHONAN_SURAT_PERMOHONAN);
            $data['ic_number_pic']      = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
            $data['location_plan']      = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_LOKASI);
            $data['photo_location']     = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_PERMOHONAN);
            $data['structure_plan']     = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_SETUJU_TERIMA);
            $data['ssm']                = $this->m_file_gallery->get_file($id,PERMOHONAN_CARIAN_SSM);

            validation_rules('name','<strong>nama syarikat</strong>','required');
            validation_rules('company_registration_number','<strong>no. pendaftaran syarikat</strong>','required');
            validation_rules('address_1','<strong>alamat syarikat</strong>','required');
            validation_rules('address_3','<strong>bandar</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon syarikat</strong>','required');
            validation_rules('fax_number','<strong>no. <fa></fa>ks</strong>');
            validation_rules('director_name','<strong>nama pengarah/pegawai</strong>','required');
            validation_rules('mobile_phone_number','<strong>no. telefon pengarah/pegawai</strong>','required');

            validation_rules('area_site','<strong>keluasan</strong>','required');
            validation_rules('area_site_unit','<strong>keluasan</strong>','required');
            validation_rules('duration_use','<strong>cadangan tempoh caj penggunaan / penyelenggaraan</strong>','required');
            validation_rules('duration_use_unit','<strong></strong>','required');
            validation_rules('structure_type_building','<strong>cadangan jenis struktur bangunan</strong>','required');
            validation_rules('charge_use_in_a_month','<strong>cadangan caj penggunaan / penyelanggaraan sebulan</strong>','required');
            validation_rules('operation_use','<strong>cadangan operasi / kegunaan tapak / bangunan</strong>','required');

        elseif ($get_details['FORM_TYPE']==4):
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
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);

            validation_rules('name','<strong>nama permohonan</strong>','required');
            validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required|exact_length[12]');
            validation_rules('date_of_birth','<strong>tarikh lahir</strong>','required');
            validation_rules('place_of_birth','<strong>tempat lahir</strong>','required');
            validation_rules('address_1','<strong>alamat kediaman</strong>','required');
            validation_rules('address_3','<strong>bandar</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');
            validation_rules('residence_information','<strong>maklumat kediaman</strong>','required');
            validation_rules('position','<strong>jawatan</strong>','required');
            validation_rules('staff_number','<strong>no. kakitangan</strong>','required');
            validation_rules('department_id','<strong>bahagian/unit</strong>','required');
            validation_rules('starting_of_service_date','<strong>tarikh mula berkhidmat</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon rumah</strong>','');
            validation_rules('mobile_phone_number','<strong>no. telefon bimbit</strong>','required');
        endif;


        validation_rules('no_fail','<strong>no. fail</strong>');
        validation_rules('asset_id','<strong>kod harta / no. gerai</strong>','required');
        validation_rules('rental_fee','<strong>harga sewaan</strong>','required');
        validation_rules('difference_rental_charge_type','<strong>penambahan / pengurangan caj sewa</strong>','required');
        validation_rules('difference_rental_charge','<strong>penambahan / pengurangan caj sewa</strong>','required');
        validation_rules('rental_charge','<strong>jumlah sewaan</strong>','required');
        validation_rules('deposit_rental','<strong>cagaran sewaan</strong>','required');
        validation_rules('rental_agreement_cost','<strong>kos perjanjian sewa</strong>','required');
        validation_rules('date_start','<strong>tarikh mula sewaan</strong>','required');
        validation_rules('date_end','<strong>tarikh tamat sewaan</strong>','required');
        validation_rules('rental_duration','<strong>>tempoh sewaan & bil</strong>','required');
        validation_rules('bill_type','<strong>jenis bil</strong>','required');
        validation_rules('waste_management_bills','<strong>dikenakan bil pengurusan sampah</strong>','required');
        if(input_data('waste_management_bills')==1):
            validation_rules('waste_management_charge','<strong>caj pengurusan sampah</strong>','required');
        endif;
        validation_rules('freezer_management_bills','<strong>dikenakan bil simpanan sejuk beku</strong>','required');
        if(input_data('freezer_management_bills')==1):
            validation_rules('freezer_management_charge','<strong>caj simpanan sejuk beku</strong>','required');
        endif;

        if($get_details['STATUS_APPLICATION'] == STATUS_APPLICATION_NEW || $get_details['STATUS_APPLICATION'] == STATUS_APPLICATION_KIV):
            if(input_data('status_application')!=STATUS_APPLICATION_NEW):
                validation_rules('meeting_number','<strong>no. bilangan mesyuarat penuh majlis/no. rujukan</strong>','required');
                validation_rules('date_meeting','<strong>tarikh mesyuarat / keputusan</strong>','required');
                validation_rules('status_application','<strong>status permohonan</strong>','required');
                validation_rules('remark','<strong>catatan</strong>');
            endif;
        elseif ($get_details['STATUS_APPLICATION'] == STATUS_APPLICATION_APPROVED):
            validation_rules('status_agree','<strong>status setuju terima</strong>','required');
            validation_rules('date_agree','<strong>tarikh keputusan</strong>','required');
            validation_rules('remark_agree','<strong>catatan</strong>');
        endif;

        if(validation_run()==false):
            templates('/rental_application/form_'.$get_details['FORM_TYPE'].'/v_form_'.$get_details['FORM_TYPE'].'_details',$data);
        else:
            if($get_details['FORM_TYPE']==1):
                $data_update_applicant['name']                = input_data('name');
                $data_update_applicant['ic_number']           = trim(str_replace(array(' ', '-', '/'), '', input_data('ic_number')));
                $data_update_applicant['date_of_birth']       = input_data('date_of_birth');
                $data_update_applicant['address_1']           = input_data('address_1');
                $data_update_applicant['address_2']           = input_data('address_2');
                $data_update_applicant['address_3']           = input_data('address_3');
                $data_update_applicant['postcode']            = input_data('postcode');
                $data_update_applicant['address_state']       = input_data('address_state');
                $data_update_applicant['mail_address_1']      = input_data('mail_address_1');
                $data_update_applicant['mail_address_2']      = input_data('mail_address_2');
                $data_update_applicant['mail_address_3']      = input_data('mail_address_3');
                $data_update_applicant['mail_postcode']       = input_data('mail_postcode');
                $data_update_applicant['mail_state']          = input_data('mail_state');
                $data_update_applicant['location_distance']   = input_data('location_distance');
                $data_update_applicant['residence_duration']  = input_data('residence_duration');
                $data_update_applicant['race']                = input_data('race');
                $data_update_applicant['marital_status']      = input_data('marital_status');
                $data_update_applicant['home_phone_number']   = input_data('home_phone_number');
                $data_update_applicant['mobile_phone_number'] = trim(str_replace(array(' ', '-', '/'), '', input_data('mobile_phone_number')));
                $data_update_applicant['occupation_status']   = input_data('occupation_status');
                $data_update_applicant['business_experience'] = input_data('business_experience');
                $data_update_applicant['business_type']       = input_data('business_type');
                if(input_data('occupation_status')==OCCUPATION_STATUS_WORKING):
                    $data_update_applicant['total_earnings']      = currencyToDouble(input_data('total_earnings'));
                    $data_update_applicant['occupation']          = input_data('occupation');
                else:
                    $data_update_applicant['total_earnings']      = 0.00;
                    $data_update_applicant['occupation']          = '';
                endif;
            elseif($get_details['FORM_TYPE']==2):

                $data_update_applicant['name']                          = input_data('name');
                $data_update_applicant['company_registration_number']   = input_data('company_registration_number');
                $data_update_applicant['address_1']                     = input_data('address_1');
                $data_update_applicant['address_2']                     = input_data('address_2');
                $data_update_applicant['address_3']                     = input_data('address_3');
                $data_update_applicant['postcode']                      = input_data('postcode');
                $data_update_applicant['address_state']                 = input_data('address_state');
                $data_update_applicant['home_phone_number']             = input_data('home_phone_number');
                $data_update_applicant['fax_number']                    = input_data('fax_number');
                $data_update_applicant['director_name']                 = input_data('director_name');
                $data_update_applicant['mobile_phone_number']           = input_data('mobile_phone_number');

                $data_update['cost_billboard']              = currencyToDouble(input_data('cost_billboard'));
                $data_update['structure_type_billboard']    = input_data('structure_type_billboard');
                $data_update['location_billboard']          = input_data('location_billboard');
                $data_update['billboard']                   = input_data('billboard');
                $data_update['height_billboard']            = input_data('height_billboard');
                $data_update['area_billboard']              = input_data('area_billboard');
                $data_update['total_income_a_year']         = currencyToDouble(input_data('total_income_a_year'));
            elseif ($get_details['FORM_TYPE']==3):

                $data_update_applicant['name']                          = input_data('name');
                $data_update_applicant['company_registration_number']   = input_data('company_registration_number');
                $data_update_applicant['address_1']                     = input_data('address_1');
                $data_update_applicant['address_2']                     = input_data('address_2');
                $data_update_applicant['address_3']                     = input_data('address_3');
                $data_update_applicant['postcode']                      = input_data('postcode');
                $data_update_applicant['address_state']                 = input_data('address_state');
                $data_update_applicant['home_phone_number']             = input_data('home_phone_number');
                $data_update_applicant['fax_number']                    = input_data('fax_number');
                $data_update_applicant['director_name']                 = input_data('director_name');
                $data_update_applicant['mobile_phone_number']           = input_data('mobile_phone_number');

                $data_update['area_site']                   = currencyToDouble(input_data('area_site'));
                $data_update['area_site_unit']              = input_data('area_site_unit');
                $data_update['duration_use']                = currencyToDouble(input_data('duration_use'));
                $data_update['duration_use_unit']           = input_data('duration_use_unit');
                $data_update['structure_type_building']     = input_data('structure_type_building');
                $data_update['charge_use_in_a_month']       = currencyToDouble(input_data('charge_use_in_a_month'));
                $data_update['operation_use']               = input_data('operation_use');

            elseif ($get_details['FORM_TYPE']==4):
                $data_update_applicant['name']                  = input_data('name');
                $data_update_applicant['ic_number']             = input_data('ic_number');
                $data_update_applicant['date_of_birth']         = input_data('date_of_birth');
                $data_update_applicant['place_of_birth']        = input_data('place_of_birth');
                $data_update_applicant['address_1']             = input_data('address_1');
                $data_update_applicant['address_2']             = input_data('address_2');
                $data_update_applicant['address_3']             = input_data('address_3');
                $data_update_applicant['postcode']              = input_data('postcode');
                $data_update_applicant['address_state']         = input_data('address_state');
                $data_update_applicant['residence_information'] = input_data('residence_information');
                $data_update_applicant['position']              = input_data('position');
                $data_update_applicant['staff_number']          = input_data('staff_number');
                $data_update_applicant['department_id']         = input_data('department_id');
                $data_update_applicant['starting_of_service_date'] = input_data('starting_of_service_date');
                $data_update_applicant['home_phone_number']     = input_data('home_phone_number');
                $data_update_applicant['mobile_phone_number']   = input_data('mobile_phone_number');
            endif;

            $data_update_applicant['total_child']   = count_child($get_details['APPLICANT_ID'],1);
            $update_status = $this->m_p_applicant->update_applicant($data_update_applicant,$get_details['APPLICANT_ID']);

            $get_asset = $this->m_a_asset->get_a_asset_by_id(input_data('asset_id'));

            $data_update['file_number']             = input_data('no_fail');
            if($get_asset['ASSET_NAME']):
                $file_number_juu =  input_data('no_fail').'('.$get_asset['ASSET_NAME'].')'.$get_details['REF_NUMBER'];
            else:
                $file_number_juu =  input_data('no_fail').$get_details['REF_NUMBER'];
            endif;
            $data_update['file_number_juu']         = $file_number_juu;
            $data_update['asset_id']                = input_data('asset_id');
            $data_update['estimation_rental_charge']= currencyToDouble(input_data('rental_fee'));
            $data_update['difference_rental_charge']= currencyToDouble(input_data('difference_rental_charge'));
            $data_update['difference_rental_charge_type']= (input_data('difference_rental_charge_type'));
            $data_update['rental_fee']              = currencyToDouble(input_data('rental_charge'));
            $data_update['waste_management_bills']  = input_data('waste_management_bills');
            if(input_data('waste_management_bills')==1):
                $data_update['waste_management_charge']  = currencyToDouble(input_data('waste_management_charge'));
            else:
                $data_update['waste_management_charge']  = 0.00;
            endif;

            $data_update['freezer_management_bills']  = input_data('freezer_management_bills');
            if(input_data('freezer_management_bills')==1):
                $data_update['freezer_management_charge']  = currencyToDouble(input_data('freezer_management_charge'));
            else:
                $data_update['freezer_management_charge']  = 0.00;
            endif;

            $data_update['deposit_rental']          = currencyToDouble(input_data('deposit_rental'));
            $data_update['rental_agreement_cost']   = currencyToDouble(input_data('rental_agreement_cost'));
            $data_update['date_start']              = input_data('date_start');
            $data_update['date_end']                = input_data('date_end');
            $data_update['rental_duration']         = input_data('rental_duration');
            $data_update['bill_type']               = input_data('bill_type');

            if($get_details['STATUS_APPLICATION'] == STATUS_APPLICATION_NEW || $get_details['STATUS_APPLICATION'] == STATUS_APPLICATION_KIV):
                if(input_data('status_application')!=STATUS_APPLICATION_NEW):
                    $data_update['status_application']  = input_data('status_application');
                    $data_update['meeting_number']      = strtoupper(input_data('meeting_number'));
                    $data_update['remark']              = input_data('remark');
                    $data_update['date_meeting']        = input_data('date_meeting');
                endif;

                $data_audit_trail['log_id'] = 2002;
                if(input_data('status_application')==STATUS_APPLICATION_NEW):
                    $data_audit_trail['log_id'] = 2002;
                elseif(input_data('status_application')==STATUS_APPLICATION_APPROVED):
                    $data_audit_trail['log_id'] = 2003;
                elseif(input_data('status_application')==STATUS_APPLICATION_REJECTED):
                    $data_audit_trail['log_id'] = 2004;
                elseif(input_data('status_application')==STATUS_APPLICATION_KIV):
                    $data_audit_trail['log_id'] = 2005;
                endif;

            elseif ($get_details['STATUS_APPLICATION'] == STATUS_APPLICATION_APPROVED):
                if(input_data('status_agree')!=STATUS_AGREE_DEFAULT):
                    $data_update['status_agree']        = input_data('status_agree');
                    $data_update['date_agree']          = input_data('date_agree');
                    $data_update['remark_agree']        = input_data('remark_agree');
                endif;

                $data_audit_trail['log_id'] = 2002;
                if(input_data('status_agree')==STATUS_AGREE_DEFAULT):
                    $data_audit_trail['log_id'] = 2002;
                elseif(input_data('status_agree')==STATUS_AGREE_ACCEPTED):
                    $data_audit_trail['log_id'] = 2006;
                elseif(input_data('status_agree')==STATUS_AGREE_REJECTED):
                    $data_audit_trail['log_id'] = 2007;
                endif;
            endif;

            $update_status = $this->m_p_application->update_application($data_update,$id);

            if($update_status):
                $data_audit_trail['remark']                  = $this->input->post();
                $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                $data_audit_trail['refer_id']                = $id;
                $this->audit_trail_lib->add($data_audit_trail);

                set_notify('notify_msg',TEXT_UPDATE_RECORD);
            else:
                set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL,2);
            endif;

            if($get_details['STATUS_APPLICATION'] == STATUS_APPLICATION_APPROVED && $get_details['STATUS_AGREE'] == STATUS_AGREE_ACCEPTED):
                redirect('/account/create_acc/'.urlEncrypt($id));
            else:
                redirect('/rental_application/application_process/'.urlEncrypt($id));
            endif;
        endif;
    }

    function get_data_category_by_type()
    {
        if(is_ajax()):
            $asset_id   = input_data('category_id');
            $type_id    = input_data('type_id');

            $data_asset = $this->m_a_category->get_data_category_by_type($type_id);

            if($data_asset):
                echo '<option> - Sila pilih - </option>';
                foreach ($data_asset as $row):
                    echo option_value($row['CATEGORY_ID'],$row['CATEGORY_NAME'].' ('.$row['CATEGORY_CODE'].')','category_id',$asset_id);
                endforeach;
            endif;
        endif;
    }

    function application_approval()
    {
        $this->auth->restrict_access($this->curuser,array(4009));

        $data['link_1']     = 'Permohonan Sewaan';
        $data['link_2']     = 'Kelulusan Permohonan';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $form_session   = input_data('form_session');
        $random_number  = rand(1,1000);
        if(empty($form_session)):
            $form_session       = 'application_approval'.'_'.$this->curuser['USER_ID'].'_'.date('YmdHis').'_'.$random_number;
        endif;

        $data['asset_type']         = $this->m_a_type->get_a_type_active();
        $data['form_session']       = $form_session;
        $file_attachment            = $this->m_file_upload_temp->get_file_by_session_id($form_session);
        $data['file_attachment']    = $file_attachment;

        $data_attachment[]         = array();
        if($file_attachment):
            foreach ($file_attachment as $attachment):
                $data_attachment[$attachment['INPUT_NAME']] = $attachment;
            endforeach;
        endif;

        $data['data_attachment']    = $data_attachment;

        $data_search['ref_start']       = input_data('ref_number_start');
        $data_search['ref_end']         = input_data('ref_number_end');
        $data_search['type_id']         = input_data('type_id');
        $data_search['approval']        = true;

        if(empty($data_search['type_id'])):
            $data_search['type_id']     = -1;
            $data['data_list']          = $this->m_p_application->get_application_by_search($data_search);
            // $data['data_list']          = array();
        else:
            $data['data_list']          = $this->m_p_application->get_application_by_search($data_search);
            // $data_list                  = $this->m_p_application->get_application_by_search($data_search);
            // $data['data_list']          = $data_list;
        endif;

        $type_submit = input_data('submit');

        if($type_submit=='submit'):
            validation_rules('meeting_number','<strong>no. bilangan mesyuarat penuh majlis/no. rujukan</strong>','required');
            validation_rules('date_meeting','<strong>Tarikh mesyuarat / keputusan</strong>','required');
        elseif ($type_submit=='search'):
            validation_rules('type_id','<strong>jenis sewaan</strong>','required');
        endif;

        if(validation_run()==false):
            templates('/rental_application/v_application_approval',$data);
        else:
            if($type_submit=='submit'):
                $list_application = input_data('application_id');
                if($list_application):
                    foreach ($list_application as $application_id):
                        $status_application = input_data('status_application_'.$application_id);
                        $remark             = input_data('remark_'.$application_id);
                        $attachment_file    = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'attachment_application_'.$application_id);

                        if($status_application>0):
                            $data_update['status_application']  = $status_application;
                            $data_update['meeting_number']      = strtoupper(input_data('meeting_number'));
                            $data_update['remark']              = $remark;
                            $data_update['date_meeting']        = input_data('date_meeting');

                            $update_status = $this->m_p_application->update_application($data_update,$application_id);

                            if($status_application==STATUS_APPLICATION_APPROVED):
                                $data_audit_trail['log_id'] = 2003;
                            elseif($status_application==STATUS_APPLICATION_REJECTED):
                                $data_audit_trail['log_id'] = 2004;
                            elseif($status_application==STATUS_APPLICATION_KIV):
                                $data_audit_trail['log_id'] = 2005;
                            endif;

                            $data_audit_trail['remark']                  = $data_update;
                            $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                            $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                            $data_audit_trail['refer_id']                = $application_id;
                            $this->audit_trail_lib->add($data_audit_trail);

                            #attachment
                            if($attachment_file):
                                if (!file_exists(FILE_APPLICATION.$application_id.'/')):
                                    mkdir(FILE_APPLICATION.$application_id.'/', 0777, true);
                                endif;

                                $data_file['filename']    = $attachment_file['FILE_NAME'];
                                $data_file['extension']   = $attachment_file['FILE_TYPE'];
                                $data_file['path']        = FILE_APPLICATION.$application_id.'/';
                                $data_file['ref_id']      = $application_id;
                                $data_file['file_type']   = 16;
                                $data_file['module_type'] = 3;

                                copy(FILE_UPLOAD_TEMP.$attachment_file['FILE_NAME'], FILE_APPLICATION.$application_id.'/'.$attachment_file['FILE_NAME']);
                                $file_id = $this->m_file_gallery->insert($data_file);

                                unlink(FILE_UPLOAD_TEMP.$attachment_file['FILE_NAME']);
                                $delete         = $this->m_file_upload_temp->delete_by_id($attachment_file['ID']);
                            else:
                                if($attachment_file):
                                    unlink(FILE_UPLOAD_TEMP.$attachment_file['FILE_NAME']);
                                    $delete         = $this->m_file_upload_temp->delete_by_id($attachment_file['ID']);
                                endif;
                            endif;
                        endif;
                    endforeach;

                    set_notify('notify_msg',TEXT_UPDATE_RECORD);
                    redirect('/rental_application/application_approval');
                else:
                    set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL);
                    redirect('/rental_application/application_approval');
                endif;
            elseif ($type_submit=='search'):
                templates('/rental_application/v_application_approval',$data);
            endif;
        endif;
    }

    function application_agree()
    {
        $this->auth->restrict_access($this->curuser,array(4010));

        $data['link_1']     = 'Permohonan Sewaan';
        $data['link_2']     = 'Setuju Terima';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $form_session   = input_data('form_session');
        $random_number  = rand(1,1000);
        if(empty($form_session)):
            $form_session       = 'application_agree'.'_'.$this->curuser['USER_ID'].'_'.date('YmdHis').'_'.$random_number;
        endif;

        $data['asset_type']         = $this->m_a_type->get_a_type_active();
        $data['form_session']       = $form_session;
        $file_attachment            = $this->m_file_upload_temp->get_file_by_session_id($form_session);
        $data['file_attachment']    = $file_attachment;

        $data_attachment[]         = array();
        if($file_attachment):
            foreach ($file_attachment as $attachment):
                $data_attachment[$attachment['INPUT_NAME']] = $attachment;
            endforeach;
        endif;

        $data['data_attachment']    = $data_attachment;

        $data_search['ref_start']       = input_data('ref_number_start');
        $data_search['ref_end']         = input_data('ref_number_end');
        $data_search['type_id']         = input_data('type_id');
        $data_search['approval']        = STATUS_APPLICATION_APPROVED;
        $data_search['status_agree']    = STATUS_AGREE_DEFAULT;

        if(empty($data_search['type_id'])):
            // $data['data_list']          = array();
            $data_search['type_id']     = -1;
            $data['data_list']          = $this->m_p_application->get_application_by_search($data_search);
        else:
            $data['data_list']          = $this->m_p_application->get_application_by_search($data_search);
            // $data_list                  = $this->m_p_application->get_application_by_search($data_search);
            // $data['data_list']          = $data_list;
        endif;

        $type_submit = input_data('submit');

        if($type_submit=='submit'):
            $list_application = input_data('application_id');
            if($list_application):
                foreach ($list_application as $application_id):
                    validation_rules('date_agree_'.$application_id,'<strong>tarikh keputusan</strong>','required');
                endforeach;
            endif;
        elseif ($type_submit=='search'):
            validation_rules('type_id','<strong>jenis sewaan</strong>','required');
        endif;

        if(validation_run()==false):
            templates('/rental_application/v_application_agree',$data);
        else:
            if($type_submit=='submit'):
                $list_application = input_data('application_id');
                if($list_application):
                    foreach ($list_application as $application_id):
                        $status_agree       = input_data('status_agree_'.$application_id);
                        $remark             = input_data('remark_agree_'.$application_id);
                        $date_agree         = input_data('date_agree_'.$application_id);
                        $attachment_file    = $this->m_file_upload_temp->get_file_by_session_id_input_name($form_session,'attachment_agree_'.$application_id);

                        if($status_agree>0):
                            $data_update['status_agree']    = $status_agree;
                            $data_update['remark_agree']    = $remark;
                            $data_update['date_agree']      = $date_agree;

                            $update_status = $this->m_p_application->update_application($data_update,$application_id);

                            if($status_agree==STATUS_AGREE_ACCEPTED):
                                $data_audit_trail['log_id'] = 2006;
                            elseif($status_agree==STATUS_AGREE_REJECTED):
                                $data_audit_trail['log_id'] = 2007;
                            endif;

                            $data_audit_trail['remark']                  = $data_update;
                            $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                            $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                            $data_audit_trail['refer_id']                = $application_id;
                            $this->audit_trail_lib->add($data_audit_trail);

                            #attachment
                            if($attachment_file):
                                if (!file_exists(FILE_APPLICATION.$application_id.'/')):
                                    mkdir(FILE_APPLICATION.$application_id.'/', 0777, true);
                                endif;

                                $data_file['filename']    = $attachment_file['FILE_NAME'];
                                $data_file['extension']   = $attachment_file['FILE_TYPE'];
                                $data_file['path']        = FILE_APPLICATION.$application_id.'/';
                                $data_file['ref_id']      = $application_id;
                                $data_file['file_type']   = 17;
                                $data_file['module_type'] = 3;

                                copy(FILE_UPLOAD_TEMP.$attachment_file['FILE_NAME'], FILE_APPLICATION.$application_id.'/'.$attachment_file['FILE_NAME']);
                                $file_id = $this->m_file_gallery->insert($data_file);

                                unlink(FILE_UPLOAD_TEMP.$attachment_file['FILE_NAME']);
                                $delete         = $this->m_file_upload_temp->delete_by_id($attachment_file['ID']);
                            endif;
                        else:
                            if($attachment_file):
                                unlink(FILE_UPLOAD_TEMP.$attachment_file['FILE_NAME']);
                                $delete         = $this->m_file_upload_temp->delete_by_id($attachment_file['ID']);
                            endif;
                        endif;
                    endforeach;

                    set_notify('notify_msg',TEXT_UPDATE_RECORD);
                    redirect('/rental_application/application_agree');
                else:
                    set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL);
                    redirect('/rental_application/application_agree');
                endif;
            elseif ($type_submit=='search'):
                templates('/rental_application/v_application_agree',$data);
            endif;

        endif;
    }

    function doc_offer_letter()
    {
        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        load_library('Generate_word');
        $this->generate_word->word_document($id,DOC_OFFER_LETTER);
    }

    function doc_acceptance_letter()
    {
        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        load_library('Generate_word');
        $this->generate_word->word_document($id,DOC_ACCEPTANCE_LETTER);
    }

    function get_asset_by_id()
    {
        if(is_ajax()):
            $asset_id   = input_data('asset_id');
            $data_asset = $this->m_a_asset->get_a_asset_by_id($asset_id);
            if($data_asset):
                echo num($data_asset['RENTAL_FEE']);
            endif;
        endif;
    }
}
