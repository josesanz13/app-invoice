<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    function __construct(){
        parent::__construct();
    }

    public function show(){
        echo json_encode(array(
            'userLog' => $this->session->userdata('user'),
            'data' => $this->db->get('users')->result()
        ));
        // echo json_encode($this->db->get('users')->result());
    }

    public function store(){
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $this->input->post();

        $this->db->trans_begin();

        if($data['password'] != ''){
            $data['password'] = md5($data['password']);
        }
        
        $this->db->insert('users',$data);

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
        echo json_encode($this->db->get('users')->result());
    }

    public function update($id){
        $_POST  = json_decode(file_get_contents('php://input'), true);
        $data   = $this->input->post();

        if($data['password'] != ''){
            $data['password'] = md5($data['password']);
        }else{
            unset($data['password']);
        }

        $this->db->trans_begin();
        
        $this->db->where('id',$id);
        $this->db->update('users',$data);

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
        $this->db->delete('users');

        if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		}else{
            $this->db->trans_commit();
			echo json_encode(1);
		}
    }
}