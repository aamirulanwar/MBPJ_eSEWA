<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Juhari
 * Date: 19/10/15
 * Time: 10:10 PM
 */

function clean_phone_number($mobile_number, $default_country = 6) {
    $return_value = $mobile_number;
    if (empty($mobile_number)):
        return '';
    endif;

    $return_value = str_replace(array('+', ' ', '-'), '', $return_value);

    $first_number = substr($return_value, 0, 1);
    if ($first_number == 0) :
        //Set as Malaysia number
        $return_value = "6" . $return_value;
    endif;
    return $return_value;
}

function check_mobile_number($mobile_number) {
    if (isset($mobile_number) && preg_match("/^(\+?6?01)[0-9]\-*[0-9]{7,8}$/", $mobile_number)):
        return true;
    else:
        return false;
    endif;
}

function set_active_menu($menu='', $current='')
{
    if(is_array($current)):
        if(in_array($menu,$current)):
            return 'active';
        endif;
    else:
        if($menu == $current):
            return 'active';
        endif;
    endif;
}

function set_active_dropdown($dropdown='', $current='')
{
    if(is_array($current)):
        if(in_array($dropdown,$current)):
            return 'open';
        endif;
    else:
        if($dropdown == $current):
            return 'open';
        endif;
    endif;
}

function sprintf_format($num,$digit_num=5){
    return sprintf("%'.0".$digit_num."d", $num);
}

function color_arr($seq){
    $data_color = array('#369EAD','#2D9828','#F9941E','#8736CB','#44A3FD','#7F6084','#FE4972','#90bf33','#C2083D','#433AF9','#02D911','#34495E','#EEAB15','#777','#e0410a','#c74e4b');


    return $data_color[$seq];
}

function convertToShortDept($search){
    return str_replace(array('JABATAN','SEKSYEN','BAHAGIAN'),array('JBT.','SEK.','BHG.'),$search);
}

function select_dept($list_dept,$show_status = 0,$default_id='',$no_escalation=''){
    $ci =& get_instance();
    if($show_status==1):
        if(CHECK_ADMIN):
            if($ci->curuser['user_id']==ADMIN_ID):
                echo option_value('',' - All - ','department',$default_id);
            else:
                if($ci->curuser['access_level']==ACCESS_LEVEL_ALL):
                    echo option_value('',' - All - ','department',$default_id);
                endif;
            endif;
        else:
            if($ci->curuser['access_level']==ACCESS_LEVEL_ALL):
                echo option_value('',' - All - ','department',$default_id);
            endif;
        endif;
    endif;
    if($no_escalation==1):
        echo option_value('no_escalation',' - No Escalation - ','department',$default_id);
    endif;
    foreach($list_dept as $department):
        $extend = '';
        if($department['level']==DEPARTMENT_LEVEL_2):
            $extend = '&nbsp;--&nbsp;';
        endif;
        if(CHECK_ADMIN):
            if($ci->curuser['user_id']==ADMIN_ID):
                echo option_value($department['department_id'],$extend.$department['department_name'],'department',$default_id);
            else:
                if($ci->curuser['access_level']==ACCESS_LEVEL_ALL):
                    echo option_value($department['department_id'],$extend.$department['department_name'],'department',$default_id);
                else:
                    if($department['department_id']==$ci->curuser['department_id'] || $department['parent_id'] == $ci->curuser['department_id']):
                        echo option_value($department['department_id'],$extend.$department['department_name'],'department',$default_id);
                    endif;
                endif;
            endif;
        else:
            if($ci->curuser['access_level']==ACCESS_LEVEL_ALL):
                echo option_value($department['department_id'],$extend.$department['department_name'],'department',$default_id);
            else:
                if($department['department_id']==$ci->curuser['department_id'] || $department['parent_id'] == $ci->curuser['department_id']):
                    echo option_value($department['department_id'],$extend.$department['department_name'],'department',$default_id);
                endif;
            endif;
        endif;
    endforeach;
}

function checking_validation($validation_err){
    if(!empty($validation_err)):
        echo '<div class="alert alert-danger">Sila lengkapkan ruangan wajib.</div>';
    endif;
}

