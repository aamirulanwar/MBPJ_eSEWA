<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: izainurie
 * Date: 7/7/12
 * Time: 1:00 AM
 * To change this template use File | Settings | File Templates.
 */

### BEGIN DATABASE HELPER ###

/**
 * @param string $data
 * @return mixed
 */

function db_escape($var)
{
    $ci =& get_instance();
    return $ci->db->escape($var);
}

function db_from($data='')
{
    if(!$data) die('No Data Provided for db_from function!');
    $ci =& get_instance();

    return $ci->db->from(strtoupper($data));
}

function db_distinct($field='')
{
    $ci =& get_instance();
    if(!$field):
        die('No field provided for db_distinct function');
    endif;
    return $ci->db->distinct(strtoupper($field));
}

function get_insert_id($table_name) {
    $ci =& get_instance();
    $ci->db->select(strtoupper($table_name).'_SEQ.CURRVAL AS CURRVAL', FALSE);
    $ci->db->from('DUAL');
    $query = $ci->db->get();
    $row = $query->row();
    return $row->CURRVAL;
}

/**
 * @param string $field
 * @param string $condition
 * @return mixed
 */
function db_where($field='',$condition=null, $escape=true)
{
    if(!$field) die('No Field or Condition provided for db_where function');
    $ci =& get_instance();

    return $ci->db->where(strtoupper($field),$condition, $escape);
}

function db_or_where($field, $condition=null, $escape=true)
{
    $ci =& get_instance();
    if(!$field):
        die('No Field or Condiction provided for db_or_where function');
    endif;
    
    return $ci->db->or_where(strtoupper($field), $condition, $escape);
}

/**
 * @param string $field
 * @return mixed
 */
function db_select($field='', $escape=null)
{
    if(!$field) die('No Field provided for db_select function');
    $ci =& get_instance();

    return $ci->db->select(strtoupper($field), $escape);
}

/**
 * @param string $field
 * @return mixed
 */
function db_max($field='')
{
    if(!$field) die('No Field provided for db_max function!');
    $ci =& get_instance();

    return $ci->db->select_max(strtoupper($field));
}

/**
 * @param string $field
 * @return mixed
 */
function db_min($field='')
{
    if(!$field) die('No Field provided for db_min function!');
    $ci =& get_instance();

    return $ci->db->select_min(strtoupper($field));
}

/**
 * @param string $field
 * @return mixed
 */
function db_avg($field='')
{
    if(!$field) die('No Field provided for db_avg function!');
    $ci =& get_instance();

    return $ci->db->select_avg(strtoupper($field));
}

function db_set_date_time($field='',$date='')
{
    if(!$field) die('No Field provided for db_avg function!');
    $ci =& get_instance();

    return $ci->db->set(strtoupper($field),"TO_DATE('".$date."','yyyy/mm/dd hh24:mi:ss')",FALSE);
}

function db_set_date($field='',$date='')
{
    if(!$field) die('No Field provided for db_avg function!');
    $ci =& get_instance();

    return $ci->db->set(strtoupper($field),"TO_DATE('".$date."','dd-mm-yyyy')",FALSE);
}
function db_set_date_v2($field='',$date='')
{
    if(!$field) die('No Field provided for db_avg function!');
    $ci =& get_instance();

    return $ci->db->set(strtoupper($field),"TO_DATE('".$date."','dd/mm/yyyy')",FALSE);
}


/**
 * @param string $field
 * @return mixed
 */
function db_sum($field='')
{
    if(!$field) die('No Field provided for db_sum Function!');
    $ci =& get_instance();

    return $ci->db->select_sum(strtoupper($field));
}

/**
 * @param string $field
 * @param string $order
 * @return mixed
 */
function db_order($field='',$order='asc')
{
    if(!$field) die('No Field provided for db_order function!');
    $ci =& get_instance();

    return $ci->db->order_by(strtoupper($field),strtoupper($order));
}

/**
 * @param string $total
 * @return mixed
 */
function db_limit($total='')
{
    if(!$total) die('db_limit function didn\'t get any amount');
    $ci =& get_instance();

    return $ci->db->limit($total);
}

