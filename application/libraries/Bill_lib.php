<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

/**
 * PENERANGAN BIL
 * bil automatik 1hb
 * item menjadi tunggakan pada 8hb - kenakan faedah lewat bayar (8%)
 * tiada faedah lewat bayar (8%) untuk papan iklan & kuarters

 * >> papan iklan - bil tahunan
 * >> taman mudun - balance bayaran bulanan
 * >> others - bil bulanan
 * tunggakan tahun lepas asingkan dari tunggakan tahun semasa
 * lebihan bayaran - paparkan balance sahaja (jika ada)
 * journal tidak dipaparkan di dalam bil (hanya final amount dipaparkan)

 * PENERANGAN BAYARAN
 * kuarters - bayaran melalui potongan gaji (semak table yang diupdate oleh finance)
 * others - bayaran melalui sistem kutipan (resit dimasukkan/dikemaskini oleh kutipan)
 * bayaran item mengikut priority
 * lebihan bayaran dibawa ke bil hadapan dan automatik deduct mengikut priority

 * @author Shahrul
 */
class Bill_lib {

    private $ci;
    private $cur_month;
    private $cur_year;
    private $status_gst;
    private $status_faedah;
    private $status_notice;

    #1 - cel already generate
    #1.1 - cek bill type yearly or monthly
    #2 - if not - cek previous data
    #3 - if any - cek tunggakan
    #4 - create master
    #5 - insert tunggakan if any
    #6 - insert current bill

    #bil semasa - cek y dah bayar juge tuk bulan same

    public function __construct(){
        $this->ci                   = & get_instance();
        $this->cur_month            = date('m');
        $this->cur_year             = date('Y');
        load_library('Audit_trail_lib');
        $this->audit_trail_lib      = $this->ci->load->library('audit_trail_lib');
        $this->admin_trcode         = $this->ci->load->database('admin',TRUE);
        $this->status_gst           = false;
        $this->status_faedah        = false;
        $this->status_notice        = true;
        $this->tunggakan_notice_up  = false;

        $this->ci->load->library('payment_process_lib');
    }

    function generate_bill($data,$user_id=0){

        $data_stage_1 = $this->generate_bill_stage_1($data);

        if($data_stage_1):
            #calculate lebihan
            $data_lebihan = $this->calculate_lebihan($data_stage_1);
            if($data_lebihan['new_lebihan']>0):
                $data_stage_1 = $this->generate_item($data_stage_1,$data_lebihan['new_lebihan']);
            endif;

            if($this->tunggakan_notice_up == true):
                $this->insert_notice($data_stage_1);
            else:
                $data_update_acc['NOTICE_LEVEL'] = 0;
                $this->update_acc($data['account_id'],$data_update_acc);
            endif;

            #update done generate next bill for auto generate
            if($data_stage_1['status_acc']==STATUS_ACCOUNT_NONACTIVE):
                if($this->tunggakan_notice_up==false):
                    $data_update_acc['STATUS_BILL'] = STATUS_BILL_NONACTIVE;
                    $upd_acc = $this->update_acc($data_stage_1['account_id'],$data_update_acc);

                    if($upd_acc):
                        $data_audit_trail['log_id']                  = 3002;
                        $data_update_acc['remark_2']                 = 'Set acc bill status to non active because no more tunggakan';
                        $data_audit_trail['remark']                  = $data_update_acc;
                        $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                        $data_audit_trail['user_id']                 = 0;
                        $data_audit_trail['refer_id']                = $data_stage_1['account_id'];
                        $this->add($data_audit_trail);
                    endif;
                endif;
            endif;

            if(empty($data_stage_1['item'])):
                $data_return = $this->generate_empty_bill($data_stage_1);
                $data_stage_1['item'] = $data_return;
            endif;

//            pre($data_stage_1);
//            echo '<hr>';
            return $data_stage_1;
        endif;
    }

