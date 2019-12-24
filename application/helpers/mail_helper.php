<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 */



/**
 * @param string $message
 * @param string $subject
 * @param string $receiver
 * @param string $viewfile
 * @param string $senderMail
 * @param string $senderName
 * @return bool
 */

#message id 1
function email_notification_online_form_customer($data){
    $subject = 'Ticket Registration : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data['recipient_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =  '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data['recipient_name']).',<br><br>'.
        'Thank you for contacting SIRIM QAS International Sdn Bhd. Your ticket has been successfully registered into our system.<br><br>'.
        '<strong>Reference No.</strong>   : <br>'.$data['ref_no'].' <br><br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br><br>'.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'You will be notified via email/phone call once a response is made. '.
        'For any follow-ups on this matter, please mention the Reference No. to our Customer Service staff to ensure your ticket is fully monitored. '.
        'Thank you.<br>'.
        '</td></tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data['recipient_email']);
}

#message id 2
function email_notification_online_form_customer_service($data){
    $subject = 'New Online Ticket : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.SIRIM_QAS_EMAIL;
    endif;
    $message = 'Dear Customer Service Team,<br><br>'.
        'You have received a new ticket from the Online Enquiry & Complaint Form.<br><br>'.
        '<strong>Reference No.</strong>   : '.$data['ref_no'].' <br>'.
        '<strong>Customer’s Name </strong>   : '.upper_case_first_char($data['recipient_name']).' <br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br><br>'.
        'Please log into <a href="'.ECHS_URL.'">ECHS</a> to manage this ticket.<br><br>'.
        'Thank you. ';

    sendmail($message,$subject,SIRIM_QAS_EMAIL);#$data['recipient_email']
}

#message id 3
function email_notification_escalation($data){

    $data_user = data_user($data['user_id']);
    $data_dept = data_dept($data['dept_id']);

    $subject = 'Ticket Escalation : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_user['user_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =  '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data_user['user_name']).',<br><br>'.
        '<strong>'.$data_dept['department_name'].'</strong> has been assigned to below ticket.<br><br>'.
        '<strong>Reference No.</strong>   : <br>'.$data['ref_no'].' <br><br>'.
        '<strong>Customer’s Name </strong>    : <br>'.upper_case_first_char($data['customer_name']).' <br><br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br>'.
        '</td></tr>';
        if(!empty($data['link_ticket_details'])):
            $message = $message.'<table width="135" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0"><tr>'.
            '<td data-bgcolor="bg-button" data-size="size button" data-min="10" data-max="16" class="btn" align="center" style="font:14px/16px Arial, Helvetica, sans-serif; color:#f8f9fb; text-transform:uppercase; mso-padding-alt:12px 10px 10px; border-radius:2px;" bgcolor="#40aceb">'.
            '<a clicktracking=off style="text-decoration:none; color:#f8f9fb; display:block; padding:12px 10px 10px;" href='.$data['link_ticket_details'].'><strong>TICKET DETAILS</strong></a>'.
            '</td></tr></table><br>';
        endif;
        $message = $message.'<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'Please log into <a href="'.ECHS_URL.'">ECHS</a> to answer this ticket and help our Customer Service Team to respond to clients in a timely manner.<br><br>'.
        'You may also assign an executive to fill in the response form. <br><br>'.
        'Thank you. <br>'.
        '</td></tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data_user['user_email']);
}

#message id 4
function email_notification_investigation($data){

    $data_user = data_user($data['user_id']);
    $data_dept = data_dept($data['dept_id']);

    $subject = 'Complaint Investigation & Validation : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_user['user_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =  '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data_user['user_name']).',<br><br>'.
        '<strong>'.$data_dept['department_name'].'</strong> has been assigned to investigate and validate below complaint.<br><br>'.
        '<strong>Reference No.</strong>   : <br>'.$data['ref_no'].' <br><br>'.
        '<strong>Customer’s Name </strong>    : <br>'.upper_case_first_char($data['customer_name']).' <br><br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br><br>'.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'Please log into <a href="'.ECHS_URL.'">ECHS</a> to answer this ticket and help our Customer Service Team to respond to clients in a timely manner.<br><br>'.
        'You may assign an executive to fill in the response form which will require your approval before it goes to Customer Service Team. <br><br>'.
        'Thank you. <br>'.
        '</td></tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data_user['user_email']);
}

