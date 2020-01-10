<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

/**
 * Description of Generate_word:
 * generate word document by filling in the variables within the existing word template
 *
 * @author Athirah
 */

//require_once APPPATH.'vendor/.php';
require_once APPPATH.'../vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;

class Generate_word {
    
    private $ci;
    
    public function __construct()
    {
        $this->ci = & get_instance();
//        load_library('Phpword');
        load_model('Application/M_p_application', 'm_p_application');
        load_model('Account/M_acc_account', 'm_acc_account');
        load_model('Interview/M_p_interview_application', 'm_p_interview_application');
        load_model('Interview/M_p_interview', 'm_p_interview');
        load_model('File/M_file_gallery', 'm_file_gallery');
        load_model('Account/M_acc_dependent', 'm_acc_dependent');
    }

    function word_document($id, $doc_type, $doc_sub_type=0,$notice_info = array())
    {
        if($doc_type==DOC_INTERVIEW_RATING):
            $this->generate_interview_rating_form($id);
        elseif($doc_type==DOC_PANEL_REVIEW):
            $this->generate_panel_review_form($id);
        elseif($doc_type==DOC_OFFER_LETTER):
            $this->generate_offer_letter($id);
        elseif($doc_type==DOC_ACCEPTANCE_LETTER):
            $this->generate_acceptance_letter($id);
        elseif($doc_type==DOC_AGREEMENT):
            $this->generate_agreement($id);
        elseif($doc_type==DOC_SIGNATURE):
            $this->generate_signature($id);
        elseif($doc_type==DOC_NOTICE):
            $this->generate_notice($id, $doc_sub_type,$notice_info);
        elseif($doc_type==DOC_QUARTERS):
            $this->generate_quarters_doc($id, $doc_sub_type);
        endif;
    }

    private function generate_interview_rating_form($id)
    {
        $get_details = $this->ci->m_p_application->get_application_details($id);
        $total_child = $this->ci->m_acc_dependent->count_dependent_by_applicant_id($get_details['APPLICANT_ID'],'anak');

        if($get_details['RENTAL_USE_ID']!=RENTAL_USE_OTHERS):
            $rental_use_name = $get_details['RENTAL_USE_NAME'];
        else:
            $rental_use_name = $get_details['RENTAL_USE_REMARK'];
        endif;

        $score_1 = 0;
        $score_2 = 0;
        $score_3 = 0;
        $score_4 = 0;
        $score_5 = 0;
        $ttl     = 0;

        if(born_in_selangor($get_details['IC_NUMBER'])):
            $score_1 = 3;
        else:
            if($get_details['RESIDENCE_DURATION'] >= 5):
                $score_1 = 2;
            else:
                $score_1 = 1;
            endif;
        endif;

        if($get_details['MARITAL_STATUS']==MARITAL_STATUS_SINGLE_PARENT):
            $score_2 = 3;
        elseif($get_details['MARITAL_STATUS']==MARITAL_STATUS_MARRIED):
            if($total_child >= 3):
                $score_2 = 2;
            else:
                $score_2 = 1;
            endif;
        elseif($get_details['MARITAL_STATUS']==MARITAL_STATUS_SINGLE || $get_details['MARITAL_STATUS']==MARITAL_STATUS_OTHERS):
            $score_2 = 1;
        endif;

        if($get_details['OCCUPATION_STATUS']==OCCUPATION_STATUS_UNEMPLOYED):
            $score_3 = 1;
        else:
            $score_3 = 0;
        endif;

        if($get_details['LOCATION_DISTANCE'] <= 5):
            $score_4 = 3;
        else:
            $score_4 = 2;
        endif;

        if($get_details['BUSINESS_EXPERIENCE'] >= 5):
            $score_5 = 5;
        else:
            $score_5 = 3;
        endif;

        $ttl = $score_1 + $score_2 + $score_3 + $score_4;

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'].'/file_download/temuduga/borang_pemarkahan_temuduga.docx');
        
        $templateProcessor->setValue('name',strtoupper($get_details['NAME']));
        $templateProcessor->setValue('ic_number',display_ic_number($get_details['IC_NUMBER']));
        $templateProcessor->setValue('category_name',strtoupper($get_details['CATEGORY_NAME']));
        $templateProcessor->setValue('rental_use_name',strtoupper($rental_use_name));
        $templateProcessor->setValue('score_1',$score_1);
        $templateProcessor->setValue('score_2',$score_2);
        $templateProcessor->setValue('score_3',$score_3);
        $templateProcessor->setValue('score_4',$score_4);
        $templateProcessor->setValue('score_5',$score_5);
        $templateProcessor->setValue('ttl',$ttl);

        $filename = 'Borang Pemarkahan Temuduga - '.$get_details['NAME'].'.docx';
        $templateProcessor->saveAs($filename);

        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        flush();
        readfile($filename);
        unlink($filename);
        exit;
    }

