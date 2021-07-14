<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    
    public function validateLogin(){
        $_POST  = json_decode(file_get_contents('php://input'), true);
        $data   = $this->input->post();

        $data['pass'] = md5($data['pass']);
        $validate =  $this->db->query("SELECT id, CONCAT(firstname,' ',lastname) AS name FROM users WHERE user = ? AND password = ?",array($data['user'],$data['pass']))->result();

        if(count($validate) > 0){
            $session = [
                "login" => TRUE,
                "userID" => $validate[0]->id,
                "user" => $validate[0]->name,
            ];
            $this->session->set_userdata($session);

            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function logout(){
        $this->session->sess_destroy();
        echo json_encode(1);
    }
}