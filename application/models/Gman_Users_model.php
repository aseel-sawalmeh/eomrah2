<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gman_Users_model extends CI_Model
{
    public $login_error = TRUE;
    public function __construct()
    {
        parent::__construct();
    }

    public function get_users()
    {
        $users = $this->db->get('gman_admins');
        return $users->result();
    }

    public function add_user()
    {
        $user_data = array(
            'gman_Login' => $this->input->post('LoginName'),
            'gman_PassWord' => sha1($this->input->post('C_Pass')),
            'gman_FullName' => $this->input->post('FullName'),
            'gman_Email' => $this->input->post('Email'),
            'gman_phone' => $this->input->post('PhoneNum')
        );
        $this->db->set($user_data);
        if($this->db->insert('gman_admins')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function edit_user($user_id)
    {
      
        $user_data = array(
            'gman_Login' => $this->input->post('LoginName'),
            'gman_FullName' => $this->input->post('FullName'),
            'gman_Email' => $this->input->post('Email'),
            'gman_phone' => $this->input->post('PhoneNum')
        );
      
        $this->db->set($user_data);
        $this->db->where("gman_ID", $user_id);
       return $this->db->update('gman_admins');

    }


    public function get_that_user($user_id)
    {
        $this->db->where('gman_ID', $user_id);
        $query = $this->db->get('gman_admins');
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }


    public function checkUserExist($user_id)
    {
        $this->db->where('gman_ID', $user_id);
        $query = $this->db->get('gman_admins');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function users_count()
    {
        return $this->db->count_all("gman_admins");
    }

    public function active_users_count()
    {
        $this->db->where('state', 1);

        return $this->db->get("gman_admins")->num_rows();
    }

    public function inactive_users_count()
    {
        $this->db->where('state', 0);

        return $this->db->get("gman_admins")->num_rows();
    }

    public function fetch_users($limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get("gman_admins");
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;

        }
        
    }

    public function fetch_b2b(){
        $query = $this->db->get('b2b_users');
        if($query->num_rows() > 0){
            return $query->result();
            show_error("true");
        }else{
            return false;
        }
    }

    public function activate_b2b_user($id){
        $this->db->where('C_ID', $id);
        $data = array(
            'state'=> 1
        );
       $query =  $this->db->update('b2b_users', $data);
       if($query){
           return true;
       }else{
           return false;
       }

    }

    public function b2b_user_details($id){
        $this->db->where('C_ID', $id);
        $query = $this->db->get('b2b_users');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }
    public function b2c_user_list(){
        $this->db->where('state', 1);
        $query = $this->db->get('public_users');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    public function add_deposit($id,$amount){
        $this->db->where('C_ID', $id);
        $data = array(
            'Deposit'=>$amount
        );
       $query = $this->db->update('b2b_users', $data);

       if($query){
           return true;
       }else{
           return false;
       }
    }
    public function get_b2binvoicelist($limit, $Offset, $id)
    {
        $this->db->where('P_UserId', $id);
        $this->db->limit($limit, $Offset);
        $query = $this->db->get('p_reservation');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function b2b_invoicecount($id)
    {
      
        $this->db->where('P_UserId', $id);
        $query = $this->db->get('p_reservation');
        if ($query) {
            return $query->num_rows();
        } else {
            return false;
        }
    }
}