    private function generate_panel_review_form($id)
    {
        $phpWord = new PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Calibri');
        $filename = 'Borang Ulasan Panel.docx';

        $section = $phpWord->addSection(array('marginLeft' => 1000, 'marginRight' => 1000,
                                            'marginTop' => 900, 'marginBottom' => 900));
        $sectionStyle = $section->getStyle();
        $sectionStyle->setOrientation($sectionStyle::ORIENTATION_LANDSCAPE);

        /*
        * Style: Text & Table
        * ----------------------
        */
        $header = array(
            'size' => 14,
            'bold' => true,
        );

        $default_table = array(
            'borderSize' => 6,
            'borderColor' => '000000',
        );

        $result_table = array(
            'spaceAfter' => 0
        );

        $cell_header = array(
            'size' => 11,
            'align' => 'center',
            'valign' => 'center',
            'bold' => true
        );

        $cell = array(
            'size' => 11,
            'spaceAfter' => 0
        );

        $font_default = array(
            'align' => 'left',
            'spaceAfter' => 0
        );

        $font_center = array(
            'align' => 'center',
            'spaceAfter' => 0
        );


        $font_align_left = array(
            'align' => 'left',
            'spaceAfter' => 0
        );
        /*
        * ----------------------
        */

        $get_interview  = $this->ci->m_p_interview->get_interview_by_id($id);
        $get_category   = $this->ci->m_p_interview_application->get_category_by_interview($id);
        $total_category = count($get_category);
        $count = 0;
        foreach($get_category as $category):
            $count++;
            $section->addText('BORANG ULASAN PANEL TEMUDUGA '.strtoupper($category['CATEGORY_NAME']), $header, $font_center);
            $section->addText('TARIKH: '.strtoupper(date_display($get_interview['DATE_INTERVIEW'],'d F Y','malay')), $header, $font_center);
            $section->addText('MASA: ', $header, $font_center);
            $section->addText('TEMPAT: MAJLIS PERBANDARAN KAJANG <w:br/>', $header, $font_center);
            
            $phpWord->addTableStyle('application_list', $default_table);
            $table = $section->addTable('application_list');
            $table->addRow();
            $table->addCell(500)->addText("BIL", $cell_header, $font_center);
            $table->addCell(2000)->addText("NAMA PEMOHON", $cell_header, $font_center);
            $table->addCell(3000)->addText("ALAMAT", $cell_header, $font_center);
            $table->addCell(1700)->addText("NO. K/P", $cell_header, $font_center);
            $table->addCell(1900)->addText("JENIS PERNIAGAAN", $cell_header, $font_center);
            $table->addCell(5500)->addText("ULASAN PANEL", $cell_header, $font_center);

            $get_application = $this->ci->m_p_interview->get_interview_details($id,$category['CATEGORY_ID']);
            $i=0;
            foreach($get_application as $application):
                $i++;
                $table->addRow();
                $table->addCell(500)->addText($i, $cell, $font_center);
                $table->addCell(2000)->addText(strtoupper($application['NAME']), $cell, $font_center);
                $table->addCell(3000)->addText(strtoupper($application['MAIL_ADDRESS_1'].",\n".$application['MAIL_ADDRESS_2']." ".$application['MAIL_ADDRESS_3'].",".$application['MAIL_POSTCODE'].",".$application['MAIL_STATE']), $cell, $font_center);
                // $table->addCell(3000)->addText(strtoupper($application['MAIL_ADDRESS_1'].",<br>".$application['MAIL_ADDRESS_2'].$application['MAIL_ADDRESS_3'].",<br>".$application['MAIL_POSTCODE'].",<br>".$application['MAIL_STATE']), $cell, $font_center);
                $table->addCell(1700)->addText(display_ic_number($application['IC_NUMBER']), $cell, $font_center);
                $table->addCell(1900)->addText(strtoupper($application['RENTAL_USE_NAME']), $cell, $font_center);
                $table->addCell(5500)->addText("<w:br/><w:br/><w:br/><w:br/><w:br/><w:br/>*                       DITERIMA / DITOLAK / SIMPANAN", $cell, $font_align_left);
                if($i % 3 == 0 && $i!=$category['TOTAL_APPLICATION']):
                    $section->addPageBreak();
                    $table = $section->addTable('application_list');
                endif;
            endforeach;

            $section->addText('');

            $phpWord->addTableStyle('result', $result_table);
            $table = $section->addTable('result');
            $table->addRow();
            $table->addCell(3500)->addText("JUMLAH KEKOSONGAN GERAI", $cell,$font_default);
            $table->addCell(400)->addText(" : ", $cell,$font_default);
            $table->addCell(3000)->addText("     UNIT", $cell,$font_default);
            $table->addRow();
            $table->addCell(3500)->addText("JUMLAH GERAI", $cell,$font_default);
            $table->addCell(400)->addText(" : ", $cell,$font_default);
            $table->addCell(3000)->addText("     UNIT", $cell,$font_default);
            $table->addRow();
            $table->addCell(3500)->addText("NO. CALON YANG DIPILIH", $cell,$font_default);
            $table->addCell(400)->addText(" : ", $cell,$font_default);
            $table->addCell(3000)->addText("_______________________________________", $cell,$font_default);
            $table->addRow();
            $table->addCell(3500)->addText("NO. CALON SIMPANAN", $cell,$font_default);
            $table->addCell(400)->addText(" : ", $cell,$font_default);
            $table->addCell(3000)->addText("_______________________________________", $cell,$font_default);

            if($count != $total_category):
                $section->addPageBreak();
            endif;
        endforeach;
         
        $phpWord = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $phpWord->save($filename);

        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        flush();
        readfile($filename);
        unlink($filename);
        exit;
    }