#message id 5
function email_notification_assign($data){

    $data_user = data_user($data['user_id']);

    $subject = 'Ticket Escalation : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_user['user_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =  '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data_user['user_name']).',<br><br>'.
        'You have been assigned to respond to below ticket.<br><br>'.
        '<strong>Reference No.</strong>   : <br>'.$data['ref_no'].' <br><br>'.
//        '<strong>Customer’s Name </strong>    : <br>'.upper_case_first_char($data['customer_name']).' <br><br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br><br>'.
        '<strong>Comment / Instruction  </strong>    : <br>'.nl2br($data['instruction']).' <br><br>'.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'Please log into <a href="'.ECHS_URL.'">ECHS</a> to answer this ticket and help our Customer Service Team to respond to clients in a timely manner.<br><br>'.
        'Thank you. <br>'.
        '</td></tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data_user['user_email']);
}

#message id 6
function email_notification_respond($data){

    $data_user      = data_user($data['user_id']);
    $data_user_hod  = data_user($data['hod_id']);

    $subject = 'Executive’s Response : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_user_hod['user_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =  '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data_user_hod['user_name']).',<br><br>'.
        ''.upper_case_first_char($data_user['user_name']).' has responded to below ticket.<br><br>'.
        '<strong>Reference No.</strong>   : <br>'.$data['ref_no'].' <br><br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br><br>'.
        '<strong>Comment / Instruction  </strong>    : <br>'.nl2br($data['instruction']).' <br><br>'.
        '<strong>Executive’s Response  </strong>    : <br>'.nl2br($data['respond']).' <br><br>'.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'You may log into <a href="'.ECHS_URL.'">ECHS</a> to view the record. Thank you. <br>'.
        '</td></tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data_user_hod['user_email']);
}

#message id 7
function email_notification_respond_rejected($data){

    $data_user      = data_user($data['user_id']);
//    $data_user_hod  = data_user($data['hod_id']);

    $subject = 'Executive’s Response : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_user['user_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =  '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data_user['user_name']).',<br><br>'.
        'Your response to below ticket has been <span style="color: red;font-weight: bold">REJECTED</span>.<br><br>'.
        '<strong>Reference No.</strong>   : <br>'.$data['ref_no'].' <br><br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br><br>'.
        '<strong>Comment / Instruction  </strong>    : <br>'.nl2br($data['instruction']).' <br><br>'.
        '<strong>Executive’s Response  </strong>    : <br>'.nl2br($data['respond']).' <br><br>'.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'Please log into <a href="'.ECHS_URL.'">ECHS</a> to place a new response. Thank you. <br>'.
        '</td></tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data_user['user_email']);
}

#message id 8
function email_notification_respond_approved($data){

    $data_user      = data_user($data['user_id']);
//    $data_user_hod  = data_user($data['hod_id']);

    $subject = 'Executive’s Response : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_user['user_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =  '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data_user['user_name']).',<br><br>'.
        'Your response to below ticket has been <span style="color: green;font-weight: bold">received</span>.<br><br>'.
        '<strong>Reference No.</strong>   : <br>'.$data['ref_no'].' <br><br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br><br>'.
        '<strong>Comment / Instruction  </strong>    : <br>'.nl2br($data['instruction']).' <br><br>'.
        '<strong>Executive’s Response  </strong>    : <br>'.nl2br($data['respond']).' <br><br>'.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'You may log into <a href="'.ECHS_URL.'">ECHS</a> to view the record. Thank you. <br>'.
        '</td></tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data_user['user_email']);
}

