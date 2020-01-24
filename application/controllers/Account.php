<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_library('Audit_trail_lib');

        load_model('Asset/M_a_type', 'm_a_type');
        load_model('Asset/M_a_rental_use', 'm_a_rental_use');
        load_model('Asset/M_a_category', 'm_a_category');
        load_model('Application/M_p_applicant', 'm_p_applicant');
        load_model('Application/M_p_application', 'm_p_application');
        load_model('Account/M_acc_user', 'm_acc_user');
        load_model('Account/M_acc_account', 'm_acc_account');
        load_model('Asset/M_a_asset', 'm_a_asset');
        load_model('Account/M_acc_dependent', 'm_acc_dependent');
        load_model('File/M_file_gallery', 'm_file_gallery');
        load_model('Department/M_department','m_dept');
        load_model('File/M_file_upload_temp', 'm_file_upload_temp');
        load_model('Account/M_acc_kuarters_defect', 'm_acc_kuarters_defect');
        load_model('Account/M_acc_kuarters_defect_list', 'm_acc_kuarters_defect_list');
        load_model('Account/M_c_document', 'm_c_document');
    }

    function _remap($method)
    {
        $array = array(
            'create_acc',
            'account_list',
            'calculate_duration',
            'account_create_list',
            'doc_agreement',
            'doc_signature',
            'doc_quarters',
            'update_acc',
            'detail_acc',
            'generate_bill',
            'create_acc_direct',
            'time_extension',
            'kuarters_list',
            'kuarters_add',
            'kuarters_detais',
            'delete_kuarters',
            'delete_item'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->create_acc();
    }

    function create_acc(){
        $data['link_1']     = 'Akaun Sewaan';
        $data['link_2']     = 'Pendaftaran akaun';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $get_details = $this->m_p_application->get_application_details($id);
        if(!$get_details):
            return false;
        endif;
        if($get_details['STATUS_CREATE_ACCOUNT']>STATUS_CREATE_ACCOUNT_NO):
            return false;
        endif;

        $data['data_details']   = $get_details;
        $data['data_asset']     = $this->m_a_asset->get_a_asset_active_by_category_id($get_details['CATEGORY_ID']);
        $data['dependent']      = $this->m_acc_dependent->get_dependent_by_applicant_id($get_details['APPLICANT_ID']);
        $data['department']     = $this->m_dept->get_dept_details($get_details['DEPARTMENT_ID']);
        $data_category          = $this->m_a_category->get_a_category_details($get_details['CATEGORY_ID']);

        if($get_details['FORM_TYPE']==1):
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
            $data['passport_pic']   = $this->m_file_gallery->get_file($id,PERMOHONAN_GAMBAR_PASPORT);
            $data['ssm_pic']        = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM);
            $data['passport_pic']   = $this->m_file_gallery->get_file($id,PERMOHONAN_GAMBAR_PASPORT);
        elseif ($get_details['FORM_TYPE']==2):
            $data['map_info']           = $this->m_file_gallery->get_file($id,PERMOHONAN_MAP_INFO);
            $data['location_plan_file'] = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_LOKASI);
            $data['structure_plan']     = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_STRUKTUR);
            $data['app_ssm_file']       = $this->m_file_gallery->get_file($id,PERMOHONAN_CARIAN_SSM);
            $data['cost_validation']    = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_PENGESAHAN_KOS_BINAAN);
        elseif ($get_details['FORM_TYPE']==3):
            $data['letter_application'] = $this->m_file_gallery->get_file($id,PERMOHONAN_SURAT_PERMOHONAN);
            $data['ic_number_pic']      = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
            $data['location_plan']      = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_LOKASI);
            $data['photo_location']     = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_PERMOHONAN);
            $data['structure_plan']     = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_SETUJU_TERIMA);
            $data['ssm']                = $this->m_file_gallery->get_file($id,PERMOHONAN_CARIAN_SSM);
        elseif ($get_details['FORM_TYPE']==4):
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
        endif;

        if($get_details['FORM_TYPE']==1):

            validation_rules('name','<strong>nama</strong>','required');
            validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required');
            validation_rules('address_1','<strong>alamat kediaman</strong>','required');
            validation_rules('address_3','<strong>bandar</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');
            validation_rules('mail_address_1','<strong>alamat surat menyurat</strong>','required');
            validation_rules('mail_address_3','<strong>bandar</strong>','required');
            validation_rules('mail_postcode','<strong>poskod</strong>','required');
            validation_rules('mail_state','<strong>alamat negeri</strong>','required');
            validation_rules('mobile_phone_number','<strong>no. telefon bimbit</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon rumah</strong>');

        elseif ($get_details['FORM_TYPE']==2):

            validation_rules('name','<strong>nama syarikat</strong>','required');
            validation_rules('company_registration_number','<strong>no. pendaftaran syarikat</strong>','required');
            validation_rules('address_1','<strong>alamat syarikat</strong>','required');
            validation_rules('address_3','<strong>alamat syarikat</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon syarikat</strong>','required');
            validation_rules('fax_number','<strong>no. faks</strong>');
            validation_rules('director_name','<strong>nama pengarah/pegawai</strong>','required');
            validation_rules('mobile_phone_number','<strong>no. telefon pengarah/pegawai</strong>','required');
            validation_rules('billboard_type','<strong>jenis</strong>','required');

        elseif ($get_details['FORM_TYPE']==3):

            validation_rules('name','<strong>nama syarikat</strong>','required');
            validation_rules('company_registration_number','<strong>no. pendaftaran syarikat</strong>','required');
            validation_rules('address_1','<strong>alamat syarikat</strong>','required');
            validation_rules('address_3','<strong>alamat syarikat</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon syarikat</strong>','required');
            validation_rules('fax_number','<strong>no. faks</strong>');
            validation_rules('director_name','<strong>nama pengarah/pegawai</strong>','required');
            validation_rules('mobile_phone_number','<strong>no. telefon pengarah/pegawai</strong>','required');
            validation_rules('area_site','<strong>keluasan</strong>','required');
            validation_rules('area_site_unit','<strong>area_site_unit</strong>','required');

        elseif ($get_details['FORM_TYPE']==4):

            validation_rules('name','<strong>nama permohonan</strong>','required');
            validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required');
            validation_rules('date_of_birth','<strong>tarikh lahir</strong>','required');
            validation_rules('place_of_birth','<strong>tempat lahir</strong>','required');
            validation_rules('address_1','<strong>alamat kediaman</strong>','required');
            validation_rules('address_3','<strong>alamat kediaman</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');
