<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */

function alpha_month($month='')
{
    if(!$month) return false;
    #switch numeric to full month
    switch($month):
        case 1:
            return 'January';
            break;
        case 2:
            return 'February';
            break;
        case 3:
            return 'March';
            break;
        case 4:
            return 'April';
            break;
        case 5:
            return 'May';
            break;
        case 6:
            return 'June';
            break;
        case 7:
            return 'July';
            break;
        case 8:
            return 'August';
            break;
        case 9:
            return 'September';
            break;
        case 10:
            return 'October';
            break;
        case 11:
            return 'November';
            break;
        case 12:
            return 'December';
            break;
    endswitch;
}

function alpha_month_short($month='')
{
    if(!$month) return false;
    #switch numeric to full month
    switch($month):
        case 1:
            return 'Jan';
            break;
        case 2:
            return 'Feb';
            break;
        case 3:
            return 'Mar';
            break;
        case 4:
            return 'Apr';
            break;
        case 5:
            return 'May';
            break;
        case 6:
            return 'Jun';
            break;
        case 7:
            return 'Jul';
            break;
        case 8:
            return 'Aug';
            break;
        case 9:
            return 'Sep';
            break;
        case 10:
            return 'Oct';
            break;
        case 11:
            return 'Nov';
            break;
        case 12:
            return 'Dec';
            break;
    endswitch;
}

function timenow()
{
    return date('Y-m-d H:i:s');
}

function dateDiff($endDate='',$beginDate='',$flag='s')
{
    $beginDate  = strtotime($beginDate);
    $endDate    = strtotime($endDate);

    if($flag=='s'):
        return ceil($endDate-$beginDate);
    elseif ($flag == 'm'):
        return ceil(($endDate-$beginDate) / 60);
    elseif ($flag == 'h'):
        return ceil(($endDate-$beginDate) / (60*60));
    elseif ($flag == 'd'):
        return ceil(($endDate-$beginDate) / (60*60*24));
    elseif ($flag == 'mn'):
        return floor(($endDate-$beginDate) / (60*60*24*30));
    endif;
}

function month_interval($beginDate='',$endDate='')
{
    $beginDate  = new DateTime(date('d-M-Y',strtotime($beginDate)));
    $endDate    = new DateTime(date('d-M-Y',strtotime($endDate. ' +1 day')));

    $date_interval = date_diff($beginDate, $endDate);
    $difference = $date_interval->m + ($date_interval->y * 12);

    if($difference<0):
        $difference = 0;
    endif;

    return $difference;
}

function proper_date($date='',$time=false,$separator='-',$format=1)
{
    if(!$date) return false;

    $dts = explode(' ',$date);
    $dt  = explode('-',$dts[0]);

    if($time==true):
        if($format==1):
            return trim($dt[2].$separator.$dt[1].$separator.$dt[0]).nbs(4).$dts[1];
        else:
            return trim($dt[2].$separator.alpha_month($dt[1]).$separator.$dt[0]).nbs(4).$dts[1];
        endif;
    else:
        if($format==1):
            return trim($dt[2].$separator.$dt[1].$separator.$dt[0]);
        else:
            return trim($dt[2].$separator.alpha_month($dt[1]).$separator.$dt[0]);
        endif;
    endif;
}

function date_manipulate($zoom='',$date='')
{
    if(!$zoom) return false;

    if(!$date):
        return date('Y-m-d H:i:s', strtotime($zoom));
    else:
        return date($date, strtotime($zoom));
    endif;
}

function zoom_date($zoom=null,$date=null,$time=true)
{
    if(!$zoom) return false;
    $date = ($date) ? $date : date('Y-m-d H:i:s');

    $date = new DateTime($date);
    $date->modify("$zoom");

    return ($time==true) ? $date->format("Y-m-d H:i:s") :  $date->format("Y-m-d");
}

function date_zoom($zoom=null,$date=null,$format='Y-m-d H:i:s')
{
    $date = !$date ? date('Y-m-d H:i:s') : $date;

    return date($format,strtotime("$date $zoom"));
}

