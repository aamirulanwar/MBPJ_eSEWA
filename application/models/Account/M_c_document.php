<?php if (!defined('BASEPATH')) exit('No direct script access allowed');/**
 * User: Shahrul Nazuhan Bin Salim
 * Date: 19/10/2018
 * Time: 12:34 AM
 */

class M_c_document extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function insert_record_document($accountId,$documentName){
        $this->account_id       = $accountId; // please read the below note
        $this->document_name    = $documentName;
        $this->total_count    = 1;

        $this->db->insert('C_DOCUMENT_LOG', $this);
    }

    public function update_record_document($accountId,$documentName)
    {
        // $this->db->set('total_count', " to_number(nvl(total_count,0))+1");
        // //$this->db->set('total_count', '1');
        // $this->db->where('ACCOUNT_ID', $accountId);
        // $this->db->where('DOCUMENT_NAME', $documentName);
        // $this->db->update('C_DOCUMENT_LOG'); // gives UPDATE `C_DOCUMENT_LOG` SET `total_printed` = 'total_printed+1'

        $sqlCondition[] = array($accountId, $documentName);
        $sql = "    UPDATE C_DOCUMENT_LOG 
                    SET TOTAL_COUNT = to_number(nvl(total_count,0))+1 
                    WHERE 
                    account_id = ".$this->db->escape_str($accountId)."
                    AND document_name = '".$this->db->escape_str($documentName)."'";
        $this->db->query($sql, $sqlCondition);

    }

    public function get_total_printed($accountId,$documentName)
    {
        $this->db->select('TOTAL_COUNT');
        $this->db->from('C_DOCUMENT_LOG');
        $this->db->where('ACCOUNT_ID', $accountId);
        $this->db->where('DOCUMENT_NAME', $documentName);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_record_exist($accountId,$documentName)
    {
        $this->db->where('ACCOUNT_ID', $accountId);
        $this->db->where('DOCUMENT_NAME', $documentName);
        $total_exist = $this->db->count_all('C_DOCUMENT_LOG');

        return $total_exist;
    }

    public function update_document_printed($accountId,$documentName)
    {
        $records = $this->get_total_printed($accountId,$documentName);
        $recordExist = count($records);
        
        if ($recordExist == 0 )
        {
            $this->insert_record_document($accountId,$documentName);
        }
        else if ($recordExist > 0)
        {
            $this->update_record_document($accountId,$documentName);
        }
    }
}