#message id 9
function email_notification_dept_response($data){

    $data_user      = data_user($data['user_id']);
    $data_user_hod  = data_user($data['owner_id']);

    $subject = 'Department’s Response : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_user_hod['user_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =  '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data_user_hod['user_name']).',<br><br>'.
        $data['dept_name']. ' has submitted a response to below ticket.<br><br>'.
        '<strong>Reference No.</strong>   : <br>'.$data['ref_no'].' <br><br>'.
        '<strong>Customer’s Name </strong>    : <br>'.upper_case_first_char($data['customer_name']).' <br><br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br><br>'.
        '<strong>Department’s Response  </strong>    : <br>'.nl2br($data['respond']).' <br><br>'.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'Please log into <a href="'.ECHS_URL.'">ECHS</a> to finalise the ticket. Thank you. <br>'.
        '</td></tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data_user_hod['user_email']);
}

#message id 10 CS to customer
function email_notification_new_message($data,$attachment=array(),$owner_id=0){

    $owner_email = data_user($owner_id);
    $owner_email = $owner_email['user_email'];

    $data_attachment = '';
    if($attachment['data_image']):
        $data_attachment = '<h2>Attachment :</h2>';
        foreach ($attachment['data_image'] as $row):
            $data_attachment .= '<a  data-toggle="tooltip" data-placement="top" title="Download" target="_blank" href="'.base_url().PROJECT_CONTENT_BASED_UPLOAD_PATH.'/'.$attachment['ym_folder'].rawurlencode($row).'">'.$row.'</a><br>';
        endforeach;
        $data_attachment .= '<br><br>';
    endif;

    $subject = 'New Message : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data['recipient_email'].' REPLYTO '.$owner_email;
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =                   '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Thank you for contacting SIRIM QAS International Sdn Bhd. You have received a new message for your ticket.<br>Reference No. : <strong>'.$data['ref_no'].'</strong><br><br>'.
        '<strong>Message</strong>:<br>'.
        nl2br($data['message']).' <br>'.
        $data_attachment.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'Please do not reply directly to this email. You may view the message or respond by <a href="'.$data['link_chat'].'">clicking here</a> or the button below: <br><br>'.
        '</td></tr>'.
        '<tr><td style="padding:0 0 20px;">'.
        '<table width="135" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-button" data-size="size button" data-min="10" data-max="16" class="btn" align="center" style="font:14px/16px Arial, Helvetica, sans-serif; color:#f8f9fb; text-transform:uppercase; mso-padding-alt:12px 10px 10px; border-radius:2px;" bgcolor="#40aceb">'.
        '<a clicktracking=off style="text-decoration:none; color:#f8f9fb; display:block; padding:12px 10px 10px;" href='.$data['link_chat'].'><strong>REPLY NOW</strong></a>'.
        '</td>'.
        '</tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data['recipient_email'],'','','',$owner_email);
}