function count_days($begin='',$end='')
{
    if(!$begin || !$end) return false;
    #begin count days

//    foreach($period as $dt) {
//        $curr = $dt->format('D');
//
//        // substract if Saturday or Sunday
//        if ($curr == 'Sat' || $curr == 'Sun') {
//            $days--;
//        }
//    }

    $startTimeStamp = strtotime($begin);
    $endTimeStamp   = strtotime($end);
    $timeDiff       = abs($endTimeStamp - $startTimeStamp);
    $numberDays     = $timeDiff/86400;  // 86400 seconds in one day
    $numberDays     = intval($numberDays)+1;

    return $numberDays;
}

function reformat_fbdate($date='',$separator='/')
{
    $dd = explode($separator,$date);
    return $dd[2].'-'.$dd[0].'-'.$dd[1];
}

function time_passed($timestamp){
    //type cast, current time, difference in timestamps
    $timestamp      = (int) $timestamp;
    $current_time   = time();
    $diff           = $current_time - $timestamp;

    //intervals in seconds
    $intervals      = array (
        'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute'=> 60
    );

    //now we just find the difference
    if ($diff == 0)
    {
        return 'just now';
    }

    if ($diff < 60)
    {
        return $diff == 1 ? $diff . ' second ago' : $diff . ' seconds ago';
    }

    if ($diff >= 60 && $diff < $intervals['hour'])
    {
        $diff = floor($diff/$intervals['minute']);
        return $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes ago';
    }

    if ($diff >= $intervals['hour'] && $diff < $intervals['day'])
    {
        $diff = floor($diff/$intervals['hour']);
        return $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';
    }

    if ($diff >= $intervals['day'] && $diff < $intervals['week'])
    {
        $diff = floor($diff/$intervals['day']);
        return $diff == 1 ? 'yesterday' : $diff . ' days ago';
    }

    if ($diff >= $intervals['week'] && $diff < $intervals['month'])
    {
        return date('l, jS F Y',$timestamp);
    }

    if ($diff >= $intervals['month'] && $diff < $intervals['year'])
    {
        $diff = floor($diff/$intervals['week']);
        return date('l, jS F Y',$timestamp);
    }

    if ($diff >= $intervals['year'])
    {
        $diff = floor($diff/$intervals['year']);
        return date('l, jS F Y',$timestamp);
    }
}

function date_display($date,$format = 'd M Y',$lang='eng'){
    if(!$date)
        return '';

    $dt = date($format,strtotime($date));
    $month_eng   = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
    if($lang=='eng'):
        if($format == 'd F Y'):
            $month_eng   = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        elseif($format == 'd M Y'):
            $month_eng   = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        endif;
        $dt = str_ireplace($month_eng, $month_eng, $dt);
    elseif($lang=='malay'):
        $month_malay = array('januari', 'februari', 'mac', 'april', 'mei', 'jun', 'julai', 'ogos', 'september', 'oktober', 'november', 'disember');
        if($format == 'd F Y'):
            $month_malay = array('Januari', 'Februari', 'Mac', 'April', 'Mei', 'Jun', 'Julai', 'Ogos', 'September', 'Oktober', 'November', 'Disember');
        elseif($format == 'd M Y'):
            $month_malay = array('Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun', 'Jul', 'Ogos', 'Sep', 'Okt', 'Nov', 'Dis');
        endif;
        $dt = str_ireplace($month_eng, $month_malay, $dt);
    endif;

    return $dt;
}