function user_role_text($user_role){
    if($user_role==USER_ROLE_CUSTOMER_SERVICE):
        return 'Customer Service';
    elseif ($user_role==USER_ROLE_HOD):
        return 'HOD';
    elseif ($user_role==USER_ROLE_EXEC):
        return 'Executive';
    elseif ($user_role==USER_ROLE_TECHNICAL_SUPPORT):
        return 'Technical Support';
    endif;
}

function urlEncrypt($string){
    $key = 'abg_kacak';
    return urlencode(base64_encode($string));
}

function urlDecrypt($string){
    return base64_decode(urldecode($string));
}

function upper_case_first_char($string){
    $string = strtolower($string);
    return ucwords($string);
}

function radio_default($val_item,$val_data){
    if($val_item==$val_data):
        return true;
    else:
        return false;
    endif;
}

function get_status_active($status){
    if($status==STATUS_ACTIVE):
        echo '<span class="badge badge-success">Aktif</span>';
    else:
        echo '<span class="badge badge-danger">Tidak Aktif</span>';
    endif;
}

function get_status_rental($status){
    if($status==RENTAL_STATUS_YES):
        // echo '<span class="badge badge-success">Ya</span>';
        echo '<span class="badge badge-danger">Telah Disewa</span>';
    else:
        // echo '<span class="badge badge-danger">Tidak</span>';
        echo '<span class="badge badge-success">Belum Disewa</span>';
    endif;
}

function status_application($status){
    if($status==STATUS_APPLICATION_NEW):
        return '<span class="badge badge-info">Baru</span>';
    elseif ($status==STATUS_APPLICATION_APPROVED):
        return '<span class="badge badge-success">Diterima</span>';
    elseif ($status==STATUS_APPLICATION_REJECTED):
        return '<span class="badge badge-danger">Ditolak</span>';
    elseif ($status==STATUS_APPLICATION_KIV):
        return '<span class="badge badge-warning">Simpanan</span>';
    endif;
}

function status_application_label($status){
    if($status==STATUS_APPLICATION_NEW):
        return 'Baru';
    elseif ($status==STATUS_APPLICATION_APPROVED):
        return 'Diterima';
    elseif ($status==STATUS_APPLICATION_REJECTED):
        return 'Ditolak';
    elseif ($status==STATUS_APPLICATION_KIV):
        return 'Simpanan';
    endif;
}

function status_application_applicant($status){
    if($status==STATUS_AGREE_DEFAULT):
        return '<span class="badge badge-secondary">Tiada</span>';
    elseif ($status==STATUS_AGREE_ACCEPTED):
        return '<span class="badge badge-success">Terima</span>';
    elseif ($status==STATUS_AGREE_REJECTED):
        return '<span class="badge badge-danger">Tolak</span>';
    endif;
}

function race($race){
    if($race==RACE_MELAYU):
        return 'Melayu';
    elseif ($race==RACE_CINA):
        return 'Cina';
    elseif ($race==RACE_INDIA):
        return 'India';
    elseif ($race==RACE_OTHERS):
        return 'Lain-lain';
    endif;
}

function marital_status($marital_status){
    if($marital_status==MARITAL_STATUS_SINGLE):
        return 'Bujang';
    elseif ($marital_status==MARITAL_STATUS_MARRIED):
        return 'Berkahwin';
    elseif ($marital_status==MARITAL_STATUS_SINGLE_PARENT):
        return 'Ibu/bapa tunggal';
    elseif ($marital_status==MARITAL_STATUS_OTHERS):
        return 'Lain-lain';
    endif;
}

function occupation_status($occupation_status){
    if($occupation_status==OCCUPATION_STATUS_WORKING):
        return 'Masih bekerja';
    elseif ($occupation_status==OCCUPATION_STATUS_UNEMPLOYED):
        return 'Tidak bekerja / baru berhenti / pencen';
    endif;
}

function num($num='',$currencyFormat=0,$decimal=2)
{
    if($num=='' || empty($num)) return '0.00';

    if($currencyFormat==0):
        return number_format($num,$decimal,'.',',');
    elseif($currencyFormat==3):
        if($num<0):
            return '('.number_format($num,$decimal,'.',',').')';
        else:
            return number_format($num,$decimal,'.',',');
        endif;
    elseif($currencyFormat==4):
        if($num<0):
            return '('.number_format(abs($num),$decimal).')';
        else:
            return number_format($num,$decimal,'.',',');
        endif;
    else:
        return number_format($num,$decimal,'.','');
    endif;
}