    private function generate_offer_letter($id)
    {
        $get_details = $this->ci->m_p_application->get_application_details($id);

        if($get_details['TYPE_ID']==1):
            $template = 'surat_tawaran_pasar.docx';
        elseif($get_details['TYPE_ID']==2 || $get_details['TYPE_ID']==10):
            $template = 'surat_tawaran_medan_selera.docx';
        elseif($get_details['TYPE_ID']==3):
            $template = 'surat_tawaran_plb.docx';
        elseif($get_details['TYPE_ID']==4):
            $template = 'surat_tawaran_upen.docx';
        elseif($get_details['TYPE_ID']==5):
            $template = 'surat_tawaran_kiosk.docx';
        elseif($get_details['TYPE_ID']==8):
            $template = 'kertas_pertimbangan_permohonan_kuarters.docx';
        else:
            return false;
        endif;

        if($get_details['RENTAL_USE_ID']!=RENTAL_USE_OTHERS):
            $rental_use_name = $get_details['RENTAL_USE_NAME'];
        else:
            $rental_use_name = 'LAIN-LAIN - '.$get_details['RENTAL_USE_REMARK'];
        endif;

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'].'/file_download/tawaran/'.$template);
        
        $address = display_address($get_details['MAIL_ADDRESS_1'],$get_details['MAIL_ADDRESS_2'],$get_details['MAIL_ADDRESS_3'],$get_details['MAIL_POSTCODE'],strtolower($get_details['MAIL_STATE']));
        if(empty($address)):
            $address = display_address($get_details['ADDRESS_1'],$get_details['ADDRESS_2'],$get_details['ADDRESS_3'],$get_details['POSTCODE'],ucwords(strtolower($get_details['STATE'])));
        endif;
        $address = str_replace('<br>', ' ', $address);
//        echo $address;
//        die();
        $templateProcessor->setValue('file_number',$get_details['FILE_NUMBER']);
        $templateProcessor->setValue('letter_date',date_display(timenow(),'d F Y','malay'));
        $templateProcessor->setValue('name',strtoupper($get_details['NAME']));
        $templateProcessor->setValue('category_name',strtoupper($get_details['CATEGORY_NAME']));
        $templateProcessor->setValue('date_start',date_display($get_details['DATE_START'],'d F Y','malay'));
        $templateProcessor->setValue('date_end',date_display($get_details['DATE_END'],'d F Y','malay'));
        $templateProcessor->setValue('asset_name',$get_details['ASSET_NAME']);
        $templateProcessor->setValue('ic_number',display_ic_number($get_details['IC_NUMBER']));
        $templateProcessor->setValue('address',strtoupper($address));
        $templateProcessor->setValue('rental_use_name',strtoupper($rental_use_name));
        $templateProcessor->setValue('rent',num($get_details['RENTAL_FEE']));
        $templateProcessor->setValue('tipping_fee',num($get_details['WASTE_MANAGEMENT_CHARGE']));
        $templateProcessor->setValue('deposit',num($get_details['DEPOSIT_RENTAL']));
        $templateProcessor->setValue('agree',num($get_details['RENTAL_AGREEMENT_COST']));
//
//        //extra info for kuarters
        $templateProcessor->setValue('name_lcase',$get_details['NAME']);
        $templateProcessor->setValue('address_lcase',$address);
        $templateProcessor->setValue('position',$get_details['POSITION']);
        $templateProcessor->setValue('place_of_birth',ucwords($get_details['PLACE_OF_BIRTH']));
        $templateProcessor->setValue('available_unit',$get_details['TOTAL_AVAILABLE_UNIT']);
        $templateProcessor->setValue('date_of_birth',date('d / m / Y',strtotime($get_details['DATE_OF_BIRTH'])));
        if(!empty($get_details['DATE_OF_BIRTH'])):
            $age =  date('Y') - date('Y',strtotime($get_details['DATE_OF_BIRTH']));
        else:
            $age = '';
        endif;
        $templateProcessor->setValue('age',$age);
        $templateProcessor->setValue('marital_status',marital_status($get_details['MARITAL_STATUS']));

        ob_clean();
        if($get_details['FORM_TYPE']==1):
            $filename = 'Surat Tawaran - '.$get_details['NAME'].'.docx';
        elseif($get_details['FORM_TYPE']==4):
            $filename = 'Kertas Pertimbangan Permohonan - '.$get_details['NAME'].'.docx';
        endif;
        $templateProcessor->saveAs($filename);

        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        flush();
        readfile($filename);
        unlink($filename);
        exit;
    }