function date_display_bm($date,$format = 'd/m/Y')
{
    $date = DateTime::createFromFormat($format, $date);

    if(!$date)
    {
        return '';
    }

    $day = $date->format('d');
    $month = $date->format('n');
    $year = $date->format('Y');

    if( $month == 1)
    {
        $new_date_in_bm = $day." Januari ".$year;
    }
    else if( $month == 2)
    {
        $new_date_in_bm = $day." Februari ".$year;
    }
    else if( $month == 3)
    {
        $new_date_in_bm = $day." Mac ".$year;
    }
    else if( $month == 4)
    {
        $new_date_in_bm = $day." April ".$year;
    }
    else if( $month == 5)
    {
        $new_date_in_bm = $day." Mei ".$year;
    }
    else if( $month == 6)
    {
        $new_date_in_bm = $day." Jun ".$year;
    }
    else if( $month == 7)
    {
        $new_date_in_bm = $day." Julai ".$year;
    }
    else if( $month == 8)
    {
        $new_date_in_bm = $day." Ogos ".$year;
    }
    else if( $month == 9)
    {
        $new_date_in_bm = $day." September ".$year;
    }
    else if( $month == 10)
    {
        $new_date_in_bm = $day." Oktober ".$year;
    }
    else if( $month == 11)
    {
        $new_date_in_bm = $day." November ".$year;
    }
    else if( $month == 12)
    {
        $new_date_in_bm = $day." Disember ".$year;
    }
    return $new_date_in_bm;
}

function get_working_days($startDate,$endDate){
            
    $endDate    = strtotime($endDate);
    $startDate  = strtotime($startDate);

    $days               = ($endDate - $startDate) / 86400 + 1;
    $no_full_weeks      = floor($days / 7);
    $no_remaining_days  = fmod($days, 7);

    $the_first_day_of_week  = date("N", $startDate);
    $the_last_day_of_week   = date("N", $endDate);

    if ($the_first_day_of_week <= $the_last_day_of_week){
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week)
            $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week)
            $no_remaining_days--;
    }else{
        if ($the_first_day_of_week == 7){
            $no_remaining_days--;
            if ($the_last_day_of_week == 6){
                $no_remaining_days--;
            }
        }else{
            $no_remaining_days -= 2;
        }
    }

    $working_days = $no_full_weeks * 5;
    if ($no_remaining_days > 0 ){
        $working_days += $no_remaining_days;
    }
    return intval($working_days);
}

function intPart($float)
{
    if($float < -0.0000001):
        return ceil($float - 0.0000001);
    else:
        return floor($float + 0.0000001);
    endif;
}

function date_display_hijri($dt)
{
    $day    = date("d",strtotime($dt));
    $month  = date("m",strtotime($dt));
    $year   = date("Y",strtotime($dt));
    $day    = (int) $day;
    $month  = (int) $month;
    $year   = (int) $year;
    
    if(($year > 1582) || (($year == 1582) && ($month > 10)) || (($year == 1582) && ($month == 10) && ($day > 14))):
        $jd = intPart((1461*($year+4800+intPart(($month-14)/12)))/4)+intPart((367*($month-2-12*(intPart(($month-14)/12))))/12)-
        intPart((3*(intPart(($year+4900+intPart(($month-14)/12))/100)))/4)+$day-32075;
    else:
        $jd = 367*$year-intPart((7*($year+5001+intPart(($month-9)/7)))/4)+intPart((275*$month)/9)+$day+1729777;
    endif;
    
    $l = $jd-1948440+10632;
    $n = intPart(($l-1)/10631);
    $l = $l-10631*$n+354;
    $j = (intPart((10985-$l)/5316))*(intPart((50*$l)/17719))+(intPart($l/5670))*(intPart((43*$l)/15238));
    $l = $l-(intPart((30-$j)/15))*(intPart((17719*$j)/50))-(intPart($j/16))*(intPart((15238*$j)/43))+29;
    
    $month = intPart((24*$l)/709);
    $day   = $l-intPart((709*$month)/24);
    $year  = 30*$n+$j-30;
    
    $hijri_month = array("Muharram", "Safar", "Rabiulawal", "Rabiulakhir", "Jamadilawal", "Jamadilakhir", "Rejab", "Syaaban", "Ramadan", "Syawal", "Zulkaedah", "Zulhijah");
    $month = $month-1;
    
    $dt = "{$day} {$hijri_month[$month]} {$year}";
    return $dt;
}
