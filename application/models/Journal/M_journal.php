<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_journal extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_lists_temp_journal()
    {
        $model =& get_instance();

        $query = $model->db->query("SELECT b.*,a.account_number,u.user_name FROM b_journal_temp b, acc_account a, users u WHERE b.account_id=a.account_id AND u.user_id=b.created_by");
        $result = $query->result_array();

        if ($result) {
            
            return $result;
        }
    }

    function journal_code_lists($bill_category="B"){
        $model =& get_instance();

        $query = $model->db->query("SELECT * FROM a_journal WHERE journal_code LIKE '%".$bill_category."%'");
        $result = $query->result_array();

        if($result):
            return $result;
        endif;
    }

    function insert_journal_temp($data)
    {
        db_set_date_time('dt_added',timenow());
        
        $insert = db_insert('b_journal_temp',$data);

        return $insert;
    }

    function approveOrDeclineJurnal($id,$status_approval,$updated_by,$updated_at)
    {
        $model =& get_instance();

        $q_get_journal = $model->db->query("
            SELECT
                *
            FROM
                b_journal_temp
            WHERE
                id = ?
        ",[$id]);
        $get_journal = $q_get_journal->result_array();

        $q_acc_account = $model->db->query("
            SELECT
                BILL_TYPE
            FROM
                acc_account
            WHERE
                account_id = ?
        ",[$get_journal[0]["ACCOUNT_ID"]]);

        $acc_account = $q_acc_account->result_array();
        $bill_type = $acc_account[0]["BILL_TYPE"];


        $update_journal = $model->db->query("
            UPDATE
                b_journal_temp
            SET
                STATUS_APPROVAL = ?,
                UPDATED_BY = ?,
                UPDATED_AT = ?
            WHERE
                id = ?
        ",[
            $status_approval,
            $updated_by,
            $updated_at,
            $id
        ]);

        if ($status_approval==1) {
            
            $q1 = $model->db->query("
                SELECT
                    COUNT(*) AS TOTAL
                FROM
                    b_master
                WHERE
                    BILL_NUMBER = ?
            ",[$get_journal[0]['BILL_NUMBER']]);

            $exist_in_b_master = $q1->result_array();

            $bill_id="";

            if ($exist_in_b_master[0]["TOTAL"] > 0) {
                
                $q2 = $model->db->query("
                    SELECT
                        BILL_ID,
                        TOTAL_AMOUNT
                    FROM
                        b_master
                    WHERE
                        BILL_NUMBER = ?
                ",[
                    $get_journal[0]['BILL_NUMBER']
                ]);

                $b_master = $q2->result_array();

                $bill_id = $b_master[0]["BILL_ID"];

                $update_b_master = $model->db->query("
                    UPDATE
                        b_master
                    SET
                        TOTAL_AMOUNT = ?
                    WHERE
                        BILL_ID = ?
                ",[
                    ($b_master[0]["TOTAL_AMOUNT"]+$get_journal[0]['AMOUNT']),
                    $bill_id
                ]);
            }
            else {

                $insert_b_master = $model->db->query("
                    INSERT INTO b_master
                    (
                        DT_ADDED,
                        ACCOUNT_ID,
                        BILL_NUMBER,
                        BILL_MONTH,
                        BILL_YEAR,
                        TOTAL_AMOUNT,
                        BILL_TYPE,
                        BILL_CATEGORY
                    )
                    VALUES
                    (
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?
                    )
                ",[
                    date('d-M-Y h:i:s'),
                    $get_journal[0]['ACCOUNT_ID'],
                    $get_journal[0]['BILL_NUMBER'],
                    $get_journal[0]['BILL_MONTH'],
                    $get_journal[0]['BILL_YEAR'],
                    $get_journal[0]['AMOUNT'],
                    $bill_type,
                    'J'
                ]);
                
                $q2 = $model->db->query("
                    SELECT
                        BILL_ID
                    FROM
                        b_master
                    WHERE
                        BILL_NUMBER = ?
                ",[
                    $get_journal[0]['BILL_NUMBER']
                ]);

                $b_master = $q2->result_array();

                $bill_id = $b_master[0]["BILL_ID"];
            }

            $insert_b_item = $model->db->query("
                INSERT INTO b_item
                (
                    BILL_ID,
                    JOURNAL_ID,
                    ACCOUNT_ID, 
                    AMOUNT,
                    DT_ADDED,
                    BILL_CATEGORY
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )
            ",[
                $bill_id,
                $get_journal[0]['JOURNAL_ID'],
                $get_journal[0]['ACCOUNT_ID'],
                $get_journal[0]['AMOUNT'],
                date('d-M-Y h:i:s'),
                'J',
            ]);
        }

        return $update_journal;
    }

    function get_unique_billnumber()
    {
        $model =& get_instance();

        $billnumber = rand(100000,999999);

        $query = $model->db->query("SELECT COUNT(*) AS TOTAL FROM b_journal_temp WHERE bill_number = ?",[$billnumber]);

        $check = $query->result_array();

        if ($check[0]['TOTAL'] > 0) {
            
            return $this->get_unique_billnumber();
        }

        return $billnumber;
    }

    function generate_running_billnumber()
    {
        $model =& get_instance();

        $query = $model->db->query("
            SELECT COUNT(*) AS TOTAL FROM b_journal_temp
        ");

        $ifAnyRecordExist = $query->result_array();

        if ($ifAnyRecordExist[0]['TOTAL']==0) {
            
            return "000001";
        }

        $query = $model->db->query("
            SELECT BILL_NUMBER
            FROM (
                SELECT a.*, max(id) over () as max_pk
                FROM b_journal_temp a
            )
            WHERE id = max_pk
        ");

        $current_record = $query->result_array();

        $current_running_number = intval($current_record[0]['BILL_NUMBER']);

        $new_running_number = $current_running_number+1;

        $prefix_number = "";

        if (strlen($new_running_number) != 6) {
            
            for ($i=0; $i < (6-strlen($new_running_number)); $i++) { 
                
                $prefix_number .="0";    
            }
            $new_running_number = $prefix_number.$new_running_number;

            return $new_running_number;
        }

        return $new_running_number;
    }
}