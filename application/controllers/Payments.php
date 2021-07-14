<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->library('pagination');
    }

    public function show($id){
        $sql =  "SELECT 
            o.amount_to_pay 
            ,IFNULL(SUM(p.amount),0) AS amount_paid
            ,IFNULL(o.amount_to_pay - IFNULL(SUM(p.amount),0),0) AS remain_amount
        FROM orders o 
            LEFT JOIN payments p ON o.id = p.orderID
        WHERE o.id = ?
        GROUP BY o.number";

        $sql2 = "SELECT id,amount,date FROM payments WHERE orderID = ?";

        $arrayData = array(
            'invoice' => $this->db->query($sql,array($id))->result(),
            'payments' => $this->db->query($sql2,array($id))->result()
        );

        echo json_encode($arrayData);
    } 

    public function store(){
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $this->input->post();

        
        $sql =  "SELECT 
            IFNULL(o.amount_to_pay - IFNULL(SUM(p.amount),0),0) AS amount
        FROM orders o 
            LEFT JOIN payments p ON o.id = p.orderID
        WHERE o.id = ?
        GROUP BY o.number";

        $validation = $this->db->query($sql,array($data['orderID']))->result();

        if (count($validation) > 0) {
            if($data['amount'] > $validation[0]->amount){
                echo json_encode(2);
                return;
            }
        }

        $this->db->trans_begin();
        
        $this->db->insert('payments',$data);

        if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		}else{
            $this->db->trans_commit();
			echo json_encode(1);
		}
    }

    public function edit($id){
        $sql = "SELECT id, amount, date FROM payments WHERE id = ?";
        echo json_encode($this->db->query($sql,array($id))->result());
    }

    public function update($id){
        $_POST  = json_decode(file_get_contents('php://input'), true);
        $data   = $this->input->post();

        $this->db->trans_begin();
        
        $this->db->where('id',$id);
        $this->db->update('payments',$data);

        if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		}else{
            $this->db->trans_commit();
			echo json_encode(1);
		}
    }

    public function delete($id){
        $this->db->trans_begin();
        
        $this->db->where('id',$id);
        $this->db->delete('payments');

        if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		}else{
            $this->db->trans_commit();
			echo json_encode(1);
		}
    }
}