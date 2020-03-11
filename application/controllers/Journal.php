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
            'doc_journal'
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

    function generate_current_journal(){
        $data['link_1']     = 'Journal';
        $data['link_2']     = '<a href="/journal/search">Carian</a>';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

        $bill_id = urlDecrypt(uri_segment(3));
        if(!is_numeric($bill_id)):
            return false;
        endif;

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

            foreach ($_POST['journal_code'] as $i => $v) {

                $this->m_journal->insert_journal_temp([
                    'created_by' => $this->curuser["USER_ID"],
                    'bill_number' => $bill_number,
                    'bill_month' => date('m'),
                    'bill_year' => date('Y'),
                    'journal_id' => $journal_id[$i],
                    'account_id' => input_data('account_id'),
                    'amount' => $amount[$i],
                    'bill_category' => 'J',
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

    function update() {

        $journal_id = uri_segment(3);

        $updated_at = date('d-M-Y h:i:s');
        // echo "<script>console.log('".$updated_at."');</script>";

        $update = $this->m_journal->approveOrDeclineJurnal($journal_id,$_POST['approve'],$this->curuser["USER_ID"],$updated_at);

        header("Content-Type: application/json");

        if ($update) {
            
            echo json_encode([
                'status' => true,
                'message' => 'Rekod jurnal berjaya '.($_POST['approve']==1 ? "diluluskan" : "dibatalkan").'.'
            ]);    
        }
        else {

            echo json_encode([
                'status' => true,
                'message' => 'Rekod jurnal tidak berjaya '.($_POST['approve']==1 ? "diluluskan" : "dibatalkan").'.'
            ]);  
        }
    }

    function doc_journal(){
        
        load_library('Generate_word');
        $this->generate_word->word_document($id, DOC_JOURNAL);
    }

}