function currencyToDouble($num){
    if($num=='') return '0.00';

    $num = str_replace(',','',$num);
    return $num;
}

function image_type($upload_name){
    if($upload_name=='ssm_pic'):
        return $image_type = PERMOHONAN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM;
    elseif ($upload_name=='passport_pic'):
        return $image_type = PERMOHONAN_GAMBAR_PASPORT;
    elseif ($upload_name=='structure_plan'):
        return $image_type = PERMOHONAN_PELAN_STRUKTUR;
    elseif ($upload_name=='ic_number_pic'):
        return $image_type = PERMOHONAN_SALINAN_KAD_PENGENALAN;
    elseif ($upload_name=='location_plan_file'):
        return $image_type = PERMOHONAN_PELAN_LOKASI;
    elseif ($upload_name=='photo_location_file'):
        return $image_type = PERMOHONAN_LAMPIRAN_PERMOHONAN;
    elseif ($upload_name=='suggestion_structure_plan_file'):
        return $image_type = PERMOHONAN_LAMPIRAN_SETUJU_TERIMA;
    elseif ($upload_name=='app_ssm_file'):
        return $image_type = PERMOHONAN_CARIAN_SSM;
    elseif ($upload_name=='letter_application_file'):
        return $image_type = PERMOHONAN_SURAT_PERMOHONAN;
    elseif ($upload_name=='map_info'):
        return $image_type = PERMOHONAN_MAP_INFO;
    elseif ($upload_name=='cost_validation'):
        return $image_type = PERMOHONAN_LAMPIRAN_PENGESAHAN_KOS_BINAAN;
    endif;
}

function having_value($data){
    if(!empty($data)):
        return true;
    elseif(is_numeric($data)):
        return true;
    else:
        return false;
    endif;
}

function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));

    if(!$num):
        return false;
    endif;

    $num = (int) $num;
    $words = array();
    $list1 = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'lapan', 'sembilan', 'sepuluh', 'sebelas',
        'dua belas', 'tiga belas', 'empat belas', 'lima belas', 'enam belas', 'tujuh belas', 'lapan belas', 'sembilan belas'
    );
    $list2 = array('', 'sepuluh', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh', 'enam puluh', 'tujuh puluh', 'lapan puluh', 'sembilan puluh', 'seratus');
    $list3 = array('', 'ribu', 'juta', 'bilion', 'trilion', 'quadrilion', 'quintilion', 'sextilion');
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);

    for($i = 0; $i<count($num_levels); $i++):
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? $list1[$hundreds] . ' ratus' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? $list3[$levels] . ' ' : '' );
    endfor;

    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }

    return trim(preg_replace('/\s\s+/', ' ', upper_case_first_char(implode(' ', $words))));
}

function search_default($data=array(),$key=''){
    if(is_array($data)):
        if(isset($data[$key])):
            return $data[$key];
        else:
            return '';
        endif;
    else:
        return '';
    endif;
}

function residence_information($id){
    if($id==1):
        return 'Rumah Sendiri';
    elseif ($id==2):
        return 'Rumah Keluarga';
    elseif ($id==3):
        return 'Rumah Sewa';
    endif;
}

function billboard_type($id){
    if($id==1):
        return 'Billboard';
    elseif($id==2):
        return 'Twinpole/unipole';
    elseif ($id==3):
        return 'Verticle pole';
    endif;
}

function bill_type($bill_type){
    if($bill_type==BILL_TYPE_MONTHLY):
        return 'Bulanan';
    elseif ($bill_type==BILL_TYPE_ANNUALLY):
        return 'Tahunan';
    elseif ($bill_type==BILL_TYPE_NO_PROCESS):
        return '-';
    endif;
}