    private function generate_acceptance_letter($id)
    {
        $get_details = $this->ci->m_p_application->get_application_details($id);

        if($get_details['FORM_TYPE']==1):
            $template = 'surat_setuju_terima.docx';
        elseif($get_details['FORM_TYPE']==4):
            $template = 'surat_setuju_terima_kuarters.docx';
        else:
            return false;
        endif;
        
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'].'/file_download/setuju_terima/'.$template);
        
        $templateProcessor->setValue('name',strtoupper($get_details['NAME']));
        $templateProcessor->setValue('ic_number',display_ic_number($get_details['IC_NUMBER']));
        $templateProcessor->setValue('position',strtoupper($get_details['POSITION']));
        $templateProcessor->setValue('address',strtoupper($get_details['MAIL_ADDRESS']));
        $templateProcessor->setValue('phone_number',display_mobile_number($get_details['MOBILE_PHONE_NUMBER']));

        ob_clean();
        $filename = 'Surat Setuju Terima - '.$get_details['NAME'].'.docx';
        $templateProcessor->saveAs($filename);

        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        flush();
        readfile($filename);
        unlink($filename);
        exit;
    }

    private function generate_agreement($id)
    {
        $get_details = $this->ci->m_acc_account->get_account_details($id);

        if($get_details['FORM_TYPE']==1):
            if($get_details['WASTE_MANAGEMENT_BILLS']==1):
                $template = 'perjanjian_sewaan_caj_pengurusan_sampah.docx';
            else:
                $template = 'perjanjian_sewaan_xcaj_pengurusan_sampah.docx';
            endif;
        elseif($get_details['FORM_TYPE']==2):
            if($get_details['BILLBOARD_TYPE']==1):
                $template = 'perjanjian_sewaan_interim.docx';
            elseif($get_details['BILLBOARD_TYPE']==2):
                $template = 'perjanjian_sewaan_sublesen.docx';
            endif;
        elseif($get_details['FORM_TYPE']==4):
            $template = 'kuarters_-_itp_dan_bantuan_sara_hidup.docx';
        else:
            return false;
        endif;

        // $address = '';
        // if(!empty($get_details['ADDRESS_1'])):
        //     $address = $get_details['ADDRESS_1'];
        //     if(!empty($get_details['ADDRESS_1'])):
        //         $address = $get_details['ADDRESS_1'];
        //     endif;
        // endif;

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'].'/file_download/perjanjian/'.$template);
        
        $templateProcessor->setValue('letter_date',date_display(timenow(),'d F Y','malay'));
        $templateProcessor->setValue('letter_date_hijri',date_display_hijri(timenow()));
        $templateProcessor->setValue('file_number_juu',$get_details['FILE_NUMBER_JUU']);
        $templateProcessor->setValue('name',$get_details['NAME']);
        $templateProcessor->setValue('name_caps',strtoupper($get_details['NAME']));
        $templateProcessor->setValue('director_name',strtoupper($get_details['DIRECTOR_NAME']));
        $templateProcessor->setValue('comp_reg_no',$get_details['COMPANY_REGISTRATION_NUMBER']);
        $templateProcessor->setValue('phone_no',$get_details['HOME_PHONE_NUMBER']);
        $templateProcessor->setValue('fax_no',$get_details['FAX_NUMBER']);
        $templateProcessor->setValue('category_name',$get_details['CATEGORY_NAME']);
        $templateProcessor->setValue('asset_name',$get_details['ASSET_NAME']);
        $templateProcessor->setValue('asset_add',$get_details['ASSET_ADD']);
        $templateProcessor->setValue('ic_number',display_ic_number($get_details['IC_NUMBER']));
        $templateProcessor->setValue('staff_number',display_ic_number($get_details['STAFF_NUMBER']));
        //$templateProcessor->setValue('address',$get_details['MAIL_ADDRESS']);
        $templateProcessor->setValue('address',$get_details['MAIL_ADDRESS_1']);
        $templateProcessor->setValue('address2',$get_details['MAIL_ADDRESS_2']);
        $templateProcessor->setValue('address3',$get_details['MAIL_ADDRESS_3']);
        $templateProcessor->setValue('postcode',$get_details['MAIL_POSTCODE']);
        $templateProcessor->setValue('location_billboard',$get_details['LOCATION_BILLBOARD']);
        $templateProcessor->setValue('area_billboard',$get_details['AREA_BILLBOARD']);
        $templateProcessor->setValue('phone_number',display_mobile_number($get_details['MOBILE_PHONE_NUMBER']));
        $templateProcessor->setValue('rental_duration',$get_details['RENTAL_DURATION']);
        $templateProcessor->setValue('rental_duration_word',convertNumberToWord($get_details['RENTAL_DURATION']));
        $templateProcessor->setValue('date_start',date_display($get_details['DATE_START'],'d F Y','malay'));
        $templateProcessor->setValue('date_end',date_display($get_details['DATE_END'],'d F Y','malay'));
        $templateProcessor->setValue('date_start_month',ucfirst(date_display($get_details['DATE_START'],'F Y','malay')));
        $templateProcessor->setValue('rental_charge',num($get_details['RENTAL_CHARGE']));
        $templateProcessor->setValue('rental_charge_word',convertNumberToWord($get_details['RENTAL_CHARGE']));
        $templateProcessor->setValue('tipping_fee',num($get_details['WASTE_MANAGEMENT_CHARGE']));
        $templateProcessor->setValue('tipping_fee_word',convertNumberToWord($get_details['WASTE_MANAGEMENT_CHARGE']));
        $templateProcessor->setValue('rental_use_name',$get_details['RENTAL_USE_NAME']);
        $templateProcessor->setValue('deposit',num($get_details['COLLATERAL_RENTAL']));
        $templateProcessor->setValue('deposit_word',convertNumberToWord($get_details['COLLATERAL_RENTAL']));

        if($get_details['FORM_TYPE']==1):
            if($get_details['ASSET_ID']>0):
                $get_file    = $this->ci->m_file_gallery->get_file_by_asset_id($get_details['ASSET_ID']);
                $image_path = $_SERVER['DOCUMENT_ROOT'].'/'.$get_file['PATH'].'/'.$get_file['FILENAME'];
                $templateProcessor->setImageValue('asset_image', array(
                  'path'  => $image_path,
                  'width' => 650,
                  'height' => 650,
                  'ratio' => true //px
                ));
            endif;
        endif;

        ob_clean();
        $filename = 'Perjanjian Sewaan - '.$get_details['NAME'].'.docx';
        $templateProcessor->saveAs($filename);

        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        flush();
        readfile($filename);
        unlink($filename);
        exit;
    }