#message id 13 Finalisation
function email_notification_finalisation($data,$attachment = array(),$owner_id=0){

    $owner_email = data_user($owner_id);
    $owner_email = $owner_email['user_email'];

    $data_attachment = '';
    if($attachment):
            $data_attachment = '<h2>Attachment :</h2>';
            foreach ($attachment as $row):
                $data_attachment .= '<a  data-toggle="tooltip" data-placement="top" title="Download" target="_blank" href="'.base_url().PROJECT_CONTENT_BASED_UPLOAD_PATH.'/'.$row['ym_folder'].rawurlencode($row['name']).'">'.$row['name'].'</a><br>';
            endforeach;
        $data_attachment .= '<br><br>';
    endif;

    $subject = 'Ticket Response : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data['recipient_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =                   '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data['customer_name']).',<br><br>'.
        'Regarding your ticket with Reference No. <strong>'.$data['ref_no'].'</strong>, below is the response from our respective Customer Service staff:<br><br>'.
        nl2br($data['message']).' <br>'.
        $data_attachment.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'Please do not reply directly to this email. If you have any questions related to this ticket or wish to respond, please <a href="'.$data['link_chat'].'">click here</a> or the button below: <br><br>'.
        '</td></tr>'.
        '<tr><td style="padding:0 0 20px;">'.
        '<table width="135" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-button" data-size="size button" data-min="10" data-max="16" class="btn" align="center" style="font:14px/16px Arial, Helvetica, sans-serif; color:#f8f9fb; text-transform:uppercase; mso-padding-alt:12px 10px 10px; border-radius:2px;" bgcolor="#40aceb">'.
        '<a clicktracking=off style="text-decoration:none; color:#f8f9fb; display:block; padding:12px 10px 10px;" href='.$data['link_chat'].'><strong>SEND A MESSAGE</strong></a>'.
        '</td>'.
        '</tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data['recipient_email'],'','','',$owner_email);
}

#message id 12
function email_notification_assignment($data){
    $data_user      = data_user($data['user_id']);

    $subject = 'Online Ticket Assignment : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_user['user_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =  '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data_user['user_name']).',<br><br>'.
        'You have been assigned to below Online Ticket:<br><br>'.
        '<strong>Reference No.</strong>   : <br>'.$data['ref_no'].' <br><br>'.
        '<strong>Customer’s Name </strong>    : <br>'.upper_case_first_char($data['customer_name']).' <br><br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br><br>'.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'Please log into <a href="'.ECHS_URL.'">ECHS</a> to manage this ticket. Thank you. <br>'.
        '</td></tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data_user['user_email']);
}

#message id SIRIM BERHAD
function email_notification_to_sirim_berhad($data,$attachment=array()){

    $data_attachment = '';
    if($attachment):
        $data_attachment = '<h2>Attachment :</h2>';
        foreach ($attachment as $row):
            $data_attachment .= '<a  data-toggle="tooltip" data-placement="top" title="Download" target="_blank" href="'.base_url().PROJECT_CONTENT_BASED_UPLOAD_PATH.'/'.$row['ym_folder'].rawurlencode($row['name']).'">'.$row['name'].'</a><br>';
        endforeach;
        $data_attachment .= '<br><br>';
    endif;

    $sirim_berhad_email = 'web@sirim.my';
    $subject = 'Enquiry from SIRIM QAS : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$sirim_berhad_email;
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =                   '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear SIRIM BERHAD,<br><br>'.
        'SIRIM QAS Customer Service received an enquiry that belongs to you:<br><br>'.
        '<strong>Reference No.</strong>   : '.$data['ref_no'].' <br>'.
        '<strong>Customer’s Name </strong>   : '.upper_case_first_char($data['customer_name']).' <br>'.
        '<strong>Phone number </strong>   : '.($data['phone_number']).' <br>'.
        '<strong>Email address </strong>   : '.($data['email_address']).' <br>'.
        '<strong>Ticket Details</strong>:<br>'.
        nl2br($data['message']).' <br>'.
        $data_attachment.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'For more details on this enquiry, please <a href="'.$data['link_ticket_details'].'">click here</a> or the button below: <br><br>'.
        '</td></tr>'.
        '<tr><td style="padding:0 0 20px;">'.
        '<table width="135" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-button" data-size="size button" data-min="10" data-max="16" class="btn" align="center" style="font:14px/16px Arial, Helvetica, sans-serif; color:#f8f9fb; text-transform:uppercase; mso-padding-alt:12px 10px 10px; border-radius:2px;" bgcolor="#40aceb">'.
        '<a clicktracking=off style="text-decoration:none; color:#f8f9fb; display:block; padding:12px 10px 10px;" href='.$data['link_ticket_details'].'><strong>TICKET DETAILS</strong></a>'.
        '</td>'.
        '</tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

        sendmail($message,$subject,$sirim_berhad_email);
}

