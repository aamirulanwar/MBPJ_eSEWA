<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
ini_set('MAX_EXECUTION_TIME', '-1');

class Cron extends CI_Controller
{
    public $curuser;
    private $today_date;

    public function __construct()
    {
        parent::__construct();
        $this->today_date   = date('d-m-Y');
        $this->today_day    = date('d');
        $this->today_year   = date('Y');
        $this->last_year    = date('Y')-1;
        $this->today_month  = date('m');
        load_model('Account/M_acc_account', 'm_acc_account');
        load_model('Cron/M_cron_config', 'm_cron_config');
        load_model('Bill/M_bill_item', 'm_bill_item');
        load_model('Payroll/M_payroll', 'm_payroll');
        load_model('Account/M_acc_user', 'm_acc_user');
        load_model('Integration/M_b_int_payment', 'm_b_int_payment');

        $this->admin_trcode     = $this->load->database('admin',TRUE);
        load_library('Bill_lib');
    }

    function _remap($method)
    {
        $array = array(
            'payment',
            'daily_cron'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function index(){

    }

    function payment(){
        load_library('payment_process_lib');
        $this->payment_process_lib->payment_process();
    }

    function daily_cron(){
        ini_set('MAX_EXECUTION_TIME', '-1');

        #get payroll insert to int_payment
        $data_cron_payroll = $this->m_cron_config->get_data_cron('GET_DATA_PAYROLL');
        if($data_cron_payroll):
            try {
                $this->get_payroll_payment();
            } catch (Exception $e) {
                echo 'Caught exception function $data_cron_generate_bill_01: ',  $e->getMessage(), "\n";
            }
        endif;

        #bill every month
        $data_cron_generate_bill_01 = $this->m_cron_config->get_data_cron('GENERATE_BILL_01');
        if($data_cron_generate_bill_01):
            if($data_cron_generate_bill_01['STATUS_PROCESS']==3 || $data_cron_generate_bill_01['STATUS_PROCESS']==1):
                if($this->today_day == '1'):
                    try {
                        $this->generate_bil_01();
                    } catch (Exception $e) {
                        echo 'Caught exception function $data_cron_generate_bill_01: ',  $e->getMessage(), "\n";
                    }
                endif;
            elseif($data_cron_generate_bill_01['STATUS_PROCESS']==2):
                if($this->today_day != '1'):
                    try {
                        $this->generate_bil_01($data_cron_generate_bill_01['LAST_ACCOUNT_ID']);
                    } catch (Exception $e) {
                        echo 'Caught exception function $data_cron_generate_bill_01: ',  $e->getMessage(), "\n";
                    }
                endif;
            endif;
        endif;

        #bill faedah
        $data_cron_generate_bill_08 = $this->m_cron_config->get_data_cron('GENERATE_BILL_08');
        if($data_cron_generate_bill_08):
            if($data_cron_generate_bill_08['STATUS_PROCESS']==3 || $data_cron_generate_bill_08['STATUS_PROCESS']==1):
                if($this->today_day == '8'):
                    try {
                        $this->generate_bil_08();
                    } catch (Exception $e) {
                        echo 'Caught exception function $data_cron_generate_bill_08: ',  $e->getMessage(), "\n";
                    }
                endif;
            elseif($data_cron_generate_bill_08['STATUS_PROCESS']==2):
                if($this->today_day > '8'):
                    try {
                        $this->generate_bil_08($data_cron_generate_bill_08['LAST_ACCOUNT_ID']);
                    } catch (Exception $e) {
                        echo 'Caught exception function $data_cron_generate_bill_08: ',  $e->getMessage(), "\n";
                    }
                endif;
            endif;
        endif;

        #lebihan setiap awal tahun dan tunggakan
        $data_cron_generate_lebihan = $this->m_cron_config->get_data_cron('GENERATE_LEBIHAN_AWAL_TAHUN');
        if($data_cron_generate_lebihan):
            $last_year_run = date('Y',strtotime($data_cron_generate_lebihan['DATE_LAST_PROCESS']));

            if(($data_cron_generate_lebihan['STATUS_PROCESS']==1 || $data_cron_generate_lebihan['STATUS_PROCESS']==3) && $last_year_run<$this->today_year):
                try {
                    $this->generate_lebihan_tunggakan();
                } catch (Exception $e) {
                    echo 'Caught exception function $data_cron_generate_lebihan: ',  $e->getMessage(), "\n";
                }
            elseif($data_cron_generate_lebihan['STATUS_PROCESS']==2):
                try {
                    $this->generate_lebihan_tunggakan($data_cron_generate_lebihan['LAST_ACCOUNT_ID']);
                } catch (Exception $e) {
                    echo 'Caught exception function $data_cron_generate_lebihan: ',  $e->getMessage(), "\n";
                }
            endif;
        endif;
    }

    function get_payroll_payment(){
        $data_payroll = $this->m_payroll->get_payroll_new();
        if($data_payroll):
            $cron_start['status_process']   = 2;
            $cron_start['dt_start']         = timenow();
            $this->m_cron_config->update_cron('GET_DATA_PAYROLL',$cron_start);

            $total_acc = 0;
            foreach ($data_payroll as $row):
                $get_acc_base_on_ic = $this->m_acc_user->get_account_base_on_ic($row['NO_KP']);
                if($get_acc_base_on_ic):
                    $no_resit = date('Ym').sprintf('%06d',$get_acc_base_on_ic['ACCOUNT_ID']).'2';
                    $data_bill_lib['account_id']    = $get_acc_base_on_ic['ACCOUNT_ID'];
                    $data_bill  = $this->bill_lib->generate_bill($data_bill_lib);
                    if($data_bill['item']):

                        #insert in B_int_payment
                        $data_insert_payment['ACCOUNT_NUMBER']  = $data_bill['account_number'];
                        $data_insert_payment['NO_RESIT']        = $no_resit;
                        $data_insert_payment['PAY_MODE']        = 1;
                        $data_insert_payment['AMOUNT']          = $row['AMAUN'];
                        $data_insert_payment['TR_CODE']         = '';
                        $data_insert_payment['PROCESS_STATUS']  = 0;
                        $data_insert_payment['TR_CODE_NEW']     = substr_replace($get_acc_base_on_ic['TRCODE_CATEGORY'],'2',0,1);

                        $payment_id = $this->m_b_int_payment->insert($data_insert_payment);

                        if($payment_id):
                            #update_status payroll
                            $data_where['BULAN']        = $row['BULAN'];
                            $data_where['TAHUN']        = $row['TAHUN'];
                            $data_where['NAMA']         = $row['NAMA'];
                            $data_where['NO_PEKERJA']   = $row['NO_PEKERJA'];
                            $data_where['NO_KP']        = $row['NO_KP'];
                            $data_where['AMAUN']        = $row['AMAUN'];
                            $data_where['TKH_HANTAR']   = $row['TKH_HANTAR'];
                            $data_where['TBACA']        = $row['TBACA'];

                            $data_update['TBACA']       = 'Y';
                            $this->m_payroll->update_payroll_process($data_where,$data_update);
                        endif;
                    endif;
                endif;

                $total_acc = $total_acc+1;
                $cron_process['TOTAL_ROW']          = $total_acc;

                $this->m_cron_config->update_cron('GET_DATA_PAYROLL',$cron_process);

            endforeach;
            $cron_end['status_process']   = 3;
            $cron_end['dt_end']           = timenow();
            $cron_end['LAST_ACCOUNT_ID']  = 0;
            $this->m_cron_config->update_cron('GET_DATA_PAYROLL',$cron_end);
        endif;
    }

    function generate_tunggakan($data_item){
        #tunggakan thaun lepas - cari item dengan no bermula 12 counter no 22. - display
//        pre($data_item);
        $account_id = $data_item['account_id'];
        $cur_data = $this->get_item_tunggakan_cur_year_by_type($account_id,'B');
//        $cur_data = array();
        if($cur_data):
            return true;
        endif;

        $all_amount_need_paid = 0;
        $tunggakan_tahun_lepas = $this->get_item_tunggakan_last_year_by_type($account_id,'B');

//        pre($tunggakan_tahun_lepas);
//        echo 'tahun lepas';

        $bill_item = array();
        if($tunggakan_tahun_lepas):
            foreach ($tunggakan_tahun_lepas as $row):
                $data_bill_row = array();

                $get_bill_sum   = $this->get_bill_sum($account_id,$row['TR_CODE']);
                $total_bil      = $get_bill_sum['BILL'] + ($get_bill_sum['JOURNAL']);

                $code_payment   = substr($row['TR_CODE'],1);
                $code_payment   = '2'.$code_payment;

                $get_resit_sum  = $this->get_bill_sum($account_id,$code_payment);
                $total_resit    = $get_resit_sum['RESIT'] + ($get_resit_sum['JOURNAL']);

                $total_amount = $total_bil - $total_resit;

                if($total_amount>0):
                    $all_amount_need_paid = $all_amount_need_paid+$total_amount;
                    $tr_code_details = $this->get_data_tr_code($row['TR_CODE']);

                    $data_bill_row['priority'] = 99;
                    if($tr_code_details):
                        $data_bill_row['priority']      = $tr_code_details['MCT_PRIORT'];
                    endif;
                    $data_bill_row['tr_code_old']   = $row['TR_CODE_OLD'];
                    $data_bill_row['tr_code']       = $row['TR_CODE'];
                    $data_bill_row['amount']        = $total_amount;
                    $data_bill_row['item_desc']     = $row['ITEM_DESC'];
                    $data_bill_row['gst_status']    = 0;

                    $bill_item[]                    = $data_bill_row;
                endif;
            endforeach;
        endif;

        #tunggakan semasa
        $tunggakan_semasa = $this->get_item_tunggakan_semasa($account_id,'B');
//        echo last_query();
//
//        pre($tunggakan_semasa);
//        echo 'tahun semasa';

        if($tunggakan_semasa):
            foreach ($tunggakan_semasa as $row):
                $data_bill_row2 = array();

                $get_bill_sum   = $this->get_bill_sum($account_id,$row['TR_CODE'],'tunggakan');
                $total_bil      = $get_bill_sum['BILL'] + ($get_bill_sum['JOURNAL']);

                $code_payment   = substr($row['TR_CODE'],1);
                $code_payment   = '2'.$code_payment;

                $get_resit_sum  = $this->get_bill_sum($account_id,$code_payment,'tunggakan');
                $total_resit    = $get_resit_sum['RESIT'] + ($get_resit_sum['JOURNAL']);

                $total_amount = $total_bil - $total_resit;

                $code_tunggakan   = substr($row['TR_CODE'],2);
                $code_tunggakan   = '12'.$code_tunggakan;

//                echo $total_amount.'<br>';

                if($total_amount>0):
                    $all_amount_need_paid = $all_amount_need_paid+$total_amount;
                    $tr_code_details = $this->get_data_tr_code($code_tunggakan);

                    $data_bill_row2['tr_code_old']  = $row['TR_CODE_OLD'];
                    $data_bill_row2['tr_code_old']   = substr($data_bill_row2['tr_code_old'],1);
                    $data_bill_row2['tr_code_old']   = '2'.$data_bill_row2['tr_code_old'];

                    $data_bill_row2['priority']     = 99;
                    if($tr_code_details):
                        $data_bill_row2['priority']     = $tr_code_details['MCT_PRIORT'];
                    endif;

                    $data_bill_row2['tr_code']      = $code_tunggakan;
                    $data_bill_row2['amount']       = $total_amount;
                    $data_bill_row2['item_desc']    = 'TUNGGAKAN '.$row['ITEM_DESC']. ' TAHUN LEPAS';
                    $data_bill_row2['gst_status']   = $row['TR_GST_STATUS'];

                    $bill_item[]                    = $data_bill_row2;
                endif;
            endforeach;
        endif;

        usort($bill_item, function($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return $a['priority'] > $b['priority'] ? 1 : -1;
            }

            return $a['priority'] > $b['priority'] ? 1 : -1;
        });

        $amount_tunggakan = $data_item['amount_lebihan'];

        $new_bill_item = array();

//        echo 'new lebihan';
//        pre($new_bill_item);

        if($bill_item):
            #order by priority
            foreach ($bill_item as $item):
                if($amount_tunggakan>0):
                    if($amount_tunggakan<=$item['amount']):
                        $item['amount_new'] = $amount_tunggakan;
                    else:
                        $amount_tunggakan   = $amount_tunggakan-$item['amount'];
                        $item['amount_new'] = $item['amount'];
                    endif;
                else:
                    $item['amount_new'] = 0;
                endif;

                #insert at new bill
                $data_bill  = $this->bill_lib->get_cur_bill_data($account_id,$data_item['bill_type']);

                if(empty($data_bill)):

                    $data_insert['ACCOUNT_ID']      = $account_id;
                    $data_insert['BILL_NUMBER']     = date('Y').date('m').sprintf('%06d',$account_id).'1';
                    $data_insert['BILL_MONTH']      = $this->today_month;
                    $data_insert['BILL_YEAR']       = $this->today_year;
                    $data_insert['NUMBER_GENERATE'] = 1;
                    $data_insert['TOTAL_AMOUNT']    = 0;
                    $data_insert['TOTAL_PAID']      = 0;
                    $data_insert['BILL_TYPE']       = $data_item['bill_type'];
                    $data_insert['BILL_CATEGORY']   = 'B';

                    $bill_id            = $this->bill_lib->insert_bill_master($data_insert);
                    $data['bill_id']    = $bill_id;

                    $insert_code['BILL_ID']                 = $bill_id;
                    $insert_code['TR_CODE']                 = $item['tr_code'];
                    $insert_code['TR_CODE_OLD']             = $item['tr_code_old'];
                    $insert_code['AMOUNT']                  = $item['amount_new'];
                    $insert_code['PRIORITY']                = $item['priority'];
                    $insert_code['ACCOUNT_ID']              = $account_id;
                    $insert_code['ITEM_DESC']               = $item['item_desc'];
                    $insert_code['TR_GST_STATUS']           = $item['gst_status'];
                    $insert_code['PREV_YEAR_OUTSTANDING']   = 1;

                    $this->bill_lib->insert_bill_item($insert_code);
                else:

                    $get_data_trcode = $this->m_bill_item->get_tunggakan_to_new_year($account_id,$item['tr_code'],$this->today_year);

                    if($get_data_trcode):
                        $insert_code['BILL_ID']                 = $data_bill['BILL_ID'];
                        $insert_code['TR_CODE']                 = $item['tr_code'];
                        $insert_code['TR_CODE_OLD']             = $item['tr_code_old'];
                        $insert_code['AMOUNT']                  = $item['amount_new'];
                        $insert_code['PRIORITY']                = $item['priority'];
                        $insert_code['ACCOUNT_ID']              = $account_id;
                        $insert_code['ITEM_DESC']               = $item['item_desc'];
                        $insert_code['TR_GST_STATUS']           = $item['gst_status'];
                        $insert_code['PREV_YEAR_OUTSTANDING']   = 1;

                        $this->bill_lib->insert_bill_item($insert_code);
                    else:
                        $insert_update['AMOUNT']  = $get_data_trcode['AMOUNT']+$item['amount_new'];
                        $this->bill_lib->update_bill_item($insert_update,$get_data_trcode['ITEM_ID']);
                    endif;
                endif;

                $new_bill_item[] = $item;
            endforeach;
        endif;

        return $new_bill_item;
    }

    function generate_lebihan_tunggakan($last_acc_id=''){
        $data_acc_search['last_acc_id']         = $last_acc_id;
        $data_acc_search['not_added_this_year'] = $this->today_year;
        $data_acc_search['status_bill']         = STATUS_BILL_ACTIVE;
        $data_account = $this->m_acc_account->get_account_cron('','',$data_acc_search);

        $total_acc = 0;
        if($data_account):
            $cron_start['status_process']   = 2;
            $cron_start['dt_start']         = timenow();
            $this->m_cron_config->update_cron('GENERATE_LEBIHAN_AWAL_TAHUN',$cron_start);

            foreach ($data_account as $row):
                #check already get lebihan or tunggakan
//                $row['ACCOUNT_ID'] = 8064;
                $lebihan = $this->m_bill_item->get_lebihan_current_year($row['ACCOUNT_ID']);
//                $lebihan = array();
                if(empty($lebihan)):
                    $lebihan_data    = $this->get_lebihan($row['ACCOUNT_ID']);

                    if($lebihan_data):
//                        load_library('Bill_lib');
                        $amount_lebihan = (($lebihan_data['BILL']+($lebihan_data['JOURNAL_B'])) - $lebihan_data['RESIT']+($lebihan_data['JOURNAL_R']));
                        if($amount_lebihan<0):
                            #insert at new bill
                            $data_bill  = $this->bill_lib->get_cur_bill_data($row['ACCOUNT_ID'],$row['BILL_TYPE']);

                            if(empty($data_bill)):

                                $data_insert['ACCOUNT_ID']      = $row['ACCOUNT_ID'];
                                $data_insert['BILL_NUMBER']     = date('Y').date('m').sprintf('%06d',$row['ACCOUNT_ID']).'1';
                                $data_insert['BILL_MONTH']      = $this->today_month;
                                $data_insert['BILL_YEAR']       = $this->today_year;
                                $data_insert['NUMBER_GENERATE'] = 1;
                                $data_insert['TOTAL_AMOUNT']    = 0;
                                $data_insert['TOTAL_PAID']      = 0;
                                $data_insert['BILL_TYPE']       = $row['BILL_TYPE'];
                                $data_insert['BILL_CATEGORY']   = 'B';

                                $bill_id            = $this->bill_lib->insert_bill_master($data_insert);
                                $data['bill_id']    = $bill_id;

                                $code_lebihan  = $this->bill_lib->get_data_tr_code(TR_CODE_LEBIHAN);

                                $insert_code['BILL_ID']                 = $bill_id;
                                $insert_code['TR_CODE']                 = TR_CODE_LEBIHAN;
                                $insert_code['TR_CODE_OLD']             = TR_CODE_OLD_LEBIHAN;
                                $insert_code['AMOUNT']                  = $amount_lebihan;
                                $insert_code['PRIORITY']                = $code_lebihan['MCT_PRIORT'];
                                $insert_code['ACCOUNT_ID']              = $row['ACCOUNT_ID'];
                                $insert_code['ITEM_DESC']               = $code_lebihan['MCT_TRDESC'].' TAHUN LEPAS';
                                $insert_code['PREV_YEAR_OUTSTANDING']   = 1;

                                $this->bill_lib->insert_bill_item($insert_code);
                            else:
                                $code_lebihan  = $this->bill_lib->get_data_tr_code(TR_CODE_LEBIHAN);

                                $insert_code['BILL_ID']                 = $data_bill['BILL_ID'];
                                $insert_code['TR_CODE']                 = TR_CODE_LEBIHAN;
                                $insert_code['TR_CODE_OLD']             = $code_lebihan['MCT_TRCODE'];
                                $insert_code['AMOUNT']                  = $amount_lebihan;
                                $insert_code['PRIORITY']                = $code_lebihan['MCT_PRIORT'];
                                $insert_code['ACCOUNT_ID']              = $row['ACCOUNT_ID'];
                                $insert_code['ITEM_DESC']               = $code_lebihan['MCT_TRDESC']. ' TAHUN LEPAS';
                                $insert_code['PREV_YEAR_OUTSTANDING']   = 1;

                                $this->bill_lib->insert_bill_item($insert_code);
                            endif;
                        #tunggakan
                        elseif ($amount_lebihan>0):

                            $row_lebihan['amount_resit']        = $lebihan_data['RESIT']+($lebihan_data['JOURNAL_R']);
                            $row_lebihan['amount_bill']         = $lebihan_data['BILL']+($lebihan_data['JOURNAL_B']);
                            $row_lebihan['amount_lebihan']      = $amount_lebihan;
                            $row_lebihan['account_id']          = $row['ACCOUNT_ID'];
                            $row_lebihan['bill_type']           = $row['BILL_TYPE'];

                            $this->generate_tunggakan($row_lebihan);
                        endif;
                    endif;
                endif;

                $total_acc = $total_acc+1;
                $cron_process['LAST_ACCOUNT_ID']    = $row['ACCOUNT_ID'];
                $cron_process['TOTAL_ROW']          = $total_acc;
                $this->m_cron_config->update_cron('GENERATE_LEBIHAN_AWAL_TAHUN',$cron_process);
                endforeach;

            $cron_end['status_process']     = 3;
            $cron_end['LAST_ACCOUNT_ID']    = 0;
            $cron_end['dt_end']             = timenow();
            $this->m_cron_config->update_cron('GENERATE_LEBIHAN_AWAL_TAHUN',$cron_end);
        endif;
    }

    function generate_bil_01($last_acc_id=''){
//        load_library('Bill_lib');

        $data_acc_search['status_bill'] = STATUS_BILL_ACTIVE;
        $data_acc_search['last_acc_id'] = $last_acc_id;

        $data_account = $this->m_acc_account->get_account_cron('','',$data_acc_search);

        $total_acc = 0;
        if($data_account):
            $cron_start['status_process']   = 2;
            $cron_start['dt_start']         = timenow();
            $this->m_cron_config->update_cron('GENERATE_BILL_01',$cron_start);
            foreach ($data_account as $row):
                $data_bill_lib['account_id']    = $row['ACCOUNT_ID'];
                $this->bill_lib->generate_bill($data_bill_lib);

                $total_acc = $total_acc+1;
                $cron_process['LAST_ACCOUNT_ID']    = $row['ACCOUNT_ID'];
                $cron_process['TOTAL_ROW']          = $total_acc;
                $this->m_cron_config->update_cron('GENERATE_BILL_01',$cron_process);
            endforeach;

            $cron_end['status_process']   = 3;
            $cron_end['dt_end']           = timenow();
            $cron_end['LAST_ACCOUNT_ID']  = 0;
            $this->m_cron_config->update_cron('GENERATE_BILL_01',$cron_end);
        endif;
    }

    function generate_bil_08($last_acc_id=''){
//        load_library('Bill_lib');

        $data_acc_search['status_bill'] = STATUS_BILL_ACTIVE;
        $data_acc_search['last_acc_id'] = $last_acc_id;

        $data_account = $this->m_acc_account->get_account_cron('','',$data_acc_search);
        $total_acc = 0;
        if($data_account):
            $cron_start['status_process']   = 2;
            $cron_start['dt_start']         = timenow();
            $this->m_cron_config->update_cron('GENERATE_BILL_08',$cron_start);
            foreach ($data_account as $row):
                $data_bill_lib['account_id']    = $row['ACCOUNT_ID'];
                $this->bill_lib->generate_bill($data_bill_lib);

                $total_acc = $total_acc+1;
                $cron_process['LAST_ACCOUNT_ID']    = $row['ACCOUNT_ID'];
                $cron_process['TOTAL_ROW']          = $total_acc;

                $this->m_cron_config->update_cron('GENERATE_BILL_08',$cron_process);
            endforeach;

            $cron_end['status_process']   = 3;
            $cron_end['dt_end']           = timenow();
            $cron_end['LAST_ACCOUNT_ID']  = 0;
            $this->m_cron_config->update_cron('GENERATE_BILL_08',$cron_end);
        endif;
    }

    function get_lebihan($account_id){
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'B' THEN AMOUNT END) as BILL");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'R' THEN AMOUNT END) as RESIT");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' and substr(i.TR_CODE,0,1)=2 THEN AMOUNT END) as JOURNAL_R");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' and substr(i.TR_CODE,0,1)=1 THEN AMOUNT END) as JOURNAL_B");
        db_from('b_item i');
        db_join('b_master m','i.bill_id=m.bill_id');
        db_where('i.account_id',$account_id);
        db_where('m.bill_year',$this->last_year);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_item_tunggakan_semasa($account_id,$type='B'){
        db_select("SUM(i.AMOUNT) as AMOUNT");
        db_select("SUM(i.TOTAL_PAID) as TOTAL_PAID");
        db_select("SUM(i.TOTAL_JOURNAL) as TOTAL_JOURNAL");
        db_select("TR_CODE,ITEM_DESC,TR_GST_STATUS,TR_CODE_OLD");
//        db_select('sum(AMOUNT) as total_amount,TR_CODE,ITEM_DESC,i.GST_TYPE');
        db_from('B_ITEM i');
        db_join('b_master m','m.bill_id = i.bill_id');
//        db_where("substr(i.TR_CODE,0,2)",'11');
        db_where('m.bill_year',$this->last_year);
        db_where('m.account_id',$account_id);
        db_where('i.bill_category',$type);
        db_where("i.PREV_YEAR_OUTSTANDING",0);
        db_group('TR_CODE,TR_CODE_OLD,ITEM_DESC,TR_GST_STATUS');

        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_bill_sum($account_id,$trcode,$type=''){
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'B' THEN AMOUNT END) as BILL");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'R' THEN AMOUNT END) as RESIT");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' THEN AMOUNT END) as JOURNAL");
        db_from('b_item i');
        db_join('b_master m','i.bill_id=m.bill_id');
        db_where('i.account_id',$account_id);
        db_where('i.TR_CODE',$trcode);
        db_where('m.bill_year',$this->last_year);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_item_tunggakan_last_year_by_type($account_id,$type='B'){
        db_select('*');
        db_from('B_ITEM i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_where("substr(i.TR_CODE,0,2)",'12');
        db_where('m.bill_year',$this->last_year);
        db_where('m.account_id',$account_id);
        db_where('i.bill_category',$type);
        db_where("i.PREV_YEAR_OUTSTANDING",1);
//        db_group('TR_CODE,ITEM_DESC');

        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_item_tunggakan_cur_year_by_type($account_id,$type='B'){
        db_select('*');
        db_from('B_ITEM i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_where("substr(i.TR_CODE,0,2)",'12');
        db_where('m.bill_year',$this->today_year);
        db_where('m.account_id',$account_id);
        db_where('i.bill_category',$type);
        db_where("i.PREV_YEAR_OUTSTANDING",1);
//        db_group('TR_CODE,ITEM_DESC');

        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_data_tr_code($tr_code){

        $this->admin_trcode->select('*');
        $this->admin_trcode->from('MCTRANCODE');
        $this->admin_trcode->where('MCT_MDCODE','B');
        $this->admin_trcode->where('MCT_TRCODENEW',$tr_code);
        $sql = $this->admin_trcode->get();
        if($sql):
            return $sql->row_array();
        endif;
    }
}