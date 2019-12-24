<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

/*
 * Please refer https://pencil.atlassian.net/wiki/display/PCL/Audit+trail+log to get more details
 */

/**
 * Description of audit
 *
 * @author Shahrul
 */
class lebihan_lib{

    private $ci;

    public function __construct(){
        $this->ci = &get_instance();
    }

    function lebihan_data($account_id){

    }
}