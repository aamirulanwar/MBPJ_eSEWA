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
            'encrypt'
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
                                            "DT_ADDED"      => $tarikh_resit, 
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

            $bill_number = $this->m_journal->generate_running_billnumber();

            foreach ($_POST['journal_code'] as $i => $v) 
            {
                $this->m_journal->insert_journal_temp([
                    'created_by' => $this->curuser["USER_ID"],
                    'bill_number' => $bill_number,
                    'bill_month' => date('m'),
                    'bill_year' => date('Y'),
                    'journal_id' => $journal_id[$i],
                    'account_id' => input_data('account_id'),
                    'amount' => $amount[$i],
                    'bill_category' => input_data('b_master_bill_category'),
                    'tr_code' => $mct_trcodenew[$i],
                    'status_approval' => 0,
                    'remark' => $remark[$i]
                ]);
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
            $journal_id = $journalDetail["JOURNAL_ID"];
            $journal_trcode = $journalDetail["TR_CODE"];
            $journal_trdesc = $journalDetail["ITEM_DESC"];
            $journal_trcode_old = $journalDetail["TR_CODE_OLD"];
            $journal_bill_category = $journalDetail["BILL_CATEGORY"];

            $existingBill = $this->m_bill_master->getBillId($account_id,$bill_month,$bill_year,$journal_bill_category);

            // Get Total Existing bill number
            if ( !empty($existingBill) && $existingBill["BILL_ID"] != "" )
            {
                $bill_id = $existingBill["BILL_ID"];
                $bill_master_update["TOTAL_AMOUNT"] = $existingBill["TOTAL_AMOUNT"] + $bill_amount;

                $this->m_bill_master->updateBillMasterTotalAmount($bill_id,$account_id,$bill_master_update);
            }
            else
            {
                $accountDetail = $this->m_acc_account->getAccountDetailOnlyById($account_id);
                $bill_type = $accountDetail["BILL_TYPE"];

                $bill_master_insert["ACCOUNT_ID"] = $account_id;
                $bill_master_insert["BILL_NUMBER"] = $bill_number;
                $bill_master_insert["BILL_MONTH"] = $bill_month;
                $bill_master_insert["BILL_YEAR"] = $bill_year;
                $bill_master_insert["TOTAL_AMOUNT"] = $bill_amount;
                $bill_master_insert["BILL_TYPE"] = $bill_type;
                $bill_master_insert["BILL_CATEGORY"] = $journal_bill_category;

                $bill_id = $this->m_bill_master->insertBillMaster($bill_master_insert);
            }

            // Insert journal item into bill_item
            $bill_item_insert["BILL_ID"]        = $bill_id;
            $bill_item_insert["JOURNAL_ID"]     = $journal_id;
            $bill_item_insert["ACCOUNT_ID"]     = $account_id;
            $bill_item_insert["AMOUNT"]         = $bill_amount;
            $bill_item_insert["BILL_CATEGORY"]  = 'J';
            $bill_item_insert["TR_CODE"]        = $journal_trcode;
            $bill_item_insert["ITEM_DESC"]      = $journal_trdesc;
            $bill_item_insert["TR_CODE_OLD"]      = $journal_trcode_old;

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
        $id = $this->curuser;
        // if(!is_numeric($id)):
        //     return false;
        // endif;

        load_library('Generate_word');
        $this->generate_word->word_document($id, DOC_JOURNAL);
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
    
}