    private function generate_signature($id)
    {
        $get_details = $this->ci->m_acc_account->get_account_details($id);
        $template = 'dokumen tandatangan.docx';

        // $address = '';
        // if(!empty($get_details['ADDRESS_1'])):
        //     $address = $get_details['ADDRESS_1'];
        //     if(!empty($get_details['ADDRESS_1'])):
        //         $address = $get_details['ADDRESS_1'];
        //     endif;
        // endif;
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'].'/file_download/perjanjian/'.$template);

        $templateProcessor->setValue('letter_date',date_display(timenow(),'d F Y','malay'));
        $templateProcessor->setValue('letter_time',date_display(timenow(),'h:i:sa'));
        $templateProcessor->setValue('letter_date_hijri',date_display_hijri(timenow()));
       // $templateProcessor->setValue('file_number_juu',$get_details['FILE_NUMBER_JUU']);
        $templateProcessor->setValue('name',$get_details['NAME']);
        $templateProcessor->setValue('name_caps',strtoupper($get_details['NAME']));
       // $templateProcessor->setValue('director_name',strtoupper($get_details['DIRECTOR_NAME']));
       // $templateProcessor->setValue('comp_reg_no',$get_details['COMPANY_REGISTRATION_NUMBER']);
       // $templateProcessor->setValue('phone_no',$get_details['HOME_PHONE_NUMBER']);
       // $templateProcessor->setValue('fax_no',$get_details['FAX_NUMBER']);
       // $templateProcessor->setValue('category_name',$get_details['CATEGORY_NAME']);
       // $templateProcessor->setValue('asset_name',$get_details['ASSET_NAME']);
        // $templateProcessor->setValue('asset_add',$get_details['ASSET_ADD']);
        $templateProcessor->setValue('ic_number',display_ic_number($get_details['IC_NUMBER']));
        // $templateProcessor->setValue('staff_number',display_ic_number($get_details['STAFF_NUMBER']));
        // $templateProcessor->setValue('address',$get_details['MAIL_ADDRESS_1']);
        // $templateProcessor->setValue('address2',$get_details['MAIL_ADDRESS_2']);
        // $templateProcessor->setValue('address3',$get_details['MAIL_ADDRESS_3']);
        // $templateProcessor->setValue('postcode',$get_details['MAIL_POSTCODE']);
        // $templateProcessor->setValue('location_billboard',$get_details['LOCATION_BILLBOARD']);
        // $templateProcessor->setValue('area_billboard',$get_details['AREA_BILLBOARD']);
        // $templateProcessor->setValue('phone_number',display_mobile_number($get_details['MOBILE_PHONE_NUMBER']));
        // $templateProcessor->setValue('rental_duration',$get_details['RENTAL_DURATION']);
        // $templateProcessor->setValue('rental_duration_word',convertNumberToWord($get_details['RENTAL_DURATION']));
        // $templateProcessor->setValue('date_start',date_display($get_details['DATE_START'],'d F Y','malay'));
        // $templateProcessor->setValue('date_end',date_display($get_details['DATE_END'],'d F Y','malay'));
        // $templateProcessor->setValue('date_start_month',ucfirst(date_display($get_details['DATE_START'],'F Y','malay')));
        // $templateProcessor->setValue('rental_charge',num($get_details['RENTAL_CHARGE']));
        // $templateProcessor->setValue('rental_charge_word',convertNumberToWord($get_details['RENTAL_CHARGE']));
        // $templateProcessor->setValue('tipping_fee',num($get_details['WASTE_MANAGEMENT_CHARGE']));
        // $templateProcessor->setValue('tipping_fee_word',convertNumberToWord($get_details['WASTE_MANAGEMENT_CHARGE']));
        // $templateProcessor->setValue('rental_use_name',$get_details['RENTAL_USE_NAME']);
        // $templateProcessor->setValue('deposit',num($get_details['COLLATERAL_RENTAL']));
        // $templateProcessor->setValue('deposit_word',convertNumberToWord($get_details['COLLATERAL_RENTAL']));

        ob_clean();
        $filename = 'Dokumen Tandatangan - '.$get_details['NAME'].'.docx';
        $templateProcessor->saveAs($filename);
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        flush();
        readfile($filename);
        unlink($filename);
        exit;
    }

