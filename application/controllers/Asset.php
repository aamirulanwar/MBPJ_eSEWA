<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Asset extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->loginonly($this->curuser);
        load_model('Asset/M_a_type', 'm_a_type');
        load_model('Asset/M_a_location', 'm_a_location');
        load_model('Asset/M_a_tenant_type', 'm_a_tenant_type');
        load_model('Asset/M_a_rental_use', 'm_a_rental_use');
        load_model('Asset/M_a_category', 'm_a_category');
        load_model('Asset/M_a_asset', 'm_a_asset');
        load_model('TrCode/M_tran_code', 'm_tran_code');
        load_model('Account/M_acc_account', 'm_acc_account');
    }

    function _remap($method)
    {
        $array = array(
            'asset_type',
            'add_asset_type',
            'edit_asset_type',
            'asset_location',
            'add_asset_location',
            'edit_asset_location',
            'tenant_type',
            'add_asset_tenant_type',
            'edit_asset_tenant_type',
            'rental_use',
            'add_asset_rental_use',
            'edit_asset_rental_use',
            'add_category',
            'edit_category',
            'category',
            'asset_unit',
            'add_asset_unit',
            'edit_asset_unit',
            'delete_asset_type',
            'delete_asset_tenant_type',
            'delete_asset_rental_use',
            'delete_asset_unit',
            'delete_asset_category',
            'get_asset_status'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function category(){
        $this->auth->restrict_access($this->curuser,array(3005));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = 'Kod Kategori';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Kod Kategori';

        $data_input = $this->input->post();
        if(!empty($data_input)):
            $this->session->set_userdata('arr_filter_code_category',$this->input->post());
            $data_search = get_session('arr_filter_code_category');
        else:
            $arr_filter_user_session = get_session('arr_filter_code_category');
            if(!empty($arr_filter_user_session)):
                $data_search = get_session('arr_filter_code_category');
            else:
                $data_search['type_name'] = '';
            endif;
        endif;
        $data['data_search'] = $data_search;

        $search_segment = uri_segment(3);
        $total = $this->m_a_category->count_a_category($data_search);
        $links          = '/asset/category';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_a_category->get_a_category($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('asset/asset_category/v_asset_category',$data);
    }

    function edit_category(){
        $this->auth->restrict_access($this->curuser,array(3007));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/category">Kod Kategori</a>';
        $data['link_3']     = 'Kemaskini Kod Kategori ';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $get_details = $this->m_a_category->get_a_category_details($id);
        if(!$get_details):
            return false;
        endif;

        $data['data']           = $get_details;
        $data['asset_type']     = $this->m_a_type->get_a_type_active();
        $data['asset_location'] = $this->m_a_location->get_a_location_active();
        $data['code_trans']     = $this->m_tran_code->get_tr_code_list();

        validation_rules('type','<strong>jenis harta</strong>','required');
        validation_rules('location','<strong>lokasi</strong>','required');
        validation_rules('category_code','<strong>kod harta</strong>','required');
        validation_rules('category_name','<strong>nama harta</strong>','required');
        validation_rules('address','<strong>alamat</strong>');
        validation_rules('area','<strong>area</strong>');
        // validation_rules('total_unit','<strong>jumlah unit</strong>','integer');
        validation_rules('current_value');
        validation_rules('value_perunit','<strong>nilai perunit</strong>','');
        validation_rules('status','<strong>status</strong>');
        validation_rules('trans_code','<strong>kod transaksi</strong>','required');


        if(validation_run()==false):
            templates('/asset/asset_category/v_asset_category_edit',$data);
        else:
            $data_update['type_id']             = input_data('type');
            $data_update['location_id']         = input_data('location');
            $data_update['category_code']       = strtoupper(input_data('category_code'));
            $data_update['category_name']       = ucfirst(input_data('category_name'));
            $data_update['address']             = input_data('address');
            // $data_insert['area']                = input_data('area');
            // $data_update['total_unit']          = input_data('total_unit');
            // $data_update['total_available_unit']= 0;
            $data_update['current_value']       = currencyToDouble(input_data('current_value'));
            $data_update['value_perunit']       = currencyToDouble(input_data('value_perunit'));
            $data_update['RENTAL_FEE_DEFAULT']  = currencyToDouble(input_data('value_perunit'));
            $data_update['active']              = (input_data('status'))?STATUS_ACTIVE:STATUS_INACTIVE;

            $data_search['MCT_TRCODENEW'] = input_data('trans_code');
            $tr_code = $this->m_tran_code->get_tr_code($data_search);

            $data_update['TRCODE_CATEGORY']       = $tr_code['MCT_TRCODENEW'];
            $data_update['TRCODE_CATEGORY_OLD']   = $tr_code['MCT_TRCODE'];

            $update_status = $this->m_a_category->update_a_category($data_update,$id);

            $data_asset_update['rental_fee'] = currencyToDouble(input_data('value_perunit'));
            $this->m_a_asset->update_a_asset_by_category_id($data_asset_update,$id);

            if($update_status):
                set_notify('notify_msg',TEXT_UPDATE_RECORD);
            else:
                set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/edit_category/'.uri_segment(3));
        endif;
    }

    function add_category(){
        $this->auth->restrict_access($this->curuser,array(3006));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/category">Kod Kategori</a>';
        $data['link_3']     = 'Tambah Kod Kategori ';
        $data['pagetitle']  = '';

        $data['asset_type']     = $this->m_a_type->get_a_type_active();
        $data['asset_location'] = $this->m_a_location->get_a_location_active();
        $data['code_trans']     = $this->m_tran_code->get_tr_code_list();

        validation_rules('type','<strong>jenis harta</strong>','required');
        validation_rules('location','<strong>lokasi</strong>','required');
        validation_rules('category_code','<strong>kod harta</strong>','required');
        validation_rules('category_name','<strong>nama harta</strong>','required');
        validation_rules('address','<strong>alamat</strong>');
        validation_rules('area','<strong>area</strong>');
        // validation_rules('total_unit','<strong>jumlah unit</strong>','integer');
        validation_rules('current_value');
        validation_rules('value_perunit','<strong>harga sewaan</strong>','required');
        validation_rules('trans_code','<strong>kod transaksi</strong>','required');

        if(validation_run()==false):
            templates('/asset/asset_category/v_asset_category_add',$data);
        else:
            $data_insert['type_id']             = input_data('type');
            $data_insert['location_id']         = input_data('location');
            $data_insert['category_code']          = strtoupper(input_data('category_code'));
            $data_insert['category_name']          = ucfirst(input_data('category_name'));
            $data_insert['address']             = input_data('address');
           // $data_insert['area']                = input_data('area');
           // $data_insert['total_unit']          = input_data('total_unit');
            $data_insert['total_available_unit']    = 0;
            $data_insert['current_value']       = currencyToDouble(input_data('current_value'));
            $data_insert['value_perunit']       = currencyToDouble(input_data('value_perunit'));
            $data_insert['RENTAL_FEE_DEFAULT']  = currencyToDouble(input_data('value_perunit'));

            $data_search['MCT_TRCODENEW'] = input_data('trans_code');
            $tr_code = $this->m_tran_code->get_tr_code($data_search);

            $data_insert['TRCODE_CATEGORY']       = $tr_code['MCT_TRCODENEW'];
            $data_insert['TRCODE_CATEGORY_OLD']   = $tr_code['MCT_TRCODE'];

            $insert_id = $this->m_a_category->insert_a_category($data_insert);

            if(is_numeric($insert_id)):
                set_notify('notify_msg',TEXT_SAVE_RECORD);
            else:
                set_notify('notify_msg',TEXT_SAVE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/add_category');
        endif;
    }

    function add_asset_type(){
        $this->auth->restrict_access($this->curuser,array(3002));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/asset_type">Jenis Harta</a>';
        $data['link_3']     = 'Tambah Jenis Harta';
        $data['pagetitle']  = '';

        validation_rules('name_asset_type','<strong>nama jenis harta</strong>','required');

        if(validation_run()==false):
            templates('/asset/asset_type/v_asset_type_add',$data);
        else:
            $data_insert['type_name']     = ucfirst(input_data('name_asset_type'));

            $insert_id = $this->m_a_type->insert_a_type($data_insert);
            if(is_numeric($insert_id)):
                set_notify('notify_msg',TEXT_SAVE_RECORD);
            else:
                set_notify('notify_msg',TEXT_SAVE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/add_asset_type/');
        endif;
    }

    function asset_type(){
        $this->auth->restrict_access($this->curuser,array(3001));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = 'Jenis Harta';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Jenis Harta';

        $search_segment = uri_segment(3);
        $total = $this->m_a_type->count_a_type();
        $links          = '/asset/asset_type';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list      = $this->m_a_type->get_a_type($per_page,$search_segment);
        $new_data_list  = array();
        if($data_list):
            foreach ($data_list as $row):
                $data_search['type_id'] = $row['TYPE_ID'];
                $count_code_category = $this->m_a_category->count_a_category($data_search);
                $row['count_code_category'] = $count_code_category;
                $new_data_list[] = $row;
            endforeach;
        endif;

        $data['total_result']   = $total;
        $data['data_list']      = $new_data_list;

        templates('asset/asset_type/v_asset_type',$data);
    }

    function edit_asset_type(){
        $this->auth->restrict_access($this->curuser,array(3003));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/asset_type">Jenis Harta</a>';
        $data['link_3']     = 'Kemaskini Jenis Harta';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $get_details = $this->m_a_type->get_a_type_details($id);
        if(!$get_details):
            return false;
        endif;

        $data['data'] = $get_details;

        validation_rules('name_asset_type','<strong>Nama jenis harta</strong>','required');
        validation_rules('status','<strong>status</strong>');

        if(validation_run()==false):
            templates('/asset/asset_type/v_asset_type_edit',$data);
        else:
            $data_update['type_name']   = ucfirst(input_data('name_asset_type'));
            $data_update['active']      = (input_data('status'))?STATUS_ACTIVE:STATUS_INACTIVE;

            $update_status = $this->m_a_type->update_asset_type($data_update,$id);
            if($update_status):
                set_notify('notify_msg',TEXT_UPDATE_RECORD);
            else:
                set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/edit_asset_type/'.urlEncrypt($id));
        endif;
    }

    function asset_location(){
        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = 'Kawasan';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Kawasan';

        $search_segment = uri_segment(3);
        $total = $this->m_a_location->count_a_location();
        $links          = '/asset/asset_location';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_a_location->get_a_location($per_page,$search_segment);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('asset/asset_location/v_asset_location',$data);
    }

    function add_asset_location(){
        // $this->auth->restrict_access($this->curuser,array(2002));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/asset_location">Kawasan</a>';
        $data['link_3']     = 'Tambah Kawasan';
        $data['pagetitle']  = '';

        validation_rules('name_asset_location','<strong>nama kawasan</strong>','required');

        if(validation_run()==false):
            templates('/asset/asset_location/v_asset_location_add',$data);
        else:
            $data_insert['location_name']     = ucfirst(input_data('name_asset_location'));

            $insert_id = $this->m_a_location->insert_a_location($data_insert);
            if(is_numeric($insert_id)):
                set_notify('notify_msg',TEXT_SAVE_RECORD);
            else:
                set_notify('notify_msg',TEXT_SAVE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/add_asset_location');
        endif;
    }

    function tenant_type(){
        $this->auth->restrict_access($this->curuser,array(3017));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = 'Jenis Penyewa';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Jenis Penyewa';

        $search_segment = uri_segment(3);
        $total = $this->m_a_tenant_type->count_a_tenant_type();
        $links          = '/asset/tenant_type';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_a_tenant_type->get_a_tenant_type($per_page,$search_segment);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('asset/asset_tenant_type/v_asset_tenant_type',$data);
    }

    function add_asset_tenant_type(){
        $this->auth->restrict_access($this->curuser,array(3018));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/asset_tenant_type">Jenis Penyewa</a>';
        $data['link_3']     = 'Tambah Jenis Penyewa';
        $data['pagetitle']  = '';

        validation_rules('name_asset_tenant_type','<strong>jenis penyewa</strong>','required');

        if(validation_run()==false):
            templates('/asset/asset_tenant_type/v_asset_tenant_type_add',$data);
        else:
            $data_insert['tenant_type_name']     = ucfirst(input_data('name_asset_tenant_type'));

            $insert_id = $this->m_a_tenant_type->insert_a_tenant_type($data_insert);
            if(is_numeric($insert_id)):
                set_notify('notify_msg',TEXT_SAVE_RECORD);
            else:
                set_notify('notify_msg',TEXT_SAVE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/add_asset_tenant_type');
        endif;
    }

    function edit_asset_tenant_type(){
        $this->auth->restrict_access($this->curuser,array(3019));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/tenant_type">Jenis Penyewa</a>';
        $data['link_3']     = 'Kemaskini Jenis Penyewa';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $get_details = $this->m_a_tenant_type->get_a_tenant_type_details($id);
        if(!$get_details):
            return false;
        endif;

        $data['data'] = $get_details;

        validation_rules('name_asset_tenant_type','<strong>Nama jenis penyewa</strong>','required');
        validation_rules('status','<strong>status</strong>');

        if(validation_run()==false):
            templates('/asset/asset_tenant_type/v_asset_tenant_type_edit',$data);
        else:
            $data_update['tenant_type_name']   = ucfirst(input_data('name_asset_tenant_type'));
            $data_update['active']          = (input_data('status'))?STATUS_ACTIVE:STATUS_INACTIVE;

            $update_status = $this->m_a_tenant_type->update_a_tenant_type($data_update,$id);
            if($update_status):
                set_notify('notify_msg',TEXT_UPDATE_RECORD);
            else:
                set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/edit_asset_tenant_type/'.urlEncrypt($id));
        endif;
    }

    function rental_use(){
        $this->auth->restrict_access($this->curuser,array(3013));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = 'Kegunaan Sewaan';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Kegunaan Sewaan';

        $search_segment = uri_segment(3);
        $total = $this->m_a_rental_use->count_a_rental_use();
        $links          = '/asset/rental_use';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_a_rental_use->get_a_rental_use($per_page,$search_segment);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('asset/asset_rental_use/v_asset_rental_use',$data);
    }

    function add_asset_rental_use(){
        $this->auth->restrict_access($this->curuser,array(3014));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/rental_use">Kegunaan Sewaan</a>';
        $data['link_3']     = 'Tambah Kegunaan Sewaan';
        $data['pagetitle']  = '';

        validation_rules('name_asset_rental_use','<strong>nama kegunaan sewaan</strong>','required');
        validation_rules('code_asset_rental_use','<strong>kod kegunaan sewaan</strong>','required');

        if(validation_run()==false):
            templates('/asset/asset_rental_use/v_asset_rental_use_add',$data);
        else:
            $data_insert['rental_use_name']     = ucfirst(input_data('name_asset_rental_use'));
            $data_insert['rental_use_code']     = strtoupper(input_data('code_asset_rental_use'));

            $insert_id = $this->m_a_rental_use->insert_a_rental_use($data_insert);
            if(is_numeric($insert_id)):
                set_notify('notify_msg',TEXT_SAVE_RECORD);
            else:
                set_notify('notify_msg',TEXT_SAVE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/add_asset_rental_use');
        endif;
    }

    function edit_asset_rental_use(){
        $this->auth->restrict_access($this->curuser,array(3015));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/rental_use">Kegunaan Sewaan</a>';
        $data['link_3']     = 'Kemaskini Kegunaan Sewaan';
        $data['pagetitle']  = '';

        $id = urlDecrypt(uri_segment(3));
        if(!is_numeric($id)):
            return false;
        endif;

        $get_details = $this->m_a_rental_use->get_a_rental_use_details($id);
        if(!$get_details):
            return false;
        endif;

        $data['data'] = $get_details;

        validation_rules('name_asset_rental_use','<strong>nama kegunaan sewaan</strong>','required');
        validation_rules('code_asset_rental_use','<strong>kod kegunaan sewaan</strong>','required');
        validation_rules('status','<strong>status</strong>');

        if(validation_run()==false):
            templates('/asset/asset_rental_use/v_asset_rental_use_edit',$data);
        else:
            $data_update['rental_use_name']   = ucfirst(input_data('name_asset_rental_use'));
            $data_update['rental_use_code']   = strtoupper(input_data('code_asset_rental_use'));
            $data_update['active']          = (input_data('status'))?STATUS_ACTIVE:STATUS_INACTIVE;

            $update_status = $this->m_a_rental_use->update_a_rental_use($data_update,$id);
            if($update_status):
                set_notify('notify_msg',TEXT_UPDATE_RECORD);
            else:
                set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/edit_asset_rental_use/'.urlEncrypt($id));
        endif;
    }

    function asset_unit(){
        $this->auth->restrict_access($this->curuser,array(3009));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = 'Kod Harta';
        $data['link_3']     = '';
        $data['pagetitle']  = 'Senarai Kod Harta';

        $data_input = $this->input->post();
        if(!empty($data_input)):
            $this->session->set_userdata('arr_filter_asset_unit',$this->input->post());
            $data_search = get_session('arr_filter_asset_unit');
        else:
            $arr_filter_user_session = get_session('arr_filter_asset_unit');
            if(!empty($arr_filter_user_session)):
                $data_search = get_session('arr_filter_asset_unit');
            else:
                $data_search['search'] = '';
            endif;
        endif;
        $data['data_search'] = $data_search;

        $search_segment = uri_segment(3);
        $total = $this->m_a_asset->count_a_asset($data_search);
        $links          = '/asset/asset_unit';
        $uri_segment    = 3;
        $per_page       = 20;
        paging_config($links,$total,$per_page,$uri_segment);

        $data_list              = $this->m_a_asset->get_a_asset($per_page,$search_segment,$data_search);
        $data['total_result']   = $total;
        $data['data_list']      = $data_list;

        templates('asset/asset_unit/v_asset_unit',$data);
    }

    function add_asset_unit(){
        $this->auth->restrict_access($this->curuser,array(3010));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/asset_unit">Kod Harta</a>';
        $data['link_3']     = 'Tambah Kod Harta';
        $data['pagetitle']  = '';

        $data['category']   = $this->m_a_category->get_a_category_active();

        validation_rules('name_asset','<strong>nama kod harta</strong>','required');
        validation_rules('category_id','<strong>kod kategori</strong>','required');
        validation_rules('address','<strong>alamat</strong>');

        if(validation_run()==false):
            templates('/asset/asset_unit/v_asset_unit_add',$data);
        else:
            $data_insert['asset_name']      = ucfirst(input_data('name_asset'));
            $data_insert['category_id']     = input_data('category_id');
            $data_insert['asset_add']       = input_data('address');

            $insert_id = $this->m_a_asset->insert_a_asset($data_insert);

            #updat_available_unit
            $data_search_asset["category_id"]   =   $data_insert['category_id'];
            $total_asset_under_category         =   $this->m_a_asset->count_a_asset( $data_search_asset );

            $data_search_asset["rental_status"] =   "0";
            $total_asset_under_category_rented  =   $this->m_a_asset->count_a_asset( $data_search_asset );

            $data_update_category['TOTAL_UNIT']             =   $total_asset_under_category;
            $data_update_category['TOTAL_AVAILABLE_UNIT']   =   $total_asset_under_category_rented;

            $this->m_a_category->update_a_category($data_update_category,$data_insert['category_id']);

            if(is_numeric($insert_id)):
                set_notify('notify_msg',TEXT_SAVE_RECORD);
            else:
                set_notify('notify_msg',TEXT_SAVE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/add_asset_unit/');
        endif;
    }

    function edit_asset_unit(){
        $this->auth->restrict_access($this->curuser,array(3011));

        $data['link_1']     = 'Kod Fail';
        $data['link_2']     = '<a href="/asset/asset_unit">Kod Harta</a>';
        $data['link_3']     = 'Kemaskini Kod Harta';
        $data['pagetitle']  = '';

        $asset_id = urlDecrypt(uri_segment(3));
        if(!is_numeric($asset_id)):
            return false;
        endif;

        $get_details = $this->m_a_asset->get_a_asset_by_id($asset_id);
        if(!$get_details):
            return false;
        endif;

        $data['category']   = $this->m_a_category->get_a_category_active();
        $data['data']       = $get_details;

        validation_rules('name_asset','<strong>nama kod harta</strong>','required');
        validation_rules('category_id','<strong>kod kategori</strong>','required');
        validation_rules('harga_sewaan','<strong>harga_sewaan</strong>','');
        validation_rules('address','<strong>alamat</strong>');

        if(validation_run()==false):
            templates('/asset/asset_unit/v_asset_unit_edit',$data);
        else:
            $data_update['asset_name']      = ucfirst(input_data('name_asset'));
            $data_update['category_id']     = input_data('category_id');
            $data_update['active']          = (input_data('status'))?STATUS_ACTIVE:STATUS_INACTIVE;
            $data_update['RENTAL_FEE']      = currencyToDouble(input_data('harga_sewaan'));
            $data_update['asset_add']       = input_data('address');

            $result_update = $this->m_a_asset->update_a_asset($data_update,$asset_id);

            $data_account_update['estimation_rental_charge'] = currencyToDouble(input_data('harga_sewaan')); 
            $data_account_update['rental_charge'] = currencyToDouble(input_data('harga_sewaan'));
            $this->m_acc_account->update_a_acc_account_by_asset_id($data_account_update,$asset_id);

            if($result_update):
                #updat_available_unit
                $data_search_asset["category_id"]   =   $data_update['category_id'];
                $total_asset_under_category         =   $this->m_a_asset->count_a_asset( $data_search_asset );

                $data_search_asset["rental_status"] =   "0";
                $total_asset_under_category_rented  =   $this->m_a_asset->count_a_asset( $data_search_asset );

                $data_update_category['TOTAL_UNIT']             =   $total_asset_under_category;
                $data_update_category['TOTAL_AVAILABLE_UNIT']   =   $total_asset_under_category_rented;

                $this->m_a_category->update_a_category($data_update_category,$data_update['category_id']);

                set_notify('notify_msg',TEXT_UPDATE_RECORD);
            else:
                set_notify('notify_msg',TEXT_UPDATE_UNSUCCESSFUL,2);
            endif;
            redirect('/asset/edit_asset_unit/'.uri_segment(3));
        endif;
    }

    function delete_asset_type()
    {
        if(is_ajax())
        {
            $delete_id = input_data('delete_id');
            $data_update['soft_delete'] = SOFT_DELETE_TRUE;
            $delete = $this->m_a_type->update_asset_type($data_update,$delete_id);
            if($delete)
            {
                set_notify('user',TEXT_DELETE_RECORD,1);
                echo TEXT_DELETE_RECORD;
            }
            else
            {
                set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                echo TEXT_DELETE_UNSUCCESSFUL;
            }
        }
    }

    function delete_asset_tenant_type(){
        if(is_ajax()):
            $delete_id = input_data('delete_id');
            $data_update['soft_delete'] = SOFT_DELETE_TRUE;
            $delete = $this->m_a_tenant_type->update_a_tenant_type($data_update,$delete_id);
            if($delete):
                set_notify('user',TEXT_DELETE_RECORD,1);
                echo TEXT_DELETE_RECORD;
            else:
                set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                echo TEXT_DELETE_UNSUCCESSFUL;
            endif;
        endif;
    }

    function delete_asset_rental_use(){
        if(is_ajax()):
            $delete_id = input_data('delete_id');
            $data_update['soft_delete'] = SOFT_DELETE_TRUE;
            $delete = $this->m_a_rental_use->update_a_rental_use($data_update,$delete_id);
            if($delete):
                set_notify('user',TEXT_DELETE_RECORD,1);
                echo TEXT_DELETE_RECORD;
            else:
                set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                echo TEXT_DELETE_UNSUCCESSFUL;
            endif;
        endif;
    }

    function delete_asset_unit()
    {
        if(is_ajax())
        {
            $delete_id = input_data('delete_id');
            $data_update['rental_status'] = "0";
            $data_update['soft_delete'] = SOFT_DELETE_TRUE;
            $category_id = $this->m_a_asset->get_a_asset_by_id($delete_id)["CATEGORY_ID"];
            $delete = $this->m_a_asset->update_a_asset($data_update,$delete_id);

            if($delete)
            {
                #updat_available_unit
                $data_search_asset["category_id"]      =   $category_id;
                $total_asset_under_category            =   $this->m_a_asset->count_a_asset( $data_search_asset );

                $data_search_asset["rental_status"]    =   "0";
                $total_asset_under_category_rented     =   $this->m_a_asset->count_a_asset( $data_search_asset );

                $data_update_category['TOTAL_UNIT']             =   $total_asset_under_category;
                $data_update_category['TOTAL_AVAILABLE_UNIT']   =   $total_asset_under_category_rented;

                $this->m_a_category->update_a_category($data_update_category,$category_id);

                set_notify('user',TEXT_DELETE_RECORD,1);
                echo TEXT_DELETE_RECORD;
            }
            else
            {
                set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                echo TEXT_DELETE_UNSUCCESSFUL;
            }
        }
    }

    function delete_asset_category(){
        if(is_ajax()):
            $delete_id = input_data('delete_id');

            $data_update['soft_delete'] = SOFT_DELETE_TRUE;
            $delete = $this->m_a_category->update_a_category($data_update,$delete_id);
            if($delete):
                set_notify('user',TEXT_DELETE_RECORD,1);
                echo TEXT_DELETE_RECORD;
            else:
                set_notify('user',TEXT_DELETE_UNSUCCESSFUL,2);
                echo TEXT_DELETE_UNSUCCESSFUL;
            endif;
        endif;
    }

    function get_asset_status()
    {
        if ( isset( $_POST["asset_id"] ) )
        {
            $asset_id = $_POST["asset_id"];
            $status = $this->m_a_asset->get_a_asset_by_id($asset_id)["RENTAL_STATUS"];
            if ($status == 1)
            {
                $status_display = "Asset telah disewakan";
            }
        }
        else
        {
            $status_display = false;
        }
        
        echo json_encode($status_display);
    }
}