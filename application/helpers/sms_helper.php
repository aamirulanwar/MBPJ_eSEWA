<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */


/**
 * @param string $name
 * @param string $value
 * @return bool
 */
function sendsms($to = '', $sms_msg = SMS_MSG_RESET_PASSWORD, $value = '', $value_2 = '',$data_table_sms='') {
    $msg = "";
    if ($sms_msg == SMS_MSG_RESET_PASSWORD):
        $msg = '[Epi2u.com] '.$value_2.', You have requested to reset password. Your new password is '.$value.'.';
    elseif($sms_msg==SMS_REG_NEW_MEMBER):
        $msg = "[Epi2u.com] $value_2, Congratulation, your account has successfully registered with us. Your login id is $value_2 and password is $value";
    elseif($sms_msg==SMS_UPGRADE_ACC):
        $msg = "[Epi2u.com] Congratulation, your account ($value) has successfully upgraded to $value_2.";
    endif;

    if($msg):
        sms_gateway($to, $msg,$data_table_sms);
    endif;
    
}

function sms_gateway($to = '', $msg_sms = '',$data_table_sms='') {
    $response = "";
    $phone_number = clean_phone_number($to);
    if ($phone_number) :
        if (SMS_GATEWAY == 'NEXMO'):
            #call ci function
            $ci = & get_instance();
            #call session library
            $ci->load->library('nexmo');
            $ci->nexmo->set_format('json');

            $from = SMS_SENDER_NEXMO;
            $message = array(
                'type' => 'text',
                'text' => utf8_encode($msg_sms),
            );
            
//            $response = $ci->nexmo->send_message($from, $phone_number, $message);

            if($data_table_sms):
                if(empty($data_table_sms))
                    return false;
                if(empty($data_table_sms['charge_to'])):
                    echo "No member account source found";
                    return false;
                endif;

                $sms_balance = $ci->nexmo->get_balance();
//                $charge_amount = $ci->nexmo->get_pricing('MY');
//                $charge_amount = $charge_amount['mt'];
                #Store into tbl_sms
                $data_sms['dt_added'] = date("Y-m-d H:i:s");
                $data_sms['for_member_id'] = (isset($data_table_sms['for_member_no']))?$data_table_sms['for_member_no']:0;
                $data_sms['to_phone_number'] = $phone_number;
                $data_sms['message'] = $msg_sms;
                $data_sms['msg_type'] = $data_table_sms['msg_type'];
                if($response):
                    if($response['messages'][0]['status']==SMS_NEXMO_STATUS_SENT):
                        $status_sms = SMS_STATUS_SENT;
                    else:
                        $status_sms = SMS_STATUS_NOT_SENT;
                    endif;
                else:
                    $status_sms = SMS_STATUS_NOT_SENT;
                endif;
                $data_sms['delivery_status'] = $status_sms;
                $data_sms['gateway_msg_id'] = 'NEXMO';
                $data_sms['sms_balance'] = (is_array($sms_balance))?$sms_balance['value']:0;
                $data_sms['charge_amount'] = CHARGE_PER_SMS;
                $data_sms['charge_to'] = $data_table_sms['charge_to'];
                $data_sms['direction'] = $data_table_sms['direction'];
                if(isset($data_table_sms['ref_id'])){
                    $data_sms['ref_id'] = $data_table_sms['ref_id'];
                }

                $ci->load->database();
                $ci->db->insert('c_sms', $data_sms);
                $sms_ref_id = $ci->db->insert_id();
            endif;
            return $status_sms;
        endif;
    endif;
}

/**
 * @param string $name
 * @return bool
 */