/**
 * @param string $field
 * @return mixed
 */
function db_group($field='')
{
    if(!$field) die('No Field is provided for function db_group!');
    $ci =& get_instance();

    return $ci->db->group_by(strtoupper($field));
}

/**
 * @param string $table
 * @return mixed
 */
function db_count_results($table='')
{
    $ci =& get_instance();
    return $ci->db->count_all_results(strtoupper($table));
}

/**
 * @param string $table
 * @param string $limit
 * @param string $segment
 * @return mixed
 */
function db_get($table='',$limit=null,$segment=null)
{
    $ci =& get_instance();
    return $ci->db->get(strtoupper($table),$limit,$segment);
}

/**
 * @param string $table
 * @param string $data
 * @return mixed
 */
function db_insert($table='',$data='')
{
    if(!$table || !$data || empty($data)) die('Error on db_insert function!');
    $ci =& get_instance();

    return $ci->db->insert(strtoupper($table),$data);
}

/**
 * @param string $table
 * @param string $data
 * @return mixed
 */
function db_update($table='',$data='')
{
    if(!$table || !$data || empty($data)) die('Function db_update error!');
    $ci =& get_instance();

    return $ci->db->update(strtoupper($table),$data);
}

/**
 * @param string $table
 * @return mixed
 */
function db_delete($table='')
{
    if(!$table) die('No Table provided for db_delete function!');
    $ci =& get_instance();

    return $ci->db->delete(strtoupper($table));
}

/**
 * @param string $table
 * @param string $data
 * @param string $type
 * @return mixed
 */
function db_join($table='',$data='',$type='')
{
    if(!$table || !$data) die('Error on db_join function, missing data!');
    $ci =& get_instance();

    return $ci->db->join(strtoupper($table),strtoupper($data),$type);
}

/**
 * @param string $field
 * @param string $data
 * @return mixed
 */
function db_not_in($field='',$data='')
{
    if(!$field || !$data) die('Error in db_not_in function, missing data!');
    $ci =& get_instance();

    return $ci->db->where_not_in(strtoupper($field),$data);
}

/**
 * @return mixed
 */
function insert_id()
{
    $ci =& get_instance();
    return $ci->db->insert_id();
}

function db_in($field='',$data)
{
    $ci =& get_instance();
    return $ci->db->where_in($field, $data);
}

/**
 * @param string $table
 * @param string $condition
 * @param string $limit
 * @param string $field
 * @return bool
 */
function get_data($table='',$condition='',$limit='',$field='')
{
    if(!$table) die('Error in get_data function, no table provided!');
    if($condition && !is_array($condition)) die('Error in get_data function, no condition provided!');
    if($limit && !$field) die('Error in get_data function, no limit and field provided!');

    $ci =& get_instance();
    if($field):
        #check field and table
        if((!$ci->db->field_exists(strtoupper($field),strtoupper($table)))) return false;
        $ci->db->select(strtoupper($field));
    endif;

    #custom rule for select
    if(is_array($condition)):
        foreach($condition as $key => $val):
            $ci->db->where($key,$val);
        endforeach;
    endif;

    if($limit)
        $ci->db->limit($limit);

    $sql = $ci->db->get($table);

    if($limit==1):
        if($sql->num_rows()>=1):
            $res = $sql->row();
            return $res->$field;
        else:
            return false;
        endif;
    else:
        return $sql;
    endif;
}

function db_query($sql='')
{
    if(!$sql) return false;
    #query
    $ci =& get_instance();
    return $ci->db->query($sql);
}

function remove_prefix()
{
    $ci =& get_instance();
    return $ci->db->dbprefix('');
}

function add_prefix()
{
    $ci =& get_instance();
    return $ci->db->dbprefix('p_');
}

function db_affected_rows(){
    $ci =& get_instance();
    return $ci->db->affected_rows();
}
/**
 * @return mixed
 */
function last_query()
{
    $ci =& get_instance();
    return $ci->db->last_query();
}

function is_ajax()
{
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest");
}