<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */


function min_upload($filename='',$aWidth=80,$aHeight=80,$type=0)
{
    if(!$filename) return false;
    if(!isset($_FILES[$filename])) return false;
    if($type==0):
        $random_digit  = mt_rand(0000,9999);
        $new_file_name = $random_digit.$_FILES[$filename]['name'];
        $target_path   = "./uploads/temp/".$new_file_name;
        $target_path   = $target_path . basename( $_FILES[$filename]['name']);
        if(copy($_FILES[$filename]['tmp_name'], $target_path)):
            #store data input
            $size   = getimagesize($target_path);
            $width  = $size[0];
            $height = $size[1];

            #delete temp image
            unlink($target_path);
            #return result
            if($width < $aWidth):
                return false;
            elseif($height < $aHeight):
                return false;
            else:
                return true;
            endif;
        endif;
    else:
        $total = count($_FILES[$filename]);
        for($i=0; $i<$total; $i++):
            if(@$_FILES[$filename]['name'][$i]!=''):
                $random_digit  = mt_rand(0000,9999);
                @$new_file_name = $random_digit.$_FILES[$filename]['name'][$i];
                @$target_path   = "./uploads/temp/".$new_file_name;
                @$target_path   = $target_path . basename( $_FILES[$filename]['name'][$i]);

                if(copy($_FILES[$filename]['tmp_name'][$i], $target_path))
                {
                    #store data input
                    $size   = getimagesize($target_path);
                    $width  = $size[0];
                    $height = $size[1];

                    #delete temp image
                    unlink($target_path);
                    #return result
                    if($width  < $aWidth)  $error = 1;
                    if($height < $aHeight) $error = 1;
                }
            endif;
        endfor;
        #set return data
        if(isset($error)):
            unset($error);
            return false;
        else:
            return true;
        endif;
    endif;
}

function max_upload($filename='',$aWidth=1920,$aHeight=1200,$type=0)
{
    if(!$filename) return false;
    if(!isset($_FILES[$filename])) return false;
    if($type==0):
        $random_digit  = mt_rand(0000,9999);
        $new_file_name = $random_digit.$_FILES[$filename]['name'];
        $target_path   = "./uploads/temp/".$new_file_name;
        $target_path   = $target_path . basename( $_FILES[$filename]['name']);
        if(copy($_FILES[$filename]['tmp_name'], $target_path)):
            #store data input
            $size   = getimagesize($target_path);
            $width  = $size[0];
            $height = $size[1];

            #delete temp image
            unlink($target_path);
            #return result
            if($width > $aWidth):
                return false;
            elseif($height > $aHeight):
                return false;
            else:
                return true;
            endif;
        endif;
    else:
        $total = count($_FILES[$filename]);
        for($i=0; $i<$total; $i++):
            if(@$_FILES[$filename]['name'][$i]!=''):
                $random_digit  = mt_rand(0000,9999);
                @$new_file_name = $random_digit.$_FILES[$filename]['name'][$i];
                @$target_path   = "./uploads/temp/".$new_file_name;
                @$target_path   = $target_path . basename( $_FILES[$filename]['name'][$i]);

                if(copy($_FILES[$filename]['tmp_name'][$i], $target_path)):
                    #store data input
                    $size   = getimagesize($target_path);
                    $width  = $size[0];
                    $height = $size[1];

                    #delete temp image
                    unlink($target_path);
                    #return result
                    if($width  > $aWidth)  $error = 1;
                    if($height > $aHeight) $error = 1;
                endif;
            endif;
        endfor;
        #set return data
        if(isset($error)):
            unset($error);
            return false;
        else:
            return true;
        endif;
    endif;
}


function max_size($filename='',$aSize=2097152,$type=0)
{
    if(!$filename) return false;
    if(!isset($_FILES[$filename])) return false;
    if($type==0):
        $random_digit  = mt_rand(0000,9999);
        $new_file_name = $random_digit.$_FILES[$filename]['name'];
        $target_path   = "./uploads/temp/".$new_file_name;
        $target_path   = $target_path . basename( $_FILES[$filename]['name']);
        if(copy($_FILES[$filename]['tmp_name'], $target_path)):
            #store data input
            $size   = filesize($target_path);
            #delete temp image
            unlink($target_path);
            #return result
            if($size > $aSize):
                return false;
            else:
                return true;
            endif;
        endif;
    else:
        $total = count($_FILES[$filename]);
        for($i=0; $i<$total; $i++):
            if(@$_FILES[$filename]['name'][$i]!=''):
                $random_digit  = mt_rand(0000,9999);
                @$new_file_name = $random_digit.$_FILES[$filename]['name'][$i];
                @$target_path   = "./uploads/temp/".$new_file_name;
                @$target_path   = $target_path . basename( $_FILES[$filename]['name'][$i]);

                if(copy($_FILES[$filename]['tmp_name'][$i], $target_path))
                {
                    #store data input
                    $size   = filesize($target_path);

                    #delete temp image
                    unlink($target_path);
                    #return result
                    if($size  > $aSize)  $error = 1;
                }
            endif;
        endfor;
        #set return data
        if(isset($error)):
            unset($error);
            return false;
        else:
            return true;
        endif;
    endif;
}



function extension_check($filename='',$allowed='',$type=0)
{
    if(!$filename) return false;
    if(!isset($_FILES[$filename])) return false;
    if($allowed=='')
        $allowed = array('.png', '.gif', '.jpg', '.jpeg','.PNG', '.GIF', '.JPG', '.JPEG');

    if($type==0):
        $random_digit  = mt_rand(0000,9999);
        $new_file_name = $random_digit.$_FILES[$filename]['name'];
        $target_path   = "./uploads/temp/".$new_file_name;
        $target_path   = $target_path . basename( $_FILES[$filename]['name']);
        if(copy($_FILES[$filename]['tmp_name'], $target_path)):
            #store data input
            $extension = strrchr($target_path, '.');
            #delete temp image
            unlink($target_path);
            #return result
            if(!in_array($extension, $allowed)):
                return false;
            else:
                return true;
            endif;
        endif;
    else:
        $total = count($_FILES[$filename]);
        for($i=0; $i<$total; $i++):
            if(@$_FILES[$filename]['name'][$i]!=''):
                $random_digit  = mt_rand(0000,9999);
                @$new_file_name = $random_digit.$_FILES[$filename]['name'][$i];
                @$target_path   = "./uploads/temp/".$new_file_name;
                @$target_path   = $target_path . basename( $_FILES[$filename]['name'][$i]);

                if(copy($_FILES[$filename]['tmp_name'][$i], $target_path))
                {
                    #store data input
                    $extension = strrchr($target_path, '.');
                    #delete temp image
                    unlink($target_path);
                    #return result
                    if(!in_array($extension, $allowed)) $error = 1;
                }
            endif;
        endfor;
        #set return data
        if(isset($error)):
            unset($error);
            return false;
        else:
            return true;
        endif;
    endif;
}