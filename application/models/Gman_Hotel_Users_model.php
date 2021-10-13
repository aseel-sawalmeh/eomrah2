<?php defined('BASEPATH') or exit('No direct script access allowed');

class Gman_Hotel_Users_model extends CI_Model
{
    public $login_error = TRUE;
    public function __construct()
    {
        parent::__construct();
    }

    public function get_h_users()
    {
        $users = $this->db->get('hotel_sys_users');
        return $users->result();
    }
    //Adding New Users
    public function add_h_user($h_username, $h_user_password, $h_user_full_name, $h_user_email, $h_user_phone)
    {
        $user_data = array(
            'H_UserName' => $h_username,
            'H_User_PassWord' => $h_user_password,
            'H_User_FullName' => $h_user_full_name,
            'H_User_EMail' => $h_user_email,
            'H_User_Phone' => $h_user_phone
        );
        $this->db->set($user_data);
        //check if the insertion done without any errors
        if ($this->db->insert('hotel_sys_users')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //check if the user id exist for edit
    public function get_that_h_user($user_id)
    {
        //select where user name found
        $this->db->select('*')->from('hotel_sys_users')->where('H_User_ID', $user_id);
        // Run the query
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    //check if the user id exist for edit
    public function checkUserExist($user_id)
    {
        //select where user name found
        $this->db->where('H_User_ID', $user_id);
        // Run the query
        $query = $this->db->get('hotel_sys_users');
        if ($query->num_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    //counting all rows for pagination
    public function users_count()
    {
        return $this->db->count_all("hotel_sys_users");
    }
    //counting all rows for pagination
    public function active_users_count()
    {
        $this->db->where('H_User_Active', 1);

        return $this->db->get("hotel_sys_users")->num_rows();
    }
    //counting all rows for pagination
    public function inactive_users_count()
    {
        $this->db->where('H_User_Active', 0);

        return $this->db->get("hotel_sys_users")->num_rows();
    }
    //pagination fetch
    public function fetch_users($limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get("hotel_sys_users");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
    //Pagination fetch
   


}
