<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

    public function show(){
        $sql = "SELECT
            o.id
            ,CASE o.status WHEN 'O' THEN 'Open' ELSE 'Close' END AS status
            ,o.number
            ,CONCAT(u.identification,' | ',u.firstname,' ',u.lastname) AS user 
        FROM orders o 
            LEFT JOIN users u ON o.userID = u.id";

        echo json_encode($this->db->query($sql)->result());
    }

    public function store(){
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $this->input->post();

        $this->db->trans_begin();

        $data['number'] = strtoupper($data['number']);
        
        $this->db->insert('orders',$data);

        if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		}else{
            $this->db->trans_commit();
			echo json_encode(1);
		}
    }

    public function edit($id){
        $this->db->where('id',$id);
        echo json_encode($this->db->get('orders')->result());
    }

    public function update($id){
        $_POST  = json_decode(file_get_contents('php://input'), true);
        $data   = $this->input->post();

        $this->db->trans_begin();
        
        $this->db->where('id',$id);
        $this->db->update('orders',$data);

        if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		}else{
            $this->db->trans_commit();
			echo json_encode(1);
		}
    }

    public function close($id){
        $this->db->trans_begin();
        
        $this->db->where('id',$id);
        $this->db->update('orders',array(
            'status'=>'C',
            'amount_to_pay' => rand(10,20)
        ));

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
        $this->db->delete('orders');

        if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		}else{
            $this->db->trans_commit();
			echo json_encode(1);
		}
    }

    public function getOrders($id){
        if($id == 'ALL'){
            echo json_encode($this->db->get('orders')->result());
        }else{
            $this->db->where('userID',$id);
            echo json_encode($this->db->get('orders')->result());
        }
    }
}