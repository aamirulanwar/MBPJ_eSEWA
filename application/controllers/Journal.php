<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Journal extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_library('Audit_trail_lib');

        load_model('Bill/M_bill_master', 'm_bill_master');
        load_model('Bill/M_bill_item', 'm_bill_item');
        load_model('Account/M_acc_account', 'm_acc_account');
        load_model('Journal/M_journal', 'm_journal');
        load_model('TrCode/M_tran_code', 'm_tran_code');
        load_model('User/M_users', 'm_users');
    }

    function _remap($method)
    {
        $array = array(
            'insert',
            'generate_current_journal',
            'index',
            'journal_code_lists',
            'entry',
            'update',
            'doc_journal',
            'generate_new_receipt_journal',
            'encrypt',
            'checkDataValidity',
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->search();
    }

    function index(){
        $this->auth->restrict_access($this->curuser,array(7003));

        $data['link_1']     = 'Penyelarasan';
        $data['link_2']     = '';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Kelulusan Jurnal';

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_journal');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_journal',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['account_number']  = '';
                $data_search['bill_number']     = '';
                $data_search['date_start']      = '';
                $data_search['date_end']        = '';
                $data_search['bill_category']   = '';
            endif;
        endif;

        $data['data_search'] = $data_search;
        $data['data'] = $this->m_journal->get_lists_temp_journal();

        // echo json_encode($data['data']);
        // die();
        // var_dump();

        templates('journal/v_journal_kelulusan',$data);
    }

    function entry(){
        $this->auth->restrict_access($this->curuser,array(7002));

        $data['link_1']     = 'Penyelarasan';
        $data['link_2']     = '';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Kemasukan Jurnal';

        $data['data'] = $this->m_journal->get_lists_temp_journal();

        templates('journal/v_journal_entry',$data);
    }

    function search(){
        $this->auth->restrict_access($this->curuser,array(7001));

        $data['link_1']     = 'Penyelarasan';
        $data['link_2']     = '';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Pelarasan';

        $search_segment = uri_segment(3);
        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_journal');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_journal',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['account_number']  = '';
                $data_search['bill_number']     = '';
                $data_search['date_start']      = '';
                $data_search['date_end']        = '';
                $data_search['bill_category']   = '';
            endif;
        endif;

        $data['data_search'] = $data_search;

        $total = $this->m_bill_master->count_bill_history($data_search);
        $links          = '/journal/search';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_bill_master->get_bill_history_list($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('journal/v_journal_search',$data);
    }

    function generate_current_journal()
    {
        $data['link_1']     = 'Journal';
        $data['link_2']     = '<a href="/journal/search">Carian</a>';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $bill_id = urlDecrypt(uri_segment(3));
        if(!is_numeric($bill_id)):
            return false;
        endif;

        if ($bill_id != -999)
        {            
            $data_bill_master = $this->m_bill_master->get_bill_master($bill_id);
            $get_details = $this->m_acc_account->get_account_details($data_bill_master['ACCOUNT_ID']);
            if(!$get_details):
                return false;
            endif;

            $data['account'] = $get_details;

            $bill_item = $this->m_bill_item->get_bill_item($bill_id);


            $data['account']        = $get_details;
            $data['statement_type'] = 'BIL';
            $data['bill_master']    = $data_bill_master;
            $data['bill_item']      = $bill_item;
        }
        else if ($bill_id == -999) 
        {
            # code...
            $formData           = urlDecrypt(uri_segment(4));
            $formDataItem       = explode( ':', $formData );
            $account_number     = $formDataItem[0];
            $tarikh_resit       = $formDataItem[1];
            $bulantahun_resit   = $formDataItem[2];
            $bulan_resit        = explode( '/', $bulantahun_resit )[0];
            $tahun_resit        = explode( '/', $bulantahun_resit )[1];

            $get_details = $this->m_acc_account->get_account_details_by_accountNo($account_number);
            // var_dump($get_details);

            if(!$get_details):
                return false;
            endif;

            $data['account'] = $get_details;
            $data['statement_type'] = 'RESIT';
            $data['bill_master'] = array( 
                                            "BILL_CATEGORY" => "R", 
                                            "BILL_MONTH"    => $bulan_resit, 
                                            "BILL_YEAR"     => $tahun_resit, 
                                            "BILL_NUMBER"   => "NEW_RESIT_JOURNAL_R02", 
                                            "CUSTOM_DT_ADDED"      => $tarikh_resit, 
                                        );
            $data['bill_item'] = array();
        }

        validation_rules('journal_code[]','<strong>kod transaksi</strong>','required');

        if(validation_run()==false):
            templates('journal/v_generate_current_journal',$data);
        else:

            $journal_code = $_POST['journal_code'];
            $amount = $_POST['amount'];
            $type = $_POST['type'];
            $remark = $_POST['remark'];
            $journal_id = $_POST['journal_id'];
            $mct_trcodenew = $_POST['mct_trcodenew'];

            //echo "<script>console.log('".json_encode($_POST['mct_trcodenew'])."');</script>"; //var_dump($_POST['mct_trcodenew']);
            // die();

            if ( $bill_id == -999 && input_data("bill_number") != "" || input_data("bill_number") != NULL)
            {
                $bill_number = input_data("bill_number");
            }
            else
            {
                $bill_number = $this->m_journal->generate_running_billnumber();
            }

            foreach ($_POST['journal_code'] as $i => $v) 
            {
                if ($mct_trcodenew[$i] == "" || $mct_trcodenew[$i] == NULL || $mct_trcodenew[$i] == -1)
                {
                    // return false;
                    set_notify('notify_msg',"Sila pilih kod transaksi");
                    templates('journal/v_generate_current_journal',$data);
                    return false;
                }
                else
                {
                    $data_search["MCT_TRCODENEW"]   =   $mct_trcodenew[$i];
                    $get_trcode_old                 =   $this->m_tran_code->get_tr_code($data_search);
                    $trCodeOld                      =   $get_trcode_old["MCT_TRCODE"];

                    $this->m_journal->insert_journal_temp([
                        'date_process' => input_data('DT_ADDED'),
                        'created_by' => $this->curuser["USER_ID"],
                        'bill_number' => $bill_number,
                        'bill_month' => input_data('journal_month'),
                        'bill_year' => input_data('journal_year'),
                        'journal_id' => $journal_id[$i],
                        'account_id' => input_data('account_id'),
                        'transfer_account_id' => input_data('transfer_account_id'),
                        'amount' => str_replace(",","",$amount[$i]),
                        'bill_category' => input_data('b_master_bill_category'),
                        'tr_code' => $mct_trcodenew[$i],
                        'tr_code_old' => $trCodeOld,
                        'status_approval' => 0,
                        'remark' => $remark[$i]
                    ]);
                }
            }

            set_notify('notify_msg',TEXT_SAVE_RECORD);
            templates('journal/v_generate_current_journal',$data);
        endif;
    }

    function journal_code_lists()
    {
        $data = $this->m_journal->journal_code_lists($_GET['bill_category']);

        if ($data) {
            
            header("Content-Type: application/json");

            echo json_encode($data);
        }
        else {

            header("HTTP/1.1 404 Not Found");
            // return null;
        }
    }

    function approveOrDeclineJurnal($id,$status_approval,$updated_by,$updated_at)
    {
        $journalDetail = $this->m_journal->get_journal_by_id($id);         

        if ($status_approval==1) 
        {
            $account_id = $journalDetail["ACCOUNT_ID"];
            $bill_number = $journalDetail["BILL_NUMBER"];
            $bill_month = $journalDetail["BILL_MONTH"];
            $bill_year = $journalDetail["BILL_YEAR"];
            $bill_amount = $journalDetail["AMOUNT"];
            $bill_amount = ($bill_amount == NULL) ? 0 : $bill_amount;
            $b_journal_id = $journalDetail["ID"];
            $journal_id = $journalDetail["JOURNAL_ID"];
            $journal_transfer_id = $journalDetail["TRANSFER_ACCOUNT_ID"];
            $journal_trcode = $journalDetail["TR_CODE"];
            $journal_trdesc = $journalDetail["ITEM_DESC"];
            $journal_trcode_old = $journalDetail["TR_CODE_OLD"];
            $journal_bill_category = $journalDetail["BILL_CATEGORY"];
            $journal_date = $journalDetail["DT_ADDED"];

            $journalTypeSearch["JOURNAL_ID"]    = $journal_id;
            $journalType = $this->m_journal->get_a_journal_detail($journalTypeSearch);

            if ( $journalType[0]["JOURNAL_CODE"] == "R05" )
            {
                $existingBill = $this->m_bill_master->getBillId($journal_transfer_id,$bill_month,$bill_year,$journal_bill_category);
                $oldExistingBill = $this->m_bill_master->getBillId($account_id,$bill_month,$bill_year,$journal_bill_category);
            }
            else
            {
                $existingBill = $this->m_bill_master->getBillId($account_id,$bill_month,$bill_year,$journal_bill_category);
            }

            // Get Total Existing bill number
            if ( !empty($existingBill) && $existingBill["BILL_ID"] != "" )
            {
                $bill_id = $existingBill["BILL_ID"];
                $bill_master_update["TOTAL_AMOUNT"] = $existingBill["TOTAL_AMOUNT"] + $bill_amount;

                $this->m_bill_master->updateBillMasterTotalAmount($bill_id,$account_id,$bill_master_update);
            }
            else
            {
                if ( $journalType[0]["JOURNAL_CODE"] == "R05" )
                {
                    $accountDetail = $this->m_acc_account->getAccountDetailOnlyById($journal_transfer_id);
                    $bill_type = $accountDetail["BILL_TYPE"];

                    $bill_master_insert["ACCOUNT_ID"]    =  $journal_transfer_id;
                    $bill_master_insert["BILL_NUMBER"]   =  $bill_number;
                    $bill_master_insert["BILL_MONTH"]    =  $bill_month;
                    $bill_master_insert["BILL_YEAR"]     =  $bill_year;
                    $bill_master_insert["TOTAL_AMOUNT"]  =  $bill_amount;
                    $bill_master_insert["BILL_TYPE"]     =  $bill_type;
                    $bill_master_insert["BILL_CATEGORY"] =  $journal_bill_category;
                    $bill_master_insert["DT_ADDED"]      =  $journal_date;

                    $bill_id = $this->m_bill_master->insertBillMaster($bill_master_insert);
                }
                else
                {
                    $accountDetail = $this->m_acc_account->getAccountDetailOnlyById($account_id);
                    $bill_type = $accountDetail["BILL_TYPE"];

                    $bill_master_insert["ACCOUNT_ID"]    =  $account_id;
                    $bill_master_insert["BILL_NUMBER"]   =  $bill_number;
                    $bill_master_insert["BILL_MONTH"]    =  $bill_month;
                    $bill_master_insert["BILL_YEAR"]     =  $bill_year;
                    $bill_master_insert["TOTAL_AMOUNT"]  =  $bill_amount;
                    $bill_master_insert["BILL_TYPE"]     =  $bill_type;
                    $bill_master_insert["BILL_CATEGORY"] =  $journal_bill_category;
                    $bill_master_insert["DT_ADDED"]      =  $journal_date;

                    $bill_id = $this->m_bill_master->insertBillMaster($bill_master_insert);
                }
            }

            // Insert journal item into bill_item
            $bill_item_insert["BILL_ID"]        = $bill_id;
            $bill_item_insert["B_JOURNAL_ID"]   = $b_journal_id;
            $bill_item_insert["ACCOUNT_ID"]     = $account_id;
            $bill_item_insert["AMOUNT"]         = $bill_amount;
            $bill_item_insert["BILL_CATEGORY"]  = 'J';
            $bill_item_insert["TR_CODE"]        = $journal_trcode;
            $bill_item_insert["ITEM_DESC"]      = $journal_trdesc;
            $bill_item_insert["TR_CODE_OLD"]    = $journal_trcode_old;
            $bill_item_insert["DT_ADDED"]       = $journal_date;

            if ( $journalType[0]["JOURNAL_CODE"] == "B01" )
            {
                // All transaction under B01 must be hidden from view except for penyata
                $bill_item_insert["DISPLAY_STATUS"] = "N";

                // Get ITEM_ID from B_ITEM that need to be hidden
                $data_search["BILL_ID"] = $bill_id;
                $data_search["TR_CODE"] = $journal_trcode;
                $billItem = $this->m_bill_item->get_bill_item_by_searchKey($data_search);
                
                // Please ensure that the result only return one row only. 
                // Anymore than 1 then this function will become obsolete and need to further check.
                $item_id = $billItem[0]["ITEM_ID"];
                $bill_item_update["DISPLAY_STATUS"] = "N";
                
                $this->m_bill_item->update_bill_item($item_id,$bill_item_update);
            }
            else if ( $journalType[0]["JOURNAL_CODE"] == "R05" )
            {
                // Get ITEM_ID from B_ITEM that need to be hidden
                $old_bill_id = $oldExistingBill["BILL_ID"];
                $data_search["BILL_ID"] = $old_bill_id;
                $data_search["TR_CODE"] = $journal_trcode;
                $billItem = $this->m_bill_item->get_bill_item_by_searchKey($data_search);

                // Please ensure that the result only return one row only. 
                // Anymore than 1 then this function will return error and need to further check.
                // All transaction under R05 must be hidden from all view [Obsolete : Request changes by Pn Normaslina during FAT 3 on 14/12/2020]
                // All transaction under R05 must be shown but add its own pair to cancel the amount by add new record with negative existing amount

                $item_id = $billItem[0]["ITEM_ID"];

                $bill_item_update                   =   $billItem[0];
                $bill_item_update["DT_ADDED"]       =   $bill_item_update["ORIGINAL_DT_ADDED"];
                $bill_item_update["AMOUNT"]         =   $billItem[0]["AMOUNT"] * -1;
                $bill_item_update["B_JOURNAL_ID"]   =   $b_journal_id;
                $bill_item_update["BILL_CATEGORY"]  =   'J';
                // $bill_item_update["DISPLAY_STATUS"] = "X";
                // $this->m_bill_item->update_bill_item($item_id,$bill_item_update);

                unset( $bill_item_update["ORIGINAL_DT_ADDED"] );  // Remove custom selected column but not exist in b_item
                unset( $bill_item_update["ITEM_ID"] );            // Remove because item_id will be auto generated
                $this->m_bill_item->insert_bill_item($bill_item_update);

                $bill_item_insert["DISPLAY_STATUS"] = "Y";
                $bill_item_insert["ACCOUNT_ID"]     = $journal_transfer_id;
                $bill_item_insert["DT_ADDED"]       = $billItem[0]["ORIGINAL_DT_ADDED"];             
            }

            $this->m_bill_item->insert_bill_item($bill_item_insert);
        }

        $journal_update["STATUS_APPROVAL"] = $status_approval;
        $journal_update["UPDATED_BY"] = $updated_by;
        $journal_update["UPDATED_AT"] = $updated_at;

        $updateJurnalStatus = $this->m_journal->update_journal($id,$journal_update);

        return $updateJurnalStatus;
    }

    function update() 
    {
        $journal_temp_id = uri_segment(3);
        $status_approval = $_POST['approve'];
        $updated_by = $this->curuser["USER_ID"];
        $updated_at = date('d-M-Y h:i:s');

        // echo "<script>console.log('".$updated_at."');</script>";

        // $update = $this->m_journal->approveOrDeclineJurnal($journal_temp_id,$_POST['approve'],$this->curuser["USER_ID"],$updated_at);
        $update = $this->approveOrDeclineJurnal($journal_temp_id,$status_approval,$updated_by,$updated_at);

        header("Content-Type: application/json");

        if ($update) 
        {            
            echo json_encode([
                'status' => true,
                'message' => 'Rekod jurnal berjaya '.($_POST['approve']==1 ? "diluluskan" : "dibatalkan").'.'
            ]);    
        }
        else 
        {
            echo json_encode([
                'status' => true,
                'message' => 'Rekod jurnal tidak berjaya '.($_POST['approve']==1 ? "diluluskan" : "dibatalkan").'.'
            ]);  
        }
    }

    function doc_journal()
    {
        $user_id = $this->curuser['USER_ID'];

        $user_details = $this->m_users->get_user_details($user_id);
        if(!$user_details):
            return false;
        endif;

        $data['user_details']   = $user_details;
        $data_search["user_id"] = $user_id;
        $data["journal_record"] = $this->m_journal->get_lists_temp_journal_print($data_search);

        $this->load->view('/journal/v_print_journal_entry',$data);
        // $id = $this->curuser;
        // // if(!is_numeric($id)):
        // //     return false;
        // // endif;

        // load_library('Generate_word');
        // $this->generate_word->word_document($id, DOC_JOURNAL);
    }

    function encrypt($string=NULL)
    {
        if (isset($_POST))
        {
            $string = $_POST["formData"];
        }
        else
        {
            if (empty($string) || $string == NULL)
            {
                $string = uri_segment(3);            
            }            
        }
        echo urlencode(base64_encode($string));
    }

    function checkDataValidity()
    {
        // $statusValid["acc_status"];
        // $statusValid["tkh_status"];
        // $statusValid["bulantahun_status"];

        $account_number     = $_POST["account_no"];
        $tarikh_resit       = $_POST["tarikh_resit"];
        $bulantahun_resit   = $_POST["bulantahun_resit"];

        $account_status = $this->m_acc_account->get_account_details_by_accountNo($account_number);
        if ($account_status != NULL )
        {
            $statusValid["acc_status"]  =  array(
                                                    "status" => true,
                                                    "value" => $account_status["ACCOUNT_NUMBER"],
                                                );
        }
        else
        {
            $statusValid["acc_status"]  =  array(
                                                    "status" => false,
                                                    "value" => "ERROR",
                                                );
        }

        if ($tarikh_resit == "" || $tarikh_resit == " " || $tarikh_resit == "Invalid date")
        {
            $tarikh_resit_status = false;
        }
        else
        {
            $tarikh_resit_day = explode( '/', $tarikh_resit )[0];
            $tarikh_resit_month = explode( '/', $tarikh_resit )[1];
            $tarikh_resit_year = explode( '/', $tarikh_resit )[2];
            $tarikh_resit_status = checkdate($tarikh_resit_month, $tarikh_resit_day, $tarikh_resit_year);            
        }

        if ($tarikh_resit_status == true )
        {
            $statusValid["tkh_status"]  =  array(
                                                    "status" => true,
                                                    "value" => array(
                                                                        "tarikh_resit" => $tarikh_resit,
                                                                        "tarikh_resit_day" => $tarikh_resit_day,
                                                                        "tarikh_resit_month" => $tarikh_resit_month,
                                                                        "tarikh_resit_year" => $tarikh_resit_year,
                                                                    ),
                                                );
        }
        else
        {
            $statusValid["tkh_status"]  =  array(
                                                    "status" => false,
                                                    "value" => array(
                                                                        "tarikh_resit" => $tarikh_resit,
                                                                        "tarikh_resit_day" => "",
                                                                        "tarikh_resit_month" => "",
                                                                        "tarikh_resit_year" => "",
                                                                    ),
                                                );
        }

        if ($bulantahun_resit != NULL || $bulantahun_resit != "")
        {
            $bulan_resit        = explode( '/', $bulantahun_resit )[0];
            $tahun_resit        = explode( '/', $bulantahun_resit )[1];            

            if ($bulan_resit > 0 && $bulan_resit < 13) 
            {
                # code...
                $bulan_status = true;
            } 
            else 
            {
                # code...
                $bulan_status = false;
            }

            if ($tahun_resit > date('Y')-1 && $tahun_resit < date('Y')+1) 
            {
                # code...
                $tahun_status = true;
            } 
            else 
            {
                # code...
                $tahun_status = false;
            }

            $statusValid["bulantahun_status"] = array(
                                                            "bulan_status" => array(
                                                                                        "status" => $bulan_status,
                                                                                        "value" => $bulan_resit,
                                                                                    ),
                                                            "tahun_status" => array(
                                                                                        "status" => $tahun_status,
                                                                                        "value" => $tahun_resit,
                                                                                    ),
                                                        );            
        }
        else
        {
            $statusValid["bulantahun_status"] = array(
                                                            "bulan_status" => array(
                                                                                        "status" => false,
                                                                                        "value" => $bulantahun_resit,
                                                                                    ),
                                                            "tahun_status" => array(
                                                                                        "status" => false,
                                                                                        "value" => $bulantahun_resit,
                                                                                    ),
                                                        ); 
        }

        echo json_encode($statusValid);
    }
}