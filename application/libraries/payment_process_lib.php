<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

class payment_process_lib{

    private $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->admin_trcode = $this->ci->load->database('admin',TRUE);
    }

    function payment_process($acc_number=''){
        $data_payment = $this->get_data_payment($acc_number);
        if ($data_payment):
            foreach ($data_payment as $payment):
                $tr_code    = $payment['TR_CODE_NEW'];
                $acc_no     = $payment['ACCOUNT_NUMBER'];
                $no_bill    = $payment['NO_RESIT'];
                $dt_bill    = $payment['DT_ADDED'];
                $amount     = $payment['AMOUNT'];
                $b_category = 'R';//$payment['CATEGORY'];

                $data_account = $this->data_account_by_acc_num($acc_no);
                if(empty($data_account)):
                    echo 'tiada akaun';
                    return false;
                endif;

                if(empty($no_bill)):
                    echo 'tiada no resit';
                    return false;
                endif;

                $acc_id = $data_account['ACCOUNT_ID'];

                #---->> cek kalo ade bil tunggakan semasa; convert bayaran tunggakan kepada bayaran semasa.

                $get_bill_master = $this->check_bill_master_exist($no_bill, $acc_id);

                if (!empty($get_bill_master)):
                    $bill_id        = $get_bill_master['BILL_ID'];
                    $new_amount     = $amount + $get_bill_master['TOTAL_AMOUNT'];
                    $bill_prev_year = $get_bill_master['BILL_YEAR'] - 1;

                    #update existing bill master
                    $data_update_master['total_amount'] = $new_amount;
                    if ($get_bill_master['BILL_CATEGORY'] != $b_category):
                        $data_update_master['bill_category'] = NULL;
                    endif;

                    $update_status = $this->update_bill_master($data_update_master, $bill_id);
                else:
                    //----INSERT INTO TABLE:B_MASTER----//
                    $data_insert_master['bill_number']      = $no_bill;
                    $data_insert_master['total_amount']     = 0;
                    $data_insert_master['account_id']       = $acc_id;
                    $data_insert_master['dt_added']         = $dt_bill;
                    $data_insert_master['bill_month']       = date('m', strtotime($dt_bill));
                    $data_insert_master['bill_year']        = date('Y', strtotime($dt_bill));
                    $data_insert_master['total_amount']     = $amount;
                    $data_insert_master['bill_category']    = $b_category;

                    $bill_prev_year = $data_insert_master['bill_year'] - 1;
//                    if (INSERT_BILL):
                    $bill_id = $this->insert_bill_master($data_insert_master);
                    echo 'Bill master insert for account id ' . $acc_no . '<br>';
//                    endif;
                endif;

                #check tr_type
                if ($tr_code == '11090' || $tr_code == 'code lebihan baru' || $tr_code == '11119999'): #lebihan
                    $tr_type = 3;   //lebihan
                elseif (substr($tr_code, 0, 2) == 11 || substr($tr_code, 0, 2) == 21):
                    $tr_type = 1;   //semasa
                elseif (substr($tr_code, 0, 2) == 12 || substr($tr_code, 0, 2) == 22):
                    $tr_type = 2;   //tunggakan
                else:
                    $tr_type = 0;
                endif;

                $get_tr_code    = $this->sewaan_tr_code($tr_code,'');
                $mct_trdesc     = $tr_code;
                $priority       = 0;

                #check from admin table here ---------------
                if (!empty($get_tr_code)):
//                    $tr_id      = $get_tr_code['TR_ID'];
                    $priority   = $get_tr_code['MCT_PRIORT'];
                    $mct_trdesc = $get_tr_code['MCT_TRDESC'];
                endif;

                #untuk cari gst type
                $gst_type       = 0;
                $tr_gst_status  = 0;
                if (strpos($mct_trdesc, 'GST') !== false):
                    $tr_gst_status  = 1;

                    $int_latest = $this->int_latest($tr_code,$acc_no);
                    if($int_latest):
                        $gst_type       = $int_latest['GST_STATUS'];
                    endif;
                endif;
                #-------------------------------------------

                //----INSERT INTO TABLE:B_ITEM----//
                $data_insert_item['bill_id']        = $bill_id;
//                $data_insert_item['account_id']     = $payment['ACCOUNT_NUMBER'];
                $data_insert_item['tr_code']        = $tr_code;
                $data_insert_item['dt_added']       = $payment['DT_ADDED'];
                $data_insert_item['bill_category']  = 'R';
                $data_insert_item['tr_code_old']    = $payment['TR_CODE'];
                $data_insert_item['tr_id']          = 0;
                $data_insert_item['account_id']     = $acc_id;
                $data_insert_item['priority']       = empty($priority)?0:$priority;
                $data_insert_item['tr_type']        = $tr_type;
                $data_insert_item['amount']         = $payment['AMOUNT'];
                $data_insert_item['tr_gst_status']  = $tr_gst_status;
                $data_insert_item['item_desc']      = $mct_trdesc;
                $data_insert_item['gst_type']       = $gst_type;
                $data_insert_item['prev_year_outstanding'] = 0;
                $data_insert_item['original_table'] = '';
                $insert_item_id = $this->insert_bill_item($data_insert_item);

                #update payment process status
                $data_update_payment['process_status'] = 1;
                $update_status = $this->update_int_payment($data_update_payment, $payment['PAYMENT_ID']);

                #get unpaid bill items
                $tr_code_bill = substr_replace($tr_code, '1', 0, 1);
                $pending_item = $this->get_pending_item($acc_id, $tr_code_bill, '', date('m', strtotime($dt_bill)), date('Y', strtotime($dt_bill)), 0);

                $balance_amount = $amount;
                foreach ($pending_item as $item):
                    $balance_tobe_paid = ($item['AMOUNT'] - $item['TOTAL_PAID'] + $item['TOTAL_JOURNAL']);
                    if ($balance_tobe_paid > 0):
                        if ($balance_amount <= $balance_tobe_paid):
                            $total_paid     = $balance_amount + $item['TOTAL_PAID'];
                            $balance_amount = 0;
                        else:
                            $total_paid = $item['AMOUNT'] + $item['TOTAL_JOURNAL'];
                            $balance_amount = $balance_amount - $balance_tobe_paid;
                        endif;

                        if ($item['ITEM_ID_PAYMENT'] == ''):
                            $data_update_item['item_id_payment'] = $insert_item_id;
                        else:
                            $data_update_item['item_id_payment'] = $item['ITEM_ID_PAYMENT'] . ',' . $insert_item_id;
                        endif;
                        $data_update_item['total_paid'] = $total_paid;
                        $update_status = $this->update_bill_item($data_update_item, $item['ITEM_ID']);

                        #update total_used of payment item
                        $data_payment_item['total_used'] = $amount - $balance_amount;
                        $update_status = $this->update_bill_item($data_payment_item, $insert_item_id);

                        if ($balance_amount == 0 || substr($tr_code, 0, 2) == 12):
                            break;
                        endif;
                    endif;
                endforeach;

                #resit menjadi lebihan
                if ($balance_amount != 0):
                    $pending_item = $this->get_pending_item($acc_id, '', '', date('m', strtotime($dt_bill)), date('Y', strtotime($dt_bill)), 0);
//                    pre($pending_item);
//                    die();
                    foreach ($pending_item as $item):
                        $balance_tobe_paid = ($item['AMOUNT'] - $item['TOTAL_PAID'] + $item['TOTAL_JOURNAL']);
                        if ($balance_tobe_paid > 0):
                            if ($balance_amount <= $balance_tobe_paid):
                                $total_paid = $balance_amount + $item['TOTAL_PAID'];
                                $balance_amount = 0;
                            else:
                                $total_paid = $item['AMOUNT'] + $item['TOTAL_JOURNAL'];
                                $balance_amount = $balance_amount - $balance_tobe_paid;
                            endif;

                            if ($item['ITEM_ID_PAYMENT'] == ''):
                                $data_update_item['item_id_payment'] = $insert_item_id;
                            else:
                                $data_update_item['item_id_payment'] = $item['ITEM_ID_PAYMENT'] . ',' . $insert_item_id;
                            endif;
                            $data_update_item['total_paid'] = $total_paid;
                            $update_status = $this->update_bill_item($data_update_item, $item['ITEM_ID']);

                            #update total_used of payment item
                            $data_payment_item['total_used'] = $amount - $balance_amount;
                            $update_status = $this->update_bill_item($data_payment_item, $insert_item_id);

                            if ($balance_amount == 0 || substr($tr_code, 0, 2) == 12):
                                break;
                            endif;
                        endif;
                    endforeach;
                endif;

                if ($balance_amount != 0):
                    $pending_item = $this->get_pending_item($acc_id, '', '', date('m', strtotime($dt_bill)), date('Y', strtotime($dt_bill)), 0,1);

                    foreach ($pending_item as $item):
                        $balance_tobe_paid = ($item['AMOUNT'] - $item['TOTAL_PAID'] + $item['TOTAL_JOURNAL']);
                        if ($balance_tobe_paid > 0):
                            if ($balance_amount <= $balance_tobe_paid):
                                $total_paid = $balance_amount + $item['TOTAL_PAID'];
                                $balance_amount = 0;
                            else:
                                $total_paid = $item['AMOUNT'] + $item['TOTAL_JOURNAL'];
                                $balance_amount = $balance_amount - $balance_tobe_paid;
                            endif;

                            if ($item['ITEM_ID_PAYMENT'] == ''):
                                $data_update_item['item_id_payment'] = $insert_item_id;
                            else:
                                $data_update_item['item_id_payment'] = $item['ITEM_ID_PAYMENT'] . ',' . $insert_item_id;
                            endif;
                            $data_update_item['total_paid'] = $total_paid;
                            $update_status = $this->update_bill_item($data_update_item, $item['ITEM_ID']);

                            #update total_used of payment item
                            $data_payment_item['total_used'] = $amount - $balance_amount;
                            $update_status = $this->update_bill_item($data_payment_item, $insert_item_id);

                            if ($balance_amount == 0):
                                break;
                            endif;
                        endif;
                    endforeach;
                endif;

                #bayaran tunggakan (tahun lepas) -- update total_paid untuk item tahun lepas
                if (substr($tr_code, 0, 2) == 22):
                    $balance_amount         = $amount - $balance_amount;
                    $tr_code_bill           = substr_replace($tr_code, '11', 0, 2);
                    $tr_code_bill_overdue   = substr_replace($tr_code, '12', 0, 2);
                    $previous_year          = date('Y', strtotime($dt_bill)) - 1;
                    $pending_item           = $this->get_pending_item($acc_id, $tr_code_bill, $tr_code_bill_overdue, date('m', strtotime($dt_bill)), date('Y', strtotime($dt_bill)), 1);

                    foreach ($pending_item as $item):
                        $balance_tobe_paid = ($item['AMOUNT'] - $item['TOTAL_PAID'] + $item['TOTAL_JOURNAL']);
                        if ($balance_tobe_paid > 0):
                            if ($balance_amount <= $balance_tobe_paid):
                                $total_paid = $balance_amount + $item['TOTAL_PAID'];
                                $balance_amount = 0;
                            else:
                                $total_paid = $item['AMOUNT'] + $item['TOTAL_JOURNAL'];
                                $balance_amount = $balance_amount - $balance_tobe_paid;
                            endif;

                            if ($item['ITEM_ID_PAYMENT'] == ''):
                                $data_update_item['item_id_payment'] = $insert_item_id;
                            else:
                                $data_update_item['item_id_payment'] = $item['ITEM_ID_PAYMENT'] . ',' . $insert_item_id;
                            endif;
                            $data_update_item['total_paid'] = $total_paid;
                            $update_status = $this->update_bill_item($data_update_item, $item['ITEM_ID']);

                            if ($balance_amount == 0):
                                break;
                            endif;
                        endif;
                    endforeach;
                endif;

            endforeach;
        endif;
    }

    function get_data_payment($acc_number='')
    {
        db_select('t.*');
        db_select("to_char(t.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_added",false);
        db_from('b_int_payment t');
        db_where('process_status', 0);
        if (!empty($acc_number)):
            db_where('account_number', $acc_number);
        endif;
        $sql = db_get();

        if ($sql):
            return $sql->result_array();
        endif;
    }

    function check_bill_master_exist($bill_no = '', $account_id){
        db_from('b_master');
        db_where('account_id', $account_id);
        if (!empty($bill_no)):
            db_where('bill_number', $bill_no);
        endif;
        $sql = db_get();
        if ($sql):
            return $sql->row_array();
        endif;
    }

    function int_latest($tr_code = '', $acc_no){
        db_from('b_int_latest');
        db_where('account_number', $acc_no);
        db_where('tr_code_new', $tr_code);
        $sql = db_get();
        if ($sql):
            return $sql->row_array();
        endif;
    }

    function data_account_by_acc_num($acc_no){
        db_from('acc_account');
        db_where('account_number', $acc_no);
        $sql = db_get();
        if ($sql):
            return $sql->row_array();
        endif;
    }

    function insert_bill_item($data_insert){
        if (!empty($data_insert['dt_added'])):
            db_set_date_time('dt_added',$data_insert['dt_added']);
        unset($data_insert['dt_added']);
        endif;
        db_insert('B_ITEM', $data_insert);
        return get_insert_id('B_ITEM');
    }

    function update_bill_master($data_update, $id)
    {
        db_where('BILL_ID', $id);
        db_update('B_MASTER', $data_update);
        return true;
    }

    function update_int_payment($data_update, $id)
    {
        db_where('PAYMENT_ID', $id);
        db_update('B_INT_PAYMENT', $data_update);
        return true;
    }

    function get_pending_item($account_id, $tr_code = '', $tr_code_overdue = '', $current_month = '', $current_year = '', $goto_prev_year = 0,$overdue_all = 0)
    {
        $sql = "SELECT M.BILL_MONTH,M.BILL_YEAR,I.*,TO_CHAR(I.DT_ADDED, 'yyyy-mm-dd') as DT_ADDED_ITEM FROM B_ITEM I " .
            " INNER JOIN B_MASTER M ON I.BILL_ID=M.BILL_ID" .
            " WHERE M.ACCOUNT_ID =" . $account_id .
            " AND (I.BILL_CATEGORY = 'B' OR (I.BILL_CATEGORY = 'J' AND I.AMOUNT > 0))";
        if ($goto_prev_year == 1):
            $sql = $sql . " AND M.BILL_YEAR != '" . $current_year . "'";
        else:
            $sql = $sql . " AND M.BILL_YEAR = '" . $current_year . "'";
        endif;
        if (!empty($tr_code_overdue)):
            $sql = $sql . " AND (I.TR_CODE =" . $tr_code_overdue . " OR I.TR_CODE =" . $tr_code . ") " .
                " AND I.PREV_YEAR_OUTSTANDING=0 ";
        elseif (!empty($tr_code)):
            $sql = $sql . " AND I.TR_CODE =" . $tr_code;
        endif;
        if($overdue_all==1):
            $sql = $sql . " AND I.TR_CODE like '12%'";
        endif;
        $sql = $sql . " ORDER BY I.ITEM_ID DESC";

        $result = db_query($sql);
        if ($result):
            return $result->result_array();
        endif;
    }

    function insert_bill_master($data_insert){
        if(!empty($data_insert['dt_added'])):
            db_set_date_time('dt_added',$data_insert['dt_added']);
            unset($data_insert['dt_added']);
        endif;
        db_insert('B_MASTER',$data_insert);
        return get_insert_id('B_MASTER');
    }

    function sewaan_tr_code($tr_code='',$tr_id=''){
        $this->admin_trcode->from('MCTRANCODE');
        if(!empty($tr_code)):
            $this->admin_trcode->where('MCT_TRCODE',$tr_code);
        endif;
        if(!empty($tr_id)):
            // $this->admin_trcode->where('TR_ID',$tr_id);
        endif;
        $sql = $this->admin_trcode->get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_bill_item($data_update,$id){
        db_where('ITEM_ID',$id);
        db_update('B_ITEM',$data_update);
        return true;
    }
}