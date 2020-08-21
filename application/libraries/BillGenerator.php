<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BillGenerator
{
    public $curuser;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Account/M_acc_user', 'm_acc_user');
        $this->CI->load->model('Account/M_acc_account', 'm_acc_account');
        $this->CI->load->model('Asset/M_a_category', 'm_a_category');
        $this->CI->load->model('Asset/M_a_asset', 'm_a_asset');
        $this->CI->load->model('Integration/M_b_int_payment', 'm_b_int_payment');
        $this->CI->load->model('Bill/M_bill_master', 'm_bill_master');
        $this->CI->load->model('Bill/M_bill_item', 'm_bill_item');
        $this->CI->load->model('TrCode/M_tran_code', 'm_tr_code');
    }

    public function getCurrentMonthBillCharge($account_id)
    {
        // Get account detail
        $account_detail = $this->CI->m_acc_account->getAccountDetailOnlyById( $account_id );

        // Get category detail
        $category_code = $this->CI->m_a_category->get_a_category_details( $account_detail["CATEGORY_ID"] );

        // Get asset detail
        $asset_code = $this->CI->m_a_asset->get_a_asset_by_id( $account_detail["ASSET_ID"] );

        // Get transaction code for selected category
        $data_search_sewaan["MCT_TRCODENEW"] = $category_code["TRCODE_CATEGORY"];
        $data_search_sampah["MCT_TRCODENEW"] = "11110014";
        $tr_code_sewaan = $this->CI->m_tr_code->get_tr_code( $data_search_sewaan );
        $tr_code_sampah = $this->CI->m_tr_code->get_tr_code( $data_search_sampah );

        if ( !empty($account_detail) )
        {
            $data_caj_sewaan = array(
                                    'KOD_CAJ_LAMA'      => $tr_code_sewaan["MCT_TRCODE"],
                                    'KOD_CAJ_BARU'      => $tr_code_sewaan["MCT_TRCODENEW"],
                                    'PERIHAL_CAJ_BARU'  => $tr_code_sewaan["MCT_TRDESC"],
                                    'CAJ'               => $account_detail["RENTAL_CHARGE"], 
                                    'CAJ_ANGGARAN'      => $account_detail["ESTIMATION_RENTAL_CHARGE"],
                                    'PRIORITY'          => $tr_code_sewaan['MCT_PRIORT'],
                                    'BILL_CATEGORY'     => "B",
                                );

            if ($account_detail["WASTE_MANAGEMENT_BILLS"] > 0 )
            {
                $data_caj_sampah = array(
                                        'KOD_CAJ_LAMA'      => $tr_code_sampah["MCT_TRCODE"],
                                        'KOD_CAJ_BARU'      => $tr_code_sampah["MCT_TRCODENEW"],
                                        'PERIHAL_CAJ_BARU'  => $tr_code_sampah["MCT_TRDESC"],
                                        'CAJ'               => $account_detail["WASTE_MANAGEMENT_CHARGE"], 
                                        'CAJ_ANGGARAN'      => $account_detail["WASTE_MANAGEMENT_CHARGE"],
                                        'PRIORITY'          => $tr_code_sampah['MCT_PRIORT'],
                                        'BILL_CATEGORY'     => "B",
                                    );                
            }
            else
            {
                $data_caj_sampah = array();
            }

            // Caj Bil air akan dimasukkan sendiri oleh admin di page "Maklumat Bil Semasa"
            // $data_caj_air = array(
            //                         'KOD_CAJ_SEWAAN_LAMA'      => "11018",
            //                         'KOD_CAJ_SEWAAN_BARU'      => "11110016",
            //                         'PERIHAL_CAJ_SEWAAN_BARU'  => "BIL AIR",
            //                         'CAJ'                      => $account_detail["WATER_BILLS"], 
            //                         'CAJ_ANGGARAN'             => $account_detail["WATER_BILLS"],
            //                     );

            if ( count($data_caj_sampah) != 0 )
            {
                $data = array($data_caj_sewaan,$data_caj_sampah );
            }
            else
            {
               $data = array($data_caj_sewaan); 
            }
        }
        else
        {
            $data = false;
        }

        return $data;
    }

    public function getCurrentMonthOutstandingCharge($account_id)
    {
        // Get list of current account charges before current month
        $month = date('n');
        $year = date('Y');
        $data = array();

        if ( $month == 1)
        {
            $month = 12;
            $year = $year - 1;
        }

        $list_of_transaction = $this->CI->m_bill_item->getPreviousBillCharges($account_id,$month,$year);

        // Manipulate data
        foreach ($list_of_transaction as $bill_transaction) 
        {
            $tr_code_semasa = $bill_transaction["TR_CODE"];
            // Replace first string of transaction code to "2" TO convert the transaction code to "Bayaran" transaction
            $tr_code_bayaran = "21".substr($bill_transaction["TR_CODE"],2);
            // Replace second string of transaction code to "2" TO convert the transaction code to "Tunggakan" transaction
            $tr_code_tunggakan = "12".substr($bill_transaction["TR_CODE"],2);

            // Get Payment fo each bill_transaction semasa
            $get_payment_details = $this->CI->m_bill_item->getPreviousBillPayment($account_id,$month,$year,$tr_code_bayaran);

            // Get tr_code description
            $data_search_semasa["MCT_TRCODENEW"] = $bill_transaction["TR_CODE"];
            $data_search_bayaran_semasa["MCT_TRCODENEW"] = $tr_code_bayaran;
            $data_search_tunggakan["MCT_TRCODENEW"] = $tr_code_tunggakan;

            $tr_detail_semasa           = $this->CI->m_tr_code->get_tr_code( $data_search_semasa );
            $tr_detail_bayaran_semasa   = $this->CI->m_tr_code->get_tr_code( $data_search_bayaran_semasa );
            $tr_detail_tunggakan  = $this->CI->m_tr_code->get_tr_code( $data_search_tunggakan );

            $data[] = array(
                                'TR_CODE_BILL'          => $bill_transaction["TR_CODE"],
                                'TR_DESC_BILL'          => $tr_detail_semasa["MCT_TRDESC"],
                                'TR_CODE_TUNGGAKAN'     => $tr_code_tunggakan,
                                'TR_CODE_OLD_TUNGGAKAN' => $tr_detail_tunggakan["MCT_TRCODE"],
                                'TR_DESC_TUNGGAKAN'     => $tr_detail_tunggakan["MCT_TRDESC"],
                                'PRIORITY'              => $tr_detail_tunggakan['MCT_PRIORT'],
                                'BALANCE_AMOUNT'        => $bill_transaction["TOTAL_AMOUNT"] - $get_payment_details["TOTAL_AMOUNT"], 
                            );
        }

        return $data;
    }

    public function getOtherCharge($account_id)
    {
        // Check if outstanding bill already not paid for greater than 3 months
        $date_search_1["ACCOUNT_ID"]        = $account_id;
        $date_search_1["BILL_MONTH_FIRST"]  = date('n') - 1;
        $date_search_1["BILL_MONTH_LAST"]   = date('n');
        $date_search_1["BILL_YEAR"]         = date('Y');
        $date_search_1["BILL_CATEGORY"]     = "R";
        $date_search_1["CUSTOM_COLUMN"]     = "BILL_ID";

        $date_search_2["ACCOUNT_ID"]        = $account_id;
        $date_search_2["BILL_MONTH_FIRST"]  = date('n') - 2;
        $date_search_2["BILL_MONTH_LAST"]   = date('n');
        $date_search_2["BILL_YEAR"]         = date('Y');
        $date_search_2["BILL_CATEGORY"]     = "R";
        $date_search_2["CUSTOM_COLUMN"]     = "BILL_ID";

        $date_search_3["ACCOUNT_ID"]        = $account_id;
        $date_search_3["BILL_MONTH_FIRST"]  = date('n') - 3;
        $date_search_3["BILL_MONTH_LAST"]   = date('n');
        $date_search_3["BILL_YEAR"]         = date('Y');
        $date_search_3["BILL_CATEGORY"]     = "R";
        $date_search_3["CUSTOM_COLUMN"]     = "BILL_ID";

        $date_search_4["ACCOUNT_ID"]        = $account_id;
        $date_search_4["BILL_MONTH_FIRST"]  = date('n') - 4;
        $date_search_4["BILL_MONTH_LAST"]   = date('n');
        $date_search_4["BILL_YEAR"]         = date('Y');
        $date_search_4["BILL_CATEGORY"]     = "R";
        $date_search_4["CUSTOM_COLUMN"]     = "BILL_ID";

        $date_search_5["ACCOUNT_ID"]        = $account_id;
        $date_search_5["BILL_MONTH_FIRST"]  = date('n') - 5;
        $date_search_5["BILL_MONTH_LAST"]   = date('n');
        $date_search_5["BILL_YEAR"]         = date('Y');
        $date_search_5["BILL_CATEGORY"]     = "R";
        $date_search_5["CUSTOM_COLUMN"]     = "BILL_ID";

        $total_payment_record_in_1_month = $this->CI->m_bill_master->get($date_search_1);
        $total_payment_record_in_2_month = $this->CI->m_bill_master->get($date_search_2);
        $total_payment_record_in_3_month = $this->CI->m_bill_master->get($date_search_3);
        $total_payment_record_in_4_month = $this->CI->m_bill_master->get($date_search_4);
        $total_payment_record_in_5_month = $this->CI->m_bill_master->get($date_search_5);

        if ( count($total_payment_record_in_5_month) == 0 )
        {
            // add LOD charge
            $current_lod_transaction = "11110020";      // Please change this value if "LOD SEWAAN" is not using this transaction code

            $data_search_lod["MCT_TRCODENEW"]   =  $current_lod_transaction;
            $tr_lod_detail                      =  $this->CI->m_tr_code->get_tr_code( $data_search_lod );

            $data[] = array( 
                                'TR_CODE_OLD'      =>  $tr_lod_detail["MCT_TRCODE"],
                                'TR_CODE_NEW'      =>  $tr_lod_detail["MCT_TRCODENEW"],
                                'TR_DESC'          =>  "NOTIS MAHKAMAH",
                                'PRIORITY'         =>  $tr_lod_detail['MCT_PRIORT'],
                                'AMOUNT'           =>  "10",
                                'DISPLAY_PRIORITY' =>  '2',
                            );

            $data_update["NOTICE_LEVEL"] = 5;
            $this->CI->m_acc_account->update_account($data_update,$account_id);
        }
        else if ( count($total_payment_record_in_4_month) == 0 )
        {
            // add LOD charge
            $current_lod_transaction = "11110020";      // Please change this value if "LOD SEWAAN" is not using this transaction code

            $data_search_lod["MCT_TRCODENEW"]   =  $current_lod_transaction;
            $tr_lod_detail                      =  $this->CI->m_tr_code->get_tr_code( $data_search_lod );

            $data[] = array( 
                                'TR_CODE_OLD'      =>  $tr_lod_detail["MCT_TRCODE"],
                                'TR_CODE_NEW'      =>  $tr_lod_detail["MCT_TRCODENEW"],
                                'TR_DESC'          =>  $tr_lod_detail["MCT_TRDESC"],
                                'PRIORITY'         =>  $tr_lod_detail['MCT_PRIORT'],
                                'AMOUNT'           =>  "10",
                                'DISPLAY_PRIORITY' =>  '2',
                            );
            

            $data_update["NOTICE_LEVEL"] = 4;
            $this->CI->m_acc_account->update_account($data_update,$account_id);
        }
        else if ( count($total_payment_record_in_3_month) == 0 )
        {
            $data_update["NOTICE_LEVEL"] = 3;
            $this->CI->m_acc_account->update_account($data_update,$account_id);
            $data = false;
        }
        else if ( count($total_payment_record_in_2_month) == 0 )
        {
            $data_update["NOTICE_LEVEL"] = 2;
            $this->CI->m_acc_account->update_account($data_update,$account_id);
            $data = false;
        }
        else if ( count($total_payment_record_in_1_month) == 0 )
        {
            $data_update["NOTICE_LEVEL"] = 1;
            $this->CI->m_acc_account->update_account($data_update,$account_id);
            $data = false;
        }
        else
        {
            $data_update["NOTICE_LEVEL"] = 0;
            $this->CI->m_acc_account->update_account($data_update,$account_id);
            $data = false;
        }

        return $data;
    }

    public function generateCurrentBill($account_id,$month,$year)
    {
        // Check if a master record already exist for current month
        $data_search["BILL_MONTH"]      =  $month;
        $data_search["BILL_YEAR"]       =  $year;
        $data_search["ACCOUNT_ID"]      =  $account_id;
        $date_search["CUSTOM_COLUMN"]   = "BILL_ID";

        $exist = count( $this->CI->m_bill_master->get($data_search) );

        if ( $exist == 0 )
        {
            // Get account detail
            $account_detail = $this->CI->m_acc_account->getAccountDetailOnlyById( $account_id );

            $data_insert_b_master["ACCOUNT_ID"]      =  $account_id;
            $data_insert_b_master["BILL_YEAR"]       =  date('Y');
            $data_insert_b_master["BILL_MONTH"]      =  date('n');
            $data_insert_b_master["BILL_CATEGORY"]   =  "B";
            $data_insert_b_master["BILL_NUMBER"]     =  date('Y').date('m').str_pad($account_id,6,"0",STR_PAD_LEFT).'1';
            $data_insert_b_master["BILL_TYPE"]       =  $account_detail["BILL_TYPE"]; // 1 =  Monthly, 2 =  Yearly
            $data_insert_b_master["TOTAL_PAID"]      =  "0";
            $data_insert_b_master["TOTAL_AMOUNT"]    =  "0";
            $data_insert_b_master["NUMBER_GENERATE"] =  "1";

            $this->CI->m_bill_master->insert_bill_master($data_insert_b_master);
        }

        $b_master = $this->CI->m_bill_master->get($data_search)[0];

        // var_dump($b_master); die();

        // Delete existing item bill under master record and recreate back. All bill_category = "J" will be ignored in this deletion
        $data_delete["ACCOUNT_ID"]      =  $account_id;
        $data_delete["BILL_ID"]         =  $b_master["BILL_ID"];
        $data_delete["BILL_CATEGORY"]   =  "B";
        $data_delete["DISPLAY_STATUS"]  =  "Y";

        $this->CI->m_bill_item->delete($data_delete);

        // Get current month item bill and save with master record
        $current_month_item = $this->getCurrentMonthBillCharge($account_id);
        foreach ($current_month_item as $item)
        {
            # code...
            $data_insert_b_item['BILL_ID']         = $b_master["BILL_ID"];
            $data_insert_b_item['TR_CODE']         = $item['KOD_CAJ_BARU'];
            $data_insert_b_item['TR_CODE_OLD']     = $item['KOD_CAJ_LAMA'];
            $data_insert_b_item['AMOUNT']          = $item['CAJ_ANGGARAN'];
            $data_insert_b_item['PRIORITY']        = $item['PRIORITY'];
            $data_insert_b_item['ACCOUNT_ID']      = $account_id;
            $data_insert_b_item['ITEM_DESC']       = $item['PERIHAL_CAJ_BARU'];
            $data_insert_b_item['BILL_CATEGORY']   = "B";

            $this->CI->m_bill_item->insert_bill_item($data_insert_b_item);
        }

        // Get other charges and save with master record
        $list_of_other_charges = $this->getOtherCharge($account_id);

        if ( $list_of_other_charges !== false)
        {
            foreach ($list_of_other_charges as $other_charge) 
            {
                # code...
                $data_insert['BILL_ID']         = $b_master["BILL_ID"];
                $data_insert['TR_CODE']         = $other_charge['TR_CODE_NEW'];
                $data_insert['TR_CODE_OLD']     = $other_charge['TR_CODE_OLD'];
                $data_insert['AMOUNT']          = $other_charge['AMOUNT'];
                $data_insert['PRIORITY']        = $other_charge['PRIORITY'];
                $data_insert['ACCOUNT_ID']      = $account_id;
                $data_insert['ITEM_DESC']       = $other_charge['TR_DESC'];
                $data_insert['BILL_CATEGORY']   = "B";

                $this->CI->m_bill_item->insert_bill_item($data_insert);
            }
        }

        $data_update_master["TOTAL_AMOUNT"] = $this->CI->m_bill_item->getBillItemTotalAmount( $b_master["BILL_ID"] )["TOTAL_AMOUNT"];
        $this->CI->m_bill_master->updateBillMasterTotalAmount($b_master["BILL_ID"],$account_id,$data_update_master);

        return $b_master;
    }

    public function viewCurrentBill($account_id)
    {
        $month = date('n');
        $year = date('Y');
        $list_processed_transaction  =  false;
        $list_monthly_charges        =  $this->getCurrentMonthBillCharge($account_id);
        $list_outstanding_charges    =  $this->getCurrentMonthOutstandingCharge($account_id);

        // Calculate monthly charges
        // ---- Current charge ----
        foreach ($list_monthly_charges as $monthly_charge_item) 
        {
            # code...
            $tr_code_old = $monthly_charge_item["KOD_CAJ_LAMA"];
            $tr_code_new = $monthly_charge_item["KOD_CAJ_BARU"];
            $tr_desc     = $monthly_charge_item["PERIHAL_CAJ_BARU"];
            $caj         = $monthly_charge_item["CAJ_ANGGARAN"];

            // Check Payment Record
            $tr_code_bayaran       =  "21".substr($tr_code_new,2);
            $check_charges_record  =  $this->CI->m_bill_item->getPreviousBillCharges($account_id,$month,$year,$tr_code_new);
            $check_payment_record  =  $get_payment_details = $this->CI->m_bill_item->getPreviousBillPayment($account_id,$month,$year,$tr_code_bayaran);
            $balance_amount        =  $check_charges_record["TOTAL_AMOUNT"] - $check_payment_record["TOTAL_AMOUNT"];

            if ( $balance_amount > 0 )
            {
                // Add one record for current caj as new bill item and one record for tunggakan caj as extra bill item
                $data[] = array( 
                                    'TR_CODE_OLD'      =>  $tr_code_old,
                                    'TR_CODE_NEW'      =>  $tr_code_new,
                                    'TR_DESC'          =>  $tr_desc,
                                    'AMOUNT'           =>  $caj,
                                    'DISPLAY_PRIORITY' =>  '1',
                                );

                $tr_code_tunggakan = "12".substr($tr_code_new,2);
                $data_search_tunggakan["MCT_TRCODENEW"] = $tr_code_tunggakan;
                $tr_detail_tunggakan  = $this->CI->m_tr_code->get_tr_code( $data_search_tunggakan );

                $data[] = array( 
                                    'TR_CODE_OLD'      =>  $tr_detail_tunggakan["MCT_TRCODE"],
                                    'TR_CODE_NEW'      =>  $tr_detail_tunggakan["MCT_TRCODENEW"],
                                    'TR_DESC'          =>  $tr_detail_tunggakan["MCT_TRDESC"],
                                    'AMOUNT'           =>  $balance_amount,
                                    'DISPLAY_PRIORITY' =>  '3',
                                );
            }
            else if ( $balance_amount <= 0 )
            {
                $new_balance_amount = $caj + $balance_amount;
                $data[] = array( 
                                    'TR_CODE_OLD'      =>  $tr_code_old,
                                    'TR_CODE_NEW'      =>  $tr_code_new,
                                    'TR_DESC'          =>  $tr_desc,
                                    'AMOUNT'           =>  $new_balance_amount,
                                    'DISPLAY_PRIORITY' =>  '1',
                                );
            }

            // Record the processed transaction
            $list_processed_transaction[] = $tr_code_new;
        }

        // ---- Outstanding Charge ----
        foreach ($list_outstanding_charges as $outstanding_charges) 
        {
            if ( array_search( $outstanding_charges["TR_CODE_BILL"], $list_processed_transaction) === false )
            {
                $data[] = array( 
                                    'TR_CODE_OLD'      =>  $outstanding_charges["TR_CODE_OLD_TUNGGAKAN"],
                                    'TR_CODE_NEW'      =>  $outstanding_charges["TR_CODE_TUNGGAKAN"],
                                    'TR_DESC'          =>  $outstanding_charges["TR_DESC_TUNGGAKAN"],
                                    'AMOUNT'           =>  $outstanding_charges["BALANCE_AMOUNT"],
                                    'DISPLAY_PRIORITY' =>  '3',
                                );
            }
        }

        // ---- Other Charge ----
        $list_of_other_charges = $this->getOtherCharge($account_id);

        if ( $list_of_other_charges !== false)
        {
            foreach ($list_of_other_charges as $other_charge) 
            {
                # code...
                $data[] = array( 
                                    'TR_CODE_OLD'      =>  $other_charge["TR_CODE_OLD"],
                                    'TR_CODE_NEW'      =>  $other_charge["TR_CODE_NEW"],
                                    'TR_DESC'          =>  $other_charge["TR_DESC"],
                                    'AMOUNT'           =>  $other_charge["AMOUNT"],
                                    'DISPLAY_PRIORITY' =>  '2',
                                );
            }
        }

        // Select list of column that we need to sort by
        foreach ($data as $key => $row) 
        {
            # code...
            $display_priority_1[$key]  = $row['DISPLAY_PRIORITY'];
            $display_priority_2[$key]  = $row['TR_CODE_NEW'];
        }

        // Sort the data retrieved from function with out custom sort by column above
        array_multisort($display_priority_1, SORT_ASC, $display_priority_2, SORT_ASC, $data);
        
        return $data;
    }

}