//            validation_rules('residence_information','<strong>maklumat kediaman</strong>','required');
//            validation_rules('position','<strong>jawatan</strong>','required');
//            validation_rules('department_id','<strong>bahagian/unit</strong>','required');
            validation_rules('starting_of_service_date','<strong>tarikh mula berkhidmat</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon rumah</strong>','');
            validation_rules('mobile_phone_number','<strong>no. telefon bimbit</strong>','required');
        endif;


        validation_rules('date_start','<strong>tarikh mula sewaan</strong>','required');
        validation_rules('asset_id','<strong>kod harta</strong>','required');
        validation_rules('date_end','<strong>tarikh tamat sewaan</strong>','required');
        validation_rules('rental_duration','<strong>tempoh sewaan & bil</strong>','required|numeric');
        validation_rules('water_bills','<strong>dikenakan bil air</strong>','required');
        validation_rules('estimation_rental_charge','<strong>kadar sewa</strong>','required');
        validation_rules('rental_charge','<strong>jumlah sewaan</strong>','required');
        validation_rules('difference_rental_charge_type','<strong>jenis penambahan / pengurangan caj sewa</strong>','required');
        validation_rules('difference_rental_charge','<strong>penambahan / pengurangan caj sewa</strong>','required');
        validation_rules('waste_management_bills','<strong>dikenakan bil pengurusan sampah</strong>','required');
        if(input_data('waste_management_bills')==1):
            validation_rules('waste_management_charge','<strong>caj pengurusan sampah</strong>','required');
        endif;

        validation_rules('freezer_management_bills','<strong>dikenakan bil simpanan sejuk beku</strong>','required');
        if(input_data('freezer_management_bills')==1):
            validation_rules('freezer_management_charge','<strong>caj simpanan sejuk beku</strong>','required');
        endif;
       // validation_rules('lms_charge');
        validation_rules('collateral_rental');
        validation_rules('fee_agreement');
        validation_rules('bill_type','<strong>jenis bil</strong>','required');

        if(!$this->input->post()):
            templates('/account/v_create_account',$data);
        else:
            if(validation_run()==false):
                templates('/account/v_create_account',$data);
            else:
                $data_type  = $this->m_a_type->get_a_type_details($data['data_details']['TYPE_ID']);

                if($data_type['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL):
                    $data_search_acc['ic_number'] = str_replace(array('-',' '),'',input_data('ic_number'));
                    $get_acc_user = $this->m_acc_user->get_acc_user_details($data_search_acc);
                    if($get_acc_user):
                        $user_id = $get_acc_user['USER_ID'];

                        #update applicant
                        $data_update['name']                = input_data('name');
                        $data_update['ic_number']           = str_replace(array('-',' '),'',input_data('ic_number'));
                        $data_update['date_of_birth']       = input_data('date_of_birth');
                        $data_update['address_1']           = input_data('address_1');
                        $data_update['address_2']           = input_data('address_2');
                        $data_update['address_3']           = input_data('address_3');
                        $data_update['postcode']            = input_data('postcode');
                        $data_update['address_state']       = input_data('address_state');
                        $data_update['mail_address_1']      = input_data('mail_address_1');
                        $data_update['mail_address_2']      = input_data('mail_address_2');
                        $data_update['mail_address_3']      = input_data('mail_address_3');
                        $data_update['mail_postcode']       = input_data('mail_postcode');
                        $data_update['mail_state']          = input_data('mail_state');
                        $data_update['race']                = $data['data_details']['RACE'];
                        $data_update['marital_status']      = $data['data_details']['MARITAL_STATUS'];
                        $data_update['home_phone_number']   = input_data('home_phone_number');
                        $data_update['mobile_phone_number'] = input_data('mobile_phone_number');
                        $data_update['occupation']          = $data['data_details']['OCCUPATION'];
                        $data_update['total_earnings']      = $data['data_details']['TOTAL_EARNINGS'];
                        $data_update['business_experience'] = $data['data_details']['BUSINESS_EXPERIENCE'];
                        $data_update['residence_information'] = $data['data_details']['RESIDENCE_INFORMATION'];
                        $data_update['position']            = $data['data_details']['POSITION'];
                        $data_update['starting_of_service_date'] = $data['data_details']['STARTING_OF_SERVICE_DATE'];
                        $data_update['place_of_birth']      = input_data('place_of_birth');
                        $data_update['department_id']      = $data['data_details']['DEPARTMENT_ID'];
                        $data_update['applicant_type']      = APPLICANT_TYPE_INDIVIDUAL;

                        $this->m_acc_user->update_user($data_update,$user_id);

                    else:
                        #insert applicant
                        $data_insert['name']                = input_data('name');
                        $data_insert['ic_number']           = str_replace(array('-',' '),'',input_data('ic_number'));
                        $data_insert['date_of_birth']       = $data['data_details']['DATE_OF_BIRTH'];
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
                        $data_insert['race']                = $data['data_details']['RACE'];
                        $data_insert['marital_status']      = $data['data_details']['MARITAL_STATUS'];
                        $data_insert['home_phone_number']   = input_data('home_phone_number');
                        $data_insert['mobile_phone_number'] = input_data('mobile_phone_number');
                        $data_insert['occupation']          = $data['data_details']['OCCUPATION'];
                        $data_insert['total_earnings']      = $data['data_details']['TOTAL_EARNINGS'];
                        $data_insert['business_experience'] = $data['data_details']['BUSINESS_EXPERIENCE'];
                        $data_insert['residence_information'] = $data['data_details']['RESIDENCE_INFORMATION'];
                        $data_insert['position']            = $data['data_details']['POSITION'];
                        $data_insert['starting_of_service_date'] = $data['data_details']['STARTING_OF_SERVICE_DATE'];
                        $data_insert['place_of_birth']      = input_data('place_of_birth');
                        $data_insert['department_id']      = $data['data_details']['DEPARTMENT_ID'];
                        $data_insert['applicant_type']      = APPLICANT_TYPE_INDIVIDUAL;

                        $user_id = $this->m_acc_user->insert_user($data_insert);

                    endif;
                elseif ($data_type['APPLICANT_TYPE']==APPLICANT_TYPE_COMPANY):
                    $data_search_acc['company_registration_number'] = str_replace(array('-',' '),'',input_data('company_registration_number'));
                    $get_acc_user = $this->m_acc_user->get_acc_user_details($data_search_acc);
                    if($get_acc_user):
                        $user_id = $get_acc_user['USER_ID'];

                        #update applicant
                        $data_update['name']                        = input_data('name');
                        $data_update['COMPANY_REGISTRATION_NUMBER'] = strtoupper(input_data('company_registration_number'));
                        $data_update['address_1']                   = input_data('address_1');
                        $data_update['address_2']                   = input_data('address_2');
                        $data_update['address_3']                   = input_data('address_3');
                        $data_update['postcode']                    = input_data('postcode');
                        $data_update['address_state']               = input_data('address_state');
                        $data_update['home_phone_number']           = input_data('home_phone_number');
                        $data_update['fax_number']                  = input_data('fax_number');
                        $data_update['director_name']               = input_data('director_name');
                        $data_update['mobile_phone_number']         = input_data('mobile_phone_number');

                        $this->m_acc_user->update_user($data_update,$user_id);

                    else:
                        #insert applicant
                        $data_insert['name']                        = input_data('name');
                        $data_insert['COMPANY_REGISTRATION_NUMBER'] = strtoupper(input_data('company_registration_number'));
                        $data_insert['address_1']                   = input_data('address_1');
                        $data_insert['address_2']                   = input_data('address_2');
                        $data_insert['address_3']                   = input_data('address_3');
                        $data_insert['postcode']                    = input_data('postcode');
                        $data_insert['address_state']               = input_data('address_state');
                        $data_insert['home_phone_number']           = input_data('home_phone_number');
                        $data_insert['fax_number']                  = input_data('fax_number');
                        $data_insert['director_name']               = input_data('director_name');
                        $data_insert['mobile_phone_number']         = input_data('mobile_phone_number');
                        $data_insert['applicant_type']              = APPLICANT_TYPE_COMPANY;

                        $user_id = $this->m_acc_user->insert_user($data_insert);
                    endif;
                endif;

                $data_insert_acc['user_id']         = $user_id;
                $data_insert_acc['type_id']         = $data['data_details']['TYPE_ID'];
                $data_insert_acc['category_id']     = $data['data_details']['CATEGORY_ID'];
                $data_insert_acc['rental_use_id']   = $data['data_details']['RENTAL_USE_ID'];

                #form 2
                $data_insert_acc['cost_billboard']              = $data['data_details']['COST_BILLBOARD'];
                $data_insert_acc['total_income_a_year']         = $data['data_details']['TOTAL_INCOME_A_YEAR'];
                $data_insert_acc['STRUCTURE_TYPE_BILLBOARD']    = $data['data_details']['STRUCTURE_TYPE_BILLBOARD'];
                $data_insert_acc['location_billboard']          = $data['data_details']['LOCATION_BILLBOARD'];
                $data_insert_acc['billboard']                   = $data['data_details']['BILLBOARD'];
                $data_insert_acc['height_billboard']            = $data['data_details']['HEIGHT_BILLBOARD'];
                $data_insert_acc['width_billboard']             = $data['data_details']['WIDTH_BILLBOARD'];
                $data_insert_acc['area_billboard']              = $data['data_details']['AREA_BILLBOARD'];
                #end form 2

                #form 3
                if($get_details['FORM_TYPE']==3):
                    $data_insert_acc['area_site']               = input_data('area_site');
                    $data_insert_acc['area_site_unit']          = input_data('area_site_unit');
                else:
                    $data_insert_acc['area_site']               = $data['data_details']['AREA_SITE'];
                    $data_insert_acc['area_site_unit']          = $data['data_details']['AREA_SITE_UNIT'];
                endif;

                $data_insert_acc['duration_use']            = $data['data_details']['DURATION_USE'];
                $data_insert_acc['duration_use_unit']       = $data['data_details']['DURATION_USE_UNIT'];
                $data_insert_acc['structure_type_building'] = $data['data_details']['STRUCTURE_TYPE_BUILDING'];
                $data_insert_acc['charge_use_in_a_month']   = $data['data_details']['CHARGE_USE_IN_A_MONTH'];
                $data_insert_acc['operation_use']           = $data['data_details']['OPERATION_USE'];
                #end form 3

                #form 4
                #end form 4

                $data_insert_acc['file_number_juu']             = input_data('file_number_juu');
                $data_insert_acc['asset_id']                    = input_data('asset_id');
                $data_insert_acc['date_start']                  = input_data('date_start');
                $data_insert_acc['date_end']                    = input_data('date_end');
                $data_insert_acc['rental_duration']             = input_data('rental_duration');
                $data_insert_acc['water_bills']                 = input_data('water_bills');
                $data_insert_acc['estimation_rental_charge']    = currencyToDouble(input_data('estimation_rental_charge'));
                $data_insert_acc['rental_charge']               = currencyToDouble(input_data('rental_charge'));
                $data_insert_acc['difference_rental_charge']    = currencyToDouble(input_data('difference_rental_charge'));
                $data_insert_acc['difference_rental_charge_type'] = (input_data('difference_rental_charge_type'));

                $data_insert_acc['waste_management_bills']      = input_data('waste_management_bills');
                if(input_data('waste_management_bills')==1):
                    $data_insert_acc['waste_management_charge']     = currencyToDouble(input_data('waste_management_charge'));
                else:
                    $data_insert_acc['waste_management_charge']     = 0.00;
                endif;

                $data_insert_acc['freezer_management_bills']     = input_data('freezer_management_bills');
                if(input_data('freezer_management_bills')==1):
                    $data_insert_acc['freezer_management_charge']    = currencyToDouble(input_data('freezer_management_charge'));
                else:
                    $data_insert_acc['freezer_management_charge']    = 0.00;
                endif;
                //                $data_insert_acc['lms_charge']                  = currencyToDouble(input_data('lms_charge'));
                $data_insert_acc['collateral_rental']           = currencyToDouble(input_data('collateral_rental'));
                $data_insert_acc['collateral_water']            = currencyToDouble(input_data('collateral_water'));
                $data_insert_acc['agreement_fee']               = currencyToDouble(input_data('agreement_fee'));
                $data_insert_acc['bill_type']                   = input_data('bill_type');
                if($data['data_details']['TYPE_ID']==6):
                    $data_insert_acc['billboard_type']          = input_data('billboard_type');
                    if(input_data('billboard_type')==BILLBOARD_TYPE_SUBLESEN):
                        $data_insert_acc['lms_bills']          = 1;
                    else:
                        $data_insert_acc['lms_bills']          = 0;
                    endif;
                endif;

               // $data_insert_acc['TR_ID_CATEGORY']              = $data_category['TR_ID_CATEGORY'];
               // $data_insert_acc['TR_ID_GST']                   = $data_category['TR_ID_GST'];

                $acc_id = $this->m_acc_account->insert_account($data_insert_acc);

                #update total_unit

                load_library('Unit_category_lib');
                $data_rental_status['asset_id']         = input_data('asset_id');
                $data_rental_status['rental_status']    = RENTAL_STATUS_YES;
                $this->unit_category_lib->set_rental_status($data_rental_status);

                if($get_details['FORM_TYPE']==1):
                    $ic_number_pic  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
                    $this->copy_img_to_acc($ic_number_pic,$acc_id,AKAUN_SALINAN_KAD_PENGENALAN);

                    $passport_pic   = $this->m_file_gallery->get_file($id,PERMOHONAN_GAMBAR_PASPORT);
                    $this->copy_img_to_acc($passport_pic,$acc_id,AKAUN_GAMBAR_PASPORT);

                    $ssm_pic        = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM);
                    $this->copy_img_to_acc($ssm_pic,$acc_id,AKAUN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM);

                    $passport_pic   = $this->m_file_gallery->get_file($id,PERMOHONAN_GAMBAR_PASPORT);
                    $this->copy_img_to_acc($passport_pic,$acc_id,AKAUN_GAMBAR_PASPORT);

                elseif ($get_details['FORM_TYPE']==2):
                    $map_info           = $this->m_file_gallery->get_file($id,PERMOHONAN_MAP_INFO);
                    $this->copy_img_to_acc($map_info,$acc_id,AKAUN_MAP_INFO);

                    $location_plan_file = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_LOKASI);
                    $this->copy_img_to_acc($location_plan_file,$acc_id,AKAUN_PELAN_LOKASI);

                    $structure_plan     = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_STRUKTUR);
                    $this->copy_img_to_acc($structure_plan,$acc_id,AKAUN_PELAN_STRUKTUR);

                    $app_ssm_file       = $this->m_file_gallery->get_file($id,PERMOHONAN_CARIAN_SSM);
                    $this->copy_img_to_acc($app_ssm_file,$acc_id,AKAUN_CARIAN_SSM);

                    $cost_validation    = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_PENGESAHAN_KOS_BINAAN);
                    $this->copy_img_to_acc($cost_validation,$acc_id,AKAUN_LAMPIRAN_PENGESAHAN_KOS_BINAAN);
                elseif ($get_details['FORM_TYPE']==3):
                    $letter_application = $this->m_file_gallery->get_file($id,PERMOHONAN_SURAT_PERMOHONAN);
                    $this->copy_img_to_acc($letter_application,$acc_id,AKAUN_SURAT_PERMOHONAN);

                    $ic_number_pic      = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
                    $this->copy_img_to_acc($ic_number_pic,$acc_id,AKAUN_SALINAN_KAD_PENGENALAN);

                    $location_plan      = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_LOKASI);
                    $this->copy_img_to_acc($location_plan,$acc_id,AKAUN_PELAN_LOKASI);

                    $photo_location     = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_PERMOHONAN);
                    $this->copy_img_to_acc($photo_location,$acc_id,AKAUN_LAMPIRAN_PERMOHONAN);

                    $structure_plan     = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_SETUJU_TERIMA);
                    $this->copy_img_to_acc($structure_plan,$acc_id,AKAUN_LAMPIRAN_SETUJU_TERIMA);

                    $ssm                = $this->m_file_gallery->get_file($id,PERMOHONAN_CARIAN_SSM);
                    $this->copy_img_to_acc($ssm,$acc_id,AKAUN_CARIAN_SSM);

                elseif ($get_details['FORM_TYPE']==4):
                    $ic_number_pic  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
                    $this->copy_img_to_acc($ic_number_pic,$acc_id,AKAUN_SALINAN_KAD_PENGENALAN);
                endif;

                load_library('Ref_number_lib');
                $ref_number         = strtoupper($this->ref_number_lib->get_ref(REF_NUMBER_TYPE_ACCOUNT,'','',$acc_id));

                $data_update_acc['account_number'] = $ref_number;
                $update_acc = $this->m_acc_account->update_account($data_update_acc,$acc_id);

                if($data_type['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL):
                    #update dependent
                    $update_dependent['ACC_ID'] = $acc_id;
                    $this->m_acc_dependent->update_dependent_by_applicant_id($update_dependent,$id);
                endif;

                if($update_acc>0):
                    #update application
                    $data_update_application['status_create_account']   = 1;
                    $data_update_application['account_id']              = $acc_id;
                    $data_update_application['dt_create_account']       = timenow();
                    $update_application = $this->m_p_application->update_application($data_update_application,$data['data_details']['APPLICATION_ID']);
                endif;

                #updat_available_unit
                $data_category = $this->m_a_category->get_a_category_details($data['data_details']['CATEGORY_ID']);
                if($data_category['TOTAL_UNIT']>0):
                    $data_update_category['TOTAL_AVAILABLE_UNIT']   = $data_category['TOTAL_UNIT']-1;
                    $this->m_a_category->update_a_category($data_update_category,$data['data_details']['CATEGORY_ID']);
                endif;

                if($update_acc>0 && $update_application>0):

                    $data_audit_trail['log_id']                  = 3001;
                    $data_audit_trail['remark']                  = $this->input->post();
                    $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                    $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                    $data_audit_trail['refer_id']                = $acc_id;
                    $this->audit_trail_lib->add($data_audit_trail);

                    set_notify('notify_msg',TEXT_SAVE_RECORD.'. No. Rujukan : <strong>'.$ref_number.'</strong>');
                    redirect('/account/account_create_list');
                else:
                    set_notify('notify_msg',TEXT_SAVE_UNSUCCESSFUL,2);
                    redirect('/account/create_acc/'.uri_segment(3));
                endif;
            endif;
        endif;
    }

    function create_acc_direct(){
        $data['link_1']     = 'Akaun Sewaan';
        $data['link_2']     = 'Pendaftaran akaun';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $type_id = urlDecrypt(uri_segment(3));
        if(!is_numeric($type_id)):
            return false;
        endif;

        $data['data_asset']     = $this->m_a_asset->get_a_asset_active_by_category_id($type_id);
        $data['data_type']      = $this->m_a_type->get_a_type_details($type_id);
        $data['data_category']  = $this->m_a_category->get_data_category_by_type($type_id);

       // if($get_details['FORM_TYPE']==1):
       //     $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
       //     $data['passport_pic']   = $this->m_file_gallery->get_file($id,PERMOHONAN_GAMBAR_PASPORT);
       //     $data['ssm_pic']        = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM);
       //     $data['passport_pic']   = $this->m_file_gallery->get_file($id,PERMOHONAN_GAMBAR_PASPORT);
       // elseif ($get_details['FORM_TYPE']==2):
       //     $data['map_info']           = $this->m_file_gallery->get_file($id,PERMOHONAN_MAP_INFO);
       //     $data['location_plan_file'] = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_LOKASI);
       //     $data['structure_plan']     = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_STRUKTUR);
       //     $data['app_ssm_file']       = $this->m_file_gallery->get_file($id,PERMOHONAN_CARIAN_SSM);
       //     $data['cost_validation']    = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_PENGESAHAN_KOS_BINAAN);
       // elseif ($get_details['FORM_TYPE']==3):
       //     $data['letter_application'] = $this->m_file_gallery->get_file($id,PERMOHONAN_SURAT_PERMOHONAN);
       //     $data['ic_number_pic']      = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
       //     $data['location_plan']      = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_LOKASI);
       //     $data['photo_location']     = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_PERMOHONAN);
       //     $data['structure_plan']     = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_SETUJU_TERIMA);
       //     $data['ssm']                = $this->m_file_gallery->get_file($id,PERMOHONAN_CARIAN_SSM);
       // elseif ($get_details['FORM_TYPE']==4):
       //     $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
       // endif;

       // if($get_details['FORM_TYPE']==1):

            validation_rules('name','<strong>nama</strong>','required');
            validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required|exact_length[12]');
            validation_rules('address_1','<strong>alamat kediaman</strong>','required');
            validation_rules('address_3','<strong>bandar</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');
//            validation_rules('mail_address_1','<strong>alamat surat menyurat</strong>','required');
//            validation_rules('mail_postcode','<strong>poskod</strong>','required');
//            validation_rules('mail_state','<strong>alamat negeri</strong>','required');
            validation_rules('mobile_phone_number','<strong>no. telefon bimbit</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon rumah</strong>');
            validation_rules('category_id','<strong>kod kategori harta sewaan yang dipohon </strong>','required');

            // elseif ($get_details['FORM_TYPE']==2):

            //    validation_rules('name','<strong>nama syarikat</strong>','required');
            //    validation_rules('company_registration_number','<strong>no. pendaftaran syarikat</strong>','required');
            //    validation_rules('address_1','<strong>alamat syarikat</strong>','required');
            //    validation_rules('postcode','<strong>poskod</strong>','required');
            //    validation_rules('address_state','<strong>alamat negeri</strong>','required');
            //    validation_rules('home_phone_number','<strong>no. telefon syarikat</strong>','required');
            //    validation_rules('fax_number','<strong>no. faks</strong>');
            //    validation_rules('director_name','<strong>nama pengarah/pegawai</strong>','required');
            //    validation_rules('mobile_phone_number','<strong>no. telefon pengarah/pegawai</strong>','required');
            //    validation_rules('billboard_type','<strong>jenis</strong>','required');

            // elseif ($get_details['FORM_TYPE']==3):

            //    validation_rules('name','<strong>nama syarikat</strong>','required');
            //    validation_rules('company_registration_number','<strong>no. pendaftaran syarikat</strong>','required');
            //    validation_rules('address_1','<strong>alamat syarikat</strong>','required');
            //    validation_rules('postcode','<strong>poskod</strong>','required');
            //    validation_rules('address_state','<strong>alamat negeri</strong>','required');
            //    validation_rules('home_phone_number','<strong>no. telefon syarikat</strong>','required');
            //    validation_rules('fax_number','<strong>no. faks</strong>');
            //    validation_rules('director_name','<strong>nama pengarah/pegawai</strong>','required');
            //    validation_rules('mobile_phone_number','<strong>no. telefon pengarah/pegawai</strong>','required');

            // elseif ($get_details['FORM_TYPE']==4):

            //    validation_rules('name','<strong>nama permohonan</strong>','required');
            //    validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required');
            //    validation_rules('date_of_birth','<strong>tarikh lahir</strong>','required');
            //    validation_rules('place_of_birth','<strong>tempat lahir</strong>','required');
            //    validation_rules('address_1','<strong>alamat kediaman</strong>','required');
            //    validation_rules('postcode','<strong>poskod</strong>','required');
            //    validation_rules('address_state','<strong>alamat negeri</strong>','required');
            //    validation_rules('residence_information','<strong>maklumat kediaman</strong>','required');
            //    validation_rules('position','<strong>jawatan</strong>','required');
            //    validation_rules('department_id','<strong>bahagian/unit</strong>','required');
            //    validation_rules('starting_of_service_date','<strong>tarikh mula berkhidmat</strong>','required');
            //    validation_rules('home_phone_number','<strong>no. telefon rumah</strong>','');
            //    validation_rules('mobile_phone_number','<strong>no. telefon bimbit</strong>','required');
            // endif;


        validation_rules('date_start','<strong>tarikh mula sewaan</strong>','required');
       // validation_rules('asset_id','<strong>kod harta</strong>','required');
        validation_rules('date_end','<strong>tarikh tamat sewaan</strong>','required');
        validation_rules('rental_duration','<strong>tempoh sewaan & bil</strong>','required|numeric');
        validation_rules('water_bills','<strong>dikenakan bil air</strong>','required');
        validation_rules('estimation_rental_charge','<strong>kadar sewa</strong>','required');
        validation_rules('rental_charge','<strong>jumlah sewaan</strong>','required');
        validation_rules('difference_rental_charge_type','<strong>jenis penambahan / pengurangan caj sewa</strong>','required');
        validation_rules('difference_rental_charge','<strong>penambahan / pengurangan caj sewa</strong>','required');
        validation_rules('waste_management_bills','<strong>dikenakan bil pengurusan sampah</strong>','required');
        if(input_data('waste_management_bills')==1):
            validation_rules('waste_management_charge','<strong>caj pengurusan sampah</strong>','required');
        endif;

        validation_rules('freezer_management_bills','<strong>dikenakan bil simpanan sejuk beku</strong>','required');
        if(input_data('freezer_management_bills')==1):
            validation_rules('freezer_management_charge','<strong>caj simpanan sejuk beku</strong>','required');
        endif;
       // validation_rules('lms_charge');
        validation_rules('collateral_rental');
        validation_rules('fee_agreement');
        validation_rules('bill_type','<strong>jenis bil</strong>','required');

        if(!$this->input->post()):
            templates('/account/v_create_account_direct',$data);
        else:
            if(validation_run()==false):
                templates('/account/v_create_account_direct',$data);
            else:
                $data_type  = $this->m_a_type->get_a_type_details($type_id);

                if($data_type['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL):
                    $data_search_acc['ic_number'] = str_replace(array('-',' '),'',input_data('ic_number'));
                    $get_acc_user = $this->m_acc_user->get_acc_user_details($data_search_acc);
                    if($get_acc_user):
                        $user_id = $get_acc_user['USER_ID'];

                        #update applicant
                        $data_update['name']                = input_data('name');
                        $data_update['ic_number']           = str_replace(array('-',' '),'',input_data('ic_number'));
                        $data_update['address_1']           = input_data('address_1');
                        $data_update['address_2']           = input_data('address_2');
                        $data_update['address_3']           = input_data('address_3');
                        $data_update['postcode']            = input_data('postcode');
                        $data_update['address_state']       = input_data('address_state');
                        $data_update['home_phone_number']   = input_data('home_phone_number');
                        $data_update['mobile_phone_number'] = input_data('mobile_phone_number');
                        $data_update['applicant_type']      = APPLICANT_TYPE_INDIVIDUAL;

                        $this->m_acc_user->update_user($data_update,$user_id);

                    else:
                        #insert applicant
                        $data_insert['name']                = input_data('name');
                        $data_insert['ic_number']           = str_replace(array('-',' '),'',input_data('ic_number'));
                        $data_insert['address_1']           = input_data('address_1');
                        $data_insert['address_2']           = input_data('address_2');
                        $data_insert['address_3']           = input_data('address_3');
                        $data_insert['postcode']            = input_data('postcode');
                        $data_insert['address_state']       = input_data('address_state');
                        $data_insert['home_phone_number']   = input_data('home_phone_number');
                        $data_insert['mobile_phone_number'] = input_data('mobile_phone_number');
                        $data_insert['applicant_type']      = APPLICANT_TYPE_INDIVIDUAL;

                        $user_id = $this->m_acc_user->insert_user($data_insert);

                    endif;
                elseif ($data_type['APPLICANT_TYPE']==APPLICANT_TYPE_COMPANY):
                    $data_search_acc['company_registration_number'] = str_replace(array('-',' '),'',input_data('company_registration_number'));
                    $get_acc_user = $this->m_acc_user->get_acc_user_details($data_search_acc);
                    if($get_acc_user):
                        $user_id = $get_acc_user['USER_ID'];

                        #update applicant
                        $data_update['name']                        = input_data('name');
                        $data_update['COMPANY_REGISTRATION_NUMBER'] = strtoupper(input_data('company_registration_number'));
                        $data_update['address_1']                   = input_data('address_1');
                        $data_update['address_2']                   = input_data('address_2');
                        $data_update['address_3']                   = input_data('address_3');
                        $data_update['postcode']                    = input_data('postcode');
                        $data_update['address_state']               = input_data('address_state');
                        $data_update['home_phone_number']           = input_data('home_phone_number');
                        $data_update['fax_number']                  = input_data('fax_number');
                        $data_update['director_name']               = input_data('director_name');
                        $data_update['mobile_phone_number']         = input_data('mobile_phone_number');

                        $this->m_acc_user->update_user($data_update,$user_id);

                    else:
                        #insert applicant
                        $data_insert['name']                        = input_data('name');
                        $data_insert['COMPANY_REGISTRATION_NUMBER'] = strtoupper(input_data('company_registration_number'));
                        $data_insert['address_1']                   = input_data('address_1');
                        $data_insert['address_2']                   = input_data('address_2');
                        $data_insert['address_3']                   = input_data('address_3');
                        $data_insert['postcode']                    = input_data('postcode');
                        $data_insert['address_state']               = input_data('address_state');
                        $data_insert['home_phone_number']           = input_data('home_phone_number');
                        $data_insert['fax_number']                  = input_data('fax_number');
                        $data_insert['director_name']               = input_data('director_name');
                        $data_insert['mobile_phone_number']         = input_data('mobile_phone_number');
                        $data_insert['applicant_type']              = APPLICANT_TYPE_COMPANY;

                        $user_id = $this->m_acc_user->insert_user($data_insert);
                    endif;
                endif;

                $data_insert_acc['user_id']         = $user_id;
                $data_insert_acc['type_id']         = $type_id;
                $data_insert_acc['category_id']     = input_data('category_id');
                $data_insert_acc['rental_use_id']   = 0;

                $data_insert_acc['file_number_juu']             = input_data('file_number_juu');
                $data_insert_acc['asset_id']                    = input_data('asset_id');
                $data_insert_acc['date_start']                  = input_data('date_start');
                $data_insert_acc['date_end']                    = input_data('date_end');
                $data_insert_acc['rental_duration']             = input_data('rental_duration');
                $data_insert_acc['water_bills']                 = input_data('water_bills');
                $data_insert_acc['estimation_rental_charge']    = currencyToDouble(input_data('estimation_rental_charge'));
                $data_insert_acc['rental_charge']               = currencyToDouble(input_data('rental_charge'));
                $data_insert_acc['difference_rental_charge']    = currencyToDouble(input_data('difference_rental_charge'));
                $data_insert_acc['difference_rental_charge_type'] = (input_data('difference_rental_charge_type'));

                $data_insert_acc['waste_management_bills']      = input_data('waste_management_bills');
                if(input_data('waste_management_bills')==1):
                    $data_insert_acc['waste_management_charge']     = currencyToDouble(input_data('waste_management_charge'));
                else:
                    $data_insert_acc['waste_management_charge']     = 0.00;
                endif;

                $data_insert_acc['freezer_management_bills']     = input_data('freezer_management_bills');
                if(input_data('freezer_management_bills')==1):
                    $data_insert_acc['freezer_management_charge']    = currencyToDouble(input_data('freezer_management_charge'));
                else:
                    $data_insert_acc['freezer_management_charge']    = 0.00;
                endif;
                //                $data_insert_acc['lms_charge']                  = currencyToDouble(input_data('lms_charge'));
                $data_insert_acc['collateral_rental']           = currencyToDouble(input_data('collateral_rental'));
                $data_insert_acc['collateral_water']            = currencyToDouble(input_data('collateral_water'));
                $data_insert_acc['agreement_fee']               = currencyToDouble(input_data('agreement_fee'));
                $data_insert_acc['bill_type']                   = input_data('bill_type');
                if($data['data_details']['TYPE_ID']==6):
                    $data_insert_acc['billboard_type']          = input_data('billboard_type');
                endif;

                $data_category  = $this->m_a_category->get_a_category_details(input_data('category_id'));
               // $data_insert_acc['TR_ID_CATEGORY']              = $data_category['TR_ID_CATEGORY'];
               // $data_insert_acc['TR_ID_GST']                   = $data_category['TR_ID_GST'];

                $acc_id = $this->m_acc_account->insert_account($data_insert_acc);

                #update total_unit

                load_library('Unit_category_lib');
                $data_rental_status['asset_id']         = 0;//input_data('asset_id');
                $data_rental_status['rental_status']    = RENTAL_STATUS_YES;
                $this->unit_category_lib->set_rental_status($data_rental_status);

               // if($get_details['FORM_TYPE']==1):
               //     $ic_number_pic  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
               //     $this->copy_img_to_acc($ic_number_pic,$acc_id,AKAUN_SALINAN_KAD_PENGENALAN);

               //     $passport_pic   = $this->m_file_gallery->get_file($id,PERMOHONAN_GAMBAR_PASPORT);
               //     $this->copy_img_to_acc($passport_pic,$acc_id,AKAUN_GAMBAR_PASPORT);

               //     $ssm_pic        = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM);
               //     $this->copy_img_to_acc($ssm_pic,$acc_id,AKAUN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM);

               //     $passport_pic   = $this->m_file_gallery->get_file($id,PERMOHONAN_GAMBAR_PASPORT);
               //     $this->copy_img_to_acc($passport_pic,$acc_id,AKAUN_GAMBAR_PASPORT);

               // elseif ($get_details['FORM_TYPE']==2):
               //     $map_info           = $this->m_file_gallery->get_file($id,PERMOHONAN_MAP_INFO);
               //     $this->copy_img_to_acc($map_info,$acc_id,AKAUN_MAP_INFO);

               //     $location_plan_file = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_LOKASI);
               //     $this->copy_img_to_acc($location_plan_file,$acc_id,AKAUN_PELAN_LOKASI);

               //     $structure_plan     = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_STRUKTUR);
               //     $this->copy_img_to_acc($structure_plan,$acc_id,AKAUN_PELAN_STRUKTUR);

               //     $app_ssm_file       = $this->m_file_gallery->get_file($id,PERMOHONAN_CARIAN_SSM);
               //     $this->copy_img_to_acc($app_ssm_file,$acc_id,AKAUN_CARIAN_SSM);

               //     $cost_validation    = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_PENGESAHAN_KOS_BINAAN);
               //     $this->copy_img_to_acc($cost_validation,$acc_id,AKAUN_LAMPIRAN_PENGESAHAN_KOS_BINAAN);
               // elseif ($get_details['FORM_TYPE']==3):
               //     $letter_application = $this->m_file_gallery->get_file($id,PERMOHONAN_SURAT_PERMOHONAN);
               //     $this->copy_img_to_acc($letter_application,$acc_id,AKAUN_SURAT_PERMOHONAN);

               //     $ic_number_pic      = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
               //     $this->copy_img_to_acc($ic_number_pic,$acc_id,AKAUN_SALINAN_KAD_PENGENALAN);

               //     $location_plan      = $this->m_file_gallery->get_file($id,PERMOHONAN_PELAN_LOKASI);
               //     $this->copy_img_to_acc($location_plan,$acc_id,AKAUN_PELAN_LOKASI);

               //     $photo_location     = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_PERMOHONAN);
               //     $this->copy_img_to_acc($photo_location,$acc_id,AKAUN_LAMPIRAN_PERMOHONAN);

               //     $structure_plan     = $this->m_file_gallery->get_file($id,PERMOHONAN_LAMPIRAN_SETUJU_TERIMA);
               //     $this->copy_img_to_acc($structure_plan,$acc_id,AKAUN_LAMPIRAN_SETUJU_TERIMA);

               //     $ssm                = $this->m_file_gallery->get_file($id,PERMOHONAN_CARIAN_SSM);
               //     $this->copy_img_to_acc($ssm,$acc_id,AKAUN_CARIAN_SSM);

               // elseif ($get_details['FORM_TYPE']==4):
               //     $ic_number_pic  = $this->m_file_gallery->get_file($id,PERMOHONAN_SALINAN_KAD_PENGENALAN);
               //     $this->copy_img_to_acc($ic_number_pic,$acc_id,AKAUN_SALINAN_KAD_PENGENALAN);
               // endif;

                load_library('Ref_number_lib');
                $ref_number         = strtoupper($this->ref_number_lib->get_ref(REF_NUMBER_TYPE_ACCOUNT));

                $data_update_acc['account_number'] = $ref_number;
                $update_acc = $this->m_acc_account->update_account($data_update_acc,$acc_id);

               // pre($update_acc);

               // if($data_type['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL):
               //     #update dependent
               //     $update_dependent['ACC_ID'] = $acc_id;
               //     $this->m_acc_dependent->update_dependent_by_applicant_id($update_dependent,$id);
               // endif;

                if($update_acc>0):
                    #update application
                    $data_update_application['status_create_account']   = 1;
                    $data_update_application['account_id']              = $acc_id;
                    $data_update_application['dt_create_account']       = timenow();
                    $update_application = $this->m_p_application->update_application($data_update_application,$data['data_details']['APPLICATION_ID']);
                endif;

                if($update_acc>0):

                    $data_audit_trail['log_id']                  = 3001;
                    $data_audit_trail['remark']                  = $this->input->post();
                    $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                    $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                    $data_audit_trail['refer_id']                = $acc_id;
                    $this->audit_trail_lib->add($data_audit_trail);

                    set_notify('notify_msg',TEXT_SAVE_RECORD.'. No. Rujukan : <strong>'.$ref_number.'</strong>');
                    redirect('/account/account_create_list');
                else:
                    set_notify('notify_msg',TEXT_SAVE_UNSUCCESSFUL,2);
                    redirect('/account/create_acc_direct/'.uri_segment(3));
                endif;
            endif;
        endif;
    }

    private function copy_img_to_acc($data_copy,$acc_id,$file_type){
        if($data_copy):
            $data_insert['FILENAME']    = $data_copy['FILENAME'];
            $data_insert['EXTENSION']   = $data_copy['EXTENSION'];
            $data_insert['PATH']        = $data_copy['PATH'];
            $data_insert['REF_ID']      = $acc_id;
            $data_insert['FILE_TYPE']   = $file_type;
            $data_insert['MODULE_TYPE'] = FILE_MODULE_TYPE_ACCOUNT;
           // $data_insert['DT_ADDED']    = timenow();

            $id = $this->m_file_gallery->insert($data_insert);

            if(!empty($id)):
                if (!file_exists(FILE_ACCOUNT.$acc_id.'/')):
                    mkdir(FILE_ACCOUNT.$acc_id.'/', 0777, true);
                endif;

                copy($data_copy['PATH'].$data_copy['FILENAME'], FILE_ACCOUNT.$acc_id.'/'.$data_copy['FILENAME']);

            endif;
        endif;
    }

    function update_acc(){
        $this->auth->restrict_access($this->curuser,array(5004));

        $data['link_1']     = 'Akaun Sewaan';
        $data['link_2']     = 'Kemaskini akaun';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $get_details = $this->m_acc_account->get_account_details($id);
        if(!$get_details):
            return false;
        endif;


       // if($get_details['STATUS_CREATE_ACCOUNT']>STATUS_CREATE_ACCOUNT_NO):
       //     return false;
       // endif;
        if($get_details['FORM_TYPE']==0):
            $get_details['FORM_TYPE'] = 1;
        endif;

        $data['data_details']   = $get_details;
        $data['data_asset']     = $this->m_a_asset->get_a_asset_active_by_category_id($get_details['CATEGORY_ID']);
        $data['dependent']      = $this->m_acc_dependent->get_dependent_by_acc_id($get_details['ACCOUNT_ID']);
        $data['department']     = $this->m_dept->get_dept_details($get_details['DEPARTMENT_ID']);

        if($get_details['FORM_TYPE']==1):
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,AKAUN_SALINAN_KAD_PENGENALAN);
            $data['passport_pic']   = $this->m_file_gallery->get_file($id,AKAUN_GAMBAR_PASPORT);
            $data['ssm_pic']        = $this->m_file_gallery->get_file($id,AKAUN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM);
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,AKAUN_SALINAN_KAD_PENGENALAN);
            $data['passport_pic']   = $this->m_file_gallery->get_file($id,AKAUN_GAMBAR_PASPORT);
        elseif ($get_details['FORM_TYPE']==2):
            $data['map_info']           = $this->m_file_gallery->get_file($id,AKAUN_MAP_INFO);
            $data['location_plan_file'] = $this->m_file_gallery->get_file($id,AKAUN_PELAN_LOKASI);
            $data['structure_plan']     = $this->m_file_gallery->get_file($id,AKAUN_PELAN_STRUKTUR);
            $data['app_ssm_file']       = $this->m_file_gallery->get_file($id,AKAUN_CARIAN_SSM);
            $data['cost_validation']    = $this->m_file_gallery->get_file($id,AKAUN_LAMPIRAN_PENGESAHAN_KOS_BINAAN);
        elseif ($get_details['FORM_TYPE']==3):
            $data['letter_application'] = $this->m_file_gallery->get_file($id,AKAUN_SURAT_PERMOHONAN);
            $data['ic_number_pic']      = $this->m_file_gallery->get_file($id,AKAUN_SALINAN_KAD_PENGENALAN);
            $data['location_plan']      = $this->m_file_gallery->get_file($id,AKAUN_PELAN_LOKASI);
            $data['photo_location']     = $this->m_file_gallery->get_file($id,AKAUN_LAMPIRAN_PERMOHONAN);
            $data['structure_plan']     = $this->m_file_gallery->get_file($id,AKAUN_LAMPIRAN_SETUJU_TERIMA);
            $data['ssm']                = $this->m_file_gallery->get_file($id,AKAUN_CARIAN_SSM);
        elseif ($get_details['FORM_TYPE']==4):
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,AKAUN_SALINAN_KAD_PENGENALAN);
        endif;

        if($get_details['FORM_TYPE']==1):

            validation_rules('name','<strong>nama</strong>','required');
            validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required');

            validation_rules('address_1','<strong>alamat kediaman</strong>','required');
            validation_rules('address_3','<strong>bandar</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');
            validation_rules('mail_address_1','<strong>alamat surat menyurat</strong>','required');
            validation_rules('mail_postcode','<strong>poskod</strong>','required');
            validation_rules('mail_state','<strong>alamat negeri</strong>','required');

            validation_rules('mobile_phone_number','<strong>no. telefon bimbit</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon rumah</strong>');

        elseif ($get_details['FORM_TYPE']==2):

            validation_rules('name','<strong>nama syarikat</strong>','required');
            validation_rules('company_registration_number','<strong>no. pendaftaran syarikat</strong>','required');

//            validation_rules('address','<strong>alamat syarikat</strong>','required');
            validation_rules('address_1','<strong>alamat syarikat</strong>','required');
            validation_rules('address_3','<strong>bandar</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');

            validation_rules('home_phone_number','<strong>no. telefon syarikat</strong>','required');
            validation_rules('fax_number','<strong>no. faks</strong>');
            validation_rules('director_name','<strong>nama pengarah/pegawai</strong>','required');
            validation_rules('mobile_phone_number','<strong>no. telefon pengarah/pegawai</strong>','required');
            validation_rules('billboard_type','<strong>jenis</strong>','required');

            if(input_data('billboard_type')==BILLBOARD_TYPE_SUBLESEN):
                validation_rules('amount_lms','<strong>amaun LMS</strong>','required');
            endif;

        elseif ($get_details['FORM_TYPE']==3):

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

        elseif ($get_details['FORM_TYPE']==4):

            validation_rules('name','<strong>nama permohonan</strong>','required');
            validation_rules('ic_number','<strong>no. kad pengenalan</strong>','required');
            validation_rules('date_of_birth','<strong>tarikh lahir</strong>','required');
            validation_rules('place_of_birth','<strong>tempat lahir</strong>','required');

            validation_rules('address_1','<strong>alamat syarikat</strong>','required');
            validation_rules('address_3','<strong>bandar</strong>','required');
            validation_rules('postcode','<strong>poskod</strong>','required');
            validation_rules('address_state','<strong>alamat negeri</strong>','required');

           // validation_rules('residence_information','<strong>maklumat kediaman</strong>','required');
           // validation_rules('position','<strong>jawatan</strong>','required');
           // validation_rules('department_id','<strong>bahagian/unit</strong>','required');
            validation_rules('starting_of_service_date','<strong>tarikh mula berkhidmat</strong>','required');
            validation_rules('home_phone_number','<strong>no. telefon rumah</strong>','');
            validation_rules('mobile_phone_number','<strong>no. telefon bimbit</strong>','required');
        endif;

        validation_rules('date_start','<strong>tarikh mula sewaan</strong>','required');
        validation_rules('asset_id','<strong>kod harta</strong>','required');
        validation_rules('date_end','<strong>tarikh tamat sewaan</strong>','required');
        validation_rules('rental_duration','<strong>tempoh sewaan & bil</strong>','required|numeric');
        validation_rules('water_bills','<strong>dikenakan bil air</strong>','required');
        validation_rules('waste_management_bills','<strong>dikenakan bil pengurusan sampah</strong>','required');
        validation_rules('estimation_rental_charge','<strong>kadar sewa</strong>','required');
        validation_rules('rental_charge','<strong>jumlah sewaan</strong>','required');
        validation_rules('difference_rental_charge_type','<strong>jenis penambahan / pengurangan caj sewa</strong>','required');
        validation_rules('difference_rental_charge','<strong>penambahan / pengurangan caj sewa</strong>','required');
        if(input_data('waste_management_bills')==1):
            validation_rules('waste_management_charge','<strong>caj pengurusan sampah</strong>','required');
        endif;
        validation_rules('freezer_management_bills','<strong>dikenakan bil simpanan sejuk beku</strong>','required');
        if(input_data('freezer_management_bills')==1):
            validation_rules('freezer_management_charge','<strong>caj simpanan sejuk beku</strong>','required');
        endif;
       // validation_rules('lms_charge');
        validation_rules('collateral_rental');
        validation_rules('fee_agreement');
       // validation_rules('bill_type','<strong>jenis bil</strong>','required');
        validation_rules('status_acc','<strong>status akaun</strong>','required');
        validation_rules('notice_level','<strong>notice level</strong>','required');

        if(validation_run()==false):
            templates('/account/v_update_account',$data);
        else:
            $data_type  = $this->m_a_type->get_a_type_details($data['data_details']['TYPE_ID']);

            if($data_type['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL):

                #update applicant
                $data_update['name']                = input_data('name');
                $data_update['ic_number']           = str_replace(array('-',' '),'',input_data('ic_number'));
               // $data_update['date_of_birth']       = input_data('date_of_birth');

                $data_update['address_1']           = input_data('address_1');
                $data_update['address_2']           = input_data('address_2');
                $data_update['address_3']           = input_data('address_3');
                $data_update['postcode']            = input_data('postcode');
                $data_update['address_state']       = input_data('address_state');
                $data_update['mail_address_1']      = input_data('mail_address_1');
                $data_update['mail_address_2']      = input_data('mail_address_2');
                $data_update['mail_address_3']      = input_data('mail_address_3');
                $data_update['mail_postcode']       = input_data('mail_postcode');
                $data_update['mail_state']          = input_data('mail_state');

               // $data_update['address']             = input_data('address');
               // $data_update['mail_address']        = input_data('mail_address');
                $data_update['race']                = $data['data_details']['RACE'];
                $data_update['marital_status']      = $data['data_details']['MARITAL_STATUS'];
                $data_update['home_phone_number']   = input_data('home_phone_number');
                $data_update['mobile_phone_number'] = input_data('mobile_phone_number');
                $data_update['occupation']          = $data['data_details']['OCCUPATION'];
                $data_update['total_earnings']      = $data['data_details']['TOTAL_EARNINGS'];
                $data_update['business_experience'] = $data['data_details']['BUSINESS_EXPERIENCE'];
                $data_update['residence_information'] = $data['data_details']['RESIDENCE_INFORMATION'];
                $data_update['position']            = $data['data_details']['POSITION'];
                $data_update['STARTING_OF_SERVICE_DATE'] = $data['data_details']['STARTING_OF_SERVICE_DATE'];
                $data_update['PLACE_OF_BIRTH']      = input_data('place_of_birth');
                $data_update['applicant_type']      = APPLICANT_TYPE_INDIVIDUAL;

                $this->m_acc_user->update_user($data_update,$get_details['USER_ID']);

            elseif ($data_type['APPLICANT_TYPE']==APPLICANT_TYPE_COMPANY):

                #update applicant
                $data_update['name']                        = input_data('name');
                $data_update['COMPANY_REGISTRATION_NUMBER'] = strtoupper(input_data('company_registration_number'));
               // $data_update['address']                     = input_data('address');
                $data_update['address_1']                   = input_data('address_1');
                $data_update['address_2']                   = input_data('address_2');
                $data_update['address_3']                   = input_data('address_3');
                $data_update['postcode']                    = input_data('postcode');
                $data_update['address_state']               = input_data('address_state');
                $data_update['home_phone_number']           = input_data('home_phone_number');
                $data_update['fax_number']                  = input_data('fax_number');
                $data_update['director_name']               = input_data('director_name');
                $data_update['mobile_phone_number']         = input_data('mobile_phone_number');

                $this->m_acc_user->update_user($data_update,$get_details['USER_ID']);

            endif;

//            $data_update_acc['user_id']         = $user_id;
            $data_update_acc['notice_level']             = input_data('notice_level');

            $data_update_acc['type_id']         = $data['data_details']['TYPE_ID'];
            $data_update_acc['category_id']     = $data['data_details']['CATEGORY_ID'];
            $data_update_acc['rental_use_id']   = $data['data_details']['RENTAL_USE_ID'];

            #form 2
            $data_update_acc['cost_billboard']              = $data['data_details']['COST_BILLBOARD'];
            $data_update_acc['total_income_a_year']         = $data['data_details']['TOTAL_INCOME_A_YEAR'];
            $data_update_acc['STRUCTURE_TYPE_BILLBOARD']    = $data['data_details']['STRUCTURE_TYPE_BILLBOARD'];
            $data_update_acc['location_billboard']          = $data['data_details']['LOCATION_BILLBOARD'];
            $data_update_acc['billboard']                   = $data['data_details']['BILLBOARD'];
            $data_update_acc['height_billboard']            = $data['data_details']['HEIGHT_BILLBOARD'];
            $data_update_acc['width_billboard']             = $data['data_details']['WIDTH_BILLBOARD'];
            $data_update_acc['area_billboard']              = $data['data_details']['AREA_BILLBOARD'];
            #end form 2

            #form 3
            $data_update_acc['area_site']               = $data['data_details']['AREA_SITE'];
            $data_update_acc['area_site_unit']          = $data['data_details']['AREA_SITE_UNIT'];
            $data_update_acc['duration_use']            = $data['data_details']['DURATION_USE'];
            $data_update_acc['duration_use_unit']       = $data['data_details']['DURATION_USE_UNIT'];
            $data_update_acc['structure_type_building'] = $data['data_details']['STRUCTURE_TYPE_BUILDING'];
            $data_update_acc['charge_use_in_a_month']   = $data['data_details']['CHARGE_USE_IN_A_MONTH'];
            $data_update_acc['operation_use']           = $data['data_details']['OPERATION_USE'];
            #end form 3

            #form 4
            #end form 4

            $data_update_acc['file_number_juu']             = input_data('file_number_juu');
            $data_update_acc['asset_id']                    = input_data('asset_id');
            $data_update_acc['date_start']                  = input_data('date_start');
            $data_update_acc['date_end']                    = input_data('date_end');
            $data_update_acc['rental_duration']             = input_data('rental_duration');
            $data_update_acc['water_bills']                 = input_data('water_bills');
            $data_update_acc['estimation_rental_charge']    = currencyToDouble(input_data('estimation_rental_charge'));
            $data_update_acc['rental_charge']               = currencyToDouble(input_data('rental_charge'));
            $data_update_acc['difference_rental_charge']    = currencyToDouble(input_data('difference_rental_charge'));
            $data_update_acc['difference_rental_charge_type'] = (input_data('difference_rental_charge_type'));
            $data_update_acc['waste_management_bills']      = input_data('waste_management_bills');
            if(input_data('waste_management_bills')==1):
                $data_update_acc['waste_management_charge']     = currencyToDouble(input_data('waste_management_charge'));
            else:
                $data_update_acc['waste_management_charge']     = 0.00;
            endif;

            $data_update_acc['freezer_management_bills']     = input_data('freezer_management_bills');
            if(input_data('freezer_management_bills')==1):
                $data_update_acc['freezer_management_charge']    = currencyToDouble(input_data('freezer_management_charge'));
            else:
                $data_update_acc['freezer_management_charge']    = 0.00;
            endif;
           // $data_update_acc['lms_charge']                  = currencyToDouble(input_data('lms_charge'));
            $data_update_acc['collateral_rental']           = currencyToDouble(input_data('collateral_rental'));
            $data_update_acc['collateral_water']            = currencyToDouble(input_data('collateral_water'));
            $data_update_acc['agreement_fee']               = currencyToDouble(input_data('agreement_fee'));
            $data_update_acc['bill_type']                   = $get_details['BILL_TYPE'];
            $data_update_acc['status_acc']                  = input_data('status_acc');
            if(input_data('status_acc')==STATUS_ACCOUNT_ACTIVE):
                $data_update_acc['status_bill'] = STATUS_BILL_ACTIVE;
            endif;

            if($data['data_details']['TYPE_ID']==6):
                $data_update_acc['billboard_type']          = input_data('billboard_type');
                if(input_data('billboard_type')==BILLBOARD_TYPE_SUBLESEN):
                    $data_update_acc['lms_bills']          = 1;
                    $data_update_acc['LMS_CHARGE']         = currencyToDouble(input_data('amount_lms'));
                else:
                    $data_update_acc['lms_bills']          = 0;
                    $data_update_acc['LMS_CHARGE']         = 0;
                endif;
            endif;

//            pre($data_update_acc);
//            exit;
            $acc_status = $this->m_acc_account->update_account($data_update_acc,$id);

            if($acc_status):
                $data_audit_trail['log_id']                  = 3002;
                $data_audit_trail['remark']                  = $this->input->post();
                $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                $data_audit_trail['refer_id']                = $id;
                $this->audit_trail_lib->add($data_audit_trail);

                set_notify('notify_msg',TEXT_UPDATE_RECORD);
                redirect('/account/update_acc/'.uri_segment(3));
            else:
                set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL,2);
                redirect('/account/create_acc/'.uri_segment(3));
            endif;
        endif;
    }

    function account_list(){
        $this->auth->restrict_access($this->curuser,array(5003));

        $data['link_1']     = 'Akaun sewaan';
        $data['link_2']     = 'Senarai akaun';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai akaun';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_acc');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_acc',$post);
            if(!isset($post['almost_expired'])):
                $post['almost_expired'] = '';
            endif;
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['account_number']  = '';
                $data_search['type_id']         = '';
                $data_search['category_id']     = '';
                $data_search['almost_expired']  = '';
            endif;
        endif;

        $data['data_search']    = $data_search;
        $data['data_type']      = $this->m_a_type->get_a_type();
        $data['data_category']  = $this->m_a_category->get_a_category_all();

        $total = $this->m_acc_account->count_account($data_search);
        $links          = '/account/account_list';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_acc_account->get_account($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('account/v_list_account',$data);
    }

    function calculate_duration(){
        if(is_ajax()):
            $date_start = input_data('date_start');
            $date_end   = input_data('date_end');

            $month_start  = date('n',strtotime($date_start));
            $year_start   = date('Y',strtotime($date_start));

            $month_end    = date('n',strtotime($date_end));
            $year_end     = date('Y',strtotime($date_end));
           // echo $month_end.'--'.$year_end.'<br><br>';
            for($i=1;$i<=1000;$i++):
               // echo $month_start.'-'.$year_start.'<br>';
                if($month_start==$month_end && $year_start==$year_end):
                    echo $i;
                    break;
                endif;

                $month_start=$month_start+1;
                if($month_start==13):
                    $month_start = 1;
                    $year_start=$year_start+1;
                endif;
            endfor;
        endif;
    }

    function account_create_list(){
        $this->auth->restrict_access($this->curuser,array(5001));

        $data['link_1']     = 'Akaun Sewaan';
        $data['link_2']     = 'Pendaftaran Akaun';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai permohonan';

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_list_register_acc');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_list_register_acc',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['ref_number']  = '';
                $data_search['type_id']     = '';
            endif;
        endif;

        $data['data_search'] = $data_search;
        $data['data_type']   = $this->m_a_type->get_a_type();

        $data_search['status_application']      = STATUS_APPLICATION_APPROVED;
        $data_search['status_agree']            = STATUS_AGREE_ACCEPTED;
        $data_search['status_create_account']   = STATUS_CREATE_ACCOUNT_NO;
        $data['data_search']                = $data_search;

        $search_segment = uri_segment(3);
        $total = $this->m_p_application->count_application($data_search);
        $links          = '/rental_application/application';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_p_application->get_application($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('account/v_list_acc_register',$data);
    }

    function doc_agreement(){
        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        load_library('Generate_word');
        $this->generate_word->word_document($id, DOC_AGREEMENT);
    }

    function doc_signature(){
        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        elseif(is_numeric($id)):
            // $this->m_c_document->get_record_exist($id,'DOC_SIGNATURE');
            echo $this->m_c_document->update_document_printed($id,'DOC_SIGNATURE');
            load_library('Generate_word');

            $this->generate_word->word_document($id, DOC_SIGNATURE);
        endif;
    }

    function doc_quarters(){
        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $subtype = uri_segment(4);
        if(!is_numeric($subtype)):
            return false;
        endif;

        load_library('Generate_word');
        $this->generate_word->word_document($id, DOC_QUARTERS, $subtype);
    }

    function generate_bill(){
        $account_id = 203;

        load_library('Bill_lib');
        $data_bill['account_id'] = $account_id;
        $this->bill_lib->generate_bill($data_bill);
    }

    function time_extension(){
        $this->auth->restrict_access($this->curuser,array(5005));

        $data['link_1']     = 'Akaun';
        $data['link_2']     = 'Lanjutan masa';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $form_session   = input_data('form_session');
        $random_number  = rand(1,1000);
        if(empty($form_session)):
            $form_session       = 'account_extension'.'_'.$this->curuser['USER_ID'].'_'.date('YmdHis').'_'.$random_number;
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

        $data_search['acc_start']       = input_data('ref_number_start');
        $data_search['acc_end']         = input_data('ref_number_end');
        $data_search['type_id']         = input_data('type_id');
        $data_search['status_acc']      = STATUS_ACCOUNT_ACTIVE;
        $data_search['approval']        = true;

        if(empty($data_search['type_id'])):
            $data['data_list']          = array();
        else:
            $data_list                  = $this->m_acc_account->get_account_by_search($data_search);
            $data['data_list']          = $data_list;
        endif;

        $type_submit = input_data('submit');

        if($type_submit=='submit'):
            validation_rules('meeting_number','<strong>no. bilangan mesyuarat penuh majlis/no. rujukan</strong>','required');
            validation_rules('date_meeting','<strong>Tarikh mesyuarat / keputusan</strong>','required');
        elseif ($type_submit=='search'):
            validation_rules('type_id','<strong>jenis sewaan</strong>','required');
        endif;

        if(validation_run()==false):
            templates('/account/v_time_extension',$data);
        else:
            if($type_submit=='submit'):
                $list_acc = input_data('account_id');
                if($list_acc):
                    foreach ($list_acc as $account_id):
                        $status_extension   = input_data('status_'.$account_id);
                        $date_start         = input_data('date_start_'.$account_id);
                        $date_end           = input_data('date_end_'.$account_id);
                        $remark             = input_data('remark_'.$account_id);

                        if($status_extension<>1):
                            $data_update['date_start']      = $date_start;
                            $data_update['date_end']        = $date_end;
                            $data_update['status_bill']     = STATUS_BILL_ACTIVE;
                           // $data_update['account_id']      = $account_id;

                            $update_acc = $this->m_acc_account->update_account($data_update,$account_id);

                            $data_audit_trail['log_id']     = 3003;

                            $data_remark['meeting_number']  = input_data('meeting_number');
                            $data_remark['date_meeting']    = input_data('date_meeting');
                            $data_remark['date_start']      = $date_start;
                            $data_remark['date_end']        = $date_end;
                            $data_remark['remark']          = $remark;

                            $data_audit_trail['remark']     = $data_remark;
                            $data_audit_trail['status']     = PROCESS_STATUS_SUCCEED;
                            $data_audit_trail['user_id']    = $this->curuser['USER_ID'];
                            $data_audit_trail['refer_id']   = $account_id;
                            $this->audit_trail_lib->add($data_audit_trail);
                        endif;
                    endforeach;

                    set_notify('notify_msg',TEXT_UPDATE_RECORD);
                    redirect('/account/time_extension');
                else:
                    set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL);
                    redirect('/account/time_extension');
                endif;
            elseif ($type_submit=='search'):
                templates('/account/v_time_extension',$data);
            endif;

        endif;
    }

    function detail_acc(){
        $data['link_1']     = 'Lanjutan Sewaan';
        $data['link_2']     = 'Maklumat akaun';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $get_details = $this->m_acc_account->get_account_details($id);
        if(!$get_details):
            return false;
        endif;


       // if($get_details['STATUS_CREATE_ACCOUNT']>STATUS_CREATE_ACCOUNT_NO):
       //     return false;
       // endif;

        $data['data_details']   = $get_details;
        $data['data_asset']     = $this->m_a_asset->get_a_asset_active_by_category_id($get_details['CATEGORY_ID']);
        $data['dependent']      = $this->m_acc_dependent->get_dependent_by_acc_id($get_details['ACCOUNT_ID']);
        $data['department']     = $this->m_dept->get_dept_details($get_details['DEPARTMENT_ID']);

        if($get_details['FORM_TYPE']==1):
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,AKAUN_SALINAN_KAD_PENGENALAN);
            $data['passport_pic']   = $this->m_file_gallery->get_file($id,AKAUN_GAMBAR_PASPORT);
            $data['ssm_pic']        = $this->m_file_gallery->get_file($id,AKAUN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM);
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,AKAUN_SALINAN_KAD_PENGENALAN);
            $data['passport_pic']   = $this->m_file_gallery->get_file($id,AKAUN_GAMBAR_PASPORT);
        elseif ($get_details['FORM_TYPE']==2):
            $data['map_info']           = $this->m_file_gallery->get_file($id,AKAUN_MAP_INFO);
            $data['location_plan_file'] = $this->m_file_gallery->get_file($id,AKAUN_PELAN_LOKASI);
            $data['structure_plan']     = $this->m_file_gallery->get_file($id,AKAUN_PELAN_STRUKTUR);
            $data['app_ssm_file']       = $this->m_file_gallery->get_file($id,AKAUN_CARIAN_SSM);
            $data['cost_validation']    = $this->m_file_gallery->get_file($id,AKAUN_LAMPIRAN_PENGESAHAN_KOS_BINAAN);
        elseif ($get_details['FORM_TYPE']==3):
            $data['letter_application'] = $this->m_file_gallery->get_file($id,AKAUN_SURAT_PERMOHONAN);
            $data['ic_number_pic']      = $this->m_file_gallery->get_file($id,AKAUN_SALINAN_KAD_PENGENALAN);
            $data['location_plan']      = $this->m_file_gallery->get_file($id,AKAUN_PELAN_LOKASI);
            $data['photo_location']     = $this->m_file_gallery->get_file($id,AKAUN_LAMPIRAN_PERMOHONAN);
            $data['structure_plan']     = $this->m_file_gallery->get_file($id,AKAUN_LAMPIRAN_SETUJU_TERIMA);
            $data['ssm']                = $this->m_file_gallery->get_file($id,AKAUN_CARIAN_SSM);
        elseif ($get_details['FORM_TYPE']==4):
            $data['ic_number_pic']  = $this->m_file_gallery->get_file($id,AKAUN_SALINAN_KAD_PENGENALAN);
        endif;

        templates('/account/v_update_account',$data);
    }

    function kuarters_list(){
        $this->auth->restrict_access($this->curuser,array(5003));

        $data['link_1']     = 'Akaun sewaan';
        $data['link_2']     = 'Senarai laporan kerosakan kuarters';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai laporan kerosakan kuarters';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_acc_kuarters');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_acc_kuarters',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['search']  = '';
            endif;
        endif;

        $data['data_search']    = $data_search;

        $total = $this->m_acc_kuarters_defect->count_account_defect($data_search);
        $links          = '/account/kuarters_list';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_acc_kuarters_defect->get_account_defect($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('account/acc_defect/v_list_account_defect',$data);
    }

    function kuarters_add(){
       // $this->auth->restrict_access($this->curuser,array(3014));

        $data['link_1']     = 'Akaun sewaan';
        $data['link_2']     = '<a href="/account/kuarters_list">Senarai laporan kerosakan kuarters</a>';
        $data['link_3']     = 'Kerosakan Kuarters';
        $data['pagetitle']  = '';

        $data['kuarters']   = $this->m_acc_account->get_account_kuarters();

        validation_rules('date_added','<strong>tarikh</strong>','required');
        validation_rules('acc_number','<strong>no. akaun sewaan</strong>','required');

        if(validation_run()==false):
            templates('/account/acc_defect/v_acc_defect_add',$data);
        else:
            $id = input_data('acc_number').strtotime(timenow()).$this->curuser['USER_ID'];
            $data_insert_main['ACC_KUARTERS_DEFECT_ID'] = $id;
            $data_insert_main['account_id']             = input_data('acc_number');
            $data_insert_main['date_added']             = input_data('date_added');

            $this->m_acc_kuarters_defect->insert_kuarters_defect($data_insert_main);

            $data_info      = input_data('info[]');
            $data_category  = input_data('category[]');
            if($data_info):
                $i = 0;
                foreach (input_data('info[]') as $row):
                    if($row || $_FILES['upload_name_'.$i] || $data_category[$i]):
                        $path_image = '';
                        $file_name  = '';
                        $folder_ext = date('YmdHis');
                        if($_FILES['upload_name_'.$i]['name']):
                            if (!file_exists("file_upload/kuarters/".$folder_ext."/")):
                                mkdir("file_upload/kuarters/".$folder_ext."/", 0777, true);
                            endif;

                            $config['upload_path'] 	    = "./file_upload/kuarters/".$folder_ext."/" ;
                            $config['allowed_types']    = '*';
                            $config['max_size']         = '15120';
                            $config['max_width']        = '4000';
                            $config['max_height']       = '4000';
                            $config['encrypt_name']	    = false;
                            $config['overwrite']        = false;
                           // $config['file_name']        = $_FILES['upload_name_'.$i]['name'].'_'.date('YmdHis');
                           // $filename = $data_file_ext['name'][$i];
                           // $ext = pathinfo($filename,PATHINFO_EXTENSION);

                            $this->load->library('upload', $config);

                            $result_upload = $this->upload->do_upload('upload_name_'.$i);

                            if (!$result_upload):
                                $error = $this->upload->display_errors();
                                $data['error_remark']   =  $error;
                            else:
                                $info_upload = $this->upload->data();
                                $file_name  = $info_upload['file_name'];
                                $path_image = '/file_upload/kuarters/'.$folder_ext.'/'.$file_name;
                            endif;
                        endif;

                        $id_list = input_data('acc_number').strtotime(timenow()).$this->curuser['USER_ID'].$i;
                        $data_insert_list['ID_LIST']                = $id_list;
                        $data_insert_list['ACC_KUARTERS_DEFECT_ID'] = $id;
                        $data_insert_list['INFO']                   = $row;
                        $data_insert_list['CATEGORY']               = $data_category[$i];
                        $data_insert_list['ATTACHMENT_FILE']        = $path_image;
                        $data_insert_list['FILE_NAME']              = $file_name;

                        $this->m_acc_kuarters_defect_list->insert_kuarters_defect_list($data_insert_list);
                    endif;
                    $i = $i+1;
                endforeach;
            endif;
            set_notify('notify_msg',TEXT_SAVE_RECORD);
            redirect('/account/kuarters_add/');
        endif;
    }

    function kuarters_detais(){
        $data['link_1']     = 'Akaun sewaan';
        $data['link_2']     = '<a href="/account/kuarters_list">Senarai laporan kerosakan kuarters</a>';
        $data['link_3']     = 'Terperinci Kerosakan Kuarters';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $get_details = $this->m_acc_kuarters_defect->get_account_defect_by_id($id);
        if(!$get_details):
            return false;
        endif;

        $get_list               = $this->m_acc_kuarters_defect_list->get_list_defect($get_details['ACC_KUARTERS_DEFECT_ID']);
        $data['get_details']    = $get_details;
        $data['get_list']       = $get_list;

       // pre($get_details);

        validation_rules('info[]','<strong>tarikh</strong>','required');

        if(validation_run()==false):
            templates('/account/acc_defect/v_acc_defect_details',$data);
        else:
            $id = $get_details['ACC_KUARTERS_DEFECT_ID'];

            $data_info      = input_data('info[]');
            $data_category  = input_data('category[]');
            if($data_info):
                $i = 0;
                foreach (input_data('info[]') as $row):
                    $new_id_list = count($get_list)+1+$i;
                    if($row || $_FILES['upload_name_'.$i] || $data_category[$i]):
                        $path_image = '';
                        $file_name  = '';
                        $folder_ext = date('Ymdh');
                        if($_FILES['upload_name_'.$i]['name']):
                            if (!file_exists("file_upload/kuarters/".$folder_ext."/")):
                                mkdir("file_upload/kuarters/".$folder_ext."/", 0777, true);
                            endif;

                            $config['upload_path'] 	    = "./file_upload/kuarters/".$folder_ext."/" ;
                            $config['allowed_types']    = '*';
                            $config['max_size']         = '15120';
                            $config['max_width']        = '4000';
                            $config['max_height']       = '4000';
                            $config['encrypt_name']	    = false;
                            $config['overwrite']        = false;
                           // $config['file_name']        = $_FILES['upload_name_'.$i]['name'].'_'.date('YmdHis');
                           // $filename = $data_file_ext['name'][$i];
                           // $ext = pathinfo($filename,PATHINFO_EXTENSION);

                            $this->load->library('upload', $config);

                            $result_upload = $this->upload->do_upload('upload_name_'.$i);

                            if (!$result_upload):
                                $error = $this->upload->display_errors();
                                $data['error_remark']   =  $error;
                            else:
                                $info_upload = $this->upload->data();
                                $file_name  = $info_upload['file_name'];
                                $path_image = '/file_upload/kuarters/'.$folder_ext.'/'.$file_name;
                            endif;
                        endif;

                        $id_list = input_data('acc_number').strtotime(timenow()).$this->curuser['USER_ID'].$new_id_list;
                        $data_insert_list['ID_LIST']                = $id_list;
                        $data_insert_list['ACC_KUARTERS_DEFECT_ID'] = $id;
                        $data_insert_list['INFO']                   = $row;
                        $data_insert_list['CATEGORY']               = $data_category[$i];
                        $data_insert_list['ATTACHMENT_FILE']        = $path_image;
                        $data_insert_list['FILE_NAME']              = $file_name;

                        $this->m_acc_kuarters_defect_list->insert_kuarters_defect_list($data_insert_list);
                    endif;
                    $i = $i+1;
                endforeach;
            endif;
            set_notify('notify_msg',TEXT_SAVE_RECORD);
            redirect('/account/kuarters_detais/'.uri_segment(3));
        endif;
    }

    function delete_kuarters(){
        if(is_ajax()):
            $delete_id = input_data('delete_id');
           // $check_available_user = $this->m_user->check_available_user($delete_id);
           // if($check_available_user):
            $data_update['soft_delete'] = SOFT_DELETE_TRUE;
            $delete = $this->m_acc_kuarters_defect->delete_kuarters($delete_id);
            if($delete):
                $this->m_acc_kuarters_defect_list->delete_item_by_parent_id($delete_id);
                set_notify('user',TEXT_DELETE_RECORD,1);
                echo TEXT_DELETE_RECORD;
            else:
                set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                echo TEXT_DELETE_UNSUCCESSFUL;
            endif;
//            else:
//                set_notify('user','Data tidak boleh dipadam kerana terdapat pengguna menggunakan kumpulan pengguna ini',2);
//            endif;
//            echo 1;
        endif;
    }

    function delete_item(){
        if(is_ajax()):
            $delete_id = input_data('delete_id');
           // $check_available_user = $this->m_user->check_available_user($delete_id);
           // if($check_available_user):
            $data_update['soft_delete'] = SOFT_DELETE_TRUE;
            $delete = $this->m_acc_kuarters_defect_list->delete_item($delete_id);
            if($delete):
                set_notify('user',TEXT_DELETE_RECORD,1);
                echo TEXT_DELETE_RECORD;
            else:
                set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                echo TEXT_DELETE_UNSUCCESSFUL;
            endif;
           // else:
           //     set_notify('user','Data tidak boleh dipadam kerana terdapat pengguna menggunakan kumpulan pengguna ini',2);
           // endif;
           // echo 1;
        endif;
    }
}