#message id 14
function email_notification_dept_response_investigation($data){

    $data_user          = data_user($data['user_id']);
    $data_user_owner    = data_user($data['owner_id']);

    $subject = 'Department’s Response : '.$data['ref_no'].' - '.$data['instruction'].' (Complaint is found to be: '.$data['status'].')' ;
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_user['user_email'];
    endif;

    $message = 'Dear Ms/Mrs/Mr '.upper_case_first_char($data_user_owner['user_name']).',<br><br>'.
        $data['dept_name']. ' has submitted a response to below ticket.<br><br>'.
        '<strong>Reference No.</strong>   : '.$data['ref_no'].' <br>'.
        '<strong>Ticket Details </strong>    : <br>'.nl2br($data['message']).' <br>'.
        '<strong>Department’s Response  </strong>    : <br>'.nl2br($data['respond']).' <br><br>'.
        'Please log into <a href="'.ECHS_URL.'">ECHS</a> to finalise the ticket.<br><br>'.
        'Thank you. ';

    sendmail($message,$subject,$data_user_owner['user_email']);
}

#cronjob id 1 : email reminder for pending tickets
function email_notification_pending_ticket($data){
    $data_user = data_user($data['owner_id']);
    $subject = 'Pending Ticket';
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_user['user_email'];
    endif;

    $message = 'Dear Ms/Mrs/Mr '.upper_case_first_char($data['owner_name']).',<br><br>'.
        'Below is your list of pending tickets that have reached 3 business days of maximum time taken for resolution:<br><br><br>'.
        '<table style="width:100%" align="center">'.
        '<tr>
            <td><strong>Ref No</strong></td>
            <td><strong>Date Received</strong></td> 
            <td><strong>Customer Name</strong></td>
        </tr>'.
        '<tr>
            <td>'.$data['ref_number'].'</td>
            <td>'.$data['dt_received'].'</td>
            <td>'.upper_case_first_char($data['cust_name']).'</td>
        </tr>'.
        '</table>'.
        
        //'<strong>Ref No. </strong>:<br>'.$data['ref_number'].'<br>'.
        /*'<td><strong>Date Received </strong>    :<tr> '.$data['dt_received'].'</tr></td><br>'.
        '<td><strong>Customer’s Name </strong>    :<tr> '.upper_case_first_char($data['cust_name']).'</tr></td><br>'.*/
        
        '<br><br><br>Please log into <a href="'.ECHS_URL.'">ECHS</a> to answer this ticket and help our Customer Service Team to respond to clients in a timely manner.<br><br>'.
        'You may also assign an executive to fill in the response form. <br><br>'.
        'Thank you. ';

    sendmail($message,$subject,$data_user['user_email']);
}

#message id 15
function email_notification_escalation_group($data){

    $data_dept = data_dept($data['dept_id']);

    $subject = 'Ticket Escalation : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data_dept['email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer =  '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear All,<br><br>'.
        '<strong>'.$data_dept['department_name'].'</strong> has been assigned to below ticket.<br><br>'.
        '<strong>Reference No.</strong>   : <br>'.$data['ref_no'].' <br><br>'.
        '<strong>Customer’s Name </strong>    : <br>'.upper_case_first_char($data['customer_name']).' <br><br>'.
        '<strong>Ticket Details</strong>    : <br>'.nl2br($data['message']).' <br>'.
        '</td></tr>';
        if(!empty($data['link_ticket_details'])):
            $message = $message.'<table width="135" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0"><tr>'.
            '<td data-bgcolor="bg-button" data-size="size button" data-min="10" data-max="16" class="btn" align="center" style="font:14px/16px Arial, Helvetica, sans-serif; color:#f8f9fb; text-transform:uppercase; mso-padding-alt:12px 10px 10px; border-radius:2px;" bgcolor="#40aceb">'.
            '<a clicktracking=off style="text-decoration:none; color:#f8f9fb; display:block; padding:12px 10px 10px;" href='.$data['link_ticket_details'].'><strong>TICKET DETAILS</strong></a>'.
            '</td></tr></table><br>';
        endif;
        $message = $message.'<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'This ticket is assigned to Section Group. Therefore, any staff from this section is able to manage and respond to this ticket.<br><br>'.
        'Please log into <a href="'.ECHS_URL.'">ECHS</a> to take on the task and respond to Customer Service.<br><br>'.
        'Thank you. <br>'.
        '</td></tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data_dept['email']);
}

