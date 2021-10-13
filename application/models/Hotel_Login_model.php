<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hotel_Login_model extends CI_Model
{
    public $login_error = TRUE;
    public function __construct(){
        parent::__construct();
    }

    public function validate()
    {
            $h_login_username = html_escape($this->input->post('login_username'));
            $h_login_password = sha1(html_escape($this->input->post('login_password')));
            // Prep the query
            $this->db->where('H_UserName', $h_login_username);
            $this->db->where('H_User_PassWord', $h_login_password);
            $this->db->where('H_Email_Verify', 1);
            $this->db->where('H_User_Active', 1);
            // Run the query
            $query = $this->db->get('hotel_sys_users');
            // Let's check if there are any results
            if($query->num_rows() > 0)
            {
                // If there is a user, then create session data
                $row = $query->row(); 
                $data = array(
                        'H_User_ID' => $row->H_User_ID,
                        'H_User_FullName' => $row->H_User_FullName,
                        'H_User_Email' => $row->H_User_Email,
                        'H_UserName' => $row->H_UserName,
                        'hvalidated' => true,
                        'Suser' => false
                        );
                $this->session->set_userdata($data);
                return true;
            }else
            {
                // If the previous process did not validate
                // then return false.
                $this->login_error = false;
                return false;
                
            }
            
    }
    //check if the user exist
    public function checkUserExist($username)
    {
        //select where user name found
        $this->db->where('H_UserName', $username);
        // Run the query
        $query = $this->db->get('hotel_sys_users');
        //show_error(var_dump($query->result()));
        if($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    //check if the user exist
    public function checkUserEmailConfirmed($username)
    {
        //select where user name found
        $this->db->where('H_UserName', $username);
        $this->db->where('H_Email_Verify', 1);
        // Run the query
        $query = $this->db->get('hotel_sys_users');
        if($query->num_rows() > 0){
            return true;
        }else{
            return FALSE;
        }
    }
}
?>