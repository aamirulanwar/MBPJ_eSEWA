<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */


/**
 * @param string $name
 * @param string $value
 * @return bool
 */
function set_session($name='',$value='')
{
    if($name == '' || $value == '') return false;
    #call ci function
    $ci =& get_instance();
    #call session library
    $ci->load->library('session');
    return $ci->session->set_userdata($name,$value);
}

/**
 * @param string $name
 * @return bool
 */
function get_session($name='')
{
    if($name == '') return false;
    #call ci function
    $ci =& get_instance();
    #call session library
    $ci->load->library('session');
    return $ci->session->userdata($name);
}

/**
 * @param string $name
 * @return bool
 */
function unset_session($name='')
{
    if(!$name) return false;
    #call ci function
    $ci =& get_instance();
    #call session library
    $ci->load->library('session');
    return $ci->session->unset_userdata($name);
}

/**
 * @param string $name
 * @param string $value
 * @return bool
 */
function set_flash($name='',$value='')
{
    if(!$name || !$value) return false;
    #call ci function
    $ci =& get_instance();
    #call session library
    $ci->load->library('session');
    return $ci->session->set_flashdata($name,$value);
}

/**
 * @param string $name
 * @return bool
 */
function get_flash($name='')
{
    if(!$name) return false;
    #call ci function
    $ci =& get_instance();
    #call session library
    $ci->load->library('session');
    return $ci->session->flashdata($name);
}

/**
 * @param string $name
 * @param string $msg
 * @param int $type
 * @return bool
 */
function set_notify($name='',$msg='',$type=1)
{
    $ci =& get_instance();
    load_library('session');

    if(!$type || !$msg) return false;
    switch($type):
        case 1:
            $name = $name.'Success';
            break;
        case 2:
            $name = $name.'Error';
            break;
        case 3:
            $name = $name.'Notify';
            break;
        default:
            $name = $name.'Success';
            break;
    endswitch;

    return set_flash($name,$msg);
}

/**
 * @param string $title
 * @return bool
 */
function notify_msg($title='')
{
    if(!$title) return false;
    $ci =& get_instance();
    load_library('session');

    if(strpos($title,'Notify')!==false && $ci->session->flashdata($title)):
        echo notNotify($ci->session->flashdata($title));
        return true;
    elseif(strpos($title,'Error')!==false && $ci->session->flashdata($title)):
        echo notError($ci->session->flashdata($title));
        return true;
    elseif(strpos($title,'Success')!==false && $ci->session->flashdata($title)):
        echo notSuccess($ci->session->flashdata($title));
        return true;
    elseif($ci->session->flashdata($title.'Error')):
        echo notError($ci->session->flashdata($title.'Error'));
        return true;
    elseif($ci->session->flashdata($title.'Notify')):
        echo notNotify($ci->session->flashdata($title.'Notify'));
        return true;
    elseif($ci->session->flashdata($title.'Success')):
        echo notSuccess($ci->session->flashdata($title.'Success'));
        return true;
    else:
        return false;
    endif;

    return false;
}

/**
 * notNotify()
 * Display Notification Message
 */
function notNotify($msg='')
{
    return '<div class="alert alert-info">'.$msg.'</div>';
}

/**
 * notError()
 * Display Error Message
 */
function notError($msg='')
{
    return '<div class="alert alert-danger">'.$msg.'</div>';
}

/**
 * notSuccess()
 * Display Success Message
 */
function notSuccess($msg='')
{
    return '<div class="alert alert-success" role="alert">'.$msg.'</div>';
}

/**
 * @param string $type
 * @return bool
 */
function get_server($type='')
{
    if(!$type) return false;
    #uppercase type
    $type = strtoupper($type);
    #call ci function
    $ci =& get_instance();
    return $ci->input->server($type);
}

/**
 * @param string $name
 * @param string $data
 * @return bool
 */
function load_view($name='',$data=null,$third=null)
{
    if(!$name) return false;
    #call ci function
    $ci =& get_instance();
    return $ci->load->view($name,$data,$third);
}

/**
 * @param string $lib
 * @param null $params
 * @return bool
 */
function load_library($lib='',$params=null)
{
    if(!$lib) return false;
    #call ci functions
    $ci =& get_instance();
    return $ci->load->library($lib,$params);
}

/**
 * @param string $name
 * @return bool
 */
function load_helper($name='')
{
    if(!$name) return false;
    #call ci functions
    $ci =& get_instance();
    return $ci->load->helper($name);
}

/**
 * @param string $name
 * @param string $short
 * @return bool
 */
function load_model($name='',$short='')
{
    if(!$name) return false;
    #call ci functions
    $ci =& get_instance();
    return $ci->load->model($name,$short);
}

/**
 * @param string $path
 */
