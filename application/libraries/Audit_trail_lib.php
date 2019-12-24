<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

/*
 * Please refer https://pencil.atlassian.net/wiki/display/PCL/Audit+trail+log to get more details
 */

/**
 * Description of audit
 *
 * @author Shahrul
 */
class Audit_trail_lib {
    
    private $ci;
    
    public function __construct()
    {
        $this->ci = & get_instance();
    }

    //$data_audit_trail['log_id']                  = 1000;
    //$data_audit_trail['remark']                  = $post_arr;
    //$data_audit_trail['status']                  = PROCESS_STATUS_SUCCEED;
    //$data_audit_trail['user_id']                 = $user_id;
    //$data_audit_trail['refer_id']                = 1;

    function add($data){
//
        $data_log = $this->get_log_desc($data['log_id']);

        $data_insert['LOG_ID']      = $data['log_id'];
        $data_insert['LOG_DESC']    = $data_log['LOG_DESC'];
        $data_insert['USER_ID']     = $data['user_id'];
        $data_insert['REMARK']      = json_encode($data['remark']);
        $data_insert['IP_ADDRESS']  = $this->ci->input->ip_address();;
        $data_insert['STATUS']      = isset($data['status'])?$data['status']:PROCESS_STATUS_SUCCEED;
        $data_insert['REFER_ID']    = isset($data['refer_id'])?$data['refer_id']:'0';
        db_set_date_time('DT_ADDED',timenow());
        $sql = db_insert('audit_trail',$data_insert);
        if($sql):
            return get_insert_id('audit_trail');
        endif;
    }

    function get_log_desc($log_id){
        db_select('log_desc');
        db_from('lookup_audit_trail');
        db_where('log_id',$log_id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }
}
