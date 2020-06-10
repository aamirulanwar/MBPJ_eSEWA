<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 18/12/2019
 * Time: 11:24 PM
 */

class M_notis_mah_generation extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_record_generation($data_insert)
    {
        db_set_date_time('date_generated',timenow());
        db_insert('C_NOTIS_MAH_GENERATION ',$data_insert);
        return get_insert_id('C_NOTIS_MAH_GENERATION');
    }

    function get_record_generation( $data_search = array() )
    {
        db_select('*');
        db_from('C_NOTIS_MAH_GENERATION');

        if ( isset($data_search["ID"]) )
        {
            db_where('id', $data_search["ID"]);
        }

        if ( isset($data_search["ACCOUNT_ID"]) )
        {
            db_where('account_id', $data_search["ACCOUNT_ID"]);
        }

        if ( isset($data_search["MONTH"]) )
        {
            db_where("MONTH",$data_search["MONTH"] );
        }

        if ( isset($data_search["YEAR"]) )
        {
            db_where("YEAR",$data_search["YEAR"] );
        }

        db_order('DATE_GENERATED','desc');

        $sql = db_get();
        if($sql)
        {
            return $sql->result_array();
        }
    }

    function get_monthly_record_generation( $data_search = array() )
    {
        db_select('month');
        db_select('year');
        db_select('count(*) as total_generated');
        db_select('sum(total_tunggakan) as total_amount');
        db_from('C_NOTIS_MAH_GENERATION');

        if ( isset($data_search["ID"]) )
        {
            db_where('id', $data_search["ID"]);
        }

        if ( isset($data_search["ACCOUNT_ID"]) )
        {
            db_where('account_id', $data_search["ACCOUNT_ID"]);
        }

        if ( isset($data_search["MONTH"]) )
        {
            db_where("MONTH",$data_search["MONTH"] );
        }

        if ( isset($data_search["YEAR"]) )
        {
            db_where("YEAR",$data_search["YEAR"] );
        }

        db_group('month');
        db_group('year');

        $sql = db_get();
        if($sql)
        {
            return $sql->result_array();
        }
    }
}