function display_mobile_number($number){
    $number = ltrim($number, '6');

    if(strlen($number) == 10):
        return preg_replace("/^(\d{3})(\d{7})$/", "$1-$2", $number);
    elseif(strlen($number) == 11):
        return preg_replace("/^(\d{3})(\d{8})$/", "$1-$2", $number);
    else:
        return $number;
    endif;
}

function display_ic_number($number){
    if(strlen($number) == 12):
        return preg_replace("/^(\d{6})(\d{2})(\d{4})$/", "$1-$2-$3", $number);
    else:
        return $number;
    endif;
}

function notice_level($level){
    if($level==NOTICE_LEVEL_1):
        return 'Pertama';
    elseif ($level==NOTICE_LEVEL_2):
        return 'Kedua';
    elseif ($level==NOTICE_LEVEL_3):
        return 'Ketiga';
    elseif ($level==NOTICE_LEVEL_4):
        return 'LOD';
    elseif ($level==NOTICE_LEVEL_5):
        return 'Penamatan';
    elseif ($level==NOTICE_LEVEL_6):
        return 'Mahkamah';
    endif;
}

function born_in_selangor($ic_number){
    $selangor_ic_code = explode('|', SELANGOR_IC_CODE);
    $ic_code          = substr($ic_number,6,-4);

    if(in_array($ic_code, $selangor_ic_code)):
        return TRUE;
    else:
        return FALSE;
    endif;
}

function select_state($input='',$name_input='address_state'){
    $state_arr = STATE_LIST; //For Production Env Only
     $state_arr = unserialize(STATE_LIST);

    $data_select = '<select class="form-control" id="'.$name_input.'" name="'.$name_input.'">';
    foreach ($state_arr as $row):
        $data_select .= option_value($row,$row,$name_input,$input);
    endforeach;
    $data_select .= '</select>';

    return $data_select;
}

function count_child($id,$type=1){
    db_select('*');
    db_from('ACC_DEPENDENT');
    if($type==1):
        db_where('applicant_id',$id);
    elseif ($type==2):
        db_where('acc_id',$id);
    endif;
    db_where('relationship','Anak');
    return db_count_results();
}

function display_amount($amount){
    if($amount>0):
        $new_amount = $amount;
    elseif($amount<0):
        $new_amount = '('.str_replace('-','',$amount).')';
    else:
        $new_amount = '-';
    endif;

    return $new_amount;
}

function get_perc_from_sentence($string){
    $next_strg = strpos($string,'%');
    $perc_gst = trim(substr($string,($next_strg-2),2));
    if(empty($perc_gst)):
        $perc_gst = 0;
    endif;
    return $perc_gst;
}

function check_trans_gst($gst_code,$gst_desc){
    $check_gst = strpos($gst_desc,'gst');

    $data['GST_TYPE']       = 0;
    $data['TR_GST_STATUS']  = 2;
    if($check_gst):
        $data['TR_GST_STATUS'] = 1;
    else:
        $data['TR_GST_STATUS'] = 2;
    endif;

    if( $data['TR_GST_STATUS']==1):
        if($gst_code==TR_CODE_GST_TIPPING):
            $data['GST_TYPE'] = GST_TYPE_TIPPING;
        elseif ($gst_code==TR_CODE_GST_WATER):
            $data['GST_TYPE'] = GST_TYPE_WATER;
        else:
            $data['GST_TYPE'] = GST_TYPE_RENTAL;
        endif;
    endif;

    return $data;
}

function display_address($line1='',$line2='',$line3='',$postcode='',$state=''){
    $address = '';
    if(!empty($line1)):
        $address = $line1;
    endif;
    if(!empty($line2)):
        $address = $address.'<br>'.$line2;
    endif;
    if(!empty($postcode) && !empty($line3)):
        $address = $address.'<br>'.$postcode.' '.$line3;
    elseif(empty($postcode) && !empty($line3)):
        $address = $address.'<br>'.$line3;
    elseif(!empty($postcode) && empty($line3)):
        $address = $address.'<br>'.$postcode;
    endif;
    
    if(!empty($state)):
        $address = $address.'<br>'.$state;
    endif;

    return $address;
}

function code_payment_from_code_bill($tr_code){
    $code_payment = substr($tr_code,1);
    $code_payment = '2'.$code_payment;

    return $code_payment;
}