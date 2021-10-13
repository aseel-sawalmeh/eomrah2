<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hotel_Registeration_model extends CI_Model
{
    public $login_error = TRUE;
    public function __construct()
    {
        parent::__construct();
    }

    public function get_h_users()
    {
        $users = $this->db->get('buisness_users');
        return $users->result();
    }

    public function chain_register_hotel_user()
    {

        $b_username = html_escape($this->input->post('UserName'));
        $b_user_password = sha1(html_escape($this->input->post('Password')));
        $b_user_full_name = html_escape($this->input->post('FullName'));
        $b_user_email = html_escape($this->input->post('Email'));
        $b_user_phone = html_escape($this->input->post('Phone'));
        $b_type = html_escape($this->input->post('type'));
        //H_User_ID 	H_UserName 	H_User_PassWord 	H_User_FullName 	H_User_Email 	H_User_Phone 	H_User_Active 	H_Email_Verify 
        $user_data = array(
            'H_UserName' =>  $b_username,
            'H_User_PassWord' => $b_user_password,
            'H_User_FullName' => $b_user_full_name,
            'H_User_EMail' => $b_user_email,
            'H_User_Phone' =>  $b_user_phone,
        );
        $this->db->set($user_data);
       //show_error(print_r($this->db->get_compiled_insert('hotel_sys_users')));
       $hotel_user_insert = $this->db->insert('hotel_sys_users', $user_data);
       return $hotel_user_insert;
        /* if(){
            $usernameid = $this->db->insert_id();
            $hotel_category_id = 1;
            $hotel_Sys_user_id = $usernameid;
            $hotel_name = html_escape($this->input->post('HotelName'));
            $hotel_description = html_escape($this->input->post('HotelDescription'));
            $hotel_country = html_escape($this->input->post('country'));
            $hotel_governorate = html_escape($this->input->post('governorate'));
            $hotel_region = html_escape($this->input->post('region'));
            $hotel_Fax = html_escape($this->input->post('Fax'));
            $hotel_email = html_escape($this->input->post('Email'));
            $hotel_phone = html_escape($this->input->post('HotelPhone'));
            $hotel_address = html_escape($this->input->post('Hoteladdress'));
            $hotel_data = array(
                'H_Category_ID' => $hotel_category_id,
                'H_Sys_User_ID' => $hotel_Sys_user_id,
                'Hotel_Name' => $hotel_name,
                'Hotel_Description' => $hotel_description,
                'Hotel_Country_ID' => $hotel_country,
                'Hotel_Governorate_ID' => $hotel_governorate,
                'Hotel_Region_ID' => $hotel_region,
                'Hotel_Address' => $hotel_address,
                'Hotel_Email' => $hotel_email,
                'Hotel_Fax' => $hotel_Fax,
                'Hotel_Phone' => $hotel_phone
            );
            $this->db->set($hotel_data);
            
                if($this->db->insert('hotels')){
                $hotelid = $this->db->insert_id();
                $hotel_category_id = 1;
                $p_item_id = $hotelid;
                $p_reference = "UserRef".($p_item_id +1);
                $product_name = html_escape($this->input->post('HotelName'));
                $registered_by = html_escape($this->input->post('HotelName'));
                $p_description = html_escape($this->input->post('Registered_By'));
                $product_data = array('P_Reference'=>$p_reference, 'P_Name'=>$product_name, 'Registered_By' => $registered_by, 'P_Category_ID'=> $hotel_category_id, 'P_Item_ID'=> $p_item_id);
                $this->db->set($product_data);
                    if($this->db->insert('products')){
                        $this->session->set_userdata('signedmail', $h_user_email);
                        return TRUE;
                    }else{
                        show_error('the product not inserted');
                        return false;
                    }
                }else{
                    show_error('the hotel not inserted');
                    return false;
                }
        }else{
            show_error('user regstration failed');
            return FALSE;
        }
        */
    }

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
        $h_user_insert = $this->db->insert('hotel_sys_users');
        if ($h_user_insert) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_that_h_user($user_id)
    {
        $this->db->select('*')->from('hotel_sys_users')->where('H_User_ID', $user_id);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function checkUserExist($user_id)
    {
        $this->db->where('H_User_ID', $user_id);
        $query = $this->db->get('hotel_sys_users');
        if ($query->num_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkhUserExist($uhname)
    {
        $this->db->where('H_UserName', $uhname);
        $query = $this->db->get('hotel_sys_users');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkhmailExist($uhmail)
    {
        $this->db->where('H_User_Email', $uhmail);
        $query = $this->db->get('hotel_sys_users');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function users_count()
    {
        return $this->db->count_all("hotel_sys_users");
    }

    public function fetch_users($limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get("hotel_sys_users");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
}