function load_css($path='')
{
    if(!$path) die('Path not provid for load_css function');

    $path = str_replace('.css','',$path);
    if(is_array($path)):
        foreach($path as $file):
            echo '<link rel="stylesheet" href="/assets/css/'.$file.'.css" />';
        endforeach;
    else:
        echo '<link rel="stylesheet" href="/assets/css/'.$path.'.css" />';
    endif;
}

/**
 * @param string $path
 */
function load_js($path='')
{
    if(!$path) die('Path not provid for load_js function');

    $path = str_replace('.js','',$path);
    if(is_array($path)):
        foreach($path as $file):
            echo '<script type="text/javascript" src="/assets/js/'.$file.'.js"></script>';
        endforeach;
    else:
        echo '<script type="text/javascript" src="/assets/js/'.$path.'.js"></script>';
    endif;
}

/**
 * @param string $value
 * @return string
 */
function trim_strtolower($value='')
{
    return trim(strtolower($value));
}

/**
 * @param $name
 * @param string $data
 * @param int $type
 * @return string
 */
function input_data($name,$data='',$type=1)
{
    $ci=&get_instance();

    if($_POST):
        if($type==1 && $ci->input->post($name)!=''):
            return $ci->input->post($name);
        else:
            return $data;
        endif;
    else:
        return $data;
    endif;
}

/**
 * @param null $fieldname
 * @param null $data
 * @param null $default
 * @return string
 */
function input_checkbox($fieldname=null,$data=null,$default=null)
{
    $post = input_data($fieldname);

    if(!$post):
        if($default==$data):
            return 'checked="checked"';
        endif;
    elseif(is_array($post)):
        if(in_array($data,$post)):
            return 'checked="checked"';
        endif;
    else:
        if($data==$post):
            return 'checked="checked"';
        endif;
    endif;
}


function input_checkbox_array($array=null,$data=null,$default=null)
{
    $post = $array;

    if(is_array($post)):
        if(in_array($data,$post)):
            return 'checked="checked"';
        endif;
    endif;
}
/**
 * @param string $value
 * @param string $display
 * @param string $name
 * @param string $input
 * @return bool|string
 */
function option_value($value='',$display='',$name='',$input='')
{
    $ci =& get_instance();
    #validate provided data
//    if(!$display) return false;

    $select = $ci->input->post($name);

    if($select=='' && $value==$input):
        return '<option value="'.$value.'" selected="selected">'.$display.'</option>';
    elseif($select!='' && $value==$select):
        return '<option value="'.$value.'" selected="selected">'.$display.'</option>';
    else:
        return '<option value="'.$value.'">'.$display.'</option>';
    endif;
}

/**
 * @param string $mb_lg
 * @return string
 */
function meltstring($mb_lg='N0P4s$word')
{
    $mb_lg1 = '';
    $mb_lg2 = '';
    $mb_lg3 = '';
    $mb_lg4 = '';
    $salt1 = 'D23zRT345%dT45g@#$';
    $salt2 = "hus234%^&^%&234fdgfd324234sd@#$32fgv";
    $countMb_lg = strlen($mb_lg);

    @$mb_lg1 = $mb_lg[$countMb_lg-5].$mb_lg[$countMb_lg - 3]."3";
    @$mb_lg2 = $mb_lg[$countMb_lg - 6].$mb_lg[$countMb_lg - 4]."@";
    @$mb_lg3 = $mb_lg[$countMb_lg - 1].$mb_lg[$countMb_lg]."8";
    @$mb_lg4 = $mb_lg[$countMb_lg - 2].$mb_lg[0]."_";

    $mbEnter = "";
    for ($i = 1; $i<=$countMb_lg;$i++):
        if ($i>5)
            @$mbEnter .= $mb_lg[$i].$mb_lg1;
        if ($i>5)
            @$mbEnter .= $mb_lg[$i].$mb_lg2;
        if ($i<2 || $i>5)
            @$mbEnter .= $mb_lg[$i].$mb_lg3;
        if ($i>=3 || $i<=6)
            @$mbEnter .= $mb_lg[$i].$mb_lg4;
    endfor;

    return md5($salt1.$mbEnter.'salt2');
}

/**
 * @param string $text
 * @param int $length
 * @return bool|string
 */
function cut_string($text='',$length=15)
{
    if(!$text) return false;
    #count text length
    $count = strlen($text);
    #cut text string
    $text = ($count > $length) ? ucfirst(substr($text,0,$length)).'..' : ucfirst($text);

    return $text;
}

/**
 * @param $str
 * @return string
 */
function strip($str)
{
    $t = preg_replace('/<[^<|>]+?>/', '', htmlspecialchars_decode($str));
    $t = htmlentities($t, ENT_QUOTES, "UTF-8");
    return strip_tags(htmlspecialchars_decode($t));
}

