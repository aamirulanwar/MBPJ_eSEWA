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
        load_model('Notice/M_lod_generation', 'm_lod_generation');
        load_model('Notice/M_notis_mah_generation', 'm_notis_mah_generation');
        load_model('User/M_users', 'm_users');
    }

    function _remap($method){
        $array = array(
            'account_summary',
            'rent_summary',
            'category_aging',
            'print_category_aging',
            'account_outstanding',
            'gl_summary',
            'category_adjustment',
            'gst_rental',
            'print_gst_rental',
            'gst_rental_simple',
            'print_gst_rental_simple',
            'code_gl',
            'print_code_gl',
            'highest_overdue',
            'print_highest_overdue',
            'record_transaction',
            'print_all_record_transaction',
            'report_dashboard',
            'category_aging_details',
            'print_category_aging_details',
            'adjustment_statement',
            'print_penyata_penyesuaian_terperinci',
            'adjustment_statement_ringkasan',
            'print_penyata_penyesuaian_ringkasan',
            'payment',
            'print_pembayaran_penyewa',
            'journal',
            'print_journal',
            'dataTable4RekodTransaksi',
            'transactionReportHeader',
            'hartanah',
            'print_hartanah',
            'papaniklan',
            'print_papaniklan',
            'perjanjian_kutipan',
            'print_perjanjian_kutipan',
            'hasil',
            'print_hasil',
            'kutipan_tunggakan',
            'print_kutipan_tunggakan',
            'laporan_iso',
            'print_iso'
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

    function category_aging_details()
    {
        $this->auth->restrict_access($this->curuser,array(8001));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Aging Sewaan (Terperinci)';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Aging Sewaan (Terperinci)';

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_category_aging_details');

        $list_of_category = $this->m_category->get_a_category_all();

        if(!empty($post))
        {
            $this->session->set_userdata('arr_filter_category_aging_details',$post);
            $data_search = $post;
        }
        else
        {
            if(!empty($filter_session))
            {
                $data_search = $filter_session;
            }
            else
            {
                $data_search['asset_code']  = '0';
                $data_search['acc_status']  = '0';
                $data_search['date_start']  = date('d-m-Y');
            }
        }

        if($_POST)
        {
            $data_search_aging["category_id"] = $_POST["category_id"];
            $data_search_aging["status_acc"] = $_POST["acc_status"];

            $data_report = $this->m_bill_item->getAgingSewaanTerperinci($data_search_aging);
            $data['data_report'] = $data_report;
        }
        else
        {
            $data['data_report']    = array();
        }

        $data['data_search']    = $data_search;
        $data['data_category']  = $list_of_category;

        templates('/report/v_category_aging_details_search',$data);
    }

    function print_category_aging_details()
    {
        $data_search = get_session('arr_filter_category_aging_details');
        $data["data_search"] = $data_search;
        
        $data_search_aging["category_id"] = $data_search["category_id"];
        $data_search_aging["status_acc"] = $data_search["acc_status"];
        $data_report = $this->m_bill_item->getAgingSewaanTerperinci($data_search_aging);

        if ($data_search["category_id"] == "0")
        {
            $data_search["category_name"] = "Semua";
        }
        else if ($data_search["category_id"] != "0")
        {
            $data_search["category_name"] = $this->m_category->get_a_category_details($data_search["category_id"])["CATEGORY_NAME"];
        }

        if ($data_search["acc_status"] == "")
        {
            $data_search["status_type"] = "Semua";
        }
        else if ($data_search["acc_status"] == "1")
        {
            $data_search["status_type"] = "Aktif";
        }
        else if ($data_search["acc_status"] == "2")
        {
            $data_search["status_type"] = "Tidak Aktif";
        }
        
        $data['data_report'] = $data_report;
        $data['data_search']  = $data_search;

        $this->load->view('/report/v_print_category_aging_details',$data);
    }

    function category_aging()
    {
        $this->auth->restrict_access($this->curuser,array(8002));

        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Aging Sewaan (Ringkasan)';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Aging Sewaan (Ringkasan)';

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_category_aging');

        if( !empty($post) )
        {
            $this->session->set_userdata('arr_filter_category_aging',$post);
            $data_search = $post;
        }
        else
        {
            if(!empty($filter_session))
            {
                $data_search = $filter_session;
            }
            else
            {
                $data_search['asset_type']  = '';
                $data_search['acc_status']  = '';
                $data_search['date_start']  = '';

                $this->session->set_userdata('arr_filter_category_aging',$data_search);
            }
        }

        if($_POST)
        {
            $data_search_aging["type_id"] = $_POST["type_id"];
            $data_search_aging["status_acc"] = $_POST["acc_status"];

            $data_report = $this->m_bill_item->getAgingSewaanRingkasan($data_search_aging);

            // Regroup data receive from model to array[type_id]
            foreach ($data_report as $report) 
            {
                // Check current type_id / description and group data based on asset type
                $type = $report["TYPE_NAME"];
                $data_report_by_type[$type][] = $report;
            }

            $data['data_report'] = $data_report_by_type;
        }
        else
        {
            $data['data_report']    = array();
        }

        $a_type = $this->m_type->get_a_type();

        $data['data_search']    = $data_search;
        $data['data_type']      = $a_type;
        // $data['data_report']    = $result_report;

        templates('/report/v_category_aging',$data);
    }

    function print_category_aging()
    {
        $data_search = get_session('arr_filter_category_aging');
        $data["data_search"] = $data_search;
        
        $data_search_aging["type_id"] = $data_search["type_id"];
        $data_search_aging["status_acc"] = $data_search["acc_status"];

        $data_report = $this->m_bill_item->getAgingSewaanRingkasan($data_search_aging);

        // Regroup data receive from model to array[type_id]
        foreach ($data_report as $report) 
        {
            // Check current type_id / description and group data based on asset type
            $type = $report["TYPE_NAME"];
            $data_report_by_type[$type][] = $report;
        }

        if ($data_search["type_id"] == "semua")
        {
            $data_search["type_name"] = "Semua";
        }
        else if ($data_search["type_id"] != "semua")
        {
            $data_search["type_name"] = $this->m_type->get_a_type_details($data_search["type_id"])["TYPE_NAME"];
        }

        if ($data_search["acc_status"] == "")
        {
            $data_search["status_type"] = "Semua";
        }
        else if ($data_search["acc_status"] == "1")
        {
            $data_search["status_type"] = "Aktif";
        }
        else if ($data_search["acc_status"] == "2")
        {
            $data_search["status_type"] = "Tidak Aktif";
        }

        $data['data_report'] = $data_report_by_type;
        $data['data_search']  = $data_search;

        $this->load->view('/report/v_print_category_aging',$data);
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

    function print_gst_rental()
    {
        $data_search = get_session('arr_filter_rental_gst');
        $data['data_type']   = $this->m_type->get_a_type();
        if($data_search):
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

        $this->load->view('/report/v_print_gst_rental',$data);
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

    function print_gst_rental_simple()
    {
        $data_search = get_session('arr_filter_rental_gst_simple');
        $data['data_type']   = $this->m_type->get_a_type();
        if($data_search):
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

        $this->load->view('/report/v_print_gst_rental_simple',$data);
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

    function print_code_gl()
    {
        $data_search = get_session('arr_filter_code_gl');
        $data['data_type']          = $this->m_type->get_a_type();
        $data['data_code_object']   = $this->m_tran_code->get_tr_code_list();

        if($data_search):
            $data_report = $this->m_bill_item->report_code_gl($data_search);
            // echo last_query();
            $data['data_report']       = $data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_report']       = array();
            $data['data_search']    = $data_search;
        endif;

        $this->load->view('/report/v_print_code_gl',$data);
    }

    function highest_overdue()
    {
        $this->auth->restrict_access($this->curuser,array(8006));

        $data['link_1']     = 'Laporan';
        $data['link_2']     = (uri_segment(3)=='two')?'Tunggakan Sewaan':'Tunggakan Sewaan Tertinggi';
        $data['link_3']     = '';
        $data['pagetitle']  = (uri_segment(3)=='two')?'Laporan Tunggakan Sewaan':'Laporan Tunggakan Sewaan Tertinggi';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_code_highest_overdue');

        if( !empty($post) )
        {
            if ( $post["category_id"] == "" )
            {
                $post["category_id"] = "-1";
            }
            $post["search_segment"] = ( $search_segment == null ? "" : $search_segment );
            $this->session->set_userdata('arr_filter_code_highest_overdue',$post);
            $data_search = $post;

        }
        else
        {
            if(!empty($filter_session))
            {
                if ( $filter_session["category_id"] == "" )
                {
                    $filter_session["category_id"] = "-1";
                }
                $filter_session["search_segment"] = ( $search_segment == null ? "" : $search_segment );
                $data_search = $filter_session;
            }
            else
            {
                $data_search['type_id']     = '-1';
                $data_search['category_id'] = '-1';
                $data_search['acc_status']  = '-1';
                $data_search["search_segment"] = ( $search_segment == null ? "" : $search_segment );
            }
        }

        if($_POST)
        {
            $data_report = $this->m_bill_item->getLaporanTunggakan($data_search);
            
            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        }
        else
        {
            $data['data_report']    = array();
            $data['data_search']    = $data_search;
        }

        $data['data_type']  = $this->m_type->get_a_type();

        templates('/report/v_highest_overdue',$data);
    }

    function print_highest_overdue()
    {
        $data_search = get_session('arr_filter_code_highest_overdue');
        $search_segment = uri_segment(3);
        $data_search["search_segment"] = ( $search_segment == null ? "" : $search_segment );


        if ($data_search["type_id"] == -1)
        {
            $data_search["type_name"] = "Semua";
        }
        else if ( $data_search["type_id"] > 0 )
        {
            $data_search["type_name"] = $this->m_type->get_a_type_details($data_search["type_id"])["TYPE_NAME"];
        }

        if ($data_search["category_id"] == -1)
        {
            $data_search["category_name"] = "Semua";
        }
        else if ($data_search["category_id"] > 0 )
        {
            $data_search["category_name"] = $this->m_category->get_a_category_details($data_search["category_id"])["CATEGORY_NAME"];
        }

        if ($data_search["acc_status"] == -1)
        {
            $data_search["status_type"] = "Semua";
        }
        else if ($data_search["acc_status"] == "1")
        {
            $data_search["status_type"] = "Aktif";
        }
        else if ($data_search["acc_status"] == "2")
        {
            $data_search["status_type"] = "Tidak Aktif";
        }

        $data_report = $this->m_bill_item->getLaporanTunggakan($data_search);            
        $data['data_report']    = $data_report;
        $data['data_search']    = $data_search;
        $this->load->view('/report/v_print_highest_overdue',$data);
    }

    function record_transaction()
    {
        // $this->output->enable_profiler(TRUE);
        $this->auth->restrict_access($this->curuser,array(8007));
        $data['link_1'] = 'Laporan';
        $data['link_2'] = 'Rekod transaksi';
        $data['link_3'] = '';
        $data['pagetitle'] = 'Rekod transaksi';

        $search_segment = uri_segment(3);

        // Get current user session to display during print document
        $user_id = $this->curuser['USER_ID'];

        $user_details = $this->m_users->get_user_details($user_id);
        if(!$user_details):
            return false;
        endif;

        $data['user_details']   = $user_details;

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

        if ($_POST || ( $data_search["account_id"] != "" && strlen($data_search["account_id"]) > 1 ) ):
            // $data_report = $this->m_bill_item->record_transaction($data_search);
            // $data_report = $this->m_bill_item->rekodTransaksi($data_search);
            // $data['data_report'] = $data_report;
            $data['data_search'] = $data_search;

            // Added for filter data by yearly record [START]
            // $backupSearch_startDate = DateTime::createFromFormat('d M Y', $data_search['date_start'])->format('d/m/Y');
            // $backupSearch_endDate = DateTime::createFromFormat('d M Y', $data_search['date_end'])->format('d/m/Y');
            $backupSearch_startDate = DateTime::createFromFormat('d M Y', $data_search['date_start']);
            $backupSearch_endDate = DateTime::createFromFormat('d M Y', $data_search['date_end']);
            $startYear          = DateTime::createFromFormat('d M Y', $data_search['date_start'])->format('Y');
            $endYear            = DateTime::createFromFormat('d M Y', $data_search['date_end'])->format('Y');
            $nextYear           = $endYear;

            $data_year = array();
            while ($nextYear >= $startYear)
            {
                if ($nextYear == $endYear)
                {
                    $new_startDate = DateTime::createFromFormat('d/m/Y', '01/01/'.$nextYear);
                    $new_endDate = DateTime::createFromFormat('d/m/Y', '31/12/'.$nextYear);

                    //  1/5/2019 > 1/1/2020
                    if ( $backupSearch_startDate > $new_startDate && $nextYear == $startYear) 
                    {
                        $data_search['date_start'] = $backupSearch_startDate->format('d/m/Y');
                    }
                    else
                    {
                        $data_search['date_start'] = $new_startDate->format('d/m/Y');
                    }
                    
                    // 31/5/2020 < 31/12/2020
                    if ( $backupSearch_endDate < $new_endDate ) 
                    {
                        $data_search['date_end'] = $backupSearch_endDate->format('d/m/Y');
                    }
                    else
                    {
                        $data_search['date_end'] = $new_endDate->format('d/m/Y');
                    }

                    $data_year["$nextYear"] = $this->m_bill_item->rekodTransaksi($data_search);
                } 
                else if ($nextYear == $startYear)
                {
                    $new_startDate = DateTime::createFromFormat('d/m/Y', '01/01/'.$nextYear);
                    $new_endDate = DateTime::createFromFormat('d/m/Y', '31/12/'.$nextYear);

                    //  1/5/2019 > 1/1/2019
                    if ( $backupSearch_startDate > $new_startDate ) 
                    {
                        $data_search['date_start'] = $backupSearch_startDate->format('d/m/Y');
                    }
                    else
                    {
                        $data_search['date_start'] = $new_startDate->format('d/m/Y');
                    }
                    
                    // 30/11/2020 < 31/12/2015
                    if ( $backupSearch_endDate < $new_endDate ) 
                    {
                        $data_search['date_end'] = $backupSearch_endDate->format('d/m/Y');
                    }
                    else
                    {
                        $data_search['date_end'] = $new_endDate->format('d/m/Y');
                    }
                    
                    $data_year["$nextYear"] = $this->m_bill_item->rekodTransaksi($data_search);
                }
                else
                {
                    $new_startDate = DateTime::createFromFormat('d/m/Y', '01/01/'.$nextYear);
                    $new_endDate = DateTime::createFromFormat('d/m/Y', '31/12/'.$nextYear);

                    $data_search['date_start'] = $new_startDate->format('d/m/Y');
                    $data_search['date_end'] = $new_endDate->format('d/m/Y');
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

    function print_all_record_transaction()
    {
        // Get current user session to display during print document
        $user_id = $this->curuser['USER_ID'];

        $user_details = $this->m_users->get_user_details($user_id);
        if(!$user_details):
            return false;
        endif;

        $data['user_details']   = $user_details;

        # code...
        $filter_session = get_session('arr_filter_record_transaction');
        $post_status = false;

        if (!empty($filter_session))
        {
            $data_search = $filter_session;
            $post_status = true;
        }
        else
        {
            $data_search['date_start'] = date_display(timenow(), 'd M Y');
            $data_search['date_end'] = '';
            $data_search['type_id'] = '';
            $data_search['category_id'] = '';
            $data_search['account_id'] = '';
            $data_search['order_by'] = '';
            $data_search['tr_code'] = '';
        }

        if (empty($data_search['date_start']) || $data_search['date_start'] == "")
        {
            $data_search['date_start'] = date("d M Y");
        }

        if (empty($data_search['date_end']) || $data_search['date_end'] == "")
        {
            $data_search['date_end'] = date("d M Y");
        }

        $data['data_type']              = $this->m_type->get_a_type();
        $data['data_account']           = $this->m_acc_account->get_account();
        $data['data_code_object']       = $this->m_tran_code->get_tr_code_list();
        $data['search_code_category']   = $this->m_category->get_a_category_details($data_search['tr_code']);

        if ($post_status)
        {
            $data['data_search'] = $data_search;

            // Added for filter data by yearly record [START]

            $backupSearch_startDate = DateTime::createFromFormat('d M Y', $data_search['date_start'])->format('d/m/Y');
            $backupSearch_endDate = DateTime::createFromFormat('d M Y', $data_search['date_end'])->format('d/m/Y');
            $startYear          = DateTime::createFromFormat('d M Y', $data_search['date_start'])->format('Y');
            $endYear            = DateTime::createFromFormat('d M Y', $data_search['date_end'])->format('Y');
            $nextYear           = $endYear;

            $data_year = array();
            while ($nextYear >= $startYear)
            {
                if ($nextYear == $endYear)
                {
                    $new_startDate = '01/01/'.$nextYear;
                    $new_endDate = '31/12/'.$nextYear;

                    //  1/5/2019 > 1/1/2020
                    if ( $backupSearch_startDate > $new_startDate && $nextYear == $startYear) 
                    {
                        $data_search['date_start'] = $backupSearch_startDate;
                    }
                    else
                    {
                        $data_search['date_start'] = $new_startDate;
                    }
                    
                    // 31/5/2020 < 31/12/2020
                    if ( $backupSearch_endDate < $new_endDate ) 
                    {
                        $data_search['date_end'] = $backupSearch_endDate;
                    }
                    else
                    {
                        $data_search['date_end'] = $new_endDate;
                    }

                    $data_year["$nextYear"] = $this->m_bill_item->rekodTransaksi($data_search);
                } 
                else if ($nextYear == $startYear)
                {
                    $new_startDate = '01/01/'.$nextYear;
                    $new_endDate = '31/12/'.$nextYear;

                    //  1/5/2019 > 1/1/2019
                    if ( $backupSearch_startDate > $new_startDate ) 
                    {
                        $data_search['date_start'] = $backupSearch_startDate;
                    }
                    else
                    {
                        $data_search['date_start'] = $new_startDate;
                    }
                    
                    if ( $backupSearch_endDate > $new_endDate ) 
                    {
                        $data_search['date_end'] = $backupSearch_endDate;
                    }
                    else
                    {
                        $data_search['date_end'] = $new_endDate;
                    }

                    // $data_search['date_start'] = $backupSearch_startDate;
                    // $data_search['date_end'] = '31 Dec '.$nextYear;
                    $data_year["$nextYear"] = $this->m_bill_item->rekodTransaksi($data_search);
                }
                else
                {
                    $new_startDate = '01/01/'.$nextYear;
                    $new_endDate = '31/12/'.$nextYear;

                    $data_search['date_start'] = $new_startDate;
                    $data_search['date_end'] = $new_endDate;
                    $data_year["$nextYear"] = $this->m_bill_item->rekodTransaksi($data_search);
                }
                
                $nextYear = $nextYear - 1;
            }

            $data['data_report'] = $data_year;

            // Added for filter data by yearly record [END]

            // [START] added for print header
            $account_details = $this->transactionReportHeader($data_search['account_id']); 

            if (isset($account_details[0]))
            {
                $account_details = $account_details[0];                
            }
            else
            {
                $account_details['ACCOUNT_NUMBER']="";
                $account_details['NAME']="";
                $account_details['ASSET_ADD']="";
                $account_details['CATEGORY_NAME']="";
                $account_details['ADDRESS']="";
            }

            $data["account_details"] = $account_details;
            // [END] added for print header

            // $data["account_details"] = $this->transactionReportHeader($data_search['account_id']); // added for print header
            $data['acc_details'] = array();

            if($data_search['account_id'])
            {
                $data['acc_details'] = $this->m_acc_account->get_account_details($data_search['account_id']);
            }
        }
        else
        {
            $data['account_details'] = array();
            $data['data_report'] = array();
            $data['acc_details'] = array();
            $data['data_search'] = $data_search;
        }

        // templates('/report/v_print_record_transaction', $data);
        $this->load->view('/report/v_print_record_transaction',$data);
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
        if(!empty($post))
        {
            if ( $post['date_end'] == "") 
            { 
                $post['date_end'] = date_display(timenow(),'d M Y'); 
            }

            $this->session->set_userdata('arr_filter_adjustment_report',$post);
            $data_search = $post;

            if ( $data_search['date_end'] == "") 
            { 
                $data_search['date_end'] = date_display(timenow(),'d M Y'); 
            }
        }
        else
        {
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['date_start']  = date_display(timenow(),'d M Y');
                $data_search['date_end']    = date_display(timenow(),'d M Y');
                $data_search['category_id'] = '';
                $data_search['status_acc']  = '';
                $data_search['type_id']     = '';
            endif;
        }

        $data['data_category']  = $this->m_category->get_a_category('','');
        $data['data_type']      = $this->m_type->get_a_type();
        $data_report = array();

        if($_POST)
        {
            $data_report = $this->m_bill_item->getPenyataPenyesuaianTerperinci( $data_search );
            $data['data_report']  = $data_report;
            $data['data_search']  = $data_search;
        }
        else
        {
            $data['data_report']  = array();
            $data['data_search']  = $data_search;
        }

        templates('/report/v_statement_adjustment',$data);
    }

    function print_penyata_penyesuaian_terperinci()
    {
        $data_search = get_session('arr_filter_adjustment_report');

        $data["data_search"] = $data_search;
        $data["data_report"] = $this->m_bill_item->getPenyataPenyesuaianTerperinci( $data_search );
        $this->load->view('/report/v_print_penyata_penyesuaian_terperinci',$data);
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

        if(!empty($post))
        {
            if ( $post['date_end'] == "") 
            { 
                $post['date_end'] = date_display(timenow(),'d M Y'); 
            }

            $this->session->set_userdata('arr_filter_adjustment_report_ringkasan',$post);
            $data_search = $post;

            if ( $data_search['date_end'] == "") 
            { 
                $data_search['date_end'] = date_display(timenow(),'d M Y'); 
            }
        }
        else
        {
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['date_start']  = date_display(timenow(),'d M Y');
                $data_search['date_end']    = date_display(timenow(),'d M Y');
                $data_search['category_id'] = '';
                $data_search['status_acc']  = '';
                $data_search['type_id']     = '';
            endif;
        }

        $data['data_category']  = $this->m_category->get_a_category('','');
        $data['data_type']      = $this->m_type->get_a_type();
        $data_report = array();

        if($_POST)
        {
            $data_report = $this->m_bill_item->getPenyataPenyesuaianRingkasan( $data_search );
            $type_id = -1;

            if ( count($data_report) > 0 )
            {
                foreach ($data_report as $row) 
                {
                    if ( $type_id != $row["TYPE_NAME"] )
                    {
                        $type_id = $row["TYPE_NAME"];
                    }

                    $data_report_group[$type_id][] = $row;
                }
            }
            else
            {
                $data_report_group[""] = array();
            }
            
            $data['data_report']  = $data_report_group;
            $data['data_search']  = $data_search;
        }
        else
        {
            $data['data_report']  = array();
            $data['data_search']  = $data_search;
        }

        templates('/report/v_statement_adjustment_ringkasan',$data);
    }

    function print_penyata_penyesuaian_ringkasan()
    {
        $data_search = get_session('arr_filter_adjustment_report_ringkasan');

        $data["data_search"] = $data_search;
        $data_report = $this->m_bill_item->getPenyataPenyesuaianRingkasan( $data_search );
        $type_id = -1;

        if ( count($data_report) > 0 )
        {
            foreach ($data_report as $row) 
            {
                if ( $type_id != $row["TYPE_NAME"] )
                {
                    $type_id = $row["TYPE_NAME"];
                }

                $data_report_group[$type_id][] = $row;
            }
        }
        else
        {
            $data_report_group[""] = array();
        }

        $data['data_report']  = $data_report_group;
        $data['data_search']  = $data_search;

        $this->load->view('/report/v_print_penyata_penyesuaian_ringkasan',$data);
    }

    function payment(){
        $this->auth->restrict_access($this->curuser,array(8010));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Pembayaran Penyewa';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Pembayaran Penyewa';

        $search_segment = uri_segment(3);
        $filter_session = get_session('arr_filter_payment');

        if($_POST)
        {
            // Set variable $data_Search [START]
            if ( $_POST["date_start"] == "" )
            {
                $_POST["date_start"] = date('d M Y');
            }

            if ( $_POST["date_end"] == "" )
            {
                $_POST['date_end']   = date('d M Y');
            }

            $this->session->set_userdata('arr_filter_payment',$_POST);

            $data_search = $_POST;
            
            // Set variable $data_Search [END]



            if ($_POST["type_id"] == "")
            {
                $data_search["type_name"] = "Semua";
                $list_of_category = $this->m_category->get_a_category_all();
            }
            else if ($_POST["type_id"] != "")
            {
                $data_search["type_name"] = $this->m_type->get_a_type_details($_POST["type_id"])["TYPE_NAME"];
            }

            if ($data_search["category_id"] == "")
            {
                $data_search["category_name"] = "Semua";
            }
            else if ($data_search["category_id"] != "0")
            {
                $data_search["category_name"] = $this->m_category->get_a_category_details($data_search["category_id"])["CATEGORY_NAME"];
            }

            if ($_POST["acc_status"] == "")
            {
                $data_search["status_type"] = "Semua";
            }
            else if ($_POST["acc_status"] == "1")
            {
                $data_search["status_type"] = "Aktif";
            }
            else if ($_POST["acc_status"] == "2")
            {
                $data_search["status_type"] = "Tidak Aktif";
            }

            $data_report = array();

            $data_search_trans["date_start"]    =   DateTime::createFromFormat('d M Y', $data_search['date_start'])->format('d-m-Y');
            $data_search_trans["date_end"]      =   DateTime::createFromFormat('d M Y', $data_search['date_end'])->format('d-m-Y');
            $data_search_trans["category_id"]   =   $data_search["category_id"];
            $data_search_trans["acc_status"]    =   $data_search["acc_status"];

            $payment_record = $this->m_bill_item->getLaporanPembayaranPenyewa( $data_search_trans );

            if ( count($payment_record) > 0 )
            {
                foreach ($payment_record as $row) 
                {
                    $account_id = $row["ACCOUNT_ID"];
                    $category = $row["CATEGORY_NAME"]." - ".$row["CATEGORY_CODE"];
                    $data_report[$category][$account_id][] = $row;
                }
            }
            else
            {
                $data_report = array();
            }            

            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        }
        else
        {
            if( !empty($filter_session) )
            {
                if ( $filter_session["date_start"] == "" )
                {
                    $filter_session["date_start"] = date('d M Y');
                }

                if ( $filter_session["date_end"] == "" )
                {
                    $filter_session['date_end']   = date('d M Y');
                }
                $data_search = $filter_session;
            }
            else
            {
                $data_search['date_start']  = date('d M Y');
                $data_search['date_end']    = date('d M Y');
                $data_search['type_id']     = '';
                $data_search['category_id'] = '';
                $data_search['acc_status']  = '';
            }

            $data['data_report']    = array();
            $data['data_search']    = $data_search;
        }

        $data['data_type']   = $this->m_type->get_a_type();
        templates('/report/v_payment',$data);
    }

    function print_pembayaran_penyewa()
    {
        $data_search = get_session('arr_filter_payment');
        if ($data_search["type_id"] == "")
        {
            $data_search["type_name"] = "Semua";
            $list_of_category = $this->m_category->get_a_category_all();
        }
        else if ($data_search["type_id"] != "")
        {
            $data_search["type_name"] = $this->m_type->get_a_type_details($data_search["type_id"])["TYPE_NAME"];
        }

        if ($data_search["category_id"] == "")
        {
            $data_search["category_name"] = "Semua";
        }
        else if ($data_search["category_id"] != "0")
        {
            $data_search["category_name"] = $this->m_category->get_a_category_details($data_search["category_id"])["CATEGORY_NAME"];
        }

        if ($data_search["acc_status"] == "")
        {
            $data_search["status_type"] = "Semua";
        }
        else if ($data_search["acc_status"] == "1")
        {
            $data_search["status_type"] = "Aktif";
        }
        else if ($data_search["acc_status"] == "2")
        {
            $data_search["status_type"] = "Tidak Aktif";
        }

        $data_report = array();

        $data_search_trans["date_start"]    =   DateTime::createFromFormat('d M Y', $data_search['date_start'])->format('d-m-Y');
        $data_search_trans["date_end"]      =   DateTime::createFromFormat('d M Y', $data_search['date_end'])->format('d-m-Y');
        $data_search_trans["category_id"]   =   $data_search["category_id"];

        $payment_record = $this->m_bill_item->getLaporanPembayaranPenyewa( $data_search_trans );

        if ( count($payment_record) > 0 )
        {
            foreach ($payment_record as $row) 
            {
                $account_id = $row["ACCOUNT_ID"];
                $category = $row["CATEGORY_NAME"]." - ".$row["CATEGORY_CODE"];
                $data_report[$category][$account_id][] = $row;
            }
        }
        else
        {
            $data_report = array();
        }            

        $data['data_report']    = $data_report;
        $data['data_search']    = $data_search;

        $this->load->view('/report/v_print_pembayar_penyewa',$data);
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
                $data_search['journal_id']  = '';
            endif;
        endif;
        $data['data_code_category']   = $this->m_category->get_a_category_all('','');
        $data['data_code_journal']   = $this->m_journal->get_a_journal_all('');

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

    function print_journal()
    {
        $filter_session = get_session('arr_filter_journal');
        if( !empty($filter_session) )
        {
            $data_search = $filter_session;
            $data_report = $this->m_journal->get_lists_temp_journal_report($data_search);
            //echo last_query();

            if ( isset($data_search["date_start"]) && $data_search["date_start"] == "")
            {
                $data_search["date_start"] = date("d M Y");
            }

            if ( isset($data_search["date_end"]) && $data_search["date_end"] == "")
            {
                $data_search["date_end"] = date("d M Y");
            }
            
            $data['filter_session']    = $filter_session;
            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        }
        else
        {
            $data = array();
        }

        // templates('report/v_journal',$data);
        $this->load->view('/report/v_print_jurnal_sewaan',$data);
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
        $this->auth->restrict_access($this->curuser,array(8012));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Perjanjian Sewaan Hartanah';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Perjanjian Sewaan Hartanah';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_hartanah');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_hartanah',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $post["year"] = '2020';
                $data_search['year']  = '2020';
                $this->session->set_userdata('arr_filter_hartanah',$post);
            endif;
        endif;
        // pre($data_search);
        // die();
      
        if($_POST):

        $data_report = $this->m_acc_account->sewaan_hartanah($data_search);
        
            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_report']    = array();
            $data['data_search']    = $data_search;
        endif;
        
        templates('report/v_hartanah',$data);
    }

    function print_hartanah()
    {
        if ( isset($_GET["year"]) )
        {
            $year = $_GET["year"];
        }
        else
        {
            $year = date('Y');
        }

        $filter_session = get_session('arr_filter_hartanah');
        $data_search["year"] = $year;
        $data['filter_session']  = $filter_session;
        $data["hartanah_record"] = $this->m_acc_account->sewaan_hartanah($data_search);
        $this->load->view('/report/v_print_report_hartanah',$data);
    }

    function papaniklan(){
        $this->auth->restrict_access($this->curuser,array(8013));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Perjanjian Sewa Papan Iklan';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Perjanjian Sewa Papan Iklan';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_papaniklan');
        // var_dump($filter_session);
        // die();
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_papaniklan',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $post["year"] = '2020';
                $data_search['year']  = '2020';
                $this->session->set_userdata('arr_filter_papaniklan',$post);
            endif;
        endif;
        // pre($data_search);
        // die();
      
        if($_POST):

        $data_report = $this->m_acc_account->sewaan_papaniklan($data_search);

            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_report']    = array();
            $data['data_search']    = $data_search;
        endif;

        templates('report/v_papaniklan',$data);

    }

    function print_papaniklan()
    {
        $filter_session = get_session('arr_filter_papaniklan');
        if( !empty($filter_session) )
        {
            $data_search = $filter_session;
            $data_report = $this->m_acc_account->sewaan_papaniklan($data_search);
            //echo last_query();
            $data['filter_session']    = $filter_session;
            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        }
        else
        {
            $data = array();
        }

        // templates('report/v_journal',$data);
        $this->load->view('/report/v_print_report_papaniklan',$data);
    }

    function perjanjian_kutipan(){
        $this->auth->restrict_access($this->curuser,array(8014));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Perbandingan';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Perbandingan Jumlah Perjanjian & Kutipan';

        // $data['data'] = $this->m_acc_account->perjanjian_kutipan();

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_perjanjian');

        if( !empty($post) )
        {
            if ( $post["year"] == "" ){ $post["year"] = date('Y'); }
            if ( $post["year2"] == "" ){ $post["year2"] = date('Y'); }

            $this->session->set_userdata('arr_filter_perjanjian',$post);
            $data_search = $post;
        }
        else
        {
            if(!empty($filter_session))
            {
                if ( $filter_session["year"] == "" ){ $filter_session["year"] = date('Y'); }
                if ( $filter_session["year2"] == "" ){ $filter_session["year2"] = date('Y'); }

                $data_search = $filter_session;
            }
            else
            {
                $data_search['year']  = date('Y');
                $data_search['year2'] = date('Y');                
            }
            $this->session->set_userdata('arr_filter_perjanjian',$data_search);
        }
      
        if($_POST || ( isset($data_search['year']) && $data_search['year'] != "") )
        {
            if ( $data_search['year'] > $data_search['year2'] )
            {
                for ($i = $data_search['year'] ; $i >= $data_search['year2'] ; $i--) 
                {
                    # code...
                    $data_search_perjanjian["year"] = $i;
                    $data_kutipan["$i"] = $this->m_acc_account->count_perjanjian_kutipan($data_search_perjanjian);
                    $data_billboard["$i"] = $this->m_acc_account->count_perjanjian_kutipan_billboard($data_search_perjanjian);
                }
            }
            else
            {
                for ($i = $data_search['year'] ; $i <= $data_search['year2'] ; $i++) 
                {
                    # code...
                    $data_search_perjanjian["year"] = $i;
                    $data_kutipan["$i"] = $this->m_acc_account->count_perjanjian_kutipan($data_search_perjanjian);
                    $data_billboard["$i"] = $this->m_acc_account->count_perjanjian_kutipan_billboard($data_search_perjanjian);
                }
            }

            $data['data_kutipan']    = $data_kutipan;
            $data['data_billboard']    = $data_billboard;
            $data['data_search']    = $data_search;
        }
        else
        {
            $data['data_kutipan']   = array();
            $data['data_billboard'] = array();
            $data['data_search']    = $data_search;
        }

        templates('report/v_perjanjian',$data);
    }

    function print_perjanjian_kutipan()
    {
        $filter_session = get_session('arr_filter_perjanjian');
        if( !empty($filter_session) || ( isset($filter_session['year']) && $filter_session['year'] != "") )
        {
            $data_search = $filter_session;
            
            if ( $data_search['year'] > $data_search['year2'] )
            {
                $data["year_diff"] = $data_search['year'] - $data_search['year2'];

                for ($i = $data_search['year'] ; $i >= $data_search['year2'] ; $i--) 
                {
                    # code...
                    $data_search_perjanjian["year"] = $i;
                    $data_kutipan["$i"] = $this->m_acc_account->count_perjanjian_kutipan($data_search_perjanjian);
                    $data_billboard["$i"] = $this->m_acc_account->count_perjanjian_kutipan_billboard($data_search_perjanjian);
                }
            }
            else
            {
                $data["year_diff"] = $data_search['year2'] - $data_search['year'];

                for ($i = $data_search['year'] ; $i <= $data_search['year2'] ; $i++) 
                {
                    # code...
                    $data_search_perjanjian["year"] = $i;
                    $data_kutipan["$i"] = $this->m_acc_account->count_perjanjian_kutipan($data_search_perjanjian);
                    $data_billboard["$i"] = $this->m_acc_account->count_perjanjian_kutipan_billboard($data_search_perjanjian);
                }
            }

            $data['data_kutipan']       =   $data_kutipan;
            $data['data_billboard']     =   $data_billboard;
            $data['data_search']        =   $data_search;
            $data['filter_session']     =   $filter_session;
        }
        else
        {
            $data = array();
        }

        // templates('report/v_journal',$data);
        $this->load->view('/report/v_print_report_perjanjian_kutipan',$data);
    }

    function hasil(){
        $this->auth->restrict_access($this->curuser,array(8015));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Hasil Perjanjian Sewa';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Hasil Perjanjian Sewa';

        // $data['data'] = $this->m_acc_account->hasil_billboard();

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_hasil');
        // var_dump($filter_session);
        // die();
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_hasil',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $post["year"] = '2020';
                $data_search['year']  = '2020';
                $this->session->set_userdata('arr_filter_hasil',$post);
            endif;
        endif;
        // pre($data_search);
        // die();
      
        if($_POST):

        $data_report= $this->m_acc_account->hasil($data_search);

            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_report']    = array();
            $data['data_search']    = $data_search;
        endif;

        templates('report/v_hasil',$data);
    }

    function print_hasil()
    {
        $filter_session = get_session('arr_filter_hasil');
        if( !empty($filter_session) )
        {
            $data_search = $filter_session;
            $data_report = $this->m_acc_account->hasil($data_search);
            //echo last_query();
            $data['filter_session']    = $filter_session;
            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        }
        else
        {
            $data = array();
        }

        // templates('report/v_journal',$data);
        $this->load->view('/report/v_print_report_hasil',$data);
    }

    function kutipan_tunggakan(){
        $this->auth->restrict_access($this->curuser,array(8017));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Kutipan Tunggakan Sewaan';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Kutipan Tunggakan Sewaan';

        $search_segment = uri_segment(3);

        $post           = $this->input->post();

        if(!empty($post))
        {
            $data_search = $post;
        }
        else
        {
            $data_search['selectedYear']  = "";
        }

        if($_POST):

            $data['data_search']    = $data_search;

            // Get data lod by Month within selected year

            $dataMonthLod['Jan'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 1 ) );
            $dataMonthLod['Feb'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 2 ) );
            $dataMonthLod['Mac'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 3 ) );
            $dataMonthLod['Apr'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 4 ) );
            $dataMonthLod['May'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 5 ) );
            $dataMonthLod['Jun'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 6 ) );
            $dataMonthLod['Jul'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 7 ) );
            $dataMonthLod['Aug'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 8 ) );
            $dataMonthLod['Sep'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 9 ) );
            $dataMonthLod['Oct'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 10 ) );
            $dataMonthLod['Nov'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 11 ) );
            $dataMonthLod['Dec'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 12 ) );

            $data['data_month_lod'] = $dataMonthLod;

            // Get data lod by Month within selected year

            $dataMonthMahkamah['Jan'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 1 ) );
            $dataMonthMahkamah['Feb'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 2 ) );
            $dataMonthMahkamah['Mac'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 3 ) );
            $dataMonthMahkamah['Apr'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 4 ) );
            $dataMonthMahkamah['May'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 5 ) );
            $dataMonthMahkamah['Jun'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 6 ) );
            $dataMonthMahkamah['Jul'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 7 ) );
            $dataMonthMahkamah['Aug'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 8 ) );
            $dataMonthMahkamah['Sep'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 9 ) );
            $dataMonthMahkamah['Oct'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 10 ) );
            $dataMonthMahkamah['Nov'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 11 ) );
            $dataMonthMahkamah['Dec'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 12 ) );

            $data['data_month_mah'] = $dataMonthMahkamah;

            // echo "<pre>";
            // var_dump($data['data_month']);
            // die();

        else:
            $data['data_report']    = array();
            $data['data_search']    = $data_search;
        endif;

        templates('/report/v_kutipan_tunggakan_undang',$data);
    }

    function print_kutipan_tunggakan()
    {
        if ( isset($_GET["key"]) )
        {
            $year = $_GET["key"];
        }
        else
        {
            $year = 0;
        }

        if ($year > 0 && isset($year))
        {
            $data_search['selectedYear']  = $year;

            $dataMonthLod['Jan'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 1 ) );
            $dataMonthLod['Feb'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 2 ) );
            $dataMonthLod['Mac'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 3 ) );
            $dataMonthLod['Apr'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 4 ) );
            $dataMonthLod['May'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 5 ) );
            $dataMonthLod['Jun'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 6 ) );
            $dataMonthLod['Jul'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 7 ) );
            $dataMonthLod['Aug'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 8 ) );
            $dataMonthLod['Sep'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 9 ) );
            $dataMonthLod['Oct'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 10 ) );
            $dataMonthLod['Nov'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 11 ) );
            $dataMonthLod['Dec'] = $this->m_lod_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 12 ) );

            $data['data_month_lod'] = $dataMonthLod;

            // Get data lod by Month within selected year

            $dataMonthMahkamah['Jan'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 1 ) );
            $dataMonthMahkamah['Feb'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 2 ) );
            $dataMonthMahkamah['Mac'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 3 ) );
            $dataMonthMahkamah['Apr'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 4 ) );
            $dataMonthMahkamah['May'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 5 ) );
            $dataMonthMahkamah['Jun'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 6 ) );
            $dataMonthMahkamah['Jul'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 7 ) );
            $dataMonthMahkamah['Aug'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 8 ) );
            $dataMonthMahkamah['Sep'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 9 ) );
            $dataMonthMahkamah['Oct'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 10 ) );
            $dataMonthMahkamah['Nov'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 11 ) );
            $dataMonthMahkamah['Dec'] = $this->m_notis_mah_generation->get_monthly_record_generation( array( "YEAR" => $data_search['selectedYear'], "MONTH" => 12 ) );

            $data['data_month_mah'] = $dataMonthMahkamah;
            $data['data_search'] = $data_search;

            // templates('/report/v_print_kutipan_tunggakan_undang',$data);
            $this->load->view('/report/v_print_kutipan_tunggakan_undang',$data);
        }
    }

    function laporan_iso(){
        $this->auth->restrict_access($this->curuser,array(8016));
        $data['link_1']     = 'Laporan';
        $data['link_2']     = 'Pencapaian Objektif Kualiti';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Laporan Pencapaian Objektif Kualiti';

        // $data['data'] = $this->m_acc_account->hasil_billboard();

        $search_segment = uri_segment(3);

        $post           = $this->input->post();
        $filter_session = get_session('arr_filter_iso');
        if(!empty($post)):
            $this->session->set_userdata('arr_filter_iso',$post);
            $data_search = $post;
        else:
            if(!empty($filter_session)):
                $data_search = $filter_session;
            else:
                $data_search['year']  = '';
            endif;
        endif;
       
      
        if($_POST):

        $data_report= $this->m_acc_account->m_acc_account->iso($data_search);

            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        else:
            $data['data_report']    = array();
            $data['data_search']    = $data_search;
        endif;

        templates('report/v_laporan_iso',$data);
    }

    function print_iso()
    {
        $filter_session = get_session('arr_filter_iso');
        if( !empty($filter_session) )
        {
            $data_search = $filter_session;
            $data_report = $this->m_acc_account->iso($data_search);
            //echo last_query();
            $data['filter_session']    = $filter_session;
            $data['data_report']    = $data_report;
            $data['data_search']    = $data_search;
        }
        else
        {
            $data = array();
        }

        // templates('report/v_journal',$data);
        $this->load->view('/report/v_print_report_iso',$data);
    }
}
/* End of file modules/login/controllers/report.php */

