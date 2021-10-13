<?php defined('BASEPATH') or exit('No direct script access allowed');

class HotelSysUsers_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //get active hotel user list
    public function get_active_hotel_users($limit, $start)
    {
        $this->db->where('H_User_Active', 1);
        $this->db->limit($limit, $start);
        $query = $this->db->get("hotel_sys_users");
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }

    public function huser_has_property()
    {
        $this->db->where('H_Sys_User_ID', $this->session->userdata('H_User_ID'));
        $query = $this->db->get("hotels");
        if ($query->num_rows() > 0) {
            return true;
        }else{
            return false;
        }
    }

    //get inactive hotel user list
    public function get_inactive_hotel_users($limit, $start)
    {
        $this->db->where('H_User_Active', 0);
        $this->db->limit($limit, $start);
        $this->db->order_by("H_User_ID", 'DESC');
        $query = $this->db->get("hotel_sys_users");
   
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }

    //Adding New User
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
            return true;
        } else {
            return false;
        }
    }

    //Activate New Users
    public function activate_user($h_user_id)
    {
        $hotel_id = $this->input->post('hotel_id');
        $this->db->where('H_User_ID', $h_user_id);
        $user_data = array('H_User_Active' => 1);
        $this->db->set($user_data);
        //check if the insertion done without any errors
        if ($this->db->update('hotel_sys_users')) {
            $this->db->reset_query();
            $hotel_data = array(
                "Hotel_Country_ID" => $this->input->post('country'),
                "Hotel_Governorate_ID" => $this->input->post('governorate'),
                "Hotel_Region_ID" => $this->input->post('city'),
                "Hotel_Address" => $this->input->post('full_address'),
                "Hotel_Email" => $this->input->post('hotel_email'),
                "Hotel_Fax" => $this->input->post('hotel_fax'),
                "Hotel_Phone" => $this->input->post('hotel_phone'),
                "State" => 1,
                "Star_Nums" => $this->input->post('hotel_stars')
            );

            $this->db->set($hotel_data);
            $this->db->where('Hotel_ID', $hotel_id);

            if ($this->db->update('hotels')) {
                $this->db->reset_query();
                $this->db->where('P_Item_ID', $hotel_id);
                $this->db->set("State", 1);
                if ($this->db->update('products')) {
                    $this->db->reset_query();
                    $provider_data = array(
                        "Admin_User_ID" => $this->session->userdata('Admin_User_ID'),
                        "Hotel_Sys_User_ID" => $h_user_id,
                        "Hotel_ID" => $hotel_id,
                        "MarkUp" => $this->input->post('markup'),
                        "Discount" => $this->input->post('discount'),
                        "Allow_4_B2B" => $this->input->post('allowb2b'),
                        "Allow_4_B2C" => $this->input->post('allowb2c'),
                        "State" => 1
                    );
                    $this->db->set($provider_data);
                    if ($this->db->insert('hotel_provider')) {

                        return $this->db->insert_id();
                    }
                }
            }
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
        if ($query->num_rows() > 0) {
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

    public function edit($user_id)
    {

        $user_data = array(
            'H_UserName' => $this->input->post('username'),
            'H_User_FullName' => $this->input->post('fullname'),
            'H_User_Email' => $this->input->post('email'),
            'H_User_Phone' => $this->input->post('userphone'),
        );
        if ($this->input->post('password') && !empty($this->input->post('password'))) {
            $user_data['H_User_PassWord'] = sha1($this->input->post('password'));
        }
        $this->db->set($user_data);
        $this->db->where('H_User_ID', $user_id);
        if ($this->db->update('hotel_sys_users')) {
            return true;
        } else {
            return false;
        }
    }
    //counting all rows for pagination
    public function users_count()
    {
        return $this->db->count_all("hotel_sys_users");
    }

    //active users count
    public function active_users_count()
    {
        $this->db->where('H_User_Active', 1);
        $query = $this->db->get('hotel_sys_users');
        return $query->num_rows();
    }
    //inactive users count
    public function inactive_users_count()
    {
        $this->db->where('H_User_Active', 0);
        return $this->db->get('hotel_sys_users')->num_rows();
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

    public function huser_profile(){
        $id = $this->session->userdata('H_User_ID');
        $this->db->where('H_User_ID', $id);
        $query = $this->db->get('hotel_sys_users');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }

    public function update_huser($id){
       
        $data = array(
            'H_UserName' => $this->input->post('husername'),
            'H_User_FullName' => $this->input->post('hfullname'),
            'H_User_Email'=> $this->input->post('hemail'),
            'H_User_Phone'=> $this->input->post('hphone'),
            'H_User_Active'=> 1
        );
        $this->db->where('H_User_ID', $id);
        $this->db->set($data);
        $query = $this->db->update('hotel_sys_users');
        if($query){
            return true;
        }else{
            return false;
        }
    }

    public function update_password(){
        $id = $this->session->userdata("H_User_ID");
        $data = array(
            'H_User_PassWord'=>sha1($this->input->post('confirm_pass'))
        );
        $this->db->where('H_User_ID', $id);
        $query = $this->db->update("hotel_sys_users", $data);
        if($query){
            return true;
        }else{
            return false;
        }
    }

    public function match_pass(){
        $pass = sha1($this->input->post('oldpass'));
        $this->db->where('H_User_PassWord', $pass);
        $query = $this->db->get('hotel_sys_users');
        if($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function hotel_user_details($id){
        $this->db->where('H_User_ID', $id);
        $query = $this->db->get('hotel_sys_users');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }


    }
}