/**
 * @param string $field
 * @param string $label
 * @param string $rules
 * @return bool
 */
function validation_rules($field='',$label='',$rules='')
{
    if(!$field) return false;
    $ci =& get_instance();

    $ci->load->library('form_validation');
    $ci->form_validation->set_error_delimiters('<p class="valError">', '</p>');
    return $ci->form_validation->set_rules($field,$label,$rules);
}

/**
 * @param string $name
 * @param string $message
 * @return bool
 */
function validation_message($name='',$message='')
{
    if(!$name || !$message) die('Validation message error!');
    $ci =& get_instance();

    return $ci->form_validation->set_message($name,$message);
}

/**
 * @return mixed
 */
function validation_run()
{
    $ci =& get_instance();
    return $ci->form_validation->run();
}

/**
 * @return mixed
 */
function get_ip()
{
    $ci =& get_instance();
    return $ci->input->ip_address();
}

/**
 * @param string $segment
 * @param null $default
 * @return bool
 */
function uri_segment($segment='',$default=null)
{
    if(!$segment || !is_numeric($segment)) return false;
    #load ci function
    $ci =& get_instance();
    return $ci->uri->segment($segment,$default);
}

### ENCRYPTION HELPER ###

/**
 * @param $string
 * @param string $key
 * @return mixed
 */
function encrypt($string, $key='p3nc1l')
{
    $result = '';

    for($i=0; $i<strlen($string); $i++):
        $char    = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key))-1, 1);
        $char    = chr(ord($char)+ord($keychar));
        $result .= $char;
    endfor;
    return str_replace('=','',base64_encode($result));
}

/**
 * @param $string
 * @param string $key
 * @return string
 */
function decrypt($string, $key='p3nc1l')
{
    $result = '';
    $string = base64_decode($string);

    for($i=0; $i<strlen($string); $i++):
        $char    = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key))-1, 1);
        $char    = chr(ord($char)-ord($keychar));
        $result .= $char;
    endfor;
    return $result;
}

/**
 * @param null $data
 * @return bool
 */
function pre($data=null)
{
    if(!$data) return false;

    echo '<pre>';
    print_r($data);
    echo '</pre>';
}


/**
 * @param string $page
 * @param $totalRow
 * @param int $perPage
 * @param int $uri
 */
function paging_config($page_url='',$total_row=10,$per_page=10,$uri_segment=3)
{
    $ci =& get_instance();

    load_library('pagination');
    $config['base_url']        = $page_url;
    $config['total_rows']      = $total_row;

    $config['per_page'] = $per_page;
    $config['uri_segment'] = $uri_segment;

    $config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination">';
    $config['full_tag_close'] = '</ul></nav>';

    $config['first_link'] = '&laquo; First';
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';

    $config['last_link'] = 'Last &raquo;';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';

    $config['next_link'] = 'Next &rarr;';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';

    $config['prev_link'] = '&larr; Previous';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';

    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';

    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    $config['attributes'] = array('class' => 'page-link');

    $ci->pagination->initialize($config);
}

/**
 * @return mixed
 */
function paging_link()
{
    $ci =& get_instance();
    return $ci->pagination->create_links();
}

/**
 * @param int $length
 * @param int $strength
 * @return string
 */
function random($length=9, $strength=4)
{
    $vowels = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';
    $codeword = 'BiSmILlahIrRahMANirAHIM';

    if ($strength & 1)
        $consonants .= 'BDGHJLMNPQRSTVWXZ'; #ACEFIKOU

    if ($strength & 2)
        $vowels .= "AEUY";

    if ($strength & 4)
        $consonants .= '23456789';

    if ($strength & 8)
        $consonants .= '@#$%!^';


    $RandomString = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++):
        if ($alt == 1):
            $RandomString .= $consonants[(rand() % strlen($consonants))];
            $alt = 0;
        elseif ($alt == 0):
            $RandomString .= $vowels[(rand() % strlen($vowels))];
            $alt = 2;
        else:
            $RandomString .= $codeword[(rand() % strlen($codeword))];
            $alt = 1;
        endif;
    endfor;

    return $RandomString;
}

function random_cookie($time=0) #set random cookie
{
    $ci =& get_instance();

    $prefix = 'data_';
    $ran[1] = 35;
    $ran[2] = 45;
    $ran[3] = 53;

    $rano = rand(1,3);
    $random = $ran[$rano];

    $cookie2 = random(45,1);
    $cookie3 = random($random,4);
    $cookie4 = random(40);

    set_cookie($prefix.'A',$cookie2,$time); #random cookie strength 1
    set_cookie($prefix.'B',$cookie3,$time); #random cookie strength 4
    set_cookie($prefix.'C',$cookie4,$time); #random cookie
    set_cookie($prefix.'D',$cookie2,$time); #random cookie strength 1

    return true;
}

