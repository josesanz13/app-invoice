<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
    function __construct(){
        parent::__construct();
    }

    public function show($client,$order){
        $sql = "SELECT 
            id
            ,number
            ,CASE status WHEN 'C' THEN 'Close' ELSE 'Open' END AS status
            ,userID
            ,amount_to_pay
            ,'' AS payments 
        FROM orders";

        if($order != 'ALL'){ 
            $sql = $sql.' WHERE id = ?';
        }

        $user       = $client == 'ALL' ? $this->db->query("SELECT *,'' AS orders FROM users")->result() : $this->db->query("SELECT *,'' AS orders FROM users WHERE id = ?",array($client))->result();
        $orders     = $order == 'ALL' ? $this->db->query($sql)->result() : $this->db->query($sql,array($order))->result();
        $payments   = $order == 'ALL' ? $this->db->query("SELECT * FROM payments")->result() : $this->db->query("SELECT * FROM payments WHERE orderID = ?",array($order))->result();

        if(count($user) > 1){
            for ($i=0; $i < count($user) ; $i++) {
                $user[$i]->orders = array(); 
                for ($j=0; $j < count($orders); $j++) { 
                    if($user[$i]->id == $orders[$j]->userID){
                        $user[$i]->orders[] = $orders[$j];
                        $arrPayments = array();
                        for ($k=0; $k < count($payments); $k++) { 
                            if($orders[$j]->id == $payments[$k]->orderID){
                                $arrPayments[] = $payments[$k];
                            }
                        }
                        $orders[$j]->payments = $arrPayments;
                    }
                }
            }
        }else{
            if(count($orders) > 1){
                $user[0]->orders = array();
                for ($j=0; $j < count($orders); $j++) { 
                    if($user[0]->id == $orders[$j]->userID){
                        $user[0]->orders[] = $orders[$j];
                        $arrPayments = array();
                        for ($k=0; $k < count($payments); $k++) { 
                            if($orders[$j]->id == $payments[$k]->orderID){
                                $arrPayments[] = $payments[$k];
                            }
                        }
                        $orders[$j]->payments = $arrPayments;
                    }
                }
            }else{
                $user[0]->orders = $orders;
                $user[0]->orders[0]->payments = $payments;
            }
        }
        echo json_encode($user); 
    }
}