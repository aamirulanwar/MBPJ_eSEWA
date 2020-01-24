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
        load_model('TrCode/M_tran_code', 'm_tran_code');
    }

    function _remap($method)
    {
        $array = array(
            'insert',
            'generate_current_journal'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->search();
    }

    function search(){
        $this->auth->restrict_access($this->curuser,array(7001));

        $data['link_1']     = 'Penyelarasan';
        $data['link_2']     = '';
        $data['link_3']     = '';
        $data['pagetitle']  = '';

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

        $data_bill_master   = $this->m_bill_master->get_bill_master($bill_id);
        $get_details        = $this->m_acc_account->get_account_details($data_bill_master['ACCOUNT_ID']);
        if(!$get_details):
            return false;
        endif;

        $data['account'] = $get_details;

        $bill_item = $this->m_bill_item->get_bill_item($bill_id);

        $data['account']        = $get_details;
        $data['statement_type'] = 'BIL';
        $data['bill_master']    = $data_bill_master;
        $data['bill_item']      = $bill_item;

        validation_rules('MCT_TRCODENEW[]','<strong>kod transaksi</strong>','required');

        if(validation_run()==false):
            templates('journal/v_generate_current_journal',$data);
        else:
            if(input_data('MCT_TRCODENEW[]')):
                $i = 0;
                $amount_arr     = input_data('amount[]');
                $add_minus_arr  = input_data('add_minus[]');
                $type_arr       = input_data('type[]');

                foreach (input_data('MCT_TRCODENEW[]') as $row):

                    $data_search['MCT_TRCODENEW'] = $row;
                    $tr_code = $this->m_tran_code->get_tr_code($data_search);

//                    pre($tr_code);
                    $insert_code['BILL_ID']             = $bill_id;
                    $insert_code['TR_CODE']             = $tr_code['MCT_TRCODENEW'];
//                    $insert_code['TR_ID']               = $tr_code['TR_ID'];
                    $insert_code['AMOUNT']              = (($add_minus_arr[$i]=='minus')?'-':'').currencyToDouble($amount_arr[$i]);
                    $insert_code['PRIORITY']            = $tr_code['MCT_PRIORT'];
//                    $insert_code['TR_TYPE']             = $tr_code['TR_TYPE'];
                    $insert_code['ACCOUNT_ID']          = $data_bill_master['ACCOUNT_ID'];
                    $insert_code['ITEM_DESC']           = $tr_code['MCT_TRDESC'];
                    $insert_code['TR_CODE_OLD']         = $tr_code['MCT_TRCODE'];
                    $insert_code['BILL_CATEGORY']       = "J";
                    $insert_code['COMPLETE_PAYMENT']    = 0;

                    $id_item = $this->m_bill_item->insert_bill_item($insert_code);
                    $i = $i+1;

                    if($id_item>0):
//                        $data_audit_trail['log_id']                  = 4002;
//                        $data_audit_trail['remark']                  = $insert_code;
//                        $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
//                        $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
//                        $data_audit_trail['refer_id']                = $id_item;
//                        $this->audit_trail_lib->add($data_audit_trail);
                    endif;
                endforeach;
            endif;
            set_notify('notify_msg',TEXT_SAVE_RECORD);
            redirect('/journal/generate_current_journal/'.uri_segment(3));
        endif;
    }
}