function loginCookie($user_id=null,$duration=null)
{
    load_helper('string');

    $time   = $duration ? 60*60*24*14 : 0;

    $ip_address = get_ip();

    $msg = md5(random_string('alnum', 16).$user_id.timenow()); #random string 1

    $data['username_id_cookies']    = $msg; #cookies
    $data['user_id']                = $user_id;
    $data['user_agent']             = get_agent();
    $data['ip_address']             = $ip_address;

    db_set_date_time('dt_added',timenow());
    db_insert('user_login',$data);

    set_cookie('demi_waktu',$msg,$time); #set primary cookie

    return random_cookie($time);
}

/**
 * @return mixed
 */
function site_config()
{
    #load codeigniter default
    $ci =& get_instance();

    return db_get('site_config',1)->row_array();
}

function get_agent()
{
    $ci =& get_instance();
    load_library('user_agent');

    if($ci->agent->is_browser())
        $agent = $ci->agent->browser().' '.$ci->agent->version();
    elseif($ci->agent->is_robot())
        $agent = $ci->agent->robot();
    elseif($ci->agent->is_mobile())
        $agent = $ci->agent->mobile();
    else
        $agent = 'Unidentified User Agent';

    return $agent;
}

function isAjax()
{
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest");
}

function first_name($th_name)
{
    $th_name = strtolower($th_name);

    if (strpos($th_name," bin ")>0):
        $th_name_chunk = explode(" bin ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," b ")>0):
        $th_name_chunk = explode(" b ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," b. ")>0):
        $th_name_chunk = explode(" b. ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," binti ")>0):
        $th_name_chunk = explode(" binti ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," bte ")>0):
        $th_name_chunk = explode(" bte ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," bte. ")>0):
        $th_name_chunk = explode(" bte. ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," bt. ")>0):
        $th_name_chunk = explode(" bt. ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," bt ")>0):
        $th_name_chunk = explode(" bt ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," a/p ")>0):
        $th_name_chunk = explode(" a/p ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," ap ")>0):
        $th_name_chunk = explode(" ap ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," a.p. ")>0):
        $th_name_chunk = explode(" a.p. ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," a/l ")>0):
        $th_name_chunk = explode(" a/l ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," al ")>0):
        $th_name_chunk = explode(" al ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    if (strpos($th_name," a.l. ")>0):
        $th_name_chunk = explode(" a.l. ",$th_name);
        $th_name = $th_name_chunk[0];
    endif;

    return ucwords($th_name);
}

function get_alphabet($value)
{
    $i=-1;
    foreach(range('A','E') as $alphabet):
        $i++;
        if($value == $i):
            return $alphabet;
        endif;
    endforeach;
}

function check_authorization($table_name,$field_val,$field_user_id,$val,$user_id,$field_user_type='',$user_type='',$where_extend=''){
    $data['subtitle'] = '';
    $data['breadcrumb'] = '';

    if($val==''):
        redirect('errors');
    else:
        db_select('*');
        db_from($table_name);
        db_where($field_val,$val);
        db_where($field_user_id,$user_id);
        if($field_user_type!='' && $user_type!=''):
            db_where($field_user_type,$user_type);
        endif;
        $where_extend;

        $sql = db_get('')->num_rows();

        if($sql==0):
            redirect('errors');
        endif;
    endif;
}

function is_empty($input){
    if (empty($input) && $input !== '0') {
        return TRUE;
    }
}

function star_rating($score){
    $star       = '<i class="fa fa-star text-yellow"></i>';
    $no_star    = '<i class="fa fa-star-o text-yellow"></i>';
    $cnt_star   = 0;
    $cnt_no_star= 0;
    $all_star   = '';

    if($score):
        if($score>0 && $score<=20):
            $cnt_star    = 1;
            $cnt_no_star = 4;
        elseif($score>20 && $score<=40):
            $cnt_star    = 2;
            $cnt_no_star = 3;
        elseif($score>40 && $score<=60):
            $cnt_star    = 3;
            $cnt_no_star = 2;
        elseif($score>60 && $score<=80):
            $cnt_star    = 4;
            $cnt_no_star = 1;
        else:
            $cnt_star    = 5;
            $cnt_no_star = 0;
        endif;

        for($cnt=0; $cnt<$cnt_star; $cnt++):
            $all_star = $all_star.$star;
        endfor;

        for($cnt=0; $cnt<$cnt_no_star; $cnt++):
            $all_star = $all_star.$no_star;
        endfor;

    else:
        $all_star = $no_star.$no_star.$no_star.$no_star.$no_star;
    endif;

    return $all_star;
}