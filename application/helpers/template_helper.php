<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */



/**
 * @param string $pagetitle
 * @param string $viewFile
 * @param string $data
 * @return bool
 */

function templates($viewFile = '', $data = '')
{
    $theme = 'default';
    $head = 'head';
    $foot = 'foot';

//    $data['head_data'] = ($data['pagetitle']) ? $data['pagetitle'] : '';
//    $data['pagetitle'] = ($data['pagetitle']) ? ':: '.$data['pagetitle'] : '';
    
    load_view('themes/'.$theme.'/'.$head, $data);
    load_view($viewFile);
    load_view('themes/'.$theme.'/'.$foot);    
}

function templates_default($viewFile = '', $data = '')
{
    $theme = 'default';
    $head = 'head_default';
    $foot = 'foot_default';
    $pagetitle = 'MPKj';
    $sitetitle = 'MPKj';

    $data['pagetitle'] = ($pagetitle) ? $pagetitle : $sitetitle;

    load_view('themes/'.$theme.'/'.$head, $data);
    load_view($viewFile);
    load_view('themes/'.$theme.'/'.$foot);
}
/**
 * @param string $pagetitle
 * @param string $viewFile
 * @param string $data
 * @return bool
 */

function hide_menu($file_id='',$access='')
{
    if(!$file_id || empty($access) || !$access) return false;
    #begin check
    return in_array($file_id,$access) ? true : false;
}

function strict_access($file_id='',$access='')
{
    if(!$file_id || empty($access) || !$access) return false;
    #begin check
    return in_array($file_id,$access) ? true : false;
}

function active($main='',$sub='',$last='',$display=false)
{
    $main_uri = uri_segment(1);
    $sub_uri  = uri_segment(2);
    $last_uri = uri_segment(3);

    if($main==$main_uri && $sub==$sub_uri && $last_uri==$last):
        echo 's_active';
    elseif($main==$main_uri && $sub==$sub_uri && !$last && $display==true):
        echo 's_active';
    elseif($main==$main_uri && !$sub && !$last && $display==true):
        echo 's_active';
    endif;
}