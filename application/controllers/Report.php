<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */

class Report extends CI_Controller
{
    public $curuser;

    function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        // load_model('transaction/M_tr_code','m_tr_code');
        load_model('bill/M_bill_item','m_bill_item');
        load_model('bill/M_bill_master','m_bill_master');
        load_model('asset/M_a_category','m_category');
        load_model('asset/M_a_type','m_type');
        load_model('account/M_acc_account','m_acc_account');
        load_model('account/M_acc_user','m_acc_user');
        load_model('trcode/M_tran_code','m_tran_code');
        load_model('application/M_p_application','m_p_application');
        load_model('audit_trail/M_audit_trail','m_audit_trail');
        load_model('asset/M_a_asset','m_asset');
        load_model('journal/M_journal','m_journal');
    }

    function _remap($method){
        $array = array(
            'account_summary',
            'rent_summary',
            'category_aging',
            'account_outstanding',
            'gl_summary',
            'category_adjustment',
            'gst_rental',
            'gst_rental_simple',
            'code_gl',
            'highest_overdue',
            'record_transaction',
            'report_dashboard',
            'category_aging_details',
            'adjustment_statement',
            'adjustment_statement_ringkasan',
            'payment',
            'journal',
            'dataTable4RekodTransaksi',
            'transactionReportHeader',
            'hartanah'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->gl_summary();
    }

    function index(){
        $data = array();
        templates('/report/gl_summary',$data);
    }

    function gl_summary(){
        // $this->auth->restrict_access($this->curuser,array(2001));

        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Hasil Kod GL';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Hasil Kod GL';

        $dt_start = date('d-m-Y',strtotime('01-01-2019'));
        $dt_end   = date('d-m-Y',strtotime('30-12-2019'));

        $data_gl_array = array();
        $gl_code = $this->m_tr_code->get_gl_code();
        foreach($gl_code as $gl):
            $bill_amount    = $this->m_bill_item->get_bill_amount_by_tr_id($gl['TR_ID'],'','',$dt_start,$dt_end);
            $receipt_amount = $this->m_bill_item->get_bill_amount_by_tr_id($gl['RECEIPT_TR_ID'],'','',$dt_start,$dt_end);

            $data_gl['object_code']     = $gl['ACC_MCT_GLCRDT'];
            $data_gl['desc']            = $gl['MCT_TRDESC'];
            $data_gl['bill_count']      = $this->m_bill_item->count_bill_item($gl['TR_ID'],'B');
            $data_gl['bill_amount']     = $bill_amount['AMOUNT'];
            $data_gl['receipt_amount']  = $receipt_amount['AMOUNT'];
            $data_gl_array[] = $data_gl;
        endforeach;

        $data['data_gl'] = $data_gl_array;
        templates('/report/v_gl_summary_search',$data);
    }

    function category_adjustment(){
        // $this->auth->restrict_access($this->curuser,array(2001));

        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Hasil Kod GL';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Ringkasan Penyata Penyesuaian';

        $dt_start = date('d-m-Y',strtotime('01-01-2019'));
        $dt_end   = date('d-m-Y',strtotime('30-12-2019'));
        $data_category_array = array();
        $a_type = $this->m_type->get_a_type();
        foreach($a_type as $type):
            $a_category = $this->m_category->get_data_category_by_type($type['TYPE_ID']);
            $new_a_category = array();
            foreach($a_category as $category):
                $jurnal_amount      = $this->m_bill_item->get_bill_amount_by_tr_code($category['TRCODE_CATEGORY'],'J',$category['CATEGORY_ID'],$dt_start,$dt_end);
                $current_pay_amount = $this->m_bill_item->get_bill_amount_by_tr_code(code_payment_from_code_bill($category['TRCODE_CATEGORY']),'R',$category['CATEGORY_ID'],$dt_start,$dt_end);

                $data_category['type_id']       = $category['TYPE_ID'];
                $data_category['category_name'] = $category['CATEGORY_NAME'];
                $data_category['total_unit']    = $category['TOTAL_UNIT'];
                $data_category['adjustment']    = $jurnal_amount['AMOUNT'];
                $data_category['current_pay']   = $current_pay_amount['AMOUNT'];
                $data_category_array[] = $data_category;
            endforeach;
        endforeach;

        $data['a_type'] = $a_type;
        $data['data_category'] = $data_category_array;
        templates('/report/v_category_adjustment_search',$data);
    }

    function category_aging___(){
        // $this->auth->restrict_access($this->curuser,array(2001));

        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Aging Sewaan';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Aging Sewaan (Ringkasan)';

        $dt_start = date('d-m-Y',strtotime('01-01-2019'));
        $dt_end   = date('d-m-Y',strtotime('30-12-2019'));
        $data_category_array = array();
        $a_type = $this->m_type->get_a_type();
        foreach($a_type as $type):
            $a_category = $this->m_category->get_data_category_by_type($type['TYPE_ID']);
            foreach($a_category as $category):
                $data_category['type_id']       = $category['TYPE_ID'];
                $data_category['category_name'] = $category['CATEGORY_NAME'];
                $data_category['total_unit']    = $category['TOTAL_UNIT'];
                $data_category_array[] = $data_category;
            endforeach;
        endforeach;

        $data['a_type'] = $a_type;
        $data['data_category'] = $data_category_array;
        templates('/report/v_category_aging_search',$data);
    }

    function category_aging_details(){
        $this->auth->restrict_access($this->curuser,array(8001));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Aging Sewaan (Terperinci)';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Aging Sewaan (Terperinci)';

        $dt_start = date('d-m-Y',strtotime('01-01-2019'));
        $dt_end   = date('d-m-Y',strtotime('30-12-2019'));

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_category_aging_details');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_category_aging_details',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['asset_code']  = '';
                $data_search['acc_status']  = '';
                $data_search['date_start']  = '';
            endif;
        endif;

        $data_category = $this->m_category->get_a_category('','');

        $data_report    = array();
        $date_start = input_data('date_start');

        if(empty($date_start)):
            $date_calculate = date('Y-m-d');
        else:
            $date_calculate = date('Y-m-d',strtotime($date_start));
        endif;
        $new_data_asset = array();
        $result_report  = array();
        if($_POST):
            if(input_data('asset_code')=='semua'):
                $data_category_loop = $data_category;
            elseif(input_data('asset_code')==''):
                $data_category_loop = array();
            else:
                $data_category_loop[] =
                array(
                    'CATEGORY_ID'=>input_data('asset_code')
                );
            endif;

            $x=0;
            if($data_category_loop):
                $i = 0;
                foreach ($data_category_loop as $row_cat):
                    $data_category_report = $this->m_category->get_a_category_details($row_cat['CATEGORY_ID']);
                    $data_asset = $this->m_acc_account->get_account_aging_report($row_cat['CATEGORY_ID'],input_data('acc_status'));
                    if($data_asset):
                        foreach ($data_asset as $row):
                            $data_bill = $this->m_bill_item->get_item_amount_not_equal($row['ACCOUNT_ID'],$date_calculate);
                            if($data_bill):
                                $data_1 = 0;
                                $data_2 = 0;
                                $data_3 = 0;
                                $data_4 = 0;
                                $data_5 = 0;
                                $data_6 = 0;
                                $data_7 = 0;
                                $data_8 = 0;
                                $data_9 = 0;
                                $data_10 = 0;
                                foreach ($data_bill as $row_bill):
                                    $x = $x+1;

                                    #1-3
                                    if($row_bill['DT_ADDED'] <=$date_calculate && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-3 month',strtotime($date_calculate)))):
                                        $data_1 = $data_1+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-3 month',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-6 month',strtotime($date_calculate)))):
                                        $data_2 = $data_2+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-6 month',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-9 month',strtotime($date_calculate)))):
                                        $data_3 = $data_3+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-9 month',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-12 month',strtotime($date_calculate)))):
                                        $data_4 = $data_4+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-12 month',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-2 year',strtotime($date_calculate)))):
                                        $data_5 = $data_5+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-2 year',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-3 year',strtotime($date_calculate)))):
                                        $data_6 = $data_6+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-3 year',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-4 year',strtotime($date_calculate)))):
                                        $data_7 = $data_7+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-4 year',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-5 year',strtotime($date_calculate)))):
                                        $data_8 = $data_8+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-5 year',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-6 year',strtotime($date_calculate)))):
                                        $data_9 = $data_9+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-6 year',strtotime($date_calculate)))):
                                        $data_10 = $data_10+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    endif;
                                endforeach;
                                $row['data_1'] = $data_1;
                                $row['data_2'] = $data_2;
                                $row['data_3'] = $data_3;
                                $row['data_4'] = $data_4;
                                $row['data_5'] = $data_5;
                                $row['data_6'] = $data_6;
                                $row['data_7'] = $data_7;
                                $row['data_8'] = $data_8;
                                $row['data_9'] = $data_9;
                                $row['data_10'] = $data_10;

                                $new_data_asset[] = $row;
                            endif;
                        endforeach;
                        if($new_data_asset):
                            $row_cat['data_category']   = $data_category_report;
                            $row_cat['data_report']     = $new_data_asset;
                            $result_report[]            = $row_cat;
                        endif;
                    endif;
                endforeach;
            endif;
        endif;

        $data['data_category'] = $data_category;
        $data['data_search']   = $data_search;
        $data['data_report']   = $result_report;

        templates('/report/v_category_aging_details_search',$data);
    }

    function category_aging(){
        $this->auth->restrict_access($this->curuser,array(8002));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Aging Sewaan (Ringkasan)';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Aging Sewaan (Ringkasan)';

        $dt_start = date('d-m-Y',strtotime('01-01-2019'));
        $dt_end   = date('d-m-Y',strtotime('30-12-2019'));

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_category_aging');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_category_aging',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['asset_type']  = '';
                $data_search['acc_status']  = '';
                $data_search['date_start']  = '';
            endif;
        endif;

        $a_type         = $this->m_type->get_a_type();

        $data_report    = array();
        $date_start = input_data('date_start');
        if(empty($date_start)):
            $date_calculate = date('Y-m-d');
        else:
            $date_calculate = date('Y-m-d',strtotime($date_start));
        endif;
        $new_data_asset = array();
        $result_report  = array();
        if($_POST):
            if(input_data('asset_type')=='semua'):
                $data_type_loop = $a_type;
            elseif(input_data('asset_type')==''):
                $data_type_loop = array();
            else:
                $data_type_loop[] =
                    array(
                        'TYPE_ID'=>input_data('asset_type')
                    );
            endif;

            if($data_type_loop):
                $i = 0;
                foreach ($data_type_loop as $row_cat):
                    $data_type_report   = $this->m_type->get_a_type_details($row_cat['TYPE_ID']);
                    $data_category      = $this->m_category->get_data_category_by_type($row_cat['TYPE_ID']);
                    if($data_category):
                        foreach ($data_category as $row):
                            $data_bill = $this->m_bill_item->get_item_amount_not_equal_by_category($row['CATEGORY_ID'],$date_calculate);
                            if($data_bill):
                                $data_1 = 0;
                                $data_2 = 0;
                                $data_3 = 0;
                                $data_4 = 0;
                                $data_5 = 0;
                                $data_6 = 0;
                                $data_7 = 0;
                                $data_8 = 0;
                                $data_9 = 0;
                                $data_10 = 0;
                                foreach ($data_bill as $row_bill):
                                    #1-3
                                    if($row_bill['DT_ADDED'] <=$date_calculate && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-3 month',strtotime($date_calculate)))):
                                        $data_1 = $data_1+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-3 month',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-6 month',strtotime($date_calculate)))):
                                        $data_2 = $data_2+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-6 month',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-9 month',strtotime($date_calculate)))):
                                        $data_3 = $data_3+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-9 month',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-12 month',strtotime($date_calculate)))):
                                        $data_4 = $data_4+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-12 month',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-2 year',strtotime($date_calculate)))):
                                        $data_5 = $data_5+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-2 year',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-3 year',strtotime($date_calculate)))):
                                        $data_6 = $data_6+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-3 year',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-4 year',strtotime($date_calculate)))):
                                        $data_7 = $data_7+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-4 year',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-5 year',strtotime($date_calculate)))):
                                        $data_8 = $data_8+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-5 year',strtotime($date_calculate))) && $row_bill['DT_ADDED'] > date('Y-m-d',strtotime('-6 year',strtotime($date_calculate)))):
                                        $data_9 = $data_9+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    elseif($row_bill['DT_ADDED'] <= date('Y-m-d',strtotime('-6 year',strtotime($date_calculate)))):
                                        $data_10 = $data_10+($row_bill['AMOUNT']-$row_bill['TOTAL_PAID']);
                                    endif;
                                endforeach;
                                $row['data_1'] = $data_1;
                                $row['data_2'] = $data_2;
                                $row['data_3'] = $data_3;
                                $row['data_4'] = $data_4;
                                $row['data_5'] = $data_5;
                                $row['data_6'] = $data_6;
                                $row['data_7'] = $data_7;
                                $row['data_8'] = $data_8;
                                $row['data_9'] = $data_9;
                                $row['data_10'] = $data_10;

                                $new_data_asset[] = $row;
                            endif;
                        endforeach;
                        if($new_data_asset):
                            $row_cat['data_type']       = $data_type_report;
                            $row_cat['data_report']     = $new_data_asset;
                            $result_report[]            = $row_cat;
                        endif;
                    endif;
                endforeach;
            endif;
        endif;

        $data['data_search']    = $data_search;
        $data['data_report']    = $result_report;
        $data['data_type']      = $a_type;

        templates('/report/v_category_aging',$data);
    }

    function account_outstanding(){
        // $this->auth->restrict_access($this->curuser,array(2001));

        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Tunggakan Tertinggi';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Tunggakan Sewaan Tertinggi';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_acc');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_acc',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['account_number']  = '';
                $data_search['type_id']         = '';
            endif;
        endif;

        $total = $this->m_acc_account->count_account();
        $links          = '/account/account_list';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_bill_master->get_highest_oustanding_bill($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('/report/v_account_outstanding_search',$data);
    }

    function gst_rental(){
        $this->auth->restrict_access($this->curuser,array(8003));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Bil gst';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Bil GST';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_rental_gst');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_rental_gst',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['date_start']  = date_display(timenow(),'d M Y');
                $data_search['date_end']    = '';
                $data_search['type_id']     = '';
                $data_search['category_id'] = '';
                $data_search['acc_status']  = '';
            endif;
        endif;
        $data['data_type']   = $this->m_type->get_a_type();

        if($_POST):
            $get_data_acc = $this->m_acc_account->acc_with_bill_gst($data_search);
            $data_report = array();
            if($get_data_acc):
                foreach ($get_data_acc as $key=>$row):
                    $data_search['account_id']  = $row['ACCOUNT_ID'];
                    $data_acc   = $this->m_acc_account->get_account_details($row['ACCOUNT_ID']);
                    $data_item      = $this->m_bill_item->report_rental_gst($data_search);
                    $data_item_prv  = $this->m_bill_item->report_rental_gst_prv($data_search);

                    $row['data_acc']    = $data_acc;
                    if($data_item):
                        foreach ($data_item as $item):
                            $data_jurnal        = array();
                            $data_gst_actual    = array();
                            $item['gst_actual'] = array();
                            if($item['BILL_CATEGORY']=='B'):
                                $data_search_item_jurnal['bill_month']      = $item['BILL_MONTH'];
                                $data_search_item_jurnal['bill_year']       = $item['BILL_YEAR'];
                                $data_search_item_jurnal['tr_code']         = $item['TR_CODE'];
                                $data_search_item_jurnal['account_id']      = $item['ACCOUNT_ID'];
                                // $data_search_item_jurnal['not_equal_last_year']  = 1;
                                // pre($data_search_item_jurnal);
                                // $data_jurnal = $this->m_bill_item->get_item_jurnal($data_search_item_jurnal);
                            elseif ($item['BILL_CATEGORY']=='R'):
                                $data_search_item_jurnal['bill_month']      = $item['BILL_MONTH'];
                                $data_search_item_jurnal['bill_year']       = $item['BILL_YEAR'];
                                $data_search_item_jurnal['tr_code']         = $item['TR_CODE'];
                                $data_search_item_jurnal['account_id']      = $item['ACCOUNT_ID'];
                                // $data_search_item_jurnal['not_equal_last_year']  = 1;
                                // pre($data_search_item_jurnal);
                                // $data_jurnal = $this->m_bill_item->get_item_jurnal($data_search_item_jurnal);
                            endif;

                            if($item['GST_TYPE']==GST_TYPE_RENTAL):
                                $data_search_item_rental['bill_month']      = $item['BILL_MONTH'];
                                $data_search_item_rental['bill_year']       = $item['BILL_YEAR'];
                                $data_search_item_rental['tr_code']         = $item['TR_CODE'];
                                $data_search_item_rental['account_id']      = $item['ACCOUNT_ID'];

                                $data_search_tr_code['MCT_TRCODENEW'] = $data_acc['TRCODE_CATEGORY'];
                                $tr_code = $this->m_tran_code->get_tr_code($data_search_tr_code);
                                // echo last_query().'<br><br>';
                                // pre($tr_code);
                                if($tr_code):

                                    $data_search_item_rental['bill_month']      = $item['BILL_MONTH'];
                                    $data_search_item_rental['bill_year']       = $item['BILL_YEAR'];
                                    $data_search_item_rental['tr_code']         = $tr_code['MCT_TRCODENEW'];
                                    $data_search_item_rental['account_id']      = $item['ACCOUNT_ID'];
                                    $data_search_item_rental['not_equal_last_year']  = 1;
                                    $data_gst_actual = $this->m_bill_item->get_item_bill($data_search_item_rental);
                                    // echo last_query();
                                    $item['gst_actual']     = $data_gst_actual;
                                endif;
                            elseif ($item['GST_TYPE']==GST_TYPE_WATER):
                                $data_search_item_water['bill_month']      = $item['BILL_MONTH'];
                                $data_search_item_water['bill_year']       = $item['BILL_YEAR'];
                                $data_search_item_water['tr_code']         = '11110016';
                                $data_search_item_water['account_id']      = $item['ACCOUNT_ID'];
                                $data_search_item_water['not_equal_last_year']  = 0;
                                $data_gst_actual = $this->m_bill_item->get_item_bill($data_search_item_water);
                                $item['gst_actual']     = $data_gst_actual;
                            elseif ($item['GST_TYPE']==GST_TYPE_TIPPING):
                                $data_search_item_tipping['bill_month']      = $item['BILL_MONTH'];
                                $data_search_item_tipping['bill_year']       = $item['BILL_YEAR'];
                                $data_search_item_tipping['tr_code']         = '11110014';
                                $data_search_item_tipping['account_id']      = $item['ACCOUNT_ID'];
                                $data_search_item_tipping['not_equal_last_year']  = 0;
                                $data_gst_actual = $this->m_bill_item->get_item_bill($data_search_item_tipping);
                                // pre($data_gst_actual);
                                $item['gst_actual']     = $data_gst_actual;
                            endif;

                            // $item['jurnal']         = $data_jurnal;
                            $row['prv_data']       = $data_item_prv;
                            // pre($item['prv_data']);
                            // $item['gst_actual']     = $data_gst_actual;

                            $row['data_item'][]     = $item;
                        endforeach;
                        $data_report[] = $row;

                        // pre($data_report);
                        // exit;
                    elseif($data_item_prv):
                        $row['data_item']       = array();
                        $row['prv_data']        = $data_item_prv;
                        $data_report[]          = $row;

                        // pre($data_report);
                    endif;
                endforeach;
            endif;
            // echo last_query();
            $data['data_gst']           = $data_report;
            $data['data_search']        = $data_search;
        else:
            $data['data_gst']       = array();
            $data['data_search']    = $data_search;
        endif;

        // pre($data);
        // exit;
        // pre($data_gst);
        // exit;
        // $links          = '/account/account_list';
        // $uri_segment    = 3;
        // $per_page       = 20;
        // paging_config($links,$total,$per_page,$uri_segment);

        // $data_list              = $this->m_bill_master->get_highest_oustanding_bill($per_page,$search_segment,$data_search);
        // $data['total_result']   = $total;
        // $data['data_list']      = $data_list;

        templates('/report/v_rental_gst',$data);
    }

    function gst_rental_simple(){
        $this->auth->restrict_access($this->curuser,array(8004));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Bil gst (Ringkasan)';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Bil GST (Ringkasan)';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_rental_gst_simple');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_rental_gst_simple',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['date_start']  = date_display(timenow(),'d M Y');
                $data_search['date_end']    = '';
                $data_search['type_id']     = '';
                $data_search['category_id'] = '';
                $data_search['acc_status']  = '';
            endif;
        endif;
        $data['data_type']   = $this->m_type->get_a_type();

        if($_POST):
            $data_report        = $this->m_bill_item->report_rental_gst_simple($data_search);
            // echo '<br>';
            // echo '<br>';
            // echo '<br>';
            // echo '<br>';
            // echo '<br>';
            // echo '<br>';
            // echo '<br>';
            // echo '<br>';
            // echo last_query();
            // pre($data_report);
            // exit;
            // pre($data_report);
            $data_report_new = array();


            if($data_report):
                foreach ($data_report as $row):
                    $data_report_prv        = $this->m_bill_item->report_rental_gst_simple_prv($data_search,$row['CATEGORY_ID']);
                    // echo '<br>';
                    // echo '<br>';
                    // echo '<br>';
                    // echo '<br>';
                    // echo '<br>';
                    // echo '<br>';
                    // echo '<br>';
                    // echo '<br>';
                    // pre($data_report_prv);
                    $row['data_report_prv'] = $data_report_prv;

                    // pre($row);
                    $data_report_new[$row['TYPE_NAME']][] = $row;
                endforeach;
            endif;
            $data['data_gst']       = $data_report_new;
            $data['data_search']    = $data_search;
        else:
            $data['data_gst']       = array();
            $data['data_search']    = $data_search;
        endif;

        // pre($data_report_new);

        templates('/report/v_rental_gst_simple',$data);
    }

    function code_gl(){
        $this->auth->restrict_access($this->curuser,array(8005));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Kod Transaksi';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Kod Transaksi';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_code_gl');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_code_gl',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['date_start']  = date_display(timenow(),'d M Y');
                $data_search['date_end']    = '';
                $data_search['type_id']     = '';
                $data_search['category_id'] = '';
                $data_search['acc_status']  = '';
                $data_search['tr_code']     = '';
            endif;
        endif;
        $data['data_type']          = $this->m_type->get_a_type();
        $data['data_code_object']   = $this->m_tran_code->get_tr_code_list();

        if($_POST):
            $data_report = $this->m_bill_item->report_code_gl($data_search);
            // echo last_query();
            $data['data_report']       = $data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_report']       = array();
            $data['data_search']    = $data_search;
        endif;

        templates('/report/v_code_gl',$data);
    }

    function highest_overdue(){
        $this->auth->restrict_access($this->curuser,array(8006));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = (uri_segment(3)=='two')?'Tunggakan Sewaan':'Tunggakan Sewaan Tertinggi';
        $data['link_3']     = '';
        $data['pagetitle']  = (uri_segment(3)=='two')?'Laporan Tunggakan Sewaan':'Laporan Tunggakan Sewaan Tertinggi';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_code_highest_overdue');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_code_highest_overdue',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                // $data_search['date_start']  = date_display(timenow(),'d M Y');
                // $data_search['date_end']    = '';
                $data_search['type_id']     = '';
                $data_search['category_id'] = '';
                $data_search['acc_status']  = '';
            endif;
        endif;
        $data['data_type']  = $this->m_type->get_a_type();
        $new_data_report    = array();

        if(uri_segment(3)=='two'):
            $data_search['order_by']    = '2';
        else:
            $data_search['order_by']    = '1';
        endif;

        if($_POST):
            $data_report = $this->m_bill_item->report_highest_overdue($data_search);
            if($data_report):
                foreach ($data_report as $row):
                    $get_data_account = $this->m_acc_account->get_account_details($row['ACCOUNT_ID']);
                    $row['account_details'] = $get_data_account;
                    $new_data_report[] = $row;
                endforeach;
            endif;
            //     echo last_query();
            // pre($new_data_report);
            // exit;
            $data['data_report']    = $new_data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_report']       = array();
            $data['data_search']    = $data_search;
        endif;

        templates('/report/v_highest_overdue',$data);
    }

    function record_transaction(){
        $this->auth->restrict_access($this->curuser,array(8007));
        $data['link_1'] = 'Laporan';
        $data['link_2'] = 'Rekod transaksi';
        $data['link_3'] = '';
        $data['pagetitle'] = 'Rekod transaksi';

        $search_segment = uri_segment(3);

        $post = $this->input->post();
        $filter_session = get_session('arr_filter_record_transaction');
        if (!empty($post)):
            $this->session->set_userdata('arr_filter_record_transaction', $post);
            $data_search = $post;
        else:
            if (!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['date_start'] = date_display(timenow(), 'd M Y');
                $data_search['date_end'] = '';
                $data_search['type_id'] = '';
                $data_search['category_id'] = '';
                $data_search['account_id'] = '';
                $data_search['order_by'] = '';
                $data_search['tr_code'] = '';
            endif;
        endif;

        if (empty($data_search['date_start']) || $data_search['date_start'] == "")
        {
            $data_search['date_start'] = date("d M Y");
        }

        if (empty($data_search['date_end']) || $data_search['date_end'] == "")
        {
            $data_search['date_end'] = date("d M Y");
        }

        $data['data_type']          = $this->m_type->get_a_type();
        $data['data_account']       = $this->m_acc_account->get_account();
        $data['data_code_object']   = $this->m_tran_code->get_tr_code_list();

        $new_data_report = array();

        if ($_POST):
            // $data_report = $this->m_bill_item->record_transaction($data_search);
            $data_report = $this->m_bill_item->rekodTransaksi($data_search);
            // $data['data_report'] = $data_report;
            $data['data_search'] = $data_search;

            // Added for filter data by yearly record [START]
            $backupSearch_startDate = $data_search['date_start'];
            $backupSearch_endDate = $data_search['date_end'];
            $startYear          = DateTime::createFromFormat('d M Y', $data_search['date_start'])->format('Y');
            $endYear            = DateTime::createFromFormat('d M Y', $data_search['date_end'])->format('Y');
            $nextYear           = $endYear;

            $data_year = array();
            while ($nextYear >= $startYear)
            {
                $new_startDate = '1 Jan '.$nextYear;
                $new_endDate = '31 Dec '.$nextYear;
                $date1 = new DateTime($backupSearch_startDate);
                $date2 = new DateTime($new_startDate);

                if ($nextYear == $endYear)
                {
                    $data_search['date_start'] = '1 Jan '.$nextYear;
                    $data_search['date_end'] = $backupSearch_endDate;
                    $data_year["$nextYear"] = $this->m_bill_item->rekodTransaksi($data_search);
                } else if ($nextYear == $startYear)
                {
                    $data_search['date_start'] = $backupSearch_startDate;
                    $data_search['date_end'] = '31 Dec '.$nextYear;
                    $data_year["$nextYear"] = $this->m_bill_item->rekodTransaksi($data_search);
                }
                else
                {
                    $data_search['date_start'] = '1 Jan '.$nextYear;
                    $data_search['date_end'] = '31 Dec '.$nextYear;
                    $data_year["$nextYear"] = $this->m_bill_item->rekodTransaksi($data_search);
                }
                
                $nextYear = $nextYear - 1;
            }

            $data['data_report'] = $data_year;
            // Added for filter data by yearly record [END]

            $data["account_details"] = $this->transactionReportHeader($data_search['account_id']); // added for print header

            $data['acc_details'] = array();
            if($data_search['account_id']):
                $data['acc_details'] = $this->m_acc_account->get_account_details($data_search['account_id']);
            endif;
        else:
            $data['account_details'] = array();
            $data['data_report'] = array();
            $data['acc_details'] = array();
            $data['data_search'] = $data_search;
        endif;

        templates('/report/v_record_transaction', $data);
    }

    public function report_dashboard(){
        if(is_ajax()):
            $application_data           = $this->m_p_application->application_report_dashboard(array());
            $data['new_application']    = $application_data['NEW_APPLICATION'];
            $data['not_registered']     = $application_data['NOT_REGISTERED'];

            $report_application_by_type = $this->m_p_application->application_report_dashboard_by_type(array());
            $label_app  = array();
            $number_app = array();
            if($report_application_by_type):
                foreach ($report_application_by_type as $row):
                    $label_app[]    = $row['TYPE_NAME'];
                    $number_app[]   = $row['COUNT_APPLICATION'];
                endforeach;
            endif;
            $data['label_app']  = $label_app;
            $data['number_app'] = $number_app;

            // $total_notice_generate_this_month = $this->m_audit_trail->count_notice_out_current_month();
            $total_notice_generate_this_month = $this->m_acc_account->count_account_outstanding();
            $data['notice_this_month'] = $total_notice_generate_this_month;

            $total_count_expiry_acc = $this->m_acc_account->count_expiry_acc();
            $data['acc_expiry']     = $total_count_expiry_acc;

            $label_status               = array();
            $number_status_accepted     = array();
            $number_status_rejected     = array();
            $report_status_application = $this->m_p_application->application_status_report_dashboard_by_type(array());

            if($report_status_application):
                #order by priority
                usort($report_status_application, function($a, $b) {
                    if ($a['MONTH_NUMBER'] == $b['MONTH_NUMBER']) {
                        return $a['MONTH_NUMBER'] > $b['MONTH_NUMBER'] ? 1 : -1;
                    }

                    return $a['MONTH_NUMBER'] > $b['MONTH_NUMBER'] ? 1 : -1;
                });

                foreach ($report_status_application as $row):
                    $label_status[]             = trim($row['MONTH']);
                    $number_status_accepted[]   = $row['APPROVED_APPLICATION'];
                    $number_status_rejected[]   = $row['REJECTED_APPLICATION'];
                endforeach;
            endif;
            $data['label_status']           = $label_status;
            $data['number_status_accepted'] = $number_status_accepted;
            $data['number_status_rejected'] = $number_status_rejected;

            $label_acc_type     = array();
            $number_acc_type    = array();
            $report_account_by_type = $this->m_acc_account->account_report_dashboard();
            if($report_account_by_type):
                foreach ($report_account_by_type as $row):
                    $label_acc_type[]   = $row['TYPE_NAME'];
                    $number_acc_type[]  = $row['TOTAL_ACC'];
                endforeach;
            endif;
            $data['label_acc_type']  = $label_acc_type;
            $data['number_acc_type'] = $number_acc_type;

            $label_accepted     = array();
            $number_offered     = array();
            $number_accepted    = array();
            $application_offered_by_type = $this->m_p_application->application_offered_by_type(array());
            if($application_offered_by_type):
                foreach ($application_offered_by_type as $row):
                    $label_accepted[]   = $row['TYPE_NAME'];
                    $number_offered[]   = $row['APPROVED_APPLICATION'];
                    $number_accepted[]  = $row['SETUJU_TERIMA'];
                endforeach;
            endif;
            $data['label_accepted']     = $label_accepted;
            $data['number_offered']     = $number_offered;
            $data['number_accepted']    = $number_accepted;

            echo json_encode($data);
        endif;
    }

    function adjustment_statement(){
        $this->auth->restrict_access($this->curuser,array(8008));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Penyata Penyesuaian Terperinci';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Penyata Penyesuaian Terperinci';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_adjustment_report');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_adjustment_report',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['date_start']  = date_display(timenow(),'d M Y');
                $data_search['date_end']    = '';
                $data_search['category_id'] = '';
                $data_search['status_acc']  = '';
                $data_search['type_id']     = '';
            endif;
        endif;

        $data['data_category']  = $this->m_category->get_a_category('','');
        $data['data_type']      = $this->m_type->get_a_type();
        $data_report = array();

        if($_POST):
            $data_account = $this->m_acc_account->get_account_by_search($data_search);
            if($data_account):
                foreach ($data_account as $row):
                    #tunggakan
                    $tr_code_payment_overdue = substr_replace($row['TRCODE_CATEGORY'],'12',0,2);
                    $data_search_overdue['tr_code_like']    = '12';//$tr_code_payment_overdue;
                    $data_search_overdue['account_id']      = $row['ACCOUNT_ID'];
                    $data_search_overdue['bill_category']   = 'B';
                    $data_search_overdue['date_start']      = $data_search['date_start'];
                    $data_search_overdue['date_end']        = $data_search['date_end'];
                    $tunggakan  = $this->m_bill_item->get_bill_by_search($data_search_overdue);

                    #$bayaran
                    $tr_code_payment_payment = substr_replace($row['TRCODE_CATEGORY'],'22',0,2);
                    $data_search_payment['tr_code_like']    = '22';
                    $data_search_payment['account_id']      = $row['ACCOUNT_ID'];
                    $data_search_payment['bill_category']   = 'R';
                    $data_search_payment['date_start']      = $data_search['date_start'];
                    $data_search_payment['date_end']        = $data_search['date_end'];
                    $bayaran    = $this->m_bill_item->get_bill_by_search($data_search_payment);

                    #lebbihan
                    $tr_code_payment_payment = substr_replace($row['TRCODE_CATEGORY'],'22',0,2);
                    $data_search_lebihan['tr_code_like']    = '11119999';
                    $data_search_lebihan['account_id']      = $row['ACCOUNT_ID'];
                    $data_search_lebihan['bill_category']   = 'B';
                    $data_search_lebihan['date_start']      = $data_search['date_start'];
                    $data_search_lebihan['date_end']        = $data_search['date_end'];
                    $lebihan    = $this->m_bill_item->get_bill_by_search($data_search_lebihan);

                    #cukai
                    $tr_code_payment_payment = substr_replace($row['TRCODE_CATEGORY'],'22',0,2);
                    $data_search_cukai['tr_code_start']   = '11110025';
                    $data_search_cukai['tr_code_end']     = '11110029';
                    $data_search_cukai['account_id']      = $row['ACCOUNT_ID'];
                    $data_search_cukai['bill_category']   = 'B';
                    $data_search_cukai['date_start']      = $data_search['date_start'];
                    $data_search_cukai['date_end']        = $data_search['date_end'];
                    $cukai    = $this->m_bill_item->get_bill_by_search($data_search_cukai);

                    #bayaran cukai
                    $tr_code_payment_payment = substr_replace($row['TRCODE_CATEGORY'],'22',0,2);
                    $data_search_bayaran_cukai['tr_code_start']   = '11110025';
                    $data_search_bayaran_cukai['tr_code_end']     = '11110029';
                    $data_search_bayaran_cukai['account_id']      = $row['ACCOUNT_ID'];
                    $data_search_bayaran_cukai['bill_category']   = 'R';
                    $data_search_bayaran_cukai['date_start']      = $data_search['date_start'];
                    $data_search_bayaran_cukai['date_end']        = $data_search['date_end'];
                    $bayaran_cukai    = $this->m_bill_item->get_bill_by_search($data_search_bayaran_cukai);

                    #sewaan semasa
                    $tr_code_payment_payment = substr_replace($row['TRCODE_CATEGORY'],'22',0,2);
                    $data_search_semasa['tr_code']           = $row['TRCODE_CATEGORY'];
                    $data_search_semasa['account_id']        = $row['ACCOUNT_ID'];
                    $data_search_semasa['bill_category']     = 'B';
                    $data_search_semasa['date_start']      = $data_search['date_start'];
                    $data_search_semasa['date_end']        = $data_search['date_end'];
                    $semasa    = $this->m_bill_item->get_bill_by_search($data_search_semasa);

                    #bayaran sewaan
                    $bayaran_semasa = substr_replace($row['TRCODE_CATEGORY'],'21',0,2);
                    $data_search_bayaran_semasa['tr_code']           = $bayaran_semasa;
                    $data_search_bayaran_semasa['account_id']        = $row['ACCOUNT_ID'];
                    $data_search_bayaran_semasa['bill_category']     = 'R';
                    $data_search_bayaran_semasa['date_start']      = $data_search['date_start'];
                    $data_search_bayaran_semasa['date_end']        = $data_search['date_end'];
                    $bayaran_semasa    = $this->m_bill_item->get_bill_by_search($data_search_bayaran_semasa);

                    $row['tunggakan']       = $tunggakan;
                    $row['bayaran']         = $bayaran;
                    $row['lebihan']         = $lebihan;
                    $row['cukai']           = $cukai;
                    $row['bayaran_cukai']   = $bayaran_cukai;
                    $row['semasa']          = $semasa;
                    $row['bayaran_semasa']  = $bayaran_semasa;

                    $data_report[] = $row;
                endforeach;
            endif;

            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_report']       = array();
            $data['data_search']    = $data_search;
        endif;

        templates('/report/v_statement_adjustment',$data);
    }

    function adjustment_statement_ringkasan(){
        $this->auth->restrict_access($this->curuser,array(8009));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Penyata Penyesuaian Ringkasan';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Penyata Penyesuaian Ringkasan';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_adjustment_report_ringkasan');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_adjustment_report_ringkasan',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['date_start']  = date_display(timenow(),'d M Y');
                $data_search['date_end']    = '';
                $data_search['category_id'] = '';
                $data_search['status_acc']  = '';
                $data_search['type_id']     = '';
            endif;
        endif;

        $data['data_category']  = $this->m_category->get_a_category('','');
        $data['data_type']      = $this->m_type->get_a_type();
        $data_report = array();

        if($_POST):
            if(input_data('type_id')==''):
                $data_type_loop = $data['data_type'];
            else:
                $data_type_loop[] =
                    array(
                        'TYPE_ID'=>input_data('type_id')
                    );
            endif;

            if($data_type_loop):
                $j=0;
                foreach ($data_type_loop as $row):
                    $j = $j+1;
                    if($j==4):
                        break;
                    endif;
                    $data_type_details          = $this->m_type->get_a_type_details($row['TYPE_ID']);
                    $data_search_cat['type_id']     = $row['TYPE_ID'];
                    $data_search_cat['category_id'] = input_data('category_id');
                    $data_category              = $this->m_category->get_a_category('','',$data_search_cat);
                    $row['data_type_details']   = $data_type_details;

                    if($data_category):
                        $loop_category = array();
                        $i=0;
                        foreach ($data_category as $category):
                                $i = $i+1;
                            /*
                                if($i==4):
                                    break;
                                endif;
                              */
                            #tunggakan
                            // $tr_code_payment_overdue = substr_replace($row['TRCODE_CATEGORY'],'12',0,2);
                            $data_search_overdue['tr_code_like']    = '12';//$tr_code_payment_overdue;
                            $data_search_overdue['category_id']     = $category['CATEGORY_ID'];
                            $data_search_overdue['bill_category']   = 'B';
                            $data_search_overdue['date_start']      = $data_search['date_start'];
                            $data_search_overdue['date_end']        = $data_search['date_end'];
                            $tunggakan  = $this->m_bill_item->get_bill_by_search($data_search_overdue);

                            #$bayaran
                            // $tr_code_payment_payment = substr_replace($row['TRCODE_CATEGORY'],'22',0,2);
                            $data_search_payment['tr_code_like']    = '22';
                            $data_search_payment['category_id']     = $category['CATEGORY_ID'];
                            $data_search_payment['bill_category']   = 'R';
                            $data_search_payment['date_start']      = $data_search['date_start'];
                            $data_search_payment['date_end']        = $data_search['date_end'];
                            $bayaran    = $this->m_bill_item->get_bill_by_search($data_search_payment);

                            #lebbihan
                            // $tr_code_payment_payment = substr_replace($row['TRCODE_CATEGORY'],'22',0,2);
                            $data_search_lebihan['tr_code_like']    = '11119999';
                            $data_search_lebihan['category_id']      = $category['CATEGORY_ID'];
                            $data_search_lebihan['bill_category']   = 'B';
                            $data_search_lebihan['date_start']      = $data_search['date_start'];
                            $data_search_lebihan['date_end']        = $data_search['date_end'];
                            $lebihan    = $this->m_bill_item->get_bill_by_search($data_search_lebihan);

                            #cukai
                            // $tr_code_payment_payment = substr_replace($row['TRCODE_CATEGORY'],'22',0,2);
                            $data_search_cukai['tr_code_start']   = '11110025';
                            $data_search_cukai['tr_code_end']     = '11110029';
                            $data_search_cukai['category_id']     = $category['CATEGORY_ID'];
                            $data_search_cukai['bill_category']   = 'B';
                            $data_search_cukai['date_start']      = $data_search['date_start'];
                            $data_search_cukai['date_end']        = $data_search['date_end'];
                            $cukai    = $this->m_bill_item->get_bill_by_search($data_search_cukai);

                            #bayaran cukai
                            // $tr_code_payment_payment = substr_replace($row['TRCODE_CATEGORY'],'22',0,2);
                            $data_search_bayaran_cukai['tr_code_start']   = '11110025';
                            $data_search_bayaran_cukai['tr_code_end']     = '11110029';
                            $data_search_bayaran_cukai['category_id']     = $category['CATEGORY_ID'];
                            $data_search_bayaran_cukai['bill_category']   = 'R';
                            $data_search_bayaran_cukai['date_start']      = $data_search['date_start'];
                            $data_search_bayaran_cukai['date_end']        = $data_search['date_end'];
                            $bayaran_cukai    = $this->m_bill_item->get_bill_by_search($data_search_bayaran_cukai);

                            #sewaan semasa
                            // $tr_code_payment_payment = substr_replace($row['TRCODE_CATEGORY'],'22',0,2);
                            $data_search_semasa['tr_code']           = $category['TRCODE_CATEGORY'];
                            $data_search_semasa['category_id']       = $category['CATEGORY_ID'];
                            $data_search_semasa['bill_category']     = 'B';
                            $data_search_semasa['date_start']      = $data_search['date_start'];
                            $data_search_semasa['date_end']        = $data_search['date_end'];
                            $semasa    = $this->m_bill_item->get_bill_by_search($data_search_semasa);

                            #bayaran sewaan
                            $bayaran_semasa = substr_replace($category['TRCODE_CATEGORY'],'21',0,2);
                            $data_search_bayaran_semasa['tr_code']          = $bayaran_semasa;
                            $data_search_bayaran_semasa['category_id']      = $category['CATEGORY_ID'];
                            $data_search_bayaran_semasa['bill_category']    = 'R';
                            $data_search_bayaran_semasa['date_start']       = $data_search['date_start'];
                            $data_search_bayaran_semasa['date_end']         = $data_search['date_end'];
                            $bayaran_semasa    = $this->m_bill_item->get_bill_by_search($data_search_bayaran_semasa);

                            $loop_category_1['tunggakan']       = $tunggakan;
                            $loop_category_1['bayaran']         = $bayaran;
                            $loop_category_1['lebihan']         = $lebihan;
                            $loop_category_1['cukai']           = $cukai;
                            $loop_category_1['bayaran_cukai']   = $bayaran_cukai;
                            $loop_category_1['semasa']          = $semasa;
                            $loop_category_1['bayaran_semasa']  = $bayaran_semasa;


                            $loop_category_1['category_details']      = $this->m_category->get_a_category_details($category['CATEGORY_ID']);
                            $loop_category[] = $loop_category_1;

                        endforeach;
                        $row['category'] = $loop_category;
                        $data_report[] = $row;
                    endif;
                endforeach;
            endif;

            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_report']       = array();
            $data['data_search']    = $data_search;
        endif;

        templates('/report/v_statement_adjustment_ringkasan',$data);
    }

    function payment(){
        $this->auth->restrict_access($this->curuser,array(8010));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Pembayaran Penyewa';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Pembayaran Penyewa';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_payment');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_payment',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['date_start']  = date_display(timenow(),'d M Y');
                $data_search['date_end']    = '';
                $data_search['type_id']     = '';
                $data_search['category_id'] = '';
                $data_search['acc_status']  = '';
            endif;
        endif;
        $data['data_type']   = $this->m_type->get_a_type();
        $data['data_report']    = array();

        if($_POST):
            $get_code_category = $this->m_category->get_a_category('','',$data_search);
            $data_report = array();
            if($get_code_category):
                foreach ($get_code_category as $row):
                    $data_search_acc['category_id'] = $row['CATEGORY_ID'];
                    $data_search_acc['status_acc']  = $data_search['acc_status'];
                    $get_data_acc = $this->m_acc_account->get_account_by_search($data_search_acc);
                    if($get_data_acc):
                        foreach ($get_data_acc as $acc):
                            $asset_name = $this->m_asset->get_a_asset_by_id($acc['ASSET_ID']);

                            $data_search_trans['date_start']    = $data_search['date_start'];
                            $data_search_trans['date_end']      = $data_search['date_end'];
                            $data_search_trans['account_id']    = $acc['ACCOUNT_ID'];
                            $data_search_trans['order_by']      = 'i.dt_added';
                            $data_report_trans = $this->m_bill_item->record_transaction($data_search_trans);
                            $acc['data_asset'] = $asset_name;
                            $acc['account_details_trans'] = $data_report_trans;
                            $row['acc'][] = $acc;
                        endforeach;
                        $data_report[] = $row;
                    endif;
                endforeach;
            endif;

            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_gst']       = array();
            $data['data_search']    = $data_search;
        endif;

        templates('/report/v_payment',$data);
    }

    function journal(){
        $this->auth->restrict_access($this->curuser,array(8011));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Jurnal Sewaan';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Jurnal Sewaan';

        // $data['data'] = $this->m_journal->get_lists_temp_journal_report();

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
                $data_search['date_start']  = date_display(timenow(),'d M Y');
                $data_search['date_end']    = '';
                $data_search['category_id'] = '';
                $data_search['acc_status']  = '';
            endif;
        endif;
        $data['data_code_category']   = $this->m_category->get_a_category_all('','');

        // pre($data_report);
        // die();
      
        if($_POST):

            $data_report = $this->m_journal->get_lists_temp_journal_report($data_search);
            //echo last_query();
            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_report']    = array();
            $data['data_search']    = $data_search;
        endif;

        templates('report/v_journal',$data);
    }

    function dataTable4RekodTransaksi()
    {
        
        // ************* Paging setup *************

        $startRecord = $this->input->get('start');            // The starting point for record to be retrieved
        $lengthRecord = $this->input->get('length');          // The total record needed to be retrieved
         
        $sLimit = "";
        if ( isset( $startRecord ) && $lengthRecord != '-1' )
        {
            $sLimit = " LIMIT ".intval( $startRecord ).", ".intval( $lengthRecord );
        }

        // ************* Filtering setup *************

        $searchKeyword = $this->input->get('search')['value'];

        $sWhere = "";

        if ( isset( $startRecord ) && $lengthRecord != '-1' )
        {
            $sLimit = " WHERE bill_number LIKE '%".$searchValue."%' 
                        or no_kontrak LIKE '%".$searchValue."%' ";
        }

        // if ( isset($_GET['search']['value']) && $_GET['search']['value'] != "" ) {
        //     $searchValue = $_GET['search']['value'];
        //     $sWhere = " WHERE nama_projek LIKE '%".$searchValue."%' or no_kontrak LIKE '%".$searchValue."%' ";
        // }

        echo json_encode(
            array(
                'startRecord' => $startRecord,
                'lengthRecord' => $lengthRecord,
                'searchKeyword' => $searchKeyword,
            )
        );
    }

    function transactionReportHeader($account_id)
    {
        // $accountId = $this->input->post('account_id');
        // $data_search["account_id"] = $accountId;

        // echo json_encode($this->m_bill_item->rekodTransaksiInfo($account_id));
        return $this->m_bill_item->rekodTransaksiInfo($account_id);
    }

    function hartanah(){
        // $this->auth->restrict_access($this->curuser,array(8011));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Perjanian Sewaan Hartanah';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Perjanian Sewaan Hartanah';

        $data['data_jan'] = $this->m_acc_account->sewaan_hartanah(2019,1);
        $data['data_feb'] = $this->m_acc_account->sewaan_hartanah(2019,2);
        $data['data_mar'] = $this->m_acc_account->sewaan_hartanah(2019,3);
        $data['data_apr'] = $this->m_acc_account->sewaan_hartanah(2019,4);
        $data['data_may'] = $this->m_acc_account->sewaan_hartanah(2019,5);
        $data['data_jun'] = $this->m_acc_account->sewaan_hartanah(2019,6);
        $data['data_jul'] = $this->m_acc_account->sewaan_hartanah(2019,7);
        $data['data_aug'] = $this->m_acc_account->sewaan_hartanah(2019,8);
        $data['data_sep'] = $this->m_acc_account->sewaan_hartanah(2019,9);
        $data['data_oct'] = $this->m_acc_account->sewaan_hartanah(2019,10);
        $data['data_nov'] = $this->m_acc_account->sewaan_hartanah(2019,11);
        $data['data_dec'] = $this->m_acc_account->sewaan_hartanah(2019,12);

        templates('report/v_hartanah',$data);
    }
}
/* End of file modules/login/controllers/report.php */

