<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Shahrul Nazuhan Bin Salim
 * Date: 06/10/15
 * Time: 10:10 PM
 */

class Ajax_js extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        load_model('Asset/M_a_category', 'm_a_category');
    }

    function _remap($method)
    {
        $array = array(
            'get_category_by_type'
        );
        #set pages data
        (in_array($method,$array)) ? $this->$method() : $this->index();
    }

    function get_category_by_type(){
        if(is_ajax()):
            $category_id    = input_data('category_id');
            $type_id        = input_data('type_id');

            $data_asset = $this->m_a_category->get_data_category_by_type($type_id);

            if($data_asset):
                echo '<option value=""> - Semua - </option>';
                foreach ($data_asset as $row):
                    echo option_value($row['CATEGORY_ID'],$row['CATEGORY_NAME'].' ('.$row['CATEGORY_CODE'].')','category_id',$category_id);
                endforeach;
            else:
                echo '<option value=""> - Semua - </option>';
            endif;
        endif;
    }
}
/* End of file modules/login/controllers/administrator.php */

