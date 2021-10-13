<?php
class Count extends CI_MODEL
{
    public function get_h_count()
    {
        $this->db->select('*')->from('hotels');
        $this->db->where('State', 1);
        $query = $this->db->get();
       if($query){
           return $query->num_rows();
       }

    }
    public function getin_h_count()
    {
        $this->db->select('*')->from('hotels');
        $this->db->where('State', 0);
        $query = $this->db->get();
       if($query){
           return $query->num_rows();
       }

    }
    public function getpr_count()
    {
        $this->db->select('*')->from('hotelrequests');
        $this->db->where('State', 0);
        $query = $this->db->get();
       if($query){
           return $query->num_rows();
       }

    }
    public function getpro_count()
    {
        $this->db->select('*')->from('hotel_provider');
        $this->db->where('State', 1);
        $query = $this->db->get();
       if($query){
           return $query->num_rows();
       }

    }
    public function ausers_count()
    {
        $this->db->select('*')->from('hotel_sys_users');
        $this->db->where('H_User_Active', 1);
        $query = $this->db->get();
       if($query){
           return $query->num_rows();
       }

    }
    public function inausers_count()
    {
        $this->db->select('*')->from('hotel_sys_users');
        $this->db->where('H_User_Active', 0);
        $query = $this->db->get();
       if($query){
           return $query->num_rows();
       }

    }
    public function acpro_count()
    {
        $this->db->select('*')->from('products');
        $this->db->where('state', 1);
        $query = $this->db->get();
       if($query){
           return $query->num_rows();
       }

    }
    public function inacpro_count()
    {
        $this->db->select('*')->from('products');
        $this->db->where('state', 0);
        $query = $this->db->get();
       if($query){
           return $query->num_rows();
       }

    }

    public function exist_providing()
    {
            $this->db->select('hotel_provider.Provider_ID, hotel_provider.Hotel_Sys_User_ID AS HuserID, hotels.Hotel_ID, hotels.Hotel_Name, hotels.Hotel_Address, Hotel_Email, Hotel_Phone');
        $this->db->join('hotels', 'hotel_provider.Hotel_ID = hotels.Hotel_ID');
        if (!$this->session->userdata('Suser')) {
        $user_id = $this->session->userdata('H_User_ID');
        $this->db->where('hotel_provider.Hotel_Sys_User_ID', $user_id);
        }
        $this->db->where('hotel_provider.State', 0);
        $user_hotels = $this->db->get('hotel_provider');
        if ($user_hotels) {
            return $user_hotels->num_rows();
        }else{
            return 0;
        }
        
    }

    public function paidb2bres($id){
        $this->db->where('P_UserId', $id);
        $this->db->where('b2b', 1);
        $this->db->where('Paid', 1);
        $query = $this->db->get('p_reservation');
        
        if($query){
            return $query->num_rows();
        }
        
    }
    public function unpaidb2bres($id){
        $this->db->where('P_UserId', $id);
        $this->db->where('b2b', 1);
        $this->db->where('Paid', 0);
        $query = $this->db->get('p_reservation');
        
        if($query){
            return $query->num_rows();
        }
        
    }

    public function confirmedb2bres($id){
        $this->db->where('P_UserId', $id);
        $this->db->where('b2b', 1);
        $this->db->where('Paid', 1);
        $this->db->where('confirm', 2);
        $query = $this->db->get('p_reservation');
        
        if($query){
            return $query->num_rows();
        }
        
    }

    public function b2bsales($id){
        $this->db->select_sum('NetPrice','total');
        $this->db->where('P_UserId', $id);
        $this->db->where('b2b', 1);
        $this->db->where('Paid', 1);
      
        $query = $this->db->get('p_reservation');
        
        if($query){
            $res = $query->row_array();
            return $res['total'];
        }
        
    }

    public function b2bdeposit($id){
       $this->db->where('C_ID', $id);
      return $this->db->get('b2b_users')->row()->Deposit;

      
        
        
        
    }
}