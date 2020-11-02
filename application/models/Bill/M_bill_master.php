<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_bill_master extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_bill_master($data_insert)
    {
        db_set_date_time('dt_added',timenow());
        db_insert('b_master',$data_insert);
        return get_insert_id('b_master');
    }

    function get($data_search)
    {
        if ( isset($data_search["CUSTOM_COLUMN"]) && $data_search["CUSTOM_COLUMN"] != "" )
        {
            db_select( $data_search["CUSTOM_COLUMN"] );
        }
        else
        {
            db_select('B_MASTER.*');
            db_select("to_char(dt_added, 'yyyy-mm-dd') as dt_added",false);
        }
                
        db_from('b_master');

        if ( isset($data_search["BILL_ID"]) && $data_search["BILL_ID"] != "" )
        {
            db_where('BILL_ID',$data_search['BILL_ID']);
        }

        if ( isset($data_search["ACCOUNT_ID"]) && $data_search["ACCOUNT_ID"] != "" )
        {
            db_where('ACCOUNT_ID',$data_search['ACCOUNT_ID']);
        }

        if ( isset($data_search["BILL_MONTH"]) && $data_search["BILL_MONTH"] != "" )
        {
            db_where('BILL_MONTH',$data_search['BILL_MONTH']);
        }
        
        if ( isset($data_search["BILL_YEAR"]) && $data_search["BILL_YEAR"] != "" )
        {
            db_where('BILL_YEAR',$data_search['BILL_YEAR']);
        }
        
        if ( isset($data_search["BILL_TYPE"]) && $data_search["BILL_TYPE"] != "" )
        {
            db_where('BILL_TYPE',$data_search['BILL_TYPE']);
        }
        
        if ( isset($data_search["BILL_CATEGORY"]) && $data_search["BILL_CATEGORY"] != "" )
        {
            db_where('BILL_CATEGORY',$data_search['BILL_CATEGORY']);
        }

        if ( isset($data_search["BILL_MONTH_FIRST"]) && $data_search["BILL_MONTH_FIRST"] != "" && isset($data_search["BILL_MONTH_LAST"]) && $data_search["BILL_MONTH_LAST"] != "" )
        {
            db_where(" BILL_MONTH between ".$data_search["BILL_MONTH_FIRST"]." and ".$data_search["BILL_MONTH_LAST"]." " );
        }

        if ( isset($data_search["BILL_YEAR_FIRST"]) && $data_search["BILL_YEAR_FIRST"] != "" && isset($data_search["BILL_YEAR_LAST"]) && $data_search["BILL_YEAR_LAST"] != "" )
        {
            db_where(" BILL_YEAR between ".$data_search["BILL_YEAR_FIRST"]." and ".$data_search["BILL_YEAR_LAST"]." " );
        }

        if ( isset($data_search["CUSTOM_DT_ADDED"]) && $data_search["CUSTOM_DT_ADDED"] != "" )
        {
            db_where("to_char(dt_added,'dd/mm/yyyy') = '".$data_search["CUSTOM_DT_ADDED"]."'" );
        }


        $sql = db_get('');

        if($sql)
        {
            return $sql->result_array();
        }
    }

    function get_bill_history_list($per_page,$search_segment,$data_search=array(),$bill_category=array('B','R'))
    {
        db_select('m.*');
        db_select('a.*');
        db_select("to_char(m.dt_added, 'yyyy-mm-dd') as dt_added",false);
        db_from('b_master m');
        db_join('acc_account a','a.account_id=m.account_id');
        db_in('BILL_CATEGORY', $bill_category);
        // Produces: OR bill category IN ('B', 'R') without J
        // db_where('m.bill_category','B''R');
        if(isset($data_search['account_id']) && having_value($data_search['account_id'])):
            db_where('m.account_id',$data_search['account_id']);
        endif;
        if(isset($data_search['bill_category']) && having_value($data_search['bill_category'])):
            db_where('m.bill_category',$data_search['bill_category']);
        endif;
        if(isset($data_search['bill_number']) && having_value($data_search['bill_number'])):
            db_where('m.bill_number',$data_search['bill_number']);
        endif;
        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("m.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;
        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("m.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;
        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
            db_where("a.account_number like '%".$data_search['account_number']."%'");
        endif;
        db_order('bill_year','desc');
        db_order('bill_month','desc');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function count_bill_history ($data_search=array(),$bill_category=array('B','R'))
    {
        db_select('*');
        db_in('BILL_CATEGORY', $bill_category);
        if(isset($data_search['account_id']) && having_value($data_search['account_id'])):
            db_where('m.account_id',$data_search['account_id']);
        endif;
        if(isset($data_search['bill_category']) && having_value($data_search['bill_category'])):
            db_where('bill_category',$data_search['bill_category']);
        endif;
        if(isset($data_search['bill_number']) && having_value($data_search['bill_number'])):
            db_where('m.bill_number',$data_search['bill_number']);
        endif;
        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("m.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;
        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("m.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;
        if(isset($data_search['account_number']) && having_value($data_search['account_number'])):
            db_where("a.account_number",$data_search['account_number']);
        endif;
        db_join('acc_account a','a.account_id=m.account_id');
        db_from('b_master m');
        return db_count_results();
    }

    function get_bill_master($id)
    {
        db_select('m.*,a.*');
        db_select("to_char(m.dt_added, 'yyyy-mm-dd') as dt_added",false);
        db_join('acc_account a','a.account_id=m.account_id');
        db_from('b_master m');
        db_where('m.bill_id',$id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function get_highest_oustanding_bill($per_page,$search_segment,$id,$data_search=array())
    {
        db_select('*');
        db_from('b_master m');
        db_join('acc_account a','a.account_id=m.account_id');
        db_join('acc_user u','u.user_id=a.user_id');
        db_join('a_category c','c.category_id=a.category_id');
        db_where('m.bill_category','B');
        db_where('m.bill_month',1);
        db_where('m.bill_year',2019);
        db_order('m.total_amount','desc');
        $sql = db_get('',$per_page,$search_segment);
        if($sql):
            return $sql->result_array();
        endif;
    }

    function getBillId($account_id,$month,$year,$bill_category='B')
    {
        if ( !empty($account_id) && !empty($month) && !empty($year) )
        {
            db_select("bill_id");
            db_select("account_id");
            db_select("bill_month");
            db_select("bill_year");
            db_select("total_amount");
            db_from("b_master");
            db_where("account_id",$account_id);
            db_where("bill_month",$month);
            db_where("bill_year",$year);
            db_where("bill_category",$bill_category);

            $sql = db_get('');

            if($sql)
            {
                return $sql->row_array('');
            }
        }
        else
        {
            return array();
        }
    }

    function updateBillMasterTotalAmount($bill_id,$account_id,$data_update)
    {
        db_where('bill_id',$bill_id);
        db_where('account_id',$account_id);
        db_update('b_master',$data_update);
        return true;
    }

    function insertBillMaster($data_insert)
    {
        if ( empty($data_insert["dt_added"]) && empty($data_insert["DT_ADDED"]) )
        {
            db_set_date_time('dt_added',timenow());
        }

        db_insert('b_master',$data_insert);
        return get_insert_id('b_master');
    }

    function getTotalBillGeneratedByKodKategory($month,$year)
    {
        db_select('count(b_master.bill_id) as total_bill_count');
        db_select('sum(b_master.total_amount) as total_bill_amount');
        db_select('b_master.bill_month');
        db_select('b_master.bill_year');
        db_select('b_master.bill_category');
        db_select('a_category.category_code');
        db_select('a_category.category_name');
        db_from('b_master');
        db_join('acc_account','acc_account.account_id = b_master.account_id');
        db_join('a_category','a_category.category_id = acc_account.category_id');
        db_where('b_master.bill_month',$month);
        db_where('b_master.bill_year',$year);
        db_where('b_master.bill_category','B');
        db_group('b_master.bill_month, b_master.bill_year, b_master.bill_category, a_category.category_code, a_category.category_name');

        $sql = db_get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}

