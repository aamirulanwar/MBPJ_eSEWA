<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */
class Auth extends CI_Model
{
    public $sec_key;

    public function __construct()
    {
        parent::__construct();
        $this->sec_key = 'data_';
    }

    function userdata($user_id='')
    {
        if(!$user_id):
            $cookie_a = get_cookie($this->sec_key.'A');
            $cookie_b = get_cookie($this->sec_key.'B');
            $cookie_c = get_cookie($this->sec_key.'C');
            $cookie_d = get_cookie($this->sec_key.'D');
            $cookie_e = get_cookie('demi_waktu');

            if(empty($cookie_a) || empty($cookie_b) || empty($cookie_c) || empty($cookie_d) || empty($cookie_e))
                return false;
        endif;

        #check from database
        db_select('a.*,b.*,g.*');
        db_select("to_char(b.LAST_DT_UPDATE_PASSWORD, 'yyyy-mm-dd hh24:mi:ss') as LAST_DT_UPDATE_PASSWORD",false);
        db_from('user_login a');
        db_join('users b','b.user_id=a.user_id');
        db_join('user_group g','g.user_group_id=b.user_group_id');
//        db_join('department d','d.department_id=b.department_id');
        if(!$user_id)
            db_where('a.username_id_cookies',$cookie_e);

        if($user_id)
            db_where('a.user_id',$user_id);

        $sql = db_get('',1);
        #store result in array data
        $res = $sql->row_array();
        #remove password from array
        unset($res['profile_version']);

        return $res;
    }

    function access_view($curuser='',$access='')
    {
        if(!$curuser && !$access) return false;
        $user_access = json_decode($curuser['FILE_ACCESS']);

        if(CHECK_ADMIN):
            if($this->curuser['USER_ID']==ADMIN_ID):
                return true;
            endif;
        endif;

        #begin checking access
        foreach($access as $row):
            if(in_array($row,$user_access)):
                return true;
            endif;
        endforeach;

        return false;
    }

    function access_main_view($curuser='',$access='')
    {
        if(!$curuser && !$access) return false;
        $user_access = json_decode($curuser['PARENT_FILE_ACCESS']);

        if (CHECK_ADMIN):
            if ($this->curuser['USER_ID'] == ADMIN_ID):
                return true;
            endif;
        endif;

        #begin checking access
        foreach($access as $row):
            if(in_array($row,$user_access)):
                return true;
            endif;
        endforeach;

        return false;
    }

    function restrict_access($curuser='',$access='')
    {
        if(!$curuser && !$access) redirect('/profile/');
        $user_access = json_decode($curuser['FILE_ACCESS']);

        if (CHECK_ADMIN):
            if ($this->curuser['USER_ID'] == ADMIN_ID):
                return true;
            endif;
        endif;

        #begin checking access
        foreach($access as $row):
            if(in_array($row,$user_access)):
                return true;
            endif;
        endforeach;

        redirect('/profile/');
    }

    function restrict_access_V2($curuser='',$access='')
    {
        $status = false;
        
        $user_access = json_decode($curuser['FILE_ACCESS']);

        if (CHECK_ADMIN)
        {
            if ($this->curuser['USER_ID'] == ADMIN_ID)
            {
                // return true;
                $status = true;
            }
        }

        #begin checking access
        foreach($access as $row)
        {
            if(in_array($row,$user_access))
            {
                // return true;
                $status = true;
            }
        }

        return $status;
    }

    function loginonly($curuser='')
    {
        if(!$curuser || empty($curuser)):
            $uri = get_server('request_uri');
            if(strpos($uri,'login')===false || strpos($uri,'logout')===false):
                set_cookie('redirect_cookie',urlencode($uri),0);
                redirect('/login/');
                return false;
            else:
                return false;
            endif;
        else:
            $total_days = count_days(timenow(),$curuser['LAST_DT_UPDATE_PASSWORD']);
            $month = floor($total_days/30);

            if($month>=6||$curuser['LAST_DT_UPDATE_PASSWORD']==NULL ):
                redirect('/reset_password/');
            endif;
        endif;
    }

    function notloginonly($curuser='')
    {
        if($curuser):
            redirect('/dashboard/');
        endif;
    }
}
/* End of file modules/auth/controllers/auth.php */
 