#message Feedback
function email_feedback($data){

    $data_ticket            = data_ticket($data['ticket_id']);
    $data_issue_unrelated   = check_issue_unrelated($data['ticket_id']);
    $data_service_unrelated = check_service_unrelated($data['ticket_id']);

    if(empty($data_ticket)):
        return true;
    endif;

    #dah sent before
    if($data_ticket['feedback_survey']==1):
        return true;
    endif;

    #sent to sirim berhad
    if($data_ticket['email_to_sirim']==1):
        return true;
    endif;

    #unrelated issue
    if($data_issue_unrelated):
        return true;
    endif;

    #unrelated service
    if($data_service_unrelated):
        return true;
    endif;

    $url = ENQUIRY_URL.'rating/'.urlEncrypt($data['ticket_id']).'/'.sha1($data['ticket_id']);


    $subject = 'SIRIM QAS Feedback Rating : '.$data['ref_no'];
    if(EMAIL_TESTING):
        $subject = $subject.' TO '.$data['recipient_email'];
    endif;

    $content_header = '<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-block" class="holder" style="padding:15px 60px 50px;" bgcolor="#f9f9f9">'.
        '<table width="100%" cellpadding="0" cellspacing="0">'.
        '<tr>';

    $content_footer = '</tr>'.
        '</table>'.
        '</td>'.
        '</tr>'.
        '</table>';

    $message = $content_header.
        '<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="left" style="font:14px/25px Arial, Helvetica, sans-serif; color:#294661; padding:0 0 21px;">'.
        'Dear Ms/Mrs/Mr '.upper_case_first_char($data['customer_name']).',<br><br>'.
        'We invite you to rate your experience when dealing with our Customer Service for ticket <strong>'.$data['ref_no'].'</strong>.<br><br>'.
        'Your rating is very valuable to us and will only require less than a minute of your time.<br><br>'.
        'Thank you.'.
        '</td></tr>'.
        '<tr><td style="min-width:100%;padding:10px 18px 25px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top-width:2px;border-top-style:solid;border-top-color:#eeeeee;border-collapse:collapse">
                    <tbody><tr>
                        <td>
                            <span></span></td></tr></tbody></table><br>'.
        'To rate, please <a href="'.$url.'">click here</a> or the button below: <br><br>'.
        '</td></tr>'.
        '<tr><td style="padding:0 0 20px;">'.
        '<table width="135" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">'.
        '<tr>'.
        '<td data-bgcolor="bg-button" data-size="size button" data-min="10" data-max="16" class="btn" align="center" style="font:14px/16px Arial, Helvetica, sans-serif; color:#f8f9fb; text-transform:uppercase; mso-padding-alt:12px 10px 10px; border-radius:2px;" bgcolor="#40aceb">'.
        '<a clicktracking=off style="text-decoration:none; color:#f8f9fb; display:block; padding:12px 10px 10px;" href='.$url.'><strong>RATE YOUR EXPERIENCE</strong></a>'.
        '</td>'.
        '</tr>'.
        '</table>'.
        '</td>'.
        $content_footer;

    sendmail($message,$subject,$data['recipient_email']);
}