    private function generate_notice($id, $notice_level,$notice_info=array())
    {
        $get_details = $this->ci->m_acc_account->get_account_details($id);

        if($notice_level==0):
            $template = 'notis_memulakan_perniagaan.docx';
        elseif($notice_level==NOTICE_LEVEL_1):
            $template = '1.notis_tuntutan_pembayaran_sewaan_-_BORANG_A.docx';
        elseif($notice_level==NOTICE_LEVEL_2):
            $template = '2.notis_tuntutan_pembayaran_sewaan_-_BORANG_B.docx';
        elseif($notice_level==NOTICE_LEVEL_3):
            $template = '3.notis_tuntutan_pembayaran_sewaan_-_BORANG_C.docx';
        elseif($notice_level==NOTICE_LEVEL_4):
            $template = '4.LOD_dan_notis_mahkamah.docx';
        elseif($notice_level==NOTICE_LEVEL_5):
            $template = '5.notis_tarik_balik_-_BORANG_D.docx';
        elseif($notice_level==NOTICE_LEVEL_6):
            $template = '6.tindakan_mahkamah.docx';
        else:
            return false;
        endif;

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'].'/file_download/notis/'.$template);
        
        $templateProcessor->setValue('name',strtoupper($get_details['NAME']));
        $templateProcessor->setValue('address',strtoupper($get_details['MAIL_ADDRESS']));
        $templateProcessor->setValue('ic_number',display_ic_number($get_details['IC_NUMBER']));
        $templateProcessor->setValue('account_number',$get_details['ACCOUNT_NUMBER']);
        $templateProcessor->setValue('letter_date',date_display(timenow(),'d F Y','malay'));
        $templateProcessor->setValue('asset_name',$get_details['ASSET_NAME']);
        $templateProcessor->setValue('category_name',$get_details['CATEGORY_NAME']);
        $templateProcessor->setValue('category_name_caps',strtoupper($get_details['CATEGORY_NAME']));
        $templateProcessor->setValue('current_date',date_display($notice_info['DT_ADDED'],'d F Y','malay'));
        $templateProcessor->setValue('overdue_rent',num($notice_info['SEWAAN']));
        $templateProcessor->setValue('overdue_utility',num($notice_info['AIR']));
        $templateProcessor->setValue('overdue_tipping_fee',num($notice_info['TIPPING']));
        $templateProcessor->setValue('total_overdue',num($notice_info['TOTAL_TUNGGAKAN']));
        $templateProcessor->setValue('total_overdue_lod',num($notice_info['TOTAL_TUNGGAKAN']));

        ob_clean();
        $filename = 'Notis '.notice_level($notice_level).' - '.$get_details['NAME'].'.docx';
        $templateProcessor->saveAs($filename);

        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        flush();
        readfile($filename);
        unlink($filename);
        exit;
    }

