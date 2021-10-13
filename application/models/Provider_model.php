<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * Model Provider_model
 *
 * This Model for ...
 *
 * @package        CodeIgniter
 * @category    Model
 * @author    Gebriel Alkhayal
 * @param     ...
 * @return    ...
 *
 */

class Provider_model extends CI_Model
{

    // ------------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();
    }
    public function active_providers_count()
    {
        if (!$this->session->userdata('Suser')) {
            $this->db->where('Hotel_Sys_User_ID', $this->session->userdata('H_User_ID'));
        }
        $this->db->where('State', 1);
        return $this->db->get("hotel_provider")->num_rows();
    }

    public function inactive_providers_count()
    {
        if (!$this->session->userdata('Suser')) {
            $this->db->where('Hotel_Sys_User_ID', $this->session->userdata('H_User_ID'));
        }
        $this->db->where('State', 0);
        return $this->db->get("hotel_provider")->num_rows();
    }

    public function fetch_active_provider($limit, $Offset)
    {
        $this->db->select('hotel_provider.Provider_ID, hotel_provider.Hotel_Sys_User_ID AS HuserID, hotels.Hotel_ID, hotels.Hotel_Name, hotels.Hotel_Address, Hotel_Email, Hotel_Phone');
        $this->db->join('hotels', 'hotel_provider.Hotel_ID = hotels.Hotel_ID');
        if (!$this->session->userdata('Suser')) {
            $user_id = $this->session->userdata('H_User_ID');
            $this->db->where('hotel_provider.Hotel_Sys_User_ID', $user_id);
        }
        $this->db->where('hotel_provider.State', 1);
        $this->db->order_by('Create_Date', 'DESC');
        $this->db->limit($limit, $Offset);
        $user_hotels = $this->db->get('hotel_provider');
        if ($user_hotels->num_rows() > 0) {
            return $user_hotels->result();
        }
        return false;
    }

    public function fetch_inactive_provider($limit, $Offset)
    {
        $this->db->select('hotel_provider.Provider_ID, hotel_provider.Hotel_Sys_User_ID AS HuserID, hotels.Hotel_ID, hotels.Hotel_Name, hotels.Hotel_Address, Hotel_Email, Hotel_Phone');
        $this->db->join('hotels', 'hotel_provider.Hotel_ID = hotels.Hotel_ID');
        if (!$this->session->userdata('Suser')) {
            $user_id = $this->session->userdata('H_User_ID');
            $this->db->where('hotel_provider.Hotel_Sys_User_ID', $user_id);
        }
        $this->db->where('hotel_provider.State', 0);
        $this->db->order_by('Create_Date', 'DESC');
        $this->db->limit($limit, $Offset);
        $user_hotels = $this->db->get('hotel_provider');
        if ($user_hotels->num_rows() > 0) {
            return $user_hotels->result();
        }
        return false;
    }

    public function activate($provider_id)
    {
        $provider_data = array(
            "Admin_User_ID" => $this->input->post('activator'),
            "MarkUp" => floatval($this->input->post('markup')),
            "Discount" => floatval($this->input->post('discount')),
            "Update_Date" => "NOW()",
            "Allow_4_B2C" => $this->input->post('allowb2c'),
            "Allow_4_B2B" => $this->input->post('allowb2b'),
            "State" => 1,
        );
        $this->db->set($provider_data);
        $this->db->where('Provider_ID', $provider_id);
        if ($this->db->update('hotel_provider')) {
            return true;
        } else {
            return false;
        }
    }

    public function getProvider($provider_id)
    {
        $this->db->where('Provider_ID', $provider_id);
        $provider = $this->db->get('hotel_provider');
        if ($provider->num_rows() > 0) {
            return $provider->result();
        } else {
            return false;
        }
    }

    public function delete_request($provider_id)
    {
        $whose_request = (!$this->session->userdata('Suser')) ? $this->session->userdata('H_User_ID') : false;
        $delete_request_data = array(
            "Req_Type" => 'delete',
            "H_User_ID" => $whose_request,
            "H_Provider_id" => $provider_id,
            "State" => 0,
        );
        $this->db->set($delete_request_data);
        if ($this->db->insert('provider_requests')) {
            return true;
        } else {
            return false;
        }
    }

    public function cancel_delete($provider_id)
    {
        $this->db->where('Req_Type', 'delete');
        $this->db->where('H_Provider_id', $provider_id);
        $this->db->where('State', 0);
        if ($this->db->delete('provider_requests')) {
            return true;
        } else {
            return false;
        }
    }

    public function confirm_delete_request($provider_id)
    {
        $admin_confirmer = ($this->session->userdata('Suser')) ? $this->session->userdata('Admin_User_ID') : false;
        $this->db->where('Req_Type', 'delete');
        $this->db->where('H_Provider_id', $provider_id);
        $this->db->where('State', 0);
        $delete_request_data = array(
            "Req_Type" => "deleted",
            "Approver_ID" => $admin_confirmer,
            "State" => 1,
        );
        $this->db->set($delete_request_data);
        if ($this->db->update('provider_requests')) {
            $this->db->reset_query();
            $data = array("State" => 3);
            $this->db->set($data);
            $this->db->where('Provider_ID', $provider_id);
            if ($this->db->update('hotel_provider')) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function request_state($provider_id)
    {
        $this->db->where('H_Provider_id', $provider_id);
        $this->db->where('State', 0);
        $result = $this->db->get('provider_requests');
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function request_list()
    {
        $provider_data = array(
            'Admin_User_ID' => null,
            'Hotel_Sys_User_ID' => html_escape($this->input->post('activate_for')),
            'Hotel_ID' => html_escape($this->input->post('hotels')),
        );
        $this->db->set($provider_data);
        if ($this->db->insert('hotel_provider')) {
            return true;
        } else {
            return false;
        }
    }

    public function hotelRequest()
    {
        $requestedHotel = array(
            'H_Category_ID' => 1,
            'H_Sys_User_ID' => html_escape($this->input->post('hotelUser')),
            'Hotel_Name' => html_escape($this->input->post('hotelName')),
            'Hotel_Description' => html_escape($this->input->post('hotelDescription')),
            'Hotel_Country_ID' => html_escape($this->input->post('hotelCountry')),
            'Hotel_Governorate_ID' => html_escape($this->input->post('hotelCity')),
            'Hotel_Region_ID' => html_escape($this->input->post('hotelRegion')),
            'Hotel_Address' => html_escape($this->input->post('hotelAddress')),
            'Hotel_Email' => html_escape($this->input->post('hotelEmail')),
            'Hotel_Fax' => html_escape($this->input->post('hotelFax')),
            'Hotel_Phone' => html_escape($this->input->post('hotelPhone')),
            'State' => 0,
            'Star_Nums' => html_escape($this->input->post('hotel_stars')),
        );
        $this->db->set($requestedHotel);
        if ($this->db->insert('hotels')) {
            $hotelId = $this->db->insert_id();
            $this->db->reset_query();
            $requestData = array(
                'H_R_Type' => 'addhotel',
                'H_User_ID' => html_escape($this->input->post('hotelUser')),
                'Hotel_ID' => $hotelId,
                'Approver_ID' => html_escape($this->input->post('activate_for')),
            );
            $this->db->set($requestData);
            //show_error($this->db->get_compiled_insert('HotelRequests'));
            if ($this->db->insert("HotelRequests")) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function delete($provider_id)
    {
        $this->db->where('Provider_ID', $provider_id);
        if ($this->db->delete('hotel_provider')) {
            return true;
        } else {
            return false;
        }
    }

    public function provider_result($find)
    {
        $this->db->select('hotel_provider.Provider_ID, hotel_provider.Hotel_Sys_User_ID AS HuserID, hotels.Hotel_ID, hotels.Hotel_Name, hotels.Hotel_Address, Hotel_Email, Hotel_Phone');
        $this->db->join('hotels', 'hotel_provider.Hotel_ID = hotels.Hotel_ID');
        if (!$this->session->userdata('Suser')) {
            $user_id = $this->session->userdata('H_User_ID');
            $this->db->where('hotel_provider.Hotel_Sys_User_ID', $user_id);
        }
        $this->db->where('hotel_provider.State', 1);
        $this->db->like('Hotel_Name', $find);
        $this->db->order_by('Create_Date', 'DESC');
        $user_hotels = $this->db->get('hotel_provider');
        //show_error(print_r($user_hotels));
        if ($user_hotels) {
            return $user_hotels->result();
        }
        return false;
    }

    public function provider_list($find)
    {
        $this->db->select('hotel_provider.Provider_ID, hotel_provider.Hotel_Sys_User_ID AS HuserID, hotels.Hotel_ID, hotels.Hotel_Name, hotels.Hotel_Address, Hotel_Email, Hotel_Phone');
        $this->db->join('hotels', 'hotel_provider.Hotel_ID = hotels.Hotel_ID', 'left');
        $this->db->join('hotel_sys_users', 'hotel_provider.Hotel_Sys_User_ID = hotel_sys_users.H_User_ID', 'left');
        $this->db->where('hotel_provider.State', 1);
        $this->db->like('H_UserName', $find);
        $user_hotels = $this->db->get('hotel_provider');
        //show_error(print_r($user_hotels));
        if ($user_hotels) {
            return $user_hotels->result();
        }
        return false;
    }

    public function provider_hotellist_count($id)
    {
        $this->db->select('hotel_provider.Provider_ID, hotel_provider.Hotel_Sys_User_ID AS H_User_ID, hotels.Hotel_ID, hotels.Hotel_Name, hotels.Hotel_Address, Hotel_Email, Hotel_Phone');
        $this->db->join('hotels', 'hotel_provider.Hotel_ID = hotels.Hotel_ID', 'left');
        $this->db->where('hotel_provider.State', 1);
        $this->db->where('Hotel_Sys_User_ID', $id);
        $user_hotels = $this->db->get('hotel_provider');
        if ($user_hotels) {
            return $user_hotels->num_rows();
        } else {
            return false;
        }
    }

    public function get_stats($provider_id)
    {

        $this->db->where('ProviderId', $provider_id);
        $providers = $this->db->get('p_reservation');
        return $providers->result();
    }

   

 

    public function searchp_fullstats($s, $period_id = null, $offset = null, $limit = null)
    {

        $this->db->where('ProviderId', $period_id);
        if ($offset != null) {
            $this->db->limit($offset, $limit);
        }
        $this->db->like('reservation_ref', $s);
        $query = $this->db->get('p_reservation');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

   
}

/* End of file Provider_model.php */
/* Location: ./application/models/Provider_model.php */