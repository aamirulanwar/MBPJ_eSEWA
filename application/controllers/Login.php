<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */

class Login extends CI_Controller
{
    public $curuser;

    public function __construct()
    {
        parent::__construct();

        $this->curuser = $this->auth->userdata();
        $this->auth->notloginonly($this->curuser);
        load_model('M_login','lg');
    }

    function _remap($method)
    {
        $array = array();
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function index()
    {
        validation_rules('username','<strong>email address</strong>','callback__external[lg,check_username]');
        validation_rules('password','<strong>password</strong>','callback__external[lg,check_password]');

        if(validation_run()==false):
            load_view('login/v_login');
        else:
            $user_id = $this->lg->process_login();
            $login_status = loginCookie($user_id) ? true : false;
            if($login_status==true):
                redirect('/dashboard/');
            else:
                redirect('/login/');
            endif;

            return false;
        endif;

    }

    #BEGIN CUSTOM VALIDATOR FOR MODEL VALIDATING METHOD
    public function _external( $postdata, $param )
    {
        $param_values = explode( ',', $param );
        // Make sure the model is loaded
        $model = $param_values[0];
        $this->load->model( $model );
        // Rename the second element in the array for easy usage
        $method = $param_values[1];
        // Check to see if there are any additional values to send as an array
        if( count( $param_values ) > 2 ):
            // Remove the first two elements in the param_values array
            array_shift( $param_values );
            array_shift( $param_values );

            $argument = $param_values;
        endif;
        // Do the actual validation in the external callback
        if( isset( $argument ) ):
            $callback_result = $this->$model->$method( $postdata, $argument );
        else:
            $callback_result = $this->$model->$method( $postdata );
        endif;
        return $callback_result;
    }
}
/* End of file modules/login/controllers/administrator.php */