    function generate_bill_stage_1($data,$total_paid=0){
        $this->tunggakan_notice_up  = false;

        $account_id = $data['account_id'];
        if (empty($account_id)):
            return false;
        endif;

        $data_acc = $this->get_account_details($account_id);

        if(empty($data_acc)):
            return false;
        endif;

        $this->ci->payment_process_lib->payment_process($data_acc['ACCOUNT_NUMBER']);

        $data['account_number'] = $data_acc['ACCOUNT_NUMBER'];
        $data['notice_level']   = $data_acc['NOTICE_LEVEL'];
        $data['status_bill']    = $data_acc['STATUS_BILL'];
        $data['status_acc']     = $data_acc['STATUS_ACC'];
        $data_bill  = $this->get_cur_bill_data($account_id,$data_acc['BILL_TYPE']);
        $bill_id        = 0;
        $bill_item      = array();
        $charge_faedah  = false;

        if(empty($data_bill) && $data_acc['STATUS_ACC']==STATUS_ACCOUNT_ACTIVE):

            $data_insert['ACCOUNT_ID']      = $account_id;
            $data_insert['BILL_NUMBER']     = date('Y').date('m').sprintf('%06d',$account_id).'1';
            $data_insert['BILL_MONTH']      = $this->cur_month;
            $data_insert['BILL_YEAR']       = $this->cur_year;
            $data_insert['NUMBER_GENERATE'] = 1;
            $data_insert['TOTAL_AMOUNT']    = 0;
            $data_insert['TOTAL_PAID']      = 0;
            $data_insert['BILL_TYPE']       = $data_acc['BILL_TYPE'];
            $data_insert['BILL_CATEGORY']   = 'B';

            $bill_id            = $this->insert_bill_master($data_insert);
            $data['bill_id']    = $bill_id;

            #insert category price
            $code_category  = $this->get_data_tr_code($data_acc['TRCODE_CATEGORY']);

            $insert_code    = array();
            if($code_category):
                $insert_code['BILL_ID']     = $bill_id;
                $insert_code['TR_CODE']     = $code_category['MCT_TRCODENEW'];
//                $insert_code['TR_ID']       = $code_category['TR_ID'];
                $insert_code['AMOUNT']      = $data_acc['RENTAL_CHARGE'];
                $insert_code['PRIORITY']    = $code_category['MCT_PRIORT'];
//                $insert_code['TR_TYPE']     = $code_category['TR_TYPE'];
                $insert_code['ACCOUNT_ID']  = $account_id;
                $insert_code['ITEM_DESC']   = $code_category['MCT_TRDESC'];
                $insert_code['TR_CODE_OLD'] = $code_category['MCT_TRCODE'];
            else:
                $insert_code['BILL_ID']     = $bill_id;
                $insert_code['TR_CODE']     = $data_acc['TRCODE_CATEGORY'];
//                $insert_code['TR_ID']       = $code_category['TR_ID'];
                $insert_code['AMOUNT']      = $data_acc['RENTAL_CHARGE'];
                $insert_code['PRIORITY']    = 1;
//                $insert_code['TR_TYPE']     = $code_category['TR_TYPE'];
                $insert_code['ACCOUNT_ID']  = $account_id;
                $insert_code['ITEM_DESC']   = $data_acc['TRCODE_CATEGORY'];
                $insert_code['TR_CODE_OLD'] = $data_acc['TRCODE_CATEGORY_OLD'];
            endif;

            $item_id = $this->insert_bill_item($insert_code);

            $data_bill_category['tr_code']      = $insert_code['TR_CODE'];
            $data_bill_category['tr_code_old']  = $code_category['MCT_TRCODE'];
            $data_bill_category['amount']       = $insert_code['AMOUNT'];
            $data_bill_category['item_desc']    = $insert_code['ITEM_DESC'];
            $data_bill_category['priority']     = $code_category['MCT_PRIORT'];
            $data_bill_category['gst_status']   = 0;
            $data_bill_category['item_id']      = $item_id;

            $bill_item[] = $data_bill_category;

            #insert gst
            if($this->status_gst==true):
                $code_gst       = $this->get_data_tr_code($data_acc['TRCODE_GST']);
                $insert_code    = array();
                if($code_gst):
                    $insert_code['BILL_ID']     = $bill_id;
                    $insert_code['TR_CODE']     = $code_gst['MCT_TRCODENEW'];
    //                $insert_code['TR_ID']       = $code_gst['TR_ID'];
    //                $insert_code['AMOUNT']      = num(($code_gst['PERCENTAGE']*$data_acc['RENTAL_CHARGE'])/100,1);
                    #get percent
                    $perc_gst = get_perc_from_sentence($code_gst['MCT_TRDESC']);
                    $insert_code['AMOUNT']      = num(($perc_gst*$data_acc['RENTAL_CHARGE'])/100,1);
                    $insert_code['PRIORITY']    = $code_gst['MCT_PRIORT'];
    //                $insert_code['TR_TYPE']     = $code_gst['TR_TYPE'];
                    $insert_code['ACCOUNT_ID']  = $account_id;
                    $insert_code['GST_TYPE']        = 1;
                    $insert_code['TR_GST_STATUS']   = 1;
                    $insert_code['ITEM_DESC']   = $code_gst['MCT_TRDESC'];
                    $insert_code['TR_CODE_OLD'] = $code_gst['MCT_TRCODE'];
                    $item_id = $this->insert_bill_item($insert_code);

                    $data_bill_gst['tr_code']       = $insert_code['TR_CODE'];
                    $data_bill_gst['tr_code_old']   = $code_gst['MCT_TRCODE'];
                    $data_bill_gst['amount']        = $insert_code['AMOUNT'];
                    $data_bill_gst['item_desc']     = $insert_code['ITEM_DESC'];
                    $data_bill_gst['priority']      = $code_category['MCT_PRIORT'];
                    $data_bill_gst['gst_status']    = 1;
                    $data_bill_gst['item_id']       = $item_id;

                    $bill_item[]                = $data_bill_gst;
                endif;
            endif;

            #insert waste management
            if($data_acc['WASTE_MANAGEMENT_BILLS']==1):
                $code_waste_management = $this->get_data_tr_code(TR_CODE_TIPPING); #cas pengurusan sampah
                $insert_code    = array();
                if($code_waste_management):
                    $insert_code['BILL_ID']     = $bill_id;
                    $insert_code['TR_CODE']     = $code_waste_management['MCT_TRCODENEW'];
//                    $insert_code['TR_ID']       = $code_waste_management['TR_ID'];
                    $insert_code['AMOUNT']      = $data_acc['WASTE_MANAGEMENT_CHARGE'];
                    $insert_code['PRIORITY']    = $code_waste_management['MCT_PRIORT'];
//                    $insert_code['TR_TYPE']     = $code_waste_management['TR_TYPE'];
                    $insert_code['ACCOUNT_ID']  = $account_id;
                    $insert_code['ITEM_DESC']   = $code_waste_management['MCT_TRDESC'];
                    $insert_code['GST_TYPE']        = 1;
                    $insert_code['TR_GST_STATUS']   = 1;
                    $insert_code['TR_CODE_OLD'] = $code_waste_management['MCT_TRCODE'];
                    $item_id = $this->insert_bill_item($insert_code);

                    $data_bill_waste_management['tr_code']      = $insert_code['TR_CODE'];
                    $data_bill_waste_management['tr_code_old']  = $code_waste_management['MCT_TRCODE'];
                    $data_bill_waste_management['amount']       = $insert_code['AMOUNT'];
                    $data_bill_waste_management['item_desc']    = $insert_code['ITEM_DESC'];
                    $data_bill_waste_management['priority']     = $code_waste_management['MCT_PRIORT'];
                    $data_bill_waste_management['gst_status']   = 1;
                    $data_bill_waste_management['item_id']      = $item_id;

                    $bill_item[]                                = $data_bill_waste_management;
                endif;

                if($this->status_gst==true):
                    #insert gst waste management
                    $code_gst_waste = $this->get_data_tr_code(TR_CODE_GST_TIPPING);
                    $insert_code    = array();
                    if($code_gst):
                        $insert_code['BILL_ID']     = $bill_id;
                        $insert_code['TR_CODE']     = $code_gst_waste['MCT_TRCODENEW'];
    //                    $insert_code['TR_ID']       = $code_gst_waste['TR_ID'];
    //                    $insert_code['AMOUNT']      = num(($code_gst_waste['PERCENTAGE']*$data_acc['WASTE_MANAGEMENT_CHARGE'])/100,1);
                        $perc_gst = get_perc_from_sentence($code_gst_waste['MCT_TRDESC']);
                        $insert_code['AMOUNT']      = num(($perc_gst*$data_acc['WASTE_MANAGEMENT_CHARGE'])/100,1);
                        $insert_code['PRIORITY']    = $code_gst_waste['MCT_PRIORT'];
    //                    $insert_code['TR_TYPE']     = $code_gst_waste['TR_TYPE'];
                        $insert_code['ACCOUNT_ID']  = $account_id;
                        $insert_code['ITEM_DESC']   = $code_gst_waste['MCT_TRDESC'];
                        $insert_code['TR_CODE_OLD'] = $code_gst_waste['MCT_TRCODE'];
                        $insert_code['TR_GST_STATUS']   = 1;
                        $insert_code['GST_TYPE']        = GST_TYPE_TIPPING;
                        $item_id = $this->insert_bill_item($insert_code);

                        $data_bill_gst_waste_management['tr_code']      = $insert_code['TR_CODE'];
                        $data_bill_gst_waste_management['tr_code_old']  = $code_gst_waste['MCT_TRCODE'];
                        $data_bill_gst_waste_management['amount']       = $insert_code['AMOUNT'];
                        $data_bill_gst_waste_management['item_desc']    = $insert_code['ITEM_DESC'];
                        $data_bill_gst_waste_management['priority']     = $code_gst_waste['MCT_PRIORT'];
                        $data_bill_gst_waste_management['gst_status']   = 1;
                        $data_bill_gst_waste_management['item_id']      = $item_id;


                        $bill_item[]                                    = $data_bill_gst_waste_management;
                    endif;
                endif;
            endif;

            #insert LMS
            if($data_acc['LMS_BILLS']==1):
                $code_lms = $this->get_data_tr_code(TR_CODE_LMS); #LMS
                $insert_code    = array();
                if($code_lms):
                    $insert_code['BILL_ID']     = $bill_id;
                    $insert_code['TR_CODE']     = $code_lms['MCT_TRCODENEW'];
//                    $insert_code['TR_ID']       = $code_waste_management['TR_ID'];
                    $insert_code['AMOUNT']      = LMS_CHARGE;
                    $insert_code['PRIORITY']    = $code_lms['MCT_PRIORT'];
//                    $insert_code['TR_TYPE']     = $code_waste_management['TR_TYPE'];
                    $insert_code['ACCOUNT_ID']  = $account_id;
                    $insert_code['ITEM_DESC']   = $code_lms['MCT_TRDESC'];
                    $insert_code['GST_TYPE']        = 0;
                    $insert_code['TR_GST_STATUS']   = 0;
                    $insert_code['TR_CODE_OLD'] = $code_lms['MCT_TRCODE'];
                    $item_id = $this->insert_bill_item($insert_code);

                    $data_bill_lms['tr_code']      = $insert_code['TR_CODE'];
                    $data_bill_lms['tr_code_old']  = $code_lms['MCT_TRCODE'];
                    $data_bill_lms['amount']       = $insert_code['AMOUNT'];
                    $data_bill_lms['item_desc']    = $insert_code['ITEM_DESC'];
                    $data_bill_lms['priority']     = $code_lms['MCT_PRIORT'];
                    $data_bill_lms['gst_status']   = 0;
                    $data_bill_lms['item_id']      = $item_id;

                    $bill_item[]                   = $data_bill_lms;
                endif;
            endif;

//            pre($bill_item);

//            pre($tunggakan_tahun_lepas);
//            exit;

            #tunggakan tahun ini sum all group by

            $total_sum = $this->sum_amount_item($bill_id);
            $data_update_master['total_amount'] = $total_sum['TOTAL_AMOUNT'];
            $this->update_bill_master($bill_id,$data_update_master);

//            return $bill_id;
        else:

            $data_bill_item = array();
            if($data_bill):
                $data['account_id'] = $account_id;
                $data['bill_id']    = $data_bill['BILL_ID'];
                $data_bill_item     = $this->get_bill_item($data);
                $bill_id            = $data_bill['BILL_ID'];
            else:
                $data_insert['ACCOUNT_ID']      = $account_id;
                $data_insert['BILL_NUMBER']     = date('Y').date('m').sprintf('%06d',$account_id).'1';
                $data_insert['BILL_MONTH']      = $this->cur_month;
                $data_insert['BILL_YEAR']       = $this->cur_year;
                $data_insert['NUMBER_GENERATE'] = 1;
                $data_insert['TOTAL_AMOUNT']    = 0;
                $data_insert['TOTAL_PAID']      = 0;
                $data_insert['BILL_TYPE']       = $data_acc['BILL_TYPE'];
                $data_insert['BILL_CATEGORY']   = 'B';

                $bill_id            = $this->insert_bill_master($data_insert);
                $data['bill_id']    = $bill_id;
            endif;

            if($data_bill_item):
                foreach ($data_bill_item as $row_item):
//                    pre($row_item);
                    $data_bill_cur = array();

                    $get_bill_sum   = $this->get_bill_sum($account_id,$row_item['TR_CODE'],'semasa');
                    $total_bil      = $get_bill_sum['BILL'] + ($get_bill_sum['JOURNAL']);

//                    pre($get_bill_sum);

                    $total_paid = $total_paid-$total_bil;

                    $code_payment   = substr($row_item['TR_CODE'],1);
                    $code_payment   = '2'.$code_payment;

                    $get_resit_sum  = $this->get_bill_sum($account_id,$code_payment,'semasa');
                    $total_resit    = $get_resit_sum['RESIT'] + ($get_resit_sum['JOURNAL']);

//                    pre($get_resit_sum);

                    $total_amount = $total_bil - $total_resit;

                    if($total_amount>0 || $row_item['TR_GST_STATUS']==1):
                        $tr_code_details = $this->get_data_tr_code($row_item['TR_CODE']);

                        $data_bill_cur['tr_code_old']   = '';
                        $data_bill_cur['priority']      = 99;
                        if($tr_code_details):
                            $data_bill_cur['tr_code_old']  = $tr_code_details['MCT_TRCODE'];
                            $data_bill_cur['priority']     = $tr_code_details['MCT_PRIORT'];
                        endif;
                        $data_bill_cur['tr_code']       = $row_item['TR_CODE'];
                        $data_bill_cur['amount']        = $total_amount;
                        $data_bill_cur['item_desc']     = $row_item['ITEM_DESC'].' '.$row_item['REMARK'];
                        $data_bill_cur['gst_status']    = $row_item['TR_GST_STATUS'];
                        $data_bill_cur['item_id']       = $row_item['ITEM_ID'];

                        $bill_item[]                    = $data_bill_cur;
                    endif;

                    if($row_item['TR_CODE']==TR_CODE_FAEDAH_8_PERC && $total_amount>0):
                        $charge_faedah = true;
                    endif;
                endforeach;
            endif;
        endif;

//        pre($bill_item);
//        exit;

        $up_level_notice = false;

        #insert faedah 8% if 8hb
        if($this->status_faedah==true):
            $today          = strtotime(date('d-m-Y'));
            $date_faedah    = '7-'.$this->cur_month.'-'.$this->cur_year;
            $date_faedah    = strtotime($date_faedah);
            if($data_acc['BILL_TYPE']==BILL_TYPE_MONTHLY && ($today>$date_faedah) && $charge_faedah==false && $data_acc['STATUS_ACC']==STATUS_ACCOUNT_ACTIVE):
                $insert_faedah['BILL_ID']     = $bill_id;
                $insert_faedah['TR_CODE']     = TR_CODE_FAEDAH_8_PERC;
    //                $insert_code['TR_ID']       = $code_category['TR_ID'];
                $insert_faedah['AMOUNT']      = round(CHARGE_FAEDAH_SEWAAN_PERC*$data_acc['RENTAL_CHARGE'], 2);
                $insert_faedah['PRIORITY']    = CHARGE_FAEDAH_PRIORITY;
    //                $insert_code['TR_TYPE']     = $code_category['TR_TYPE'];
                $insert_faedah['ACCOUNT_ID']  = $account_id;
                $insert_faedah['ITEM_DESC']   = TR_CODE_DESC_FAEDAH_8_PERC;
                $insert_faedah['TR_CODE_OLD'] = TR_CODE_FAEDAH_8_PERC_OLD;

                $item_id = $this->insert_bill_item($insert_faedah);

                $data_bill_faedah['tr_code']     = $insert_faedah['TR_CODE'];
                $data_bill_faedah['tr_code_old'] = TR_CODE_OLD_FAEDAH_8_PERC;
                $data_bill_faedah['amount']      = $insert_faedah['AMOUNT'];
                $data_bill_faedah['item_desc']   = $insert_faedah['ITEM_DESC'];
                $data_bill_faedah['priority']    = $insert_faedah['PRIORITY'];
                $data_bill_faedah['gst_status']  = 0;
                $data_bill_faedah['item_id']      = $item_id;

                if($insert_faedah['AMOUNT']>0):
                    $up_level_notice = true;
                endif;
                $bill_item[] = $data_bill_faedah;
            endif;
        endif;

        #tunggakan thaun lepas - cari item dengan no bermula 12 counter no 22. - display
        $tunggakan_tahun_lepas = $this->get_item_tunggakan_last_year_by_type($account_id,'B');

        if($tunggakan_tahun_lepas):
            foreach ($tunggakan_tahun_lepas as $row):
                $data_bill_row = array();

                $get_bill_sum   = $this->get_bill_sum($account_id,$row['TR_CODE']);
                $total_bil      = $get_bill_sum['BILL'] + ($get_bill_sum['JOURNAL']);

                $total_paid = $total_paid-$total_bil;

                $code_payment   = substr($row['TR_CODE'],1);
                $code_payment   = '2'.$code_payment;

                $get_resit_sum  = $this->get_bill_sum($account_id,$code_payment);
                $total_resit    = $get_resit_sum['RESIT'] + ($get_resit_sum['JOURNAL']);

                $total_amount = $total_bil - $total_resit;

                if($total_amount>0):
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
                    $data_bill_row['item_id']       = $row['ITEM_ID'];

                    $bill_item[]                    = $data_bill_row;

                    $this->tunggakan_notice_up = true;
                endif;
            endforeach;
        endif;

        #tunggakan semasa
        $tunggakan_semasa = $this->get_item_tunggakan_semasa($account_id,'B');

        if($tunggakan_semasa):
            foreach ($tunggakan_semasa as $row):
                $data_bill_row2 = array();

                $get_bill_sum   = $this->get_bill_sum($account_id,$row['TR_CODE'],'tunggakan');
                $total_bil      = $get_bill_sum['BILL'] + ($get_bill_sum['JOURNAL']);

                $total_paid = $total_paid-$total_bil;

                $code_payment   = substr($row['TR_CODE'],1);
                $code_payment   = '2'.$code_payment;

                $get_resit_sum  = $this->get_bill_sum($account_id,$code_payment,'tunggakan');
                $total_resit    = $get_resit_sum['RESIT'] + ($get_resit_sum['JOURNAL']);

                $total_amount = $total_bil - $total_resit;

                if($total_amount>0):
                    $tr_code_details = $this->get_data_tr_code($row['TR_CODE']);

                    $data_bill_row2['tr_code_old']  = $row['TR_CODE_OLD'];
                    $data_bill_row2['priority']     = 99;
                    if($tr_code_details):
                        $data_bill_row2['priority']     = $tr_code_details['MCT_PRIORT'];
                    endif;

                    $data_bill_row2['tr_code']      = $row['TR_CODE'];
                    $data_bill_row2['amount']       = $total_amount;
                    $data_bill_row2['item_desc']    = 'TUNGGAKAN '.$row['ITEM_DESC'];
                    $data_bill_row2['gst_status']   = $row['TR_GST_STATUS'];
                    $data_bill_row2['item_id']      = 0;

                    $this->tunggakan_notice_up  = true;
                    $bill_item[]                = $data_bill_row2;
                endif;
            endforeach;
        endif;

        #order by priority
        usort($bill_item, function($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return $a['priority'] > $b['priority'] ? 1 : -1;
            }

            return $a['priority'] > $b['priority'] ? 1 : -1;
        });


