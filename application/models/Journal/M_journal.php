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

        $query = $model->db->query("SELECT b.*,a.account_number,u.user_name,j.journal_code,j.journal_desc,
                                    (SELECT account_number FROM acc_account WHERE account_id=b.transfer_account_id) as new_account
                                    FROM b_journal_temp b, acc_account a, users u, a_journal j 
                                    WHERE b.account_id=a.account_id AND u.user_id=b.CREATED_BY And b.journal_id=j.journal_id
                                    ORDER BY bill_number");
        $result = $query->result_array();
        db_order('category_code');

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

    function get_a_journal_all(){
        $model =& get_instance();

        $query = $model->db->query("SELECT * FROM a_journal");
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

    function get_journal_by_id($id)
    {
        db_select('journal.*');
        db_select('admin.MCT_TRDESC as ITEM_DESC');
        db_join('admin.mctrancode admin','admin.mct_trcodenew = journal.tr_code');
        db_from('b_journal_temp journal');
        db_where('journal.id',$id);
        $sql = db_get();
        if($sql):
            return $sql->row_array();
        endif;
    }

    function update_journal($journal_id,$data_update)
    {
        db_where('id',$journal_id);
        db_update('b_journal_temp',$data_update);
        return true;
    }

    // This function is obsolete. Please use function at the controller with the same name as this function
    // function approveOrDeclineJurnal($id,$status_approval,$updated_by,$updated_at)
    // {
    //     $model =& get_instance();

    //     $q_get_journal = $model->db->query("
    //                                         SELECT
    //                                             b_journal_temp.*, admin.MCT_TRDESC as ITEM_DESC
    //                                         FROM
    //                                             b_journal_temp,admin.mctrancode admin
    //                                         WHERE
    //                                             b_journal_temp.tr_code=admin.mct_trcodenew and 
    //                                             id = ?
    //                                     ",[$id]);

    //     $get_journal = $q_get_journal->result_array();

    //     $q_acc_account = $model->db->query( "
    //                                             SELECT
    //                                                 BILL_TYPE
    //                                             FROM
    //                                                 acc_account
    //                                             WHERE
    //                                                 account_id = ?
    //                                         ",[$get_journal[0]["ACCOUNT_ID"]]);

    //     $acc_account = $q_acc_account->result_array();
    //     $bill_type = $acc_account[0]["BILL_TYPE"];

    //     $update_journal = $model->db->query("
    //                                             UPDATE
    //                                                 b_journal_temp
    //                                             SET
    //                                                 STATUS_APPROVAL = ?,
    //                                                 UPDATED_BY = ?,
    //                                                 UPDATED_AT = ?
    //                                             WHERE
    //                                                 id = ?
    //                                         ",[
    //                                             $status_approval,
    //                                             $updated_by,
    //                                             $updated_at,
    //                                             $id
    //                                         ]);

    //     $journal_update["STATUS_APPROVAL"] = $status_approval;
    //     $journal_update["UPDATED_BY"] = $updated_by;
    //     $journal_update["UPDATED_AT"] = $updated_at;

    //     $this->update_journal($id,$journal_update);

    //     if ($status_approval==1) 
    //     {
            
    //         $q1 = $model->db->query("
    //                                     SELECT
    //                                         COUNT(*) AS TOTAL
    //                                     FROM
    //                                         b_master
    //                                     WHERE
    //                                         BILL_NUMBER = ?
    //                                 ",[$get_journal[0]['BILL_NUMBER']]);

    //         $exist_in_b_master = $q1->result_array();

    //         $bill_id="";

    //         if ($exist_in_b_master[0]["TOTAL"] > 0) 
    //         {
    //             $q2 = $model->db->query("
    //                                         SELECT
    //                                             BILL_ID,
    //                                             TOTAL_AMOUNT
    //                                         FROM
    //                                             b_master
    //                                         WHERE
    //                                             BILL_NUMBER = ?
    //                                     ",[
    //                                         $get_journal[0]['BILL_NUMBER']
    //                                     ]);

    //             $b_master = $q2->result_array();

    //             $bill_id = $b_master[0]["BILL_ID"];

    //             $update_b_master = $model->db->query("
    //                                                     UPDATE
    //                                                         b_master
    //                                                     SET
    //                                                         TOTAL_AMOUNT = ?
    //                                                     WHERE
    //                                                         BILL_ID = ?
    //                                                 ",[
    //                                                     ($b_master[0]["TOTAL_AMOUNT"]+$get_journal[0]['AMOUNT']),
    //                                                     $bill_id
    //                                                 ]);
    //         }
    //         else 
    //         {
    //             $insert_b_master = $model->db->query("
    //                                                     INSERT INTO b_master
    //                                                     (
    //                                                         DT_ADDED,
    //                                                         ACCOUNT_ID,
    //                                                         BILL_NUMBER,
    //                                                         BILL_MONTH,
    //                                                         BILL_YEAR,
    //                                                         TOTAL_AMOUNT,
    //                                                         BILL_TYPE,
    //                                                         BILL_CATEGORY
    //                                                     )
    //                                                     VALUES
    //                                                     (
    //                                                         ?,
    //                                                         ?,
    //                                                         ?,
    //                                                         ?,
    //                                                         ?,
    //                                                         ?,
    //                                                         ?,
    //                                                         ?
    //                                                     )
    //                                                 ",[
    //                                                     date('d-M-Y h:i:s'),
    //                                                     $get_journal[0]['ACCOUNT_ID'],
    //                                                     $get_journal[0]['BILL_NUMBER'],
    //                                                     $get_journal[0]['BILL_MONTH'],
    //                                                     $get_journal[0]['BILL_YEAR'],
    //                                                     $get_journal[0]['AMOUNT'],
    //                                                     $bill_type,
    //                                                     'J'
    //                                                 ]);
                
    //             $q2 = $model->db->query("
    //                                         SELECT
    //                                             BILL_ID
    //                                         FROM
    //                                             b_master
    //                                         WHERE
    //                                             BILL_NUMBER = ?
    //                                     ",[
    //                                         $get_journal[0]['BILL_NUMBER']
    //                                     ]);

    //             $b_master = $q2->result_array();

    //             $bill_id = $b_master[0]["BILL_ID"];
    //         }

    //         $insert_b_item = $model->db->query("
    //             INSERT INTO b_item
    //             (
    //                 BILL_ID,
    //                 JOURNAL_ID,
    //                 ACCOUNT_ID, 
    //                 AMOUNT,
    //                 DT_ADDED,
    //                 BILL_CATEGORY,
    //                 TR_CODE,
    //                 ITEM_DESC
    //             )
    //             VALUES
    //             (
    //                 ?,
    //                 ?,
    //                 ?,
    //                 ?,
    //                 ?,
    //                 ?,
    //                 ?,
    //                 ?
    //             )
    //         ",[
    //             $bill_id,
    //             $get_journal[0]['JOURNAL_ID'],
    //             $get_journal[0]['ACCOUNT_ID'],
    //             $get_journal[0]['AMOUNT'],
    //             date('d-M-Y h:i:s'),
    //             'J',
    //             $get_journal[0]['TR_CODE'],
    //             $get_journal[0]['ITEM_DESC'],
    //         ]);
    //     }

    //     return $update_journal;
    // }

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

    function get_lists_temp_journal_report($data_search=array())
    {
        db_select('b.*,a.account_number,u.user_name,j.journal_code,j.journal_desc,c.category_name,(SELECT account_number FROM acc_account WHERE account_id=b.transfer_account_id) as new_account');
        db_from('b_journal_temp b');
        db_join('acc_account a','b.account_id = a.account_id');
        db_join('users u','u.user_id = b.CREATED_BY');
        db_join('a_journal j','b.journal_id = j.journal_id'); 
        db_join('a_category c','a.category_id = c.category_id','LEFT');
        db_order('bill_number');
         // $model =& get_instance();
        //        $query = $model->db->query("SELECT b.*,a.account_number,u.user_name,j.journal_code,j.journal_desc,c.category_name FROM b_journal_temp b, acc_account a, users u, a_journal j, a_category c WHERE b.account_id=a.account_id AND u.user_id=b.CREATED_BY AND b.journal_id=j.journal_id AND a.category_id=c.category_id ORDER BY bill_number");

        if(isset($data_search['date_start']) && having_value($data_search['date_start'])):
            db_where("b.dt_added >= to_date('".date('d-M-y',strtotime($data_search['date_start']))."')");
        endif;

        if(isset($data_search['date_end']) && having_value($data_search['date_end'])):
            db_where("b.dt_added <= to_date('".date('d-M-y',strtotime($data_search['date_end']))."')");
        endif;

        if(isset($data_search['acc_status']) && having_value($data_search['acc_status'])):
            db_where('a.status_acc',$data_search['acc_status']);
        endif;

        if(isset($data_search['category_id']) && having_value($data_search['category_id'])):
            db_where('a.category_id',$data_search['category_id']);
        endif; 

        if(isset($data_search['journal_id']) && having_value($data_search['journal_id'])):
            db_where('b.journal_id',$data_search['journal_id']);
        endif;

        // db_group("a.category_id.ITEM_DESC");
        // $result = $query->result_array();
        // if ($result) {
            
        //     return $result;
        // }
        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function get_lists_temp_journal_print($data_search = array())
    {
        // $model =& get_instance();

        // $query = $model->db->query("SELECT b.*,a.account_number,u.user_name,j.journal_code,j.journal_desc FROM b_journal_temp b, acc_account a, users u, a_journal j WHERE b.account_id=a.account_id AND u.user_id=b.CREATED_BY And b.journal_id=j.journal_id AND b.status_approval=0 ORDER BY bill_number");
        // $result = $query->result_array(); 
        // db_order('category_code');

        // if ($result) {
            
        //     return $result;
        // }

        db_select('b.*');
        db_select('a.account_number,u.user_name,j.journal_code,j.journal_desc,(SELECT account_number FROM acc_account WHERE account_id=b.transfer_account_id) as new_account');
        db_from('b_journal_temp b');
        db_join('acc_account a','b.account_id=a.account_id');
        db_join('users u','u.user_id=b.CREATED_BY');
        db_join('a_journal j','b.journal_id=j.journal_id');
        db_where('b.status_approval = 0');


        if (isset($data_search["user_id"]) && having_value($data_search['user_id']) )
        {
            db_where('b.CREATED_BY',$data_search['user_id']);
        }

        db_order('bill_number');
        // db_order('category_code');

        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }

    function get_a_journal_detail($data_search=array())
    {
        db_select('*');
        db_from('a_journal a');

        if (isset($data_search["JOURNAL_ID"]) && having_value($data_search['JOURNAL_ID']) )
        {
            db_where('a.JOURNAL_ID',$data_search['JOURNAL_ID']);
        }

        if (isset($data_search["JOURNAL_CODE"]) && having_value($data_search['JOURNAL_CODE']) )
        {
            db_where('a.JOURNAL_CODE',$data_search['JOURNAL_CODE']);
        }

        $sql = db_get('');
        if($sql):
            return $sql->result_array('');
        endif;
    }
}