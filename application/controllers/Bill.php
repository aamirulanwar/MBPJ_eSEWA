<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bill extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_library('Audit_trail_lib');

        load_model('Asset/M_a_type', 'm_a_type');
        load_model('Asset/M_a_rental_use', 'm_a_rental_use');
        load_model('Asset/M_a_category', 'm_a_category');
        load_model('Application/M_p_applicant', 'm_p_applicant');
        load_model('Application/M_p_application', 'm_p_application');
        load_model('Account/M_acc_user', 'm_acc_user');
        load_model('Account/M_acc_account', 'm_acc_account');
        load_model('Asset/M_a_asset', 'm_a_asset');
        // load_model('Transaction/M_tr_code', 'm_tr_code');
        load_model('TrCode/M_tran_code', 'm_tr_code');
        load_model('Bill/M_bill_master', 'm_bill_master');
        load_model('Bill/M_bill_item', 'm_bill_item');
        load_model('Notice/M_notice_log', 'm_notice_log');
        load_model('Notice/M_lod_generation', 'm_lod_generation');
        load_model('Notice/M_notis_mah_generation', 'm_notis_mah_generation');
    }

    function _remap($method)
    {
        $array = array(
            'account_list',
            'current_bill',
            'generate_current_bill',
            'notice_list',
            'generate_notice',
            'add_transaction',
            'bill_history',
            'statement',
            'generate_barcode',
            'add_journal_transaction',
            'addLODcharge',
            'updateBillAmount'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->account_list();
    }

    function account_list()
    {
        $this->auth->restrict_access($this->curuser,array(6001));

        $data['link_1']     = 'Bil sewaan';
        $data['link_2']     = 'Senarai akaun';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai akaun';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_acc_bill_semasa');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_acc_bill_semasa',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['account_number']          = '';
                $data_search['ic_number_company_reg']   = '';
                $data_search['name']                    = '';
            endif;
        endif;

        $data['data_search'] = $data_search;

        $total = $this->m_acc_account->count_account($data_search);
        $links          = '/bill/account_list';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_acc_account->get_account($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('bill/v_list_account',$data);
    }

    function current_bill()
    {
        // $this->output->enable_profiler(TRUE);
        $this->auth->restrict_access($this->curuser,array(6003));

        $data['link_1']     = 'Bil sewaan';
        $data['link_2']     = '<a href="/bill/acount_list">Akaun</a>';
        $data['link_3']     = 'Akaun semasa';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));

        if(!is_numeric($id))
        {
            return false;
        }

        $get_details = $this->m_acc_account->get_account_details($id);
        if(!$get_details)
        {
            return false;
        }

        $data_bill_lib['account_id']  = $id;

        // load_library('Bill_lib');
        // Check current bill need to add LOD charge or not
        // $this->addLODcharge($id);
        // $bill_item          = $this->bill_lib->generate_bill($data_bill_lib);
        // $data['item_bil'] = $bill_item['item'];
        // $data['bill_item']      = $bill_item['item'];

        $this->load->library('BillGenerator');
        $bill_master_id = $this->m_bill_master->getBillId( $id, date('n'), date('Y'), 'B');
        $data_bill_master = $this->m_bill_master->get_bill_master($bill_master_id['BILL_ID']);

        $data['account']        = $get_details;
        $data['statement_type'] = 'BIL';
        $data['bill_master']    = $data_bill_master;
        $data['bill_item']      = $this->billgenerator->listCurrentBill($id);

        templates('bill/v_current_bill',$data);
    }

    function generate_current_bill()
    {
        $this->auth->restrict_access($this->curuser,array(6002));

        $data['link_1']     = 'Bil sewaan';
        $data['link_2']     = '<a href="/bill/acount_list">Akaun</a>';
        $data['link_3']     = 'Penjanaan Bil semasa';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));

        if(!is_numeric($id))
        {
            return false;
        }
        
        $get_details = $this->m_acc_account->get_account_details($id);

        if(!$get_details)
        {
            return false;
        }
        
        $data['account'] = $get_details;

        /*

        load_library('Bill_lib');
        $data_bill_lib['account_id']  = $id;

        // Check current bill need to add LOD charge or not
        $this->addLODcharge($id);

        $bill_item = $this->bill_lib->generate_bill($data_bill_lib);


        $data_bill_master = $this->m_bill_master->get_bill_master($bill_item['bill_id']);

        $data['item_bil']       = $bill_item['item'];
        $data['account']        = $get_details;
        $data['statement_type'] = 'BIL';
        $data['bill_master']    = $data_bill_master;
        $data['bill_item']      = $bill_item;
        
        */

        // Debug Line for new development function 
        // $this->load->controller('BillGenerator');
        $this->load->library('BillGenerator');

        $data['list_of_bill']   =  $this->billgenerator->listCurrentBill($id);
        $generate_bill          =  $this->billgenerator->generateCurrentBill( $id, date('n'), date('Y') );
        $bill_id_master         =  $generate_bill['BILL_ID'];

        $new_total_bill_amount              = $this->m_bill_item->getBillItemTotalAmount($bill_id_master);
        $data_update_master["TOTAL_AMOUNT"] = $new_total_bill_amount["TOTAL_AMOUNT"];

        $this->m_bill_master->updateBillMasterTotalAmount( $bill_id_master, $id, $data_update_master);

        if(input_data('MCT_TRCODENEW[]'))
        {
            validation_rules('MCT_TRCODENEW[]','<strong>kod transaksi</strong>','required');
        }
        
        if(validation_run()==false)
        {
            templates('bill/v_generate_current_bill',$data);
        }
        else
        {
            if(input_data('MCT_TRCODENEW[]'))
            {
                $i = 0;
                $amount_arr     = input_data('amount[]');
                $add_minus_arr  = input_data('add_minus[]');
                $type_arr       = input_data('type[]');
                $remark_arr     = input_data('remark[]');
                foreach (input_data('MCT_TRCODENEW[]') as $row)
                {
                    $search_tr_code['MCT_TRCODENEW'] = $row;
                    $tr_code = $this->m_tr_code->get_tr_code($search_tr_code);

                    $insert_code['ACCOUNT_ID']      =   $id;
                    $insert_code['BILL_ID']         =   $generate_bill['BILL_ID'];
                    $insert_code['TR_CODE']         =   $tr_code['MCT_TRCODENEW'];
                    $insert_code['TR_CODE_OLD']     =   $tr_code['MCT_TRCODE'];
                    $insert_code['ITEM_DESC']       =   $tr_code['MCT_TRDESC'];
                    $insert_code['AMOUNT']          =   currencyToDouble($amount_arr[$i]);
                    $insert_code['PRIORITY']        =   $tr_code['MCT_PRIORT'];
                    $insert_code['BILL_CATEGORY']   =   "B";
                    $insert_code['DISPLAY_STATUS']  =   "C";
                    $remark                         =   '';

                    if(!empty($remark_arr[$i]))
                    {
                        $remark = ' ('.$remark_arr[$i].')';
                    }                    
                    $insert_code['REMARK']          =   $remark;

                    #check gst type
                    $gst_status                     =  check_trans_gst($insert_code['TR_CODE'],$insert_code['ITEM_DESC']);
                    $insert_code['TR_GST_STATUS']   =  $gst_status['TR_GST_STATUS'];
                    $insert_code['GST_TYPE']        =  $gst_status['GST_TYPE'];

                    $id_item = $this->m_bill_item->insert_bill_item($insert_code);
                    $i = $i+1;

                    if($id_item > 0)
                    {
                        $data_audit_trail['log_id']                  = 4002;
                        $data_audit_trail['remark']                  = $insert_code;
                        $data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
                        $data_audit_trail['user_id']                 = $this->curuser['USER_ID'];
                        $data_audit_trail['refer_id']                = $id_item;
                        $this->audit_trail_lib->add($data_audit_trail);
                    }
                }

                $new_total_bill_amount = $this->m_bill_item->getBillItemTotalAmount($id);
                $data_update_master["TOTAL_AMOUNT"] = $new_total_bill_amount;
                $this->m_bill_master->updateBillMasterTotalAmount( $generate_bill['BILL_ID'], $id, $data_update_master);
            }

            set_notify('notify_msg',TEXT_SAVE_RECORD);
            redirect('/bill/generate_current_bill/'.uri_segment(3));
        }
    }

    function notice_list()
    {
        $data['link_1']     = 'Bil sewaan';
        $data['link_2']     = 'Senarai tunggakan';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai tunggakan';

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_notice');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_notice',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['account_number']  = '';
                $data_search['ic_number']   = '';
                $data_search['name']        = '';
            endif;
        endif;

        $search_segment = uri_segment(3);
        $total = $this->m_acc_account->count_account_outstanding($data_search);
        $links          = '/bill/notice_list';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_acc_account->get_account_outstanding($per_page,$search_segment,$data_search);

        $data['total_result']   = $total;

        $new_data_list = array();
        if($data_list):
            foreach ($data_list as $row):
                #get latest tunggakan
                $latest_notice = $this->m_notice_log->get_log_notice($row['ACCOUNT_ID']);
                $row['total_tunggakan'] = 0;
                if($latest_notice):
                    $row['total_tunggakan'] = $latest_notice['TOTAL_TUNGGAKAN'];
                endif;

                $new_data_list[] = $row;
            endforeach;
        endif;
        $data['data_search']    = $data_search;
        $data['data_list']      = $new_data_list;

        templates('bill/v_list_notice',$data);
    }

    function generate_notice()
    {
        $id            = urlDecrypt(uri_segment(3));
        $notice_level  = urlDecrypt(uri_segment(4));
        if(!is_numeric($id)):
            return false;
        endif;

        // echo $notice_level;

        // if($notice_level<=4):
        //     $get_all_notice = $this->m_notice_log->get_notice_level($id,$notice_level);
        // else:
        //     $get_all_notice = $this->m_notice_log->get_notice_level($id,99);
        // endif;

        $this->load->library('BillGenerator');
        $list_outstanding_charges    =  $this->billgenerator->getCurrentMonthOutstandingCharge($id);

        // ---- Outstanding Charge ----
        $total_outstanding_amount = 0;
        foreach ($list_outstanding_charges as $outstanding_charges) 
        {
            // $data[] = array( 
            //                     'TR_CODE_OLD'      =>  $outstanding_charges["TR_CODE_OLD_TUNGGAKAN"],
            //                     'TR_CODE_NEW'      =>  $outstanding_charges["TR_CODE_TUNGGAKAN"],
            //                     'TR_DESC'          =>  $outstanding_charges["TR_DESC_TUNGGAKAN"],
            //                     'AMOUNT'           =>  $outstanding_charges["BALANCE_AMOUNT"],
            //                     'DISPLAY_PRIORITY' =>  '3',
            //                 );
            $total_outstanding_amount = $total_outstanding_amount + $outstanding_charges["BALANCE_AMOUNT"];

            // echo "<pre>";
            // var_dump($outstanding_charges["BALANCE_AMOUNT"]);
        }

        // save record lod generation to db for reporting feature
        if ($notice_level == 4)
        {
            $data_search["ACCOUNT_ID"] = $id;
            $data_search["MONTH"] = date('n');
            $data_search["YEAR"] = date('Y');
            $checkCondition = $this->m_lod_generation->get_record_generation($data_search);

            // echo DateTime::createFromFormat('Y-m-d H:i:s', $checkCondition[0]->updated_date)->format('d-m-Y h:i:s A');
            if ( count($checkCondition) == 0 )
            {
                $data_insert_lod["ACCOUNT_ID"] = $id;
                $data_insert_lod["TOTAL_TUNGGAKAN"] = $total_outstanding_amount;
                $data_insert_lod["MONTH"] = date('n');
                $data_insert_lod["YEAR"] = date('Y');
                $this->m_lod_generation->insert_record_generation($data_insert_lod);
            }
            else
            {
                $data_update_condition_lod["ACCOUNT_ID"]    = $id;
                $data_update_condition_lod["MONTH"]         = date('n');
                $data_update_condition_lod["YEAR"]          = date('Y');
                $data_update_lod["TOTAL_TUNGGAKAN"]         = $total_outstanding_amount;
                $this->m_lod_generation->update_record_generation($data_update_condition_lod, $data_update_lod);
            }
        }

        // save record notis mahkamah generation to db for reporting feature
        if ($notice_level == 6)
        {
            $data_search["ACCOUNT_ID"] = $id;
            $data_search["MONTH"] = date('n');
            $data_search["YEAR"] = date('Y');
            $checkCondition = $this->m_notis_mah_generation->get_record_generation($data_search);

            // echo DateTime::createFromFormat('Y-m-d H:i:s', $checkCondition[0]->updated_date)->format('d-m-Y h:i:s A');
            if ( count($checkCondition) == 0 )
            {
                $data_insert_notis_mahkamah["ACCOUNT_ID"]       = $id;
                $data_insert_notis_mahkamah["MONTH"]            = date('n');
                $data_insert_notis_mahkamah["YEAR"]             = date('Y');
                $data_insert_notis_mahkamah["TOTAL_TUNGGAKAN"]  = $total_outstanding_amount;
                $this->m_notis_mah_generation->insert_record_generation($data_insert_notis_mahkamah);
            }
            else
            {
                $data_update_condition_notis_mahkamah["ACCOUNT_ID"]       = $id;
                $data_update_condition_notis_mahkamah["MONTH"]            = date('n');
                $data_update_condition_notis_mahkamah["YEAR"]             = date('Y');
                $data_update_notis_mahkamah["TOTAL_TUNGGAKAN"]            = $total_outstanding_amount;

                $this->m_lod_generation->update_record_generation($data_update_condition_notis_mahkamah, $data_update_notis_mahkamah);
            }
        }

        load_library('Generate_word');
        $this->generate_word->word_document($id, DOC_NOTICE, $notice_level,$get_all_notice);
    }

    function add_transaction()
    {
        if(is_ajax()):
            $type = input_data('type');
            // $data_search['TR_TYPE']         = 1;
            // $data_search['AUTO_GENERATE']   = 0;
            $data_search['MCT_TSTATS'] = 'B';
            $data_transaction = $this->m_tr_code->get_tr_code_list($data_search);
            
            $data_option = '';
            if($data_transaction):
                foreach ($data_transaction as $row):
                    if(empty($data_option)):
                        $data_option = option_value($row['MCT_TRCODENEW'],$row['MCT_TRCODENEW'].' - '.$row['MCT_TRDESC'],'MCT_TRCODENEW[]');
                    else:
                        $data_option = $data_option.option_value($row['MCT_TRCODENEW'],$row['MCT_TRCODENEW'].' - '.$row['MCT_TRDESC'],'MCT_TRCODENEW[]');
                    endif;
                endforeach;
            endif;

            $data_insert =
                '<div class="form-group row">'.
                    '<div class="col-sm-5">'.
                        '<select class="form-control js-example-basic-single" name="MCT_TRCODENEW[]">'.
                            $data_option.
                        '</select>'.
                    '</div>'.
                    '<div class="col-sm-2">'.
                        '<input name="amount[]" onkeyup="currency_format(this)" class="form-control" value="0.00">'.
                    '</div>'.
                    '<div class="col-sm-1">'.
                        '<input name="type[]" readonly="readonly" class="form-control" value="'.strtoupper($type).'">'.
                    '</div>'.
                    '<div class="col-sm-2">'.
                        '<input name="remark[]" type="text" placeholder="Remark" class="form-control" value="">'.
                    '</div>'.
                    '<div class="col-sm-2">'.
                        '<button type="button" onclick="remove_tr_code(this)" class="btn btn-danger">x</i></button>'.
                    '</div>'.
                '</div>';

            echo $data_insert;
        endif;
    }

    function add_journal_transaction()
    {
        if(is_ajax()):
            $type = input_data('type');
            $journal_code = input_data('journal_code');
            $journal_id = input_data('journal_id');
            $tr_code = input_data('tr_code');
            $bill_amount = input_data('bill_amount');

            $data_search['MCT_TSTATS'] = 'B';
            $data_transaction = $this->m_tr_code->get_tr_code_list_journal($data_search);
            
            $data_option = '';

            // echo "<script>console.log(".json_encode($data_transaction)."); </script>";

            $is_changeable_trcode = false;
            if($data_transaction):
                foreach ($data_transaction as $row):

                    if ($journal_code!='B03' || $journal_code !='R03') {
                        
                        if ($tr_code==$row['MCT_TRCODENEW']) {
                            
                            $data_option .= '<option value="'.$row['MCT_TRCODENEW'].'" selected>'.$row['MCT_TRCODENEW'].' - '.$row['MCT_TRDESC'].'</option>';
                            $is_changeable_trcode = false;
                        }
                        else {

                            $data_option .= '<option value="'.$row['MCT_TRCODENEW'].'">'.$row['MCT_TRCODENEW'].' - '.$row['MCT_TRDESC'].'</option>';
                        }
                    }
                    else {

                        $data_option .= '<option value="'.$row['MCT_TRCODENEW'].'">'.$row['MCT_TRCODENEW'].' - '.$row['MCT_TRDESC'].'</option>';
                        $is_changeable_trcode = true;
                    }
                endforeach;
            endif;            

            $unique_select_id = "select_trcode_".time();
            $select_script = "";
            $select_input_value = "";

            $list_allowed_editable = ["B02","B03","R02","R03"];

            if (in_array($journal_code, $list_allowed_editable)) {
                
                $select_script = '<script>$("#'.$unique_select_id.'").select2();</script>';
                $select_input_value = '<input type="hidden" name="mct_trcodenew[]" id="'.$unique_select_id.'_input" value="'.$tr_code.'">';
            }
            else {

                $select_script = '<script>$("#'.$unique_select_id.'").select2({disabled:true});</script>';
                $select_input_value = '<input type="hidden" name="mct_trcodenew[]" id="'.$unique_select_id.'_input" value="'.$tr_code.'">';
            }

            // set journal amount for selected journal code transaction
            if ($journal_code=="B01" || $journal_code=="R01")
            {
                $bill_amount = abs($bill_amount) * -1;
            }
            else if ($journal_code=="R05")
            {
                $bill_amount = abs($bill_amount) ;
            }
            else
            {
                // do nothing
            }

            $data_insert =
            '<div class="" id="'.$unique_select_id.'_row">'.
                '<div class="form-group row" >'.
                    '<div class="col-sm-4">'.
                        '<select class="form-control js-example-basic-singlex select2" id="'.$unique_select_id.'" onchange="setSelectedTrcode(this,\'#'.$unique_select_id.'_input\')" ><option value="-1" selected >-Sila Pilih - </option>'.
                            $data_option.
                        '</select>'.
                        '<p id="'.$unique_select_id.'_input_error" class="valError"></p>'.
                    '</div>'.
                    '<div class="col-sm-2" style="'.($journal_code == "R05" ? 'display:none' : '').'">'.
                        '<input name="amount[]" onkeyup="currency_format1(this)" class="form-control" value="'.($journal_code=="B01" || $journal_code=="R01" || $journal_code=="R05" ? $bill_amount : '').'" '.($journal_code=="B01" || $journal_code=="R01" || $journal_code=="R05" ? 'readonly' : '').'>'.
                    '</div>'.
                    '<div class="col-sm-2" style="'.($journal_code == "R05" ? '' : 'display:none').'">'.
                        '<select class="form-control" name="transfer_account_id" id="transfer_account_id" >'.
                        '   <option value="-1" selected> -Sila Pilih - </option>'.
                        '</select>'.
                    '</div>'.
                    '<div class="col-sm-1">'.
                        '<input name="type[]" readonly="readonly" class="form-control" value="'.strtoupper($type).'">'.
                    '</div>'.
                    '<div class="col-sm-2">'.
                        '<input name="journal_code[]" type="text" placeholder="Kod Jurnal" class="form-control" value="'.strtoupper($journal_code).'" readonly>'.
                    '</div>'.
                    '<div class="col-sm-2">'.
                        '<input name="remark[]" type="text" placeholder="Remark" class="form-control" value="">'.
                    '</div>'.
                    '<div class="col-sm-1">'.
                        '<button type="button" onclick="remove_tr_code('."'".$unique_select_id."_row'".')" class="btn btn-danger">x</i></button>'.
                    '</div>'.
                '</div>
                <input type="hidden" name="journal_id[]" value="'.$journal_id.'">
                '.$select_script.$select_input_value
            .'</div>'.
            '<script type="text/javascript">
                $(document).ready(function()
                {
                    $("#transfer_account_id").select2({
                        ajax: {
                            url: "/account/retrieveListAccountForJournal",
                            type: "post",
                            dataType: "json",
                            delay: 250,
                            data: function (params) {
                               return {
                                  searchTerm: params.term // search term
                               };
                            },
                            processResults: function (response) {
                               return {
                                  results: response
                               };
                            },
                            cache: true
                        }
                    });
                });
            </script>';

            echo $data_insert;
        endif;
    }

    function bill_history()
    {
       // $this->auth->restrict_access($this->curuser,array(6004));

       // $data['link_1']     = 'Bil sewaan';
       // $data['link_2']     = 'Senarai akaun';
       // $data['link_3']     = 'Resit terdahulu';
       // $data['pagetitle']  = 'Senarai resit terdahulu';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $data_account = $this->m_acc_account->get_account_details($id);
        if(empty($data_account)):
            return false;
        endif;

       // $data['data_account'] = $data_account;

       // $per_page       = 20;
       // $search_segment = uri_segment(4);
       // $data_search['bill_category']   = 'R';
       // $data_search['account_id']      = $id;
       // $data_list = $this->m_bill_master->get_bill_history_list($per_page,$search_segment,$data_search);
       // if(!$data_list):
       //     return false;
       // endif;

       // $total = $this->m_bill_master->count_bill_history($data_search);
       // $links          = '/bill/bill_history/'.uri_segment(3);
       // $uri_segment    = 4;
       // $per_page       = 20;
       // paging_config($links,$total,$per_page,$uri_segment);

       // $data['data_list']      = $data_list;
       // $data['total_result']   = $total;

       // templates('bill/v_list_bill_history',$data);
        $data_search['date_start']  = '';
        $data_search['date_end']    = '';
        $data_search['type_id']     = '';
        $data_search['category_id'] = '';
        $data_search['account_id']  = $id;
        $data_search['order_by']    = '';
        $this->session->set_userdata('arr_filter_record_transaction', $data_search);

        redirect('/report/record_transaction/post/'.uri_segment(4).'/'.uri_segment(5));

    }

    function statement()
    {
        $data['link_1']     = 'Bil sewaan';
        $data['link_2']     = 'Senarai akaun';
        $data['link_3']     = 'Resit terdahulu';
        $data['pagetitle']  = 'Senarai resit terdahulu';

        $bill_id = urlDecrypt(uri_segment(3));
        if(!is_numeric($bill_id)):
            return false;
        endif;

        $data_bill_master = $this->m_bill_master->get_bill_master($bill_id);
        // pre($data_bill_master);

        $get_details = $this->m_acc_account->get_account_details($data_bill_master['ACCOUNT_ID']);
        if(!$get_details):
            return false;
        endif;

        $new_bill_item  = array();
        $bill_item      = $this->m_bill_item->get_bill_item($bill_id);
        // pre($bill_item);
        if($bill_item):
            foreach ($bill_item as $row):
                $row['amount']      = $row['AMOUNT'];
                $row['item_desc']   = $row['ITEM_DESC'];
            endforeach;
            $new_bill_item[] = $row;
        endif;

        $data['account']        = $get_details;
        $data['statement_type'] = 'RESIT';
        $data['bill_master']    = $data_bill_master;
        $data['bill_item']      = $new_bill_item;
        // pre($data['bill_item']);

        templates('bill/v_current_bill',$data);
    }

    function generate_barcode()
    {
        // For demonstration purposes, get pararameters that are passed in through $_GET or set to the default value
        $filepath = (isset($_GET["filepath"])?$_GET["filepath"]:"");
        $text = (isset($_GET["text"])?$_GET["text"]:"0");
        $size = (isset($_GET["size"])?$_GET["size"]:"20");
        $orientation = (isset($_GET["orientation"])?$_GET["orientation"]:"horizontal");
        $code_type = (isset($_GET["codetype"])?$_GET["codetype"]:"code128");
        $print = (isset($_GET["print"])&&$_GET["print"]=='true'?true:false);
        $sizefactor = (isset($_GET["sizefactor"])?$_GET["sizefactor"]:"1");

        // This function call can be copied into your project and can be made from anywhere in your code
        barcode( $filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );
    }

    function addLODcharge($account_id = -999)
    {
        // This is for bypass if account_id is not supplied and this function is call using url
        if ($account_id == -999)
        {
            $account_id = uri_segment(3);
        }

        $curAccountNotice = $this->m_acc_account->getAccountNoticeLevel($account_id);
        $curNoticeLevel = $curAccountNotice["NOTICE_LEVEL"];

        // echo json_encode($curNoticeLevel);
        // echo "Current notice level is ".$curNoticeLevel." for account number ".$account_id;

        $month = date('n');     // Return date month with no leading zero
        $year = date('Y');

        // echo "</br>".$month." / ".$year."</br>";

        if ($curNoticeLevel == 4)
        {
            // check if LOD or Notis Mahkamah already been charged on the current bill.
            // if yes the ignore else add charges
            $getNoticeChargeList = $this->m_bill_item->getNoticeChargeTransaction($account_id,$month,$year);            

            if ( count($getNoticeChargeList) == 0 )
            {
                $getCurrentMonthBill = $this->m_bill_master->getBillId($account_id,$month,$year);
                $masterBillId = $getCurrentMonthBill["BILL_ID"];

                if (isset($masterBillId) && $masterBillId > 0)
                {
                    // echo "</br> Current Master Bill ID => ".$masterBillId."</br>";

                    $insert_code['BILL_ID']             =   $masterBillId;                
                    $insert_code['AMOUNT']              =   currencyToDouble("10");                
                    $insert_code['ACCOUNT_ID']          =   $account_id;
                    $insert_code['BILL_CATEGORY']       =   "B";                
                    $insert_code['TR_CODE']             =   "11110020";
                    $insert_code['TR_CODE_OLD']         =   "11033";
                    $insert_code['PRIORITY']            =   36; // based on temp_mctrancode
                    $insert_code['ITEM_DESC']           =   "LOD SEWAAN";
                    $insert_code['REMARK']              =   "CAJ NOTIS LOD";                               

                    $id_item = $this->m_bill_item->insert_bill_item($insert_code);

                    if (!empty($id_item) && $id_item > 0)
                    {
                        // If insert operation successful, update total_amount in b_master for that bill_id
                        $getTotalAmountByBillId = $this->m_bill_item->getBillItemTotalAmount($masterBillId);
                        $data_update["TOTAL_AMOUNT"] = $getTotalAmountByBillId["TOTAL_AMOUNT"];
                        $this->m_bill_master->updateBillMasterTotalAmount($masterBillId,$account_id,$data_update);

                        // echo "</br>CAJ NOTIS BERJAYA DIMASUKKAN</br>";
                        return true;
                    }
                    else
                    {
                        // echo "</br>CAJ NOTIS TIDAK BERJAYA DIMASUKKAN</br>";
                        // echo "</br>MAKLUMAT BILL ITEM ".json_encode($id_item)."</br>";
                        return false;
                    }
                }
            }
            else
            {
                // echo json_encode($getNoticeChargeList);
                return false;
            }
        }
        else
        {
            // If current bill should not have LOD charge, delete it from the db.
            // This case should happen only when admin change the notice level at function "Kemaskini" at sub-modul "Senarai Akaun"
            // Please advise admin to not use the function "Kemaskini" at sub-modul "Senarai Akaun" willfully or in malay "sesuka hati or sesedap rasa"
            $getNoticeChargeList = $this->m_bill_item->getNoticeChargeTransaction($account_id,$month,$year);
            foreach ($getNoticeChargeList as $row) 
            {
                # code...
                $item_id = $row["ITEM_ID"];
                $this->m_bill_item->deleteLODCharge($item_id);
            }

            // If delete operation successful, update total_amount in b_master for that bill_id
            $getCurrentMonthBill = $this->m_bill_master->getBillId($account_id,$month,$year);
            $masterBillId = $getCurrentMonthBill["BILL_ID"];

            $getTotalAmountByBillId = $this->m_bill_item->getBillItemTotalAmount($masterBillId);
            $data_update["TOTAL_AMOUNT"] = $getTotalAmountByBillId["TOTAL_AMOUNT"];
            $this->m_bill_master->updateBillMasterTotalAmount($masterBillId,$account_id,$data_update);

            return false;
        }
    }

    // Do not enable this function unless for custom usage
    function updateBillAmount()
    {
        $account_id     = 6930;

        $data_search["ACCOUNT_ID"] = 6949;
        $data_search["CUSTOM_COLUMN"] = "BILL_ID";

        $list_bill_master = $this->m_bill_master->get($data_search);

        foreach ($list_bill_master as $bill) 
        {
            # code...
            $bill_items = $this->m_bill_item->getBillItemTotalAmount( $bill["BILL_ID"] );
            $bill_master_update["TOTAL_AMOUNT"] = $bill_items["TOTAL_AMOUNT"];
            $this->m_bill_master->updateBillMasterTotalAmount( $bill["BILL_ID"], $account_id, $bill_master_update );
        }

        if ( isset($list_bill_master) )
        {
            echo "DONE";
        }
        else
        {
            echo "NO RECORD TO BE UPDATED";
        }


        // $bill_month     = 4;
        // $bill_year      = 2020;
        // $bill_category  = "R";
        // $existingBill   = $this->m_bill_master->getBillId($account_id,$bill_month,$bill_year,$bill_category);
        // $bill_id        = $existingBill["BILL_ID"];
        // $billItemDetail = $this->m_bill_item->getBillItemTotalAmount($bill_id );
        // $bill_master_update["TOTAL_AMOUNT"] = $billItemDetail["TOTAL_AMOUNT"];

        // echo "<pre>";
        // var_dump($bill_master_update);
        // die();

        // $this->m_bill_master->updateBillMasterTotalAmount($bill_id,$account_id,$bill_master_update);
    }
}