function sendmail($message='',$subject='',$receiver='',$viewfile='',$senderMail=SIRIM_QAS_EMAIL,$senderName='SIRIM QAS Customer Service',$reply_to='',$cc_to='')
{
    $ci =& get_instance();

    #stop if localhost
    //if(get_ip()=='127.0.0.1') return false;

    #validate some data
    if($receiver=='') return false;

    #if viewfile is provide send data to view file and load page view
    if($viewfile != ''):
        if(is_array($message)):
            foreach($message as $key=>$val):
                $eml[$key] = $val;
            endforeach;
        else:
            $eml['message']  = $message;
        endif;
        #load view file for email and pass data message
        $msg = $ci->load->view($viewfile, $eml, TRUE);
    else:
        $msg = $message;
    endif;

    #load email library
    load_library('email');
    #set email configuration config

    if(EMAIL_TESTING):
        #sengrid configuration
        $config['protocol']     = 'smtp';//smtp / PHPmail / mail
        $config['smtp_host']    = 'smtp.sendgrid.net';
        $config['smtp_user']    = 'aufaintelligence';
        $config['smtp_pass']    = 'Strategyaufa123';
        $config['smtp_port']    = '587';
    else:
        #sirim configuration
        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'smtp.sirim.my';
        $config['smtp_user']    = '';
        $config['smtp_pass']    = '';
        $config['smtp_port']    = '25';
    endif;
    
    $config['crlf']         = "\r\n";
    $config['newline']      = "\r\n";


    $config['smtp_timeout'] = '7';
    $config['charset']      = 'utf-8';
    $config['mailtype']     = 'html';
    $config['validation']   = TRUE; // bool whether to validate email or not
    $config['wordwrap']     = TRUE;

    $ci->email->initialize($config);
    $ci->email->from(SIRIM_QAS_EMAIL,'SIRIM QAS Customer Service');

    if(EMAIL_TESTING):
        $ci->email->to('tirarashid@gmail.com');
        if(!empty($reply_to)):
            $ci->email->reply_to($reply_to);
        endif;
	else:
		$ci->email->to($receiver);
		$ci->email->bcc('sqas@aufa.com.my');
		if(!empty($reply_to)):
            $ci->email->reply_to($reply_to);
        endif;
        if(!empty($cc_to)):
            $ci->email->cc($cc_to);
        endif;
	endif;

    $ci->email->subject($subject);
    $ci->email->message($msg);
//    if($attachment):
//        foreach ($attachment as $row):
//            $ci->email->attach(PROJECT_CONTENT_BASED_UPLOAD_PATH.'/'.$row['name']);
//        endforeach;
//    endif;
//    $ci->email->send();

//    echo $ci->email->print_debugger();
//    exit;
}

function data_user($user_id){
    db_where('user_id',$user_id);
    db_from('users');
    $sql = db_get();
    if($sql):
        return $sql->row_array();
    endif;
}

function data_dept($dept_id){
    db_where('department_id',$dept_id);
    db_from('department');
    $sql = db_get();
    if($sql):
        return $sql->row_array();
    endif;
}

function data_ticket($ticket_id){
    db_where('ticket_id',$ticket_id);
    db_from('t_ticket');
    $sql = db_get();
    if($sql):
        return $sql->row_array();
    endif;
}

function check_data_exist($ticket_id){
    db_from('f_data d');
    db_where('d.ticket_id',$ticket_id);
    $sql = db_get();
    if($sql):
        return $sql->row_array();
    endif;
}

function check_issue_unrelated($ticket_id){
    db_from('t_ticket_issue');
    db_where('ticket_id',$ticket_id);
    #Outside the Scope of SQAS
    db_where('issue_id',7);
    $sql = db_get();
    if($sql):
        return $sql->row_array();
    endif;
}

function check_service_unrelated($ticket_id){
    db_from('t_ticket_service');
    db_where('ticket_id',$ticket_id);
    #Unrelated To SIRIM QAS
    db_where('service_id',5);
    $sql = db_get();
    if($sql):
        return $sql->row_array();
    endif;
}