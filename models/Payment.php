<?php
class Payment {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function create($data) {
        $this->db->query('INSERT INTO payments (payment_id, intent, status, amount, currency, payer_email, create_time, update_time) 
                         VALUES (:payment_id, :intent, :status, :amount, :currency, :payer_email, :create_time, :update_time)');
        
        $this->db->bind(':payment_id', $data['payment_id']);
        $this->db->bind(':intent', $data['intent']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':currency', $data['currency']);
        $this->db->bind(':payer_email', $data['payer_email']);
        $this->db->bind(':create_time', $data['create_time']);
        $this->db->bind(':update_time', $data['update_time']);
        
        return $this->db->execute();
    }
    
    public function updateStatus($paymentId, $status) {
        $this->db->query('UPDATE payments SET status = :status, update_time = NOW() WHERE payment_id = :payment_id');
        $this->db->bind(':status', $status);
        $this->db->bind(':payment_id', $paymentId);
        
        return $this->db->execute();
    }
    
    public function getPaymentById($paymentId) {
        $this->db->query('SELECT * FROM payments WHERE payment_id = :payment_id');
        $this->db->bind(':payment_id', $paymentId);
        
        return $this->db->single();
    }
}