    private function generate_quarters_doc($id,$doc_sub_type)
    {
        $get_details = $this->ci->m_acc_account->get_account_details($id);

        if($doc_sub_type==1):
            $template = 'sijil_akuan_masuk_rumah.docx';
        elseif($doc_sub_type==2):
            $template = 'sijil_akuan_keluar_rumah.docx';
        else:
            return false;
        endif;

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'].'/file_download/kuarters/'.$template);
        
        $templateProcessor->setValue('letter_date',date_display(timenow(),'d F Y','malay'));
        $templateProcessor->setValue('file_number_juu',$get_details['FILE_NUMBER_JUU']);
        $templateProcessor->setValue('name',strtoupper($get_details['NAME']));
        $templateProcessor->setValue('position',strtoupper($get_details['POSITION']));
        $templateProcessor->setValue('department',strtoupper($get_details['DEPARTMENT']));
        $templateProcessor->setValue('category_name',$get_details['CATEGORY_NAME']);
        $templateProcessor->setValue('asset_name',$get_details['ASSET_NAME']);
        $templateProcessor->setValue('ic_number',display_ic_number($get_details['IC_NUMBER']));
        $templateProcessor->setValue('staff_number',display_ic_number($get_details['STAFF_NUMBER']));
        $templateProcessor->setValue('address',$get_details['MAIL_ADDRESS']);
        $templateProcessor->setValue('phone_number',display_mobile_number($get_details['MOBILE_PHONE_NUMBER']));
        $templateProcessor->setValue('date_start',date_display($get_details['DATE_START'],'d F Y','malay'));
        $templateProcessor->setValue('date_end',date_display($get_details['DATE_END'],'d F Y','malay'));

        ob_clean();
        if($doc_sub_type==1):
            $filename = 'Sijil Akuan Masuk Rumah - '.$get_details['NAME'].'.docx';
        elseif($doc_sub_type==2):
            $filename = 'Sijil Akuan Keluar Rumah - '.$get_details['NAME'].'.docx';
        endif;
        $templateProcessor->saveAs($filename);

        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        flush();
        readfile($filename);
        unlink($filename);
        exit;
    }
}
