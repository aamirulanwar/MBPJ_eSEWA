<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_cron_config extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_data_cron($where){
        db_select('*');
//        db_select("to_char(DATE_LAST_PROCESS, 'yyyy-mm-dd') as date_process");
        db_from('cron_config');
        db_where('cron_name',$where);
        $sql = db_get();

        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_cron($where,$data_update){
        if(isset($data_update['dt_start']) && !empty($data_update['dt_start'])):
            db_set_date_time('dt_start',$data_update['dt_start']);
            unset($data_update['dt_start']);
        endif;

        if(isset($data_update['dt_end']) && !empty($data_update['dt_end'])):
            db_set_date_time('dt_end',$data_update['dt_end']);
            unset($data_update['dt_end']);
        endif;

        db_set_date('DATE_LAST_PROCESS',date_display(timenow()));
        db_where('cron_name',$where);
        db_update('cron_config',$data_update);
        return true;
    }
}