        $data['item'] = $bill_item;
        $this->insert_b_int_latest($data);

        return $data;
    }

    function calculate_lebihan($data){
        $bill_item  = $data['item'];
        $account_id = $data['account_id'];
        #calculate lebihan
        $total_bil = 0;
        if($bill_item):
            foreach ($bill_item as $row):
                $total_bil = $total_bil + $row['amount'];
            endforeach;
        endif;

        $amount_lebihan = 0;
        $lebihan    = $this->get_lebihan($account_id);

        if($lebihan):
            $amount_lebihan = ($lebihan['RESIT']+($lebihan['JOURNAL_R']) - ($lebihan['BILL']+($lebihan['JOURNAL_B'])));
        endif;

        if($amount_lebihan>0 && $total_bil>0):
            $this->lebihan_payment($amount_lebihan,$account_id);
            $row_lebihan['amount_lebihan']      = $amount_lebihan;
            $row_lebihan['status_lebihan']      = 1;
            $row_lebihan['amount_resit']        = $lebihan['RESIT']+($lebihan['JOURNAL_R']);
            $row_lebihan['amount_bill']         = $lebihan['BILL']+($lebihan['JOURNAL_B']);
            $row_lebihan['total_bil']           = $total_bil;

            $amount_already_paid = $row_lebihan['amount_bill']-$row_lebihan['total_bil'];
            $row_lebihan['amount_already_paid'] = $amount_already_paid;

            $row_lebihan['new_lebihan']         = $row_lebihan['amount_resit']-$row_lebihan['amount_already_paid'];
            return $row_lebihan;
        else:
            $row_lebihan['status_lebihan']      = 2;
            $row_lebihan['amount_lebihan']      = $amount_lebihan;
            $row_lebihan['amount_resit']        = $lebihan['RESIT']+($lebihan['JOURNAL_R']);
            $row_lebihan['amount_bill']         = $lebihan['BILL']+($lebihan['JOURNAL_B']);
            $row_lebihan['total_bil']           = $total_bil;

            $amount_already_paid = $row_lebihan['amount_bill']-$row_lebihan['total_bil'];
            $row_lebihan['amount_already_paid'] = $amount_already_paid;
            $row_lebihan['new_lebihan']         = $row_lebihan['amount_resit']-$row_lebihan['amount_already_paid'];
            return $row_lebihan;
        endif;
    }

    function insert_b_int_latest($data){
        if($data):
            if(isset($data['item'])):
                #delete previous data
                $this->delete_data_b_int_latest($data['account_number']);

                #insert item
                foreach ($data['item'] as $item):
                    $data_insert['account_number']  = $data['account_number'];
                    $data_insert['tr_code']         = $item['tr_code_old'];
                    $data_insert['tr_code_new']     = $item['tr_code'];
                    $data_insert['amount']          = $item['amount'];
                    $data_insert['gst_status']      = $item['gst_status'];

                    $this->insert_table_b_int_latest($data_insert);
                endforeach;
            endif;
        endif;
    }

    function insert_table_b_int_latest($data_insert){
        db_set_date_time('dt_added',timenow());
        db_insert('b_int_latest',$data_insert);
        return true;
    }

    function delete_data_b_int_latest($acc_num){
        db_where('account_number',$acc_num);
        $sql = db_delete('b_int_latest');
        if($sql):
            return true;
        else:
            return false;
        endif;
    }

    function get_account_details($id){
        db_select('a.*,acc.*,t.*,c.*,r.*,asset.asset_name');
        db_select("to_char(a.dt_added, 'yyyy-mm-dd hh24:mi:ss') as dt_create_acc",false);
        db_select('acc.address as user_address');
        db_join('acc_user acc','acc.user_id = a.user_id');
        db_join('a_type t','t.type_id = a.type_id');
        db_join('a_category c','c.category_id = a.category_id');
        db_join('a_asset asset','a.asset_id=asset.asset_id','left');
        db_join('a_rental_use r','r.rental_use_id = a.rental_use_id','left');
        db_from('acc_account a');
        db_where('account_id',$id);
//        db_where('status_acc',STATUS_ACCOUNT_ACTIVE);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_cur_bill_data($id,$bill_type=0){
        db_select('m.*');
        db_select("to_char(m.DT_ADDED, 'yyyy-mm-dd hh24:mi:ss') as dt_added",false);
        db_from('b_master m');
        db_where('m.account_id',$id);
        db_where('m.bill_category','B');
        if($bill_type==BILL_TYPE_MONTHLY):
            db_where('m.bill_month',$this->cur_month);
        endif;
        db_where('m.bill_year',$this->cur_year);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function insert_bill_master($data_insert){
        db_set_date_time('dt_added',timenow());
        db_insert('b_master',$data_insert);
        return get_insert_id('b_master');
    }

    function insert_bill_item($data_insert){

        $data_insert['BILL_CATEGORY']       = 'B';
        $data_insert['COMPLETE_PAYMENT']    = 0;

        db_set_date_time('dt_added',timenow());
        db_insert('B_ITEM ',$data_insert);

        return get_insert_id('B_ITEM');
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

    function get_bill_item($data){
        db_select('*');
        db_from('B_ITEM');

        if($data['account_id']):
            db_where('account_id',$data['account_id']);
        endif;
        if($data['bill_id']):
            db_where('bill_id',$data['bill_id']);
        endif;

        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function sum_amount_item($bill_id){
        db_select('sum(amount) as total_amount');
        db_from('B_ITEM');
        db_where('bill_id',$bill_id);

        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_bill_master($bill_id,$data_update){
        db_where('bill_id',$bill_id);
        db_update('b_master',$data_update);
        return true;
    }

    function get_item_tunggakan_last_year_by_type($account_id,$type='B'){
        db_select('*');
        db_from('B_ITEM i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_where("substr(i.TR_CODE,0,2)",'12');
        db_where('m.bill_year',$this->cur_year);
        db_where('m.account_id',$account_id);
        db_where('i.bill_category',$type);
        db_where("i.PREV_YEAR_OUTSTANDING",1);
//        db_group('TR_CODE,ITEM_DESC');

        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_item_tunggakan_semasa($account_id,$type='B'){
        db_select("SUM(i.AMOUNT) as AMOUNT");
        db_select("SUM(i.AMOUNT) as TOTAL_AMOUNT");
        db_select("SUM(i.TOTAL_PAID) as TOTAL_PAID");
        db_select("SUM(i.TOTAL_JOURNAL) as TOTAL_JOURNAL");
        db_select("TR_CODE,ITEM_DESC,TR_GST_STATUS,TR_CODE_OLD");
//        db_select('sum(AMOUNT) as total_amount,TR_CODE,ITEM_DESC,i.GST_TYPE');
        db_from('B_ITEM i');
        db_join('b_master m','m.bill_id = i.bill_id');
//        db_where("substr(i.TR_CODE,0,2)",'11');
        db_where('m.bill_year',$this->cur_year);
        db_where('m.bill_month < '.$this->cur_month);
        db_where('m.account_id',$account_id);
        db_where('i.bill_category',$type);
        db_where("i.PREV_YEAR_OUTSTANDING",0);
        db_group('TR_CODE,TR_CODE_OLD,ITEM_DESC,TR_GST_STATUS');

        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function get_sum_item($account_id,$type='B',$tr_code,$ype='semasa'){
        db_select('sum(AMOUNT) as total_amount');
        db_from('B_ITEM i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_where("TR_CODE",$tr_code);
        db_where('m.bill_year',$this->cur_year);
        if($ype=='semasa'):
            db_where('m.bill_month',$this->cur_month);
        else:
            db_where('m.bill_month < '.$this->cur_month);
        endif;
        db_where('m.account_id',$account_id);
        db_where('i.bill_category',$type);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_acc($acc_id,$data_update){
        db_where('account_id',$acc_id);
        db_update('acc_account',$data_update);
        return true;
    }

    function get_lebihan($account_id){
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'B' THEN AMOUNT END) as BILL");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'R' THEN AMOUNT END) as RESIT");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' and substr(i.TR_CODE,0,1)=2 THEN AMOUNT END) as JOURNAL_R");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' and substr(i.TR_CODE,0,1)=1 THEN AMOUNT END) as JOURNAL_B");
        db_from('b_item i');
        db_join('b_master m','i.bill_id=m.bill_id');
        db_where('i.account_id',$account_id);
        db_where('m.bill_year',date('Y'));
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_bill_sum($account_id,$trcode,$type='',$remark=''){
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'B' THEN AMOUNT END) as BILL");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'R' THEN AMOUNT END) as RESIT");
        db_select("SUM(CASE WHEN i.BILL_CATEGORY = 'J' THEN AMOUNT END) as JOURNAL");
        db_from('b_item i');
        db_join('b_master m','i.bill_id=m.bill_id');
        db_where('i.account_id',$account_id);
        db_where('i.TR_CODE',$trcode);
        if($type=='semasa'):
            db_where('m.bill_month',$this->cur_month);
        elseif($type=='tunggakan'):
            db_where('m.bill_month < '.$this->cur_month);
        endif;
        if($remark):
            db_where('i.remark',$remark);
        endif;
        db_where('m.bill_year',date('Y'));
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_lebihan_row($account_id){
        db_select('*');
        db_from('b_item i');
        db_join('b_master m','i.bill_id=m.bill_id');
//        db_where('i.account_id',$account_id);
        db_where('m.bill_year',date('Y'));
        db_where('m.account_id',$account_id);
        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

    function lebihan_payment($balance_amount,$acc_id){
        //resit menjadi lebihan
        if($balance_amount != 0):
            $pending_item = $this->get_pending_item($acc_id,'','',date('m'),date('Y'),0);

            foreach($pending_item as $item):
                $balance_tobe_paid = ($item['AMOUNT']-$item['TOTAL_PAID']+$item['TOTAL_JOURNAL']);
                if($balance_tobe_paid > 0):
                    if($balance_amount <= $balance_tobe_paid):
                        $total_paid     = $balance_amount + $item['TOTAL_PAID'];
                        $balance_amount = 0;
                    else:
                        $total_paid     = $item['AMOUNT'] + $item['TOTAL_JOURNAL'];
                        $balance_amount = $balance_amount - $balance_tobe_paid;
                    endif;

//                    if($item['ITEM_ID_PAYMENT']==''):
//                        $data_update_item['item_id_payment'] = $insert_item_id;
//                    else:
//                        $data_update_item['item_id_payment'] = $item['ITEM_ID_PAYMENT'].','.$insert_item_id;
//                    endif;
                    $data_update_item['total_paid'] = $total_paid;
                    $update_status = $this->update_bill_item($data_update_item,$item['ITEM_ID']);

                    //update total_used of payment item
//                    $data_payment_item['total_used'] = $amount - $balance_amount;
//                    $update_status = $this->update_bill_item($data_payment_item,$insert_item_id);

                    if($balance_amount == 0):
                        break;
                    endif;
                endif;
            endforeach;
            return true;
        endif;
    }

    function get_pending_item($account_id, $tr_code = '', $tr_code_overdue = '', $current_month = '', $current_year = '', $goto_prev_year = 0){
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
        $sql = $sql . " ORDER BY I.PRIORITY";

        $result = db_query($sql);
        if ($result):
            return $result->result_array();
        endif;
    }

    function update_bill_item($data_update,$id){
        db_where('ITEM_ID',$id);
        db_update('B_ITEM',$data_update);
        return true;
    }

    function add($data){
//
        $data_log = $this->get_log_desc($data['log_id']);

        $data_insert['LOG_ID']      = $data['log_id'];
        $data_insert['LOG_DESC']    = $data_log['LOG_DESC'];
        $data_insert['USER_ID']     = $data['user_id'];
        $data_insert['REMARK']      = json_encode($data['remark']);
        $data_insert['IP_ADDRESS']  = $this->ci->input->ip_address();;
        $data_insert['STATUS']      = isset($data['status'])?$data['status']:PROCESS_STATUS_SUCCEED;
        $data_insert['REFER_ID']    = isset($data['refer_id'])?$data['refer_id']:'0';
        db_set_date_time('DT_ADDED',timenow());
        $sql = db_insert('audit_trail',$data_insert);
        if($sql):
            return get_insert_id('audit_trail');
        endif;
    }

    function get_log_desc($log_id){
        db_select('log_desc');
        db_from('lookup_audit_trail');
        db_where('log_id',$log_id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_log_notice($acc_id){
        db_select('*');
        db_from('notice_log');
        db_where('account_id',$acc_id);
        db_where("extract(year from dt_added) = ".date('Y')."");
        db_where("extract(month from DT_ADDED) = ".date('m')."");
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function generate_item($data_item,$lebihan){
        $this->tunggakan_notice_up = false;
        if(isset($data_item['item'])):
            $new_item = array();

            foreach ($data_item['item'] as $key=>$row):
                if($lebihan>0):
                    $new_lebihan = $lebihan-$row['amount'];
                    if($new_lebihan>0):
                        $lebihan = $lebihan-$row['amount'];
                        $row['amount'] = 0;
                    else:
                        $row['amount'] =  $row['amount'] - $lebihan;
                        $lebihan = 0;
                    endif;
                endif;

                if($row['amount']>0):
                    if(strpos(strtoupper($row['item_desc']),strtoupper('TUNGGAKAN')) !== false):
                        $this->tunggakan_notice_up = true;
                    endif;
                    $new_item[] = $row;
                endif;
            endforeach;

            $data_item['item'] = $new_item;
            $this->insert_b_int_latest($data_item);

            return $data_item;
        endif;
    }

    function insert_notice($data){
        #insert notice level
        $cur_level_notice = $data['notice_level'];
//            echo $cur_level_notice;
//            die();
        $cek_rin = $this->get_log_notice($data['account_id']);
        $total_tunggakan    = 0;
        $item_tunggakan     = array();
        $total_air          = 0;
        $total_sewaan       = 0;
        $total_tipping      = 0;

        if(empty($cek_rin) && $cur_level_notice<=4):
            if($cur_level_notice<4):
                $new_notice = $cur_level_notice+1;
                $data_update_acc_notice['NOTICE_LEVEL'] = $new_notice;
                $this->update_acc($data['account_id'],$data_update_acc_notice);
            else:
                $new_notice = 99;
                $data_update_acc_notice = array();
            endif;

            $data_audit_trail['log_id']                  = 3004;
            $data_update_acc['remark_2']                 = 'Set level notice';
            $data_audit_trail['remark']                  = $data_update_acc_notice;
            $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
            $data_audit_trail['user_id']                 = 0;
            $data_audit_trail['refer_id']                = $data['account_id'];
            $this->add($data_audit_trail);

            if(isset($data['item'])):
                foreach ($data['item'] as $item):
                    if(strpos(strtoupper($item['item_desc']),strtoupper('TUNGGAKAN')) !== false):
                        $total_tunggakan    = $total_tunggakan+$item['amount'];
                        $item_tunggakan[]   = $item;

                        if($item['tr_code'] == '11110016' || $item['tr_code'] == '12110016'):
                            $total_air          = $total_air+$item['amount'];
                        elseif ($item['tr_code'] == '11110014' || $item['tr_code'] == '12110014'):
                            $total_tipping       = $total_tipping+$item['amount'];
                        elseif (strpos(strtoupper($item['item_desc']),strtoupper('SEWAAN')) !== false):
                            $total_sewaan       = $total_sewaan+$item['amount'];
                        endif;
                    endif;
                endforeach;
            endif;

            #insert log notice
            $data_insert['ACCOUNT_ID']      = $data['account_id'];
            $data_insert['TOTAL_TUNGGAKAN'] = $total_tunggakan;
            $data_insert['SEWAAN']          = $total_sewaan;
            $data_insert['AIR']             = $total_air;
            $data_insert['TIPPING']         = $total_tipping;
            $data_insert['OTHERS_INFO']     = json_encode($item_tunggakan);
            $data_insert['NOTICE_LEVEL']    = $new_notice;

            $this->insert_notice_log($data_insert);
        endif;
    }

    function insert_notice_log($data_insert){
        db_set_date_time('dt_added',timenow());
        db_insert('notice_log',$data_insert);
        return true;
    }

    function generate_empty_bill($data_bil){
        $data_cur_bill  = $this->get_generate_empty_bill($data_bil);
        $bill_item      = array();
        if($data_cur_bill):
            foreach ($data_cur_bill as $row):
                $tr_code_details = $this->get_data_tr_code($row['TR_CODE']);

                $data_bill_row['priority'] = 99;
                if($tr_code_details):
                    $data_bill_row['priority']      = $tr_code_details['MCT_PRIORT'];
                endif;
                $data_bill_row['tr_code_old']   = $row['TR_CODE_OLD'];
                $data_bill_row['tr_code']       = $row['TR_CODE'];
                $data_bill_row['amount']        = 0;
                $data_bill_row['item_desc']     = $row['ITEM_DESC'];
                $data_bill_row['gst_status']    = 0;
//                $data_bill_row['item_id']       = $row['ITEM_ID'];

                $bill_item[]                    = $data_bill_row;
            endforeach;
        endif;
        return $bill_item;
    }

    function get_generate_empty_bill($data_bil){
        db_select('i.tr_code,i.item_desc,i.TR_CODE_OLD');
        db_from('B_ITEM i');
        db_join('b_master m','m.bill_id = i.bill_id');
        db_where('m.account_id',$data_bil['account_id']);
        db_where('i.bill_category','B');
        db_where('i.TR_GST_STATUS',0);
        db_where('i.tr_code is not null');
        db_group('tr_code,item_desc,TR_CODE_OLD');

        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }

}
