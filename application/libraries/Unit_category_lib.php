<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

/*
 * Please refer https://pencil.atlassian.net/wiki/display/PCL/Audit+trail+log to get more details
 */

/**
 * Description of audit
 *
 * @author Shahrul
 */
class Unit_category_lib {
    
    private $ci;
    
    public function __construct()
    {
        $this->ci = & get_instance();
    }    
    
    function set_rental_status($data){

        if($data['asset_id']):
            $data_asset = $this->get_a_asset_by_id($data['asset_id']);
            if($data_asset):
                $data_update['rental_status'] = $data['rental_status'];
                $update_a_asset = $this->update_a_asset($data_update,$data['asset_id']);
                if($update_a_asset):
                    $total_unit     = $this->count_a_asset_all($data_asset['CATEGORY_ID']);
                    $balance_unit   = $this->count_a_asset_not_rental($data_asset['CATEGORY_ID']);

                    $data_update_category['total_unit']             = $total_unit;
                    $data_update_category['total_available_unit']   = $balance_unit;
                    $update_category = $this->update_a_category($data_update_category,$data_asset['CATEGORY_ID']);
                   
                    if($update_category):
                        return true;
                    else:
                        return false;
                    endif;
                else:
                    return false;
                endif;
            else:
                return false;
            endif;
        else:
            return false;
        endif;
    }

    function get_a_asset_by_id($asset_id){
        db_select('a.*,c.*');
        db_select('a.active as active');
        db_join('a_category c','c.category_id = a.category_id');
        db_from('a_asset a');
        db_where('asset_id',$asset_id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_a_asset($data_update,$id){
        db_where('asset_id',$id);
        db_update('a_asset',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }

    function count_a_asset_all($category_id){
        db_select('*');
        db_from('a_asset a');
        db_where('active',STATUS_ACTIVE);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_where('category_id',$category_id);
        return db_count_results();
    }

    function count_a_asset_not_rental($category_id){
        db_select('*');
        db_from('a_asset a');
        db_where('active',STATUS_ACTIVE);
        db_where('rental_status',RENTAL_STATUS_NO);
        db_where('soft_delete',SOFT_DELETE_FALSE);
        db_where('category_id',$category_id);
        return db_count_results();
    }

    function update_a_category($data_update,$id){
        db_where('category_id',$id);
        db_update('a_category',$data_update);
        if(db_affected_rows()!=0):
            return true;
        else:
            return false;
        endif;
    }
}
