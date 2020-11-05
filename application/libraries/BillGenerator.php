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
        $this->CI->load->model('Integration/M_b_int_latest', 'm_b_int_latest');
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

        // Get transaction code for selected category such as "BIL TIPPING FEE" or "BIL AIR"
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

        if ( $account_detail["STATUS_ACC"] == 2 )
        {
            $data = false;
        }

        return $data;
    }

    public function getLebihanBayaranTahunan($account_id,$year_to_check)
    {
        $year = 0;
        $prev_year = 0;
        $total_yearly_amount = 0;

        $list_of_transaction = $this->CI->m_bill_item->groupAmountByYearTrcode( $account_id );

        foreach ($list_of_transaction as $bill_transaction) 
        {
            $year = $bill_transaction["BILL_YEAR"];

            if ( $year != $year_to_check) 
            {
                // Skip data checking if $bill_transaction["BILL_YEAR"] not within the required $year_to_check
                continue;
            }

            if ( $bill_transaction["TR_CODE"] != "" && strlen( $bill_transaction["TR_CODE"] ) > 5 )
            {
                $original_trcode = $bill_transaction["TR_CODE"];
            }
            else if ( $bill_transaction["TR_CODE"] == "" && strlen( $bill_transaction["TR_CODE_OLD"] ) == 5 )
            {
                $original_trcode = $bill_transaction["TR_CODE_OLD"];
            }

            // Save all bill transaction amount
            if ( $prev_year == 0 || $prev_year == $bill_transaction["BILL_YEAR"] )
            {
                $prev_year = $year;

                // Sum up all bill amount to get last balance at the the end of the year
                if ( substr($original_trcode,0,1) == "1" )
                {
                    $total_yearly_amount = $total_yearly_amount + $bill_transaction["TOTAL_AMOUNT"];
                }
                else if ( substr($original_trcode,0,1) == "2" && $bill_transaction["BILL_CATEGORY"] = "R")
                {
                    $total_yearly_amount = $total_yearly_amount - $bill_transaction["TOTAL_AMOUNT"];
                }
                else if ( substr($original_trcode,0,1) == "2" && $bill_transaction["BILL_CATEGORY"] = "J")
                {
                    $total_yearly_amount = $total_yearly_amount + $bill_transaction["TOTAL_AMOUNT"];
                }
                
                // echo $prev_year." ---> ".$total_yearly_amount." [$original_trcode]"."</br>";
            }
            else if ( $prev_year != $year )
            {
                // echo $prev_year." ---> ".$total_yearly_amount." [$original_trcode]"."</br>";

                $prev_year = $year;
                $total_yearly_amount = 0;

                // Sum up all bill amount to get last balance at the the end of the year
                if ( substr($original_trcode,0,1) == "1" )
                {
                    $total_yearly_amount = $total_yearly_amount + $bill_transaction["TOTAL_AMOUNT"];
                }
                else if ( substr($original_trcode,0,1) == "2" && $bill_transaction["BILL_CATEGORY"] = "R")
                {
                    $total_yearly_amount = $total_yearly_amount - $bill_transaction["TOTAL_AMOUNT"];
                }
                else if ( substr($original_trcode,0,1) == "2" && $bill_transaction["BILL_CATEGORY"] = "J")
                {
                    $total_yearly_amount = $total_yearly_amount + $bill_transaction["TOTAL_AMOUNT"];
                }
            }
        }
        // echo $year_to_check." ---> ".$total_yearly_amount." "."</br>";

        return $total_yearly_amount;
    }

    public function getCurrentMonthOutstandingCharge($account_id)
    {
        $this->calcPastTransaction($account_id);
        die();
        // Get account detail
        $account_detail = $this->CI->m_acc_account->getAccountDetailOnlyById( $account_id );

        // Get category detail
        $category_code = $this->CI->m_a_category->get_a_category_details( $account_detail["CATEGORY_ID"] );
        $default_trcode = $category_code["TRCODE_CATEGORY"];

        // Get list of current account charges before current month
        $month = date('n');
        $year = date('Y');
        $data = array();

        if ( $month == 1)
        {
            $month = 12;
            $year = $year - 1;
        }

        $list_of_transaction = $this->CI->m_bill_item->groupAmountByYearTrcode( $account_id );

        // var_dump( $this->getLebihanBayaranTahunan($account_id,0) );
        // die();

        
        if ( !empty($list_of_transaction) )
        {
            // Manipulate data
            $i = 1;
            $prev_year = 0;
            $already_check = 0;
            foreach ($list_of_transaction as $bill_transaction) 
            {
                if ( $prev_year != $bill_transaction["BILL_YEAR"] )
                {
                    $already_check = 0;
                }               

                // Check last year extra payment
                if ( $already_check == 0 ) 
                {
                    $last_year_extra = $this->getLebihanBayaranTahunan( $account_id, $bill_transaction["BILL_YEAR"] );
                    $new_year_tunggakan_trcode = "12".substr( $default_trcode,2 );

                    // echo "Triggered".PHP_EOL;

                    if ( $last_year_extra > 0 )
                    {
                        $outstandingBill[ $new_year_tunggakan_trcode ] = $last_year_extra ;
                    }
                    else if ( $last_year_extra < 0 )
                    {
                        $tr_code_lebihan_tahunan_old = "11090";
                        $tr_code_lebihan_tahunan_new = "11119999";
                        $outstandingBill[ $new_year_tunggakan_trcode ] = $last_year_extra;
                        $outstandingBill[$tr_code_lebihan_tahunan_new] = $last_year_extra;
                    }

                    $already_check = 1; // Disable check for next row with the same bill_year value
                }


                if ( $bill_transaction["TR_CODE"] != "" && strlen( $bill_transaction["TR_CODE"] ) > 5 )
                {
                    $original_trcode = $bill_transaction["TR_CODE"];
                    $temp_trcode = $bill_transaction["TR_CODE"];
                }
                else if ( $bill_transaction["TR_CODE"] == "" && strlen( $bill_transaction["TR_CODE_OLD"] ) == 5 )
                {
                    $original_trcode = $bill_transaction["TR_CODE_OLD"];
                    $temp_trcode = $bill_transaction["TR_CODE_OLD"];
                }

                if ( substr( $temp_trcode,0,2 ) == '11')
                {
                    $temp_trcode = "12".substr( $temp_trcode,2 );
                }

                if ( ( $temp_trcode != "" || $temp_trcode != NULL ) && ( $original_trcode != "11090" && $original_trcode != "11119999" ) )
                {
                    if ( substr( $temp_trcode,0,1 ) == "1" ) // Handle caj
                    {
                        if ( empty( $outstandingBill[ $temp_trcode ] ) )
                        {
                            $outstandingBill[ $temp_trcode ] = $bill_transaction["TOTAL_AMOUNT"] ;
                        }
                        else if ( isset( $outstandingBill[ $temp_trcode ] ) )
                        {
                            if ( isset( $outstandingBill[$temp_trcode] ) && substr( $temp_trcode,0,2 ) == '12' && $outstandingBill[$temp_trcode] == $bill_transaction["TOTAL_AMOUNT"] && substr( $original_trcode,0,2 ) == '12' )
                            {
                                $outstandingBill[$temp_trcode] = $outstandingBill[$temp_trcode] + 0;
                            }
                            else if ( isset( $outstandingBill[$temp_trcode] ) && substr( $temp_trcode,0,2 ) == '12' && $outstandingBill[$temp_trcode] != $bill_transaction["TOTAL_AMOUNT"] && substr( $original_trcode,0,2 ) == '12' && $bill_transaction["BILL_CATEGORY"] == "B" )
                            {
                                $outstandingBill[$temp_trcode] = $bill_transaction["TOTAL_AMOUNT"];
                            }
                            else
                            {
                                $outstandingBill[$temp_trcode] = $outstandingBill[$temp_trcode] + $bill_transaction["TOTAL_AMOUNT"];
                            }
                        }
                        // echo $temp_trcode.PHP_EOL;
                        // echo $outstandingBill[$temp_trcode] .PHP_EOL;
                    }
                    else if ( substr( $temp_trcode,0,1 ) == "2" ) // Handle payment
                    {
                        // Merge payment to tunggakan transaction code
                        $caj_tunggakan_trcode = "12".substr( $temp_trcode,2 ) ;

                        if ( empty( $outstandingBill[ $caj_tunggakan_trcode ] ) )
                        {
                            $outstandingBill[ $caj_tunggakan_trcode ] = $bill_transaction["TOTAL_AMOUNT"] * -1;
                        }
                        else if ( isset( $outstandingBill[ $caj_tunggakan_trcode ] ) )
                        {
                            $outstandingBill[$caj_tunggakan_trcode] = $outstandingBill[$caj_tunggakan_trcode] + ($bill_transaction["TOTAL_AMOUNT"] * -1);
                        }
                        // echo $caj_tunggakan_trcode.PHP_EOL;
                        // echo $outstandingBill[$caj_tunggakan_trcode] .PHP_EOL;
                    }
                }

                $prev_year = $bill_transaction["BILL_YEAR"];
                $i++;
            }

            foreach ($outstandingBill as $tr_code => $total_amount) 
            {
                if ( $tr_code != "11090" && $tr_code != "11119999" )
                {
                    $tr_code_tunggakan = "12".substr($tr_code,2);
                    
                    if ( strlen($tr_code) == 5 )
                    {
                        $data_search_tunggakan["MCT_TRCODE"] = $tr_code_tunggakan;
                        unset( $data_search_tunggakan["MCT_TRCODENEW"] );
                    }
                    else
                    {
                        $data_search_tunggakan["MCT_TRCODENEW"] = $tr_code_tunggakan;
                        unset( $data_search_tunggakan["MCT_TRCODE"] );
                    }

                    // Get tr_code description
                    $tr_detail_tunggakan  = $this->CI->m_tr_code->get_tr_code( $data_search_tunggakan );

                    $data[] = array(
                                        'TR_CODE_TUNGGAKAN'     => $tr_code_tunggakan,
                                        'TR_CODE_OLD_TUNGGAKAN' => $tr_detail_tunggakan["MCT_TRCODE"],
                                        'TR_DESC_TUNGGAKAN'     => $tr_detail_tunggakan["MCT_TRDESC"],
                                        'PRIORITY'              => $tr_detail_tunggakan['MCT_PRIORT'],
                                        'BALANCE_AMOUNT'        => $total_amount, 
                                    );
                }
            }

            if ( isset( $outstandingBill["11090"] ) || isset( $outstandingBill["11119999"] ) )
            {
                // Get tr_code description
                $data_search_lebihan["MCT_TRCODENEW"] = "11119999";
                $tr_detail_tunggakan  = $this->CI->m_tr_code->get_tr_code( $data_search_lebihan );
                
                $data = array(
                                'TR_CODE_TUNGGAKAN'     => $tr_code_tunggakan,
                                'TR_CODE_OLD_TUNGGAKAN' => $tr_detail_tunggakan["MCT_TRCODE"],
                                'TR_DESC_TUNGGAKAN'     => $tr_detail_tunggakan["MCT_TRDESC"],
                                'PRIORITY'              => $tr_detail_tunggakan['MCT_PRIORT'],
                                'BALANCE_AMOUNT'        => $total_amount, 
                            );
            }

        }
        else
        {
            $data = false;
            // $data = $list_of_transaction;
        }

        echo "<pre>";
        var_dump($outstandingBill);

        die();

        return $data;
    }

    public function calcPastTransaction($account_id)
    {
        /* *****************************************
            Calc all transaction as yearly
        ***************************************** */
        
        // Get account detail
        $account_detail = $this->CI->m_acc_account->getAccountDetailOnlyById( $account_id );

        // Get category detail
        $category_code = $this->CI->m_a_category->get_a_category_details( $account_detail["CATEGORY_ID"] );
        $default_trcode = $category_code["TRCODE_CATEGORY"];

        $list_of_transaction = $this->CI->m_bill_item->groupAmountByYearTrcode( $account_id );

        if ( !empty($list_of_transaction) )
        {
            $prev_year = 0;
            $already_check = 0;
            $outstandingBill = array();

            foreach ($list_of_transaction as $bill_transaction) 
            {
                // echo "</br>";
                if ( $prev_year != $bill_transaction["BILL_YEAR"] )
                {
                    $already_check = 0;
                    // echo json_encode($outstandingBill)."</br>";
                }               

                // Check last year extra payment
                if ( $already_check == 0 ) 
                {
                    $last_year_extra = $this->getLebihanBayaranTahunan( $account_id, $bill_transaction["BILL_YEAR"] - 1 );
                    $new_year_tunggakan_trcode = "12".substr( $default_trcode,2 );

                    // echo "Triggered"." => ".$last_year_extra."</br>";

                    // Check if lebihan tahunan already exist
                    // If exist then delete all transaction in variable $outstandingBill and only leave lebihan transaction
                    // var_dump( $outstandingBill );
                    if ( $last_year_extra < 0 )
                    {
                        unset($outstandingBill);
                        $tr_code_lebihan_tahunan_old = "11090";
                        $tr_code_lebihan_tahunan_new = "11119999";
                        $outstandingBill[ $new_year_tunggakan_trcode ] = $last_year_extra;
                        $outstandingBill[$tr_code_lebihan_tahunan_new] = $last_year_extra;
                    }

                    $already_check = 1; // Disable check for next row with the same bill_year value
                }

                // Check if account already has transaction 11090.
                // If value not the same then take from existing transaction
                if ( $bill_transaction["TR_CODE"] == "11119999" || $bill_transaction["TR_CODE_OLD"] == "11090" )
                {
                    // Compare with the value $last_year_extra
                    if ( isset($outstandingBill["11119999"] ) && $outstandingBill["11119999"] != $bill_transaction["TOTAL_AMOUNT"] )
                    {
                        // $tr_code_tunggakan_special should be used in here only
                        $tr_code_tunggakan_special = "12".substr( $default_trcode,2 );
                        $outstandingBill[ $tr_code_tunggakan_special ] = $bill_transaction["TOTAL_AMOUNT"];
                        $outstandingBill["11119999"] = $bill_transaction["TOTAL_AMOUNT"];
                    }
                    else if ( empty($outstandingBill["11119999"] ) )
                    {
                        // $tr_code_tunggakan_special should be used in here only
                        $tr_code_tunggakan_special = "12".substr( $default_trcode,2 );
                        $outstandingBill[ $tr_code_tunggakan_special ] = $bill_transaction["TOTAL_AMOUNT"];
                        $outstandingBill["11119999"] = $bill_transaction["TOTAL_AMOUNT"];
                    }
                }


                if ( $bill_transaction["TR_CODE"] != "" && strlen( $bill_transaction["TR_CODE"] ) > 5 )
                {
                    $original_trcode = $bill_transaction["TR_CODE"];
                    $temp_trcode = $bill_transaction["TR_CODE"];
                }
                else if ( $bill_transaction["TR_CODE"] == "" && strlen( $bill_transaction["TR_CODE_OLD"] ) == 5 )
                {
                    $original_trcode = $bill_transaction["TR_CODE_OLD"];
                    $temp_trcode = $bill_transaction["TR_CODE_OLD"];
                }

                if ( substr( $temp_trcode,0,2 ) == '11')
                {
                    $temp_trcode = "12".substr( $temp_trcode,2 );
                }

                if ( ( $temp_trcode != "" || $temp_trcode != NULL ) && ( $original_trcode != "11090" && $original_trcode != "11119999" ) )
                {
                    if ( substr( $temp_trcode,0,1 ) == "1" ) // Handle caj
                    {
                        if ( empty( $outstandingBill[ $temp_trcode ] ) )
                        {
                            $outstandingBill[ $temp_trcode ] = $bill_transaction["TOTAL_AMOUNT"] ;
                        }
                        else if ( isset( $outstandingBill[ $temp_trcode ] ) )
                        {
                            if ( substr( $temp_trcode,0,2 ) == '12' && substr( $original_trcode,0,2 ) == '12' )
                            {
                                if ( $outstandingBill[$temp_trcode] == $bill_transaction["TOTAL_AMOUNT"] )
                                {
                                    $outstandingBill[$temp_trcode] = $outstandingBill[$temp_trcode] + 0;
                                }
                                else if ( $outstandingBill[$temp_trcode] != $bill_transaction["TOTAL_AMOUNT"] && $bill_transaction["BILL_CATEGORY"] == "B")
                                {
                                    $outstandingBill[$temp_trcode] = $bill_transaction["TOTAL_AMOUNT"];
                                }
                                else
                                {
                                    $outstandingBill[$temp_trcode] = $outstandingBill[$temp_trcode] + $bill_transaction["TOTAL_AMOUNT"];
                                }
                            }
                            else
                            {
                                $outstandingBill[$temp_trcode] = $outstandingBill[$temp_trcode] + $bill_transaction["TOTAL_AMOUNT"];
                            }
                        }

                        // echo $temp_trcode.PHP_EOL;
                        // echo $outstandingBill[$temp_trcode] .PHP_EOL;
                        // echo "</br>";
                    }
                    else if ( substr( $temp_trcode,0,1 ) == "2" ) // Handle payment
                    {
                        // Merge payment to tunggakan transaction code
                        $caj_tunggakan_trcode = "12".substr( $temp_trcode,2 ) ;

                        if ( empty( $outstandingBill[ $caj_tunggakan_trcode ] ) )
                        {
                            $outstandingBill[ $caj_tunggakan_trcode ] = $bill_transaction["TOTAL_AMOUNT"] * -1;
                        }
                        else if ( isset( $outstandingBill[ $caj_tunggakan_trcode ] ) )
                        {
                            $outstandingBill[$caj_tunggakan_trcode] = $outstandingBill[$caj_tunggakan_trcode] + ($bill_transaction["TOTAL_AMOUNT"] * -1);
                        }

                        // echo $caj_tunggakan_trcode.PHP_EOL;
                        // echo $outstandingBill[$caj_tunggakan_trcode] .PHP_EOL;
                        // echo "</br>";
                    }
                }

                $prev_year = $bill_transaction["BILL_YEAR"];
                // $i++;
            }


            // var_dump( array_sum($outstandingBill) );
            // die();

            foreach ($outstandingBill as $tr_code => $total_amount) 
            {
                if ( $tr_code != "11090" && $tr_code != "11119999" )
                {
                    $tr_code_tunggakan = "12".substr($tr_code,2);
                    
                    if ( strlen($tr_code) == 5 )
                    {
                        $data_search_tunggakan["MCT_TRCODE"] = $tr_code_tunggakan;
                        unset( $data_search_tunggakan["MCT_TRCODENEW"] );
                    }
                    else
                    {
                        $data_search_tunggakan["MCT_TRCODENEW"] = $tr_code_tunggakan;
                        unset( $data_search_tunggakan["MCT_TRCODE"] );
                    }

                    // Get tr_code description
                    $tr_detail_tunggakan  = $this->CI->m_tr_code->get_tr_code( $data_search_tunggakan );

                    $data[] = array(
                                        'TR_CODE_TUNGGAKAN'     => $tr_code_tunggakan,
                                        'TR_CODE_OLD_TUNGGAKAN' => $tr_detail_tunggakan["MCT_TRCODE"],
                                        'TR_DESC_TUNGGAKAN'     => $tr_detail_tunggakan["MCT_TRDESC"],
                                        'PRIORITY'              => $tr_detail_tunggakan['MCT_PRIORT'],
                                        'BALANCE_AMOUNT'        => $total_amount, 
                                    );
                }
            }

            if ( array_sum($outstandingBill) < 0 )
            {
                // Get tr_code description
                $data_search_tunggakan["MCT_TRCODENEW"] = "11119999";
                $tr_detail_tunggakan  = $this->CI->m_tr_code->get_tr_code( $data_search_tunggakan );

                unset($data);

                $data[] = array(
                                    'TR_CODE_TUNGGAKAN'     => "11119999",
                                    'TR_CODE_OLD_TUNGGAKAN' => $tr_detail_tunggakan["MCT_TRCODE"],
                                    'TR_DESC_TUNGGAKAN'     => $tr_detail_tunggakan["MCT_TRDESC"],
                                    'PRIORITY'              => $tr_detail_tunggakan['MCT_PRIORT'],
                                    'BALANCE_AMOUNT'        => array_sum($outstandingBill), 
                                );
            }
        }
        else
        {
            $data = false;
        }

        // echo "<pre>";
        // var_dump($data);
        return $data;
    }

    public function getOtherCharge($account_id)
    {
        // Get account detail
        $account_detail = $this->CI->m_acc_account->getAccountDetailOnlyById( $account_id );

        // Check if outstanding bill already not paid for greater than 3 months
        $date_search_1["ACCOUNT_ID"]        = $account_id;
        $date_search_1["BILL_CATEGORY"]     = "R";
        $date_search_1["CUSTOM_COLUMN"]     = "BILL_ID";

        $date_search_2["ACCOUNT_ID"]        = $account_id;
        $date_search_2["BILL_CATEGORY"]     = "R";
        $date_search_2["CUSTOM_COLUMN"]     = "BILL_ID";
        
        $date_search_3["ACCOUNT_ID"]        = $account_id;
        $date_search_3["BILL_CATEGORY"]     = "R";
        $date_search_3["CUSTOM_COLUMN"]     = "BILL_ID";
        
        $date_search_4["ACCOUNT_ID"]        = $account_id;
        $date_search_4["BILL_CATEGORY"]     = "R";
        $date_search_4["CUSTOM_COLUMN"]     = "BILL_ID";
        
        $date_search_5["ACCOUNT_ID"]        = $account_id;
        $date_search_5["BILL_CATEGORY"]     = "R";
        $date_search_5["CUSTOM_COLUMN"]     = "BILL_ID";

        if ( $account_detail["BILL_TYPE"] == 1 || $account_detail["BILL_TYPE"] == 3 )
        {
            $date_search_1["BILL_MONTH_FIRST"]  = date('n') - 1;
            $date_search_1["BILL_MONTH_LAST"]   = date('n');
            $date_search_1["BILL_YEAR"]         = date('Y');

            $date_search_2["BILL_MONTH_FIRST"]  = date('n') - 2;
            $date_search_2["BILL_MONTH_LAST"]   = date('n');
            $date_search_2["BILL_YEAR"]         = date('Y');

            $date_search_3["BILL_MONTH_FIRST"]  = date('n') - 3;
            $date_search_3["BILL_MONTH_LAST"]   = date('n');
            $date_search_3["BILL_YEAR"]         = date('Y');

            $date_search_4["BILL_MONTH_FIRST"]  = date('n') - 4;
            $date_search_4["BILL_MONTH_LAST"]   = date('n');
            $date_search_4["BILL_YEAR"]         = date('Y');

            $date_search_5["BILL_MONTH_FIRST"]  = date('n') - 5;
            $date_search_5["BILL_MONTH_LAST"]   = date('n');
            $date_search_5["BILL_YEAR"]         = date('Y');
        }
        else if ( $account_detail["BILL_TYPE"] == 2 )
        {
            $date_search_1["BILL_YEAR_FIRST"]  = date('Y') - 1;
            $date_search_1["BILL_YEAR_LAST"]   = date('Y');

            $date_search_2["BILL_YEAR_FIRST"]  = date('Y') - 2;
            $date_search_2["BILL_YEAR_LAST"]   = date('Y');

            $date_search_3["BILL_YEAR_FIRST"]  = date('Y') - 3;
            $date_search_3["BILL_YEAR_LAST"]   = date('Y');

            $date_search_4["BILL_YEAR_FIRST"]  = date('Y') - 4;
            $date_search_4["BILL_YEAR_LAST"]   = date('Y');

            $date_search_5["BILL_YEAR_FIRST"]  = date('Y') - 5;
            $date_search_5["BILL_YEAR_LAST"]   = date('Y');
        }

        $total_payment_delayed_1_times = $this->CI->m_bill_master->get($date_search_1);
        $total_payment_delayed_2_times = $this->CI->m_bill_master->get($date_search_2);
        $total_payment_delayed_3_times = $this->CI->m_bill_master->get($date_search_3);
        $total_payment_delayed_4_times = $this->CI->m_bill_master->get($date_search_4);
        $total_payment_delayed_5_times = $this->CI->m_bill_master->get($date_search_5);

        if ( count($total_payment_delayed_5_times) == 0 )
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
        else if ( count($total_payment_delayed_4_times) == 0 )
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
        else if ( count($total_payment_delayed_3_times) == 0 )
        {
            $data_update["NOTICE_LEVEL"] = 3;
            $this->CI->m_acc_account->update_account($data_update,$account_id);
            $data = false;
        }
        else if ( count($total_payment_delayed_2_times) == 0 )
        {
            $data_update["NOTICE_LEVEL"] = 2;
            $this->CI->m_acc_account->update_account($data_update,$account_id);
            $data = false;
        }
        else if ( count($total_payment_delayed_1_times) == 0 )
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

        // Check if account do not have outstanding balance greater than 0
        /* This check is required to ensure that if inactive account that have no outstanding balance
           must not have lod or notis mahkamah charge
        */
        $check_balance_amount = $this->CI->m_bill_item->getLastAmountTrcode( $account_id );
        
        foreach ($check_balance_amount as $detail) 
        {
            if ( $detail["TR_CODE"] != "" )
            {
                $tr_code = $detail["TR_CODE"];
            }
            else if ( $detail["TR_CODE"] == "" && $detail["TR_CODE_OLD"] != "" )
            {
                $tr_code = $detail["TR_CODE_OLD"];
            }
            
            if ( substr($tr_code, 0, 1) == "1" )
            {
                $tr_code = "12".substr($tr_code, 2);
                if ( isset($data_test[ $tr_code ]) )
                {
                    $data_test[ $tr_code ] =  $data_test[ $tr_code ] + $detail["TOTAL_AMOUNT"];
                }
                else if ( empty($data_test[ $tr_code ]) )
                {
                    $data_test[ $tr_code ] =  $detail["TOTAL_AMOUNT"];
                }
            }
            else if ( substr($tr_code, 0, 1) == "2" )
            {
                $tr_code = "12".substr($tr_code, 2);

                if ( isset($data_test[ $tr_code ]) )
                {
                    $data_test[ $tr_code ] =  $data_test[ $tr_code ] - $detail["TOTAL_AMOUNT"];
                }
                else if ( empty($data_test[ $tr_code ]) )
                {
                    $data_test[ $tr_code ] = $detail["TOTAL_AMOUNT"];
                }
            }
        }
        
        if ( isset($data_test) )
        {
            if ( array_sum($data_test) == 0 )
            {
                $data_update["NOTICE_LEVEL"] = 0;
                $this->CI->m_acc_account->update_account($data_update,$account_id);
                $data = false;
            }
        }
        else if ( empty($data_test) )
        {
            $data_update["NOTICE_LEVEL"] = 0;
            $this->CI->m_acc_account->update_account($data_update,$account_id);
            $data = false;            
        }

        return $data;
    }

    public function generateCurrentBill($account_id,$month,$year)
    {
        // Get account detail
        $account_detail = $this->CI->m_acc_account->getAccountDetailOnlyById( $account_id );

        if ($account_detail["TYPE_ID"] == "11" )
        {
            // Do nothing
        }
        else
        {
            if ( $account_detail["STATUS_ACC"] == 1 && ( $account_detail["CATEGORY_ID"] != "" || $account_detail["CATEGORY_ID"] != NULL ) )
            {
                // Check if a master record already exist for current month

                $data_search["BILL_YEAR"]       =  $year;
                $data_search["ACCOUNT_ID"]      =  $account_id;
                $data_search["BILL_CATEGORY"]   =  "B";
                $date_search["CUSTOM_COLUMN"]   =  "BILL_ID";

                if ( $account_detail["BILL_TYPE"] == 1 )
                {
                    $data_search["BILL_MONTH"]      =  $month;
                }        

                $exist = count( $this->CI->m_bill_master->get($data_search) );

                if ( $exist == 0 )
                {
                    $data_insert_b_master["ACCOUNT_ID"]      =  $account_id;
                    $data_insert_b_master["BILL_YEAR"]       =  date('Y');
                    $data_insert_b_master["BILL_MONTH"]      =  date('n');
                    $data_insert_b_master["BILL_CATEGORY"]   =  "B";
                    $data_insert_b_master["BILL_NUMBER"]     =  date('Y').date('m').str_pad($account_id,6,"0",STR_PAD_LEFT).'1';
                    $data_insert_b_master["BILL_TYPE"]       =  $account_detail["BILL_TYPE"]; // 1 =  Monthly, 2 =  Yearly, 3 = Rumah Tmn mudun
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

                    // After add record on b_item, add to b_int_latest for system "KUTIPAN" to retreive bill record and process
                    $data_insert_b_int_latest['ACCOUNT_NUMBER']  = $account_detail["ACCOUNT_NUMBER"];
                    $data_insert_b_int_latest['TR_CODE']         = $item['KOD_CAJ_LAMA'];
                    $data_insert_b_int_latest['TR_CODE_NEW']     = $item['KOD_CAJ_BARU'];
                    $data_insert_b_int_latest['AMOUNT']          = $item['CAJ_ANGGARAN'];
                    $data_insert_b_int_latest['PROSES_STATUS']   = "0";

                    // Check bill item in b_int_latest before add. If no record then add new record else update record
                    $data_condition = $data_insert_b_int_latest;
                    $data_condition["CURRENT_MONTH"] = date('n');
                    $data_condition["CURRENT_YEAR"] = date('Y');
                    unset( $data_condition['AMOUNT'] );
                    $b_int_latest_status = $this->CI->m_b_int_latest->get($data_condition);

                    if ( isset($b_int_latest_status) && count($b_int_latest_status) == 0 )
                    {
                        $this->CI->m_b_int_latest->insert($data_insert_b_int_latest);
                    }
                    else if ( isset($b_int_latest_status) && count($b_int_latest_status) > 0 )
                    {
                        // $dt_added = DateTime::createFromFormat('d/m/Y', $b_int_latest_status[0]["CUSTOM_DT_ADDED"]);                    
                        $this->CI->m_b_int_latest->update( $data_condition, $data_insert_b_int_latest );
                    }
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

                        // After add record on b_item, add to b_int_latest for system "KUTIPAN" to retreive bill record and process
                        $data_insert_b_int_latest['ACCOUNT_NUMBER']  = $account_detail["ACCOUNT_NUMBER"];
                        $data_insert_b_int_latest['TR_CODE']         = $other_charge['TR_CODE_OLD'];
                        $data_insert_b_int_latest['TR_CODE_NEW']     = $other_charge['TR_CODE_NEW'];
                        $data_insert_b_int_latest['AMOUNT']          = $other_charge['AMOUNT'];
                        $data_insert_b_int_latest['PROSES_STATUS']   = "0";

                        // Check bill item in b_int_latest before add. If no record then add new record else update record
                        $data_condition = $data_insert_b_int_latest;
                        $data_condition["CURRENT_MONTH"] = date('n');
                        $data_condition["CURRENT_YEAR"] = date('Y');
                        unset( $data_condition['AMOUNT'] );
                        $b_int_latest_status = $this->CI->m_b_int_latest->get($data_condition);

                        if ( isset($b_int_latest_status) && count($b_int_latest_status) == 0 )
                        {
                            $this->CI->m_b_int_latest->insert($data_insert_b_int_latest);
                        }
                        else if ( isset($b_int_latest_status) && count($b_int_latest_status) > 0 )
                        {
                            $this->CI->m_b_int_latest->update( $data_condition, $data_insert_b_int_latest );
                        }
                    }
                }

                $data_update_master["TOTAL_AMOUNT"] = $this->CI->m_bill_item->getBillItemTotalAmount( $b_master["BILL_ID"] )["TOTAL_AMOUNT"];
                $this->CI->m_bill_master->updateBillMasterTotalAmount($b_master["BILL_ID"],$account_id,$data_update_master);

                return $b_master;
            }
            else
            {
                return false;
            }
        }
    }

    public function generate_bil_awal_tahun()
    {
        $month = date('n');
        $year = date('Y');

        if ( $month == 1 )
        {
            // Get all active account detail including akaun inactive but bill active
            $data_search_account["CUSTOM_CONDITION1"] = true;
            $account_active_list = $this->CI->m_acc_account->get( $data_search_account );

            $i = 1;
            $total_row = count($account_active_list);

            foreach ($account_active_list as $account)
            {
                # code...
                $account_id = $account["ACCOUNT_ID"];
                $last_year_balance = $this->getLebihanBayaranTahunan( $account_id, $year - 1);

                // echo $account_id." --> ".$last_year_balance."</br>";

                $data_search["BILL_YEAR"]       =  $year;
                $data_search["ACCOUNT_ID"]      =  $account_id;
                $data_search["BILL_CATEGORY"]   =  "B";
                $data_search["CUSTOM_DT_ADDED"] =  "01/01/".$year;
                $date_search["CUSTOM_COLUMN"]   =  "BILL_ID";

                if ( $account["BILL_TYPE"] == 1 )
                {
                    $data_search["BILL_MONTH"]      =  $month;
                }

                $exist = count( $this->CI->m_bill_master->get($data_search) );

                if ( $exist == 0 )
                {
                    $data_insert_b_master["ACCOUNT_ID"]      =  $account_id;
                    $data_insert_b_master["BILL_YEAR"]       =  date('Y');
                    $data_insert_b_master["BILL_MONTH"]      =  date('n');
                    $data_insert_b_master["BILL_CATEGORY"]   =  "B";
                    $data_insert_b_master["BILL_NUMBER"]     =  date('Y').date('m').str_pad($account_id,6,"0",STR_PAD_LEFT).'1';
                    $data_insert_b_master["BILL_TYPE"]       =  $account["BILL_TYPE"]; // 1 =  Monthly, 2 =  Yearly, 3 = Rumah Tmn mudun
                    $data_insert_b_master["TOTAL_PAID"]      =  "0";
                    $data_insert_b_master["TOTAL_AMOUNT"]    =  "0";
                    $data_insert_b_master["NUMBER_GENERATE"] =  "1";

                    $this->CI->m_bill_master->insert_bill_master($data_insert_b_master);
                }

                $b_master = $this->CI->m_bill_master->get($data_search)[0];

                // Delete existing item bill under master record and recreate back. All bill_category = "J" will be ignored in this deletion
                $data_delete["ACCOUNT_ID"]      =  $account_id;
                $data_delete["BILL_ID"]         =  $b_master["BILL_ID"];
                $data_delete["BILL_CATEGORY"]   =  "B";
                $data_delete["DISPLAY_STATUS"]  =  "Y";

                $this->CI->m_bill_item->delete($data_delete);

                if ( $last_year_balance < 0 )
                {
                    $search_lebihan_trcode["MCT_TRCODENEW"] = "11119999";
                    $tr_code_lebihan_detail =  $this->CI->m_tr_code->get_tr_code( $search_lebihan_trcode );

                    $data_insert['BILL_ID']         = $b_master["BILL_ID"];
                    $data_insert['TR_CODE']         = $tr_code_lebihan_detail['MCT_TRCODENEW'];
                    $data_insert['TR_CODE_OLD']     = $tr_code_lebihan_detail['MCT_TRCODE'];
                    $data_insert['AMOUNT']          = $last_year_balance;
                    $data_insert['PRIORITY']        = "1";
                    $data_insert['ACCOUNT_ID']      = $account_id;
                    $data_insert['ITEM_DESC']       = $tr_code_lebihan_detail['MCT_TRDESC'];
                    $data_insert['BILL_CATEGORY']   = "B";

                    $this->CI->m_bill_item->insert_bill_item($data_insert);
                }
                else
                {
                    $list_of_outstanding = $this->calcPastTransaction($account_id);

                    if ( $list_of_outstanding !== false )
                    {
                        foreach ($list_of_outstanding as $transaction) 
                        {
                            # code...
                            if ( $transaction["BALANCE_AMOUNT"] > 0 )
                            {
                                $data_insert['BILL_ID']         = $b_master["BILL_ID"];
                                $data_insert['TR_CODE']         = $transaction['TR_CODE_TUNGGAKAN'];
                                $data_insert['TR_CODE_OLD']     = $transaction['TR_CODE_OLD_TUNGGAKAN'];
                                $data_insert['AMOUNT']          = $transaction['BALANCE_AMOUNT'];
                                $data_insert['PRIORITY']        = $transaction['PRIORITY'];
                                $data_insert['ACCOUNT_ID']      = $account_id;
                                $data_insert['ITEM_DESC']       = $transaction['TR_DESC_TUNGGAKAN'];
                                $data_insert['BILL_CATEGORY']   = "B";

                                $this->CI->m_bill_item->insert_bill_item($data_insert);
                            }
                        }
                    }
                }

                $data_update_master["TOTAL_AMOUNT"] = $this->CI->m_bill_item->getBillItemTotalAmount( $b_master["BILL_ID"] )["TOTAL_AMOUNT"];
                $this->CI->m_bill_master->updateBillMasterTotalAmount($b_master["BILL_ID"],$account_id,$data_update_master);

                // Display progress bar
                $this->generate_progress_bar_CLI($i,$total_row);
                $i++;
            }

            if ( $total_row > 0)
            {
                echo $total_row." bil akaun telah berjaya diproses.".PHP_EOL;
            }
            else if ( $total_row == 0 )
            {
                echo "Tiada bil akaun diproses".PHP_EOL;
            }
        }
        else
        {
            echo "!!! Proses ini hanya berjalan pada 1 Januari setiap tahun !!!".PHP_EOL;
        }
    }

    public function listCurrentBill($account_id)
    {
        // Get account detail
        $account_detail = $this->CI->m_acc_account->getAccountDetailOnlyById( $account_id );

        // Get category detail
        $category_code = $this->CI->m_a_category->get_a_category_details( $account_detail["CATEGORY_ID"] );
        $default_trcode = $category_code["TRCODE_CATEGORY"];

        $month = date('n');
        $year = date('Y');
        $list_processed_transaction  =  false;
        $list_monthly_charges        =  $this->getCurrentMonthBillCharge($account_id);
        // $list_outstanding_charges    =  $this->getCurrentMonthOutstandingCharge($account_id);
        $list_outstanding_charges    =  $this->calcPastTransaction($account_id);
        $list_of_other_charges       =  $this->getOtherCharge($account_id);

        // ---- Other Charge ----
        if ( $list_of_other_charges !== false && $account_detail["TYPE_ID"] != "11" )
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

        // ---- Outstanding Charge ----
        if ( $list_outstanding_charges !== false && $account_detail["TYPE_ID"] != "11" )
        {
            foreach ($list_outstanding_charges as $outstanding_charges) 
            {
                if ( $outstanding_charges["BALANCE_AMOUNT"] > 0 )
                {
                    $data[] = array( 
                                        'TR_CODE_OLD'      =>  $outstanding_charges["TR_CODE_OLD_TUNGGAKAN"],
                                        'TR_CODE_NEW'      =>  $outstanding_charges["TR_CODE_TUNGGAKAN"],
                                        'TR_DESC'          =>  $outstanding_charges["TR_DESC_TUNGGAKAN"],
                                        'AMOUNT'           =>  $outstanding_charges["BALANCE_AMOUNT"],
                                        'DISPLAY_PRIORITY' =>  '3',
                                    );
                }

                $temp_outstanding_charges[ $outstanding_charges["TR_CODE_TUNGGAKAN"] ] = $outstanding_charges["BALANCE_AMOUNT"];
                
                if ( $outstanding_charges["BALANCE_AMOUNT"] < 0 && $outstanding_charges["TR_CODE_TUNGGAKAN"] == "11119999" )
                {
                    $custom_trcode1  =   '12'.substr( $default_trcode, 2 );
                    $temp_outstanding_charges[ $custom_trcode1 ] = $outstanding_charges["BALANCE_AMOUNT"];
                }
                
            }
        }

        // echo "<pre>";
        // var_dump( $list_outstanding_charges);
        // die();

        // Calculate monthly charges
        // ---- Current charge ----  
        if ( $list_monthly_charges !== false )
        {      
            foreach ($list_monthly_charges as $monthly_charge_item) 
            {
                # code...
                $tr_code_old        =   $monthly_charge_item["KOD_CAJ_LAMA"];
                $tr_code_new        =   $monthly_charge_item["KOD_CAJ_BARU"];
                $tr_desc            =   $monthly_charge_item["PERIHAL_CAJ_BARU"];
                $caj                =   $monthly_charge_item["CAJ_ANGGARAN"] ;
                $tunggakan_tr_code  =   '12'.substr($monthly_charge_item["KOD_CAJ_BARU"], 2);

                if ( isset($temp_outstanding_charges[ $tunggakan_tr_code ] ) )
                {
                    if ( $temp_outstanding_charges[ $tunggakan_tr_code ] <=0 )
                    {
                        $caj = $caj + $temp_outstanding_charges[ $tunggakan_tr_code ];
                    }
                }

                $data[] = array( 
                                    'TR_CODE_OLD'      =>  $tr_code_old,
                                    'TR_CODE_NEW'      =>  $tr_code_new,
                                    'TR_DESC'          =>  $tr_desc,
                                    'AMOUNT'           =>  $caj,
                                    'DISPLAY_PRIORITY' =>  '1',
                                );
            }
        }

        // Check if data array is generated. If not return empty array
        if( isset($data) )
        {
            // Select list of column that we need to sort by
            foreach ($data as $key => $row) 
            {
                # code...
                $display_priority_1[$key]  = $row['DISPLAY_PRIORITY'];
                $display_priority_2[$key]  = $row['TR_CODE_NEW'];
            }

            // Sort the data retrieved from function with out custom sort by column above
            array_multisort($display_priority_1, SORT_ASC, $display_priority_2, SORT_ASC, $data);
        }
        else
        {
            $data = array();
        }

                
        return $data;
    }

    public function generate_progress_bar_CLI($current_count,$total_count)
    {
        # code...
        $perc = number_format( ($current_count/$total_count*100) , 2, '.', ',');

        $loading_arrow = str_repeat("=", round($perc/2)).">";
        $loading_bar = str_pad($loading_arrow,51," ");

        echo "[".$loading_bar."] ".$perc." % "."(".$current_count."/".$total_count.")"."\r";
        flush();
    }

}