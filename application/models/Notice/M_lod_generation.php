<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 18/12/2019
 * Time: 11:24 PM
 */

class M_lod_generation extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_record_generation($data_insert)
    {
        db_set_date_time('date_generated',timenow());
        db_insert('C_LOD_GENERATION ',$data_insert);
        return get_insert_id('C_LOD_GENERATION');
    }

    function update_record_generation($condition,$data_update)
    {     
        if ( isset($condition["ID"]) && $condition["ID"] != "" )
        {
            db_where('ID',$condition['ID']);
        }

        if ( isset($condition["ACCOUNT_ID"]) && $condition["ACCOUNT_ID"] != "" )
        {
            db_where('ACCOUNT_ID',$condition['ACCOUNT_ID']);
        }

        if ( isset($condition["MONTH"]) && $condition["MONTH"] != "" )
        {
            db_where('MONTH',$condition['MONTH']);
        }

        if ( isset($condition["YEAR"]) && $condition["YEAR"] != "" )
        {
            db_where('YEAR',$condition['YEAR']);
        }

        db_update('C_LOD_GENERATION',$data_update);
        return true;
    }

    function get_record_generation( $data_search = array() )
    {
        db_select('*');
        db_from('C_LOD_GENERATION');

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
        db_from('C_LOD_GENERATION');

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