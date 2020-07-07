<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_b_int_kewangan extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->sistem_perakaunan = $this->load->database('sistem_perakaunan',TRUE);
    }

    function insert($data_insert)
    {
        // Check if record already exist
        $data_search["SIRI"] = date('m')."-".date('Y');
        $data_search["SAH"] = "N";
        $status = $this->get($data_search);

        if ( count($status) == 0 )
        {
            if(!empty($data_insert['TARIKH']))
            {
                $this->sistem_perakaunan->set('TARIKH', 'to_date('."'".$data_insert["TARIKH"]."','dd/mm/yyyy')", false);
                unset($data_insert['TARIKH']);
            }

            $response = array(
                                "status" => $this->sistem_perakaunan->insert( 'AKRU_SEWAAN', $data_insert ),
                                "message" => "Berjaya"
                            );
        }
        else
        {
            $response = array(
                                "status" => FALSE,
                                "message" => "Akru bil bulan ini, no siri ".$data_search["SIRI"]." telah dihantar"
                            );
        }

        echo json_encode($response);
    }

    function get($data_search = array())
    {                
        $this->sistem_perakaunan->select('*');
        $this->sistem_perakaunan->from('AKRU_SEWAAN');

        if ( isset($data_search["SIRI"]) && $data_search["SIRI"] != "" )
        {
            // db_where('SIRI',$data_search['SIRI']);
            $this->sistem_perakaunan->where('SIRI', $data_search['SIRI'] );
        }

        if ( isset($data_search["SAH"]) && $data_search["SAH"] != "" )
        {
            // db_where('SAH',$data_search['SAH']);
            $this->sistem_perakaunan->where('SAH', $data_search['SAH'] );
        }
        
        $sql = $this->sistem_perakaunan->get();
        if($sql):
            return $sql->result_array();
        endif;
    }
}

