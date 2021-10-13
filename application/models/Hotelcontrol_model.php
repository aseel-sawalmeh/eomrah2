<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hotelcontrol_model extends CI_Model
{

    private $huserid;
    public function __construct()
    {
        parent::__construct();
        $this->huserid = $this->session->userdata('H_User_ID');
        $this->load->model('translate/translate_model', 'trm');
    }

    public function get_h_users()
    {
        $users = $this->db->get('buisness_users');
        return $users->result();
    }

    public function hasProvider($hotelid){
        $this->db->where('Hotel_ID', $hotelid);
        $hprovider = $this->db->get('hotel_provider');
        if($hprovider->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function provider_request()
    {
        //Admin_User_ID    Hotel_Sys_User_ID    Hotel_ID    MarkUp    Discount    Create_Date    Update_Date    Hotel_Commission    Allow_4_B2B    Allow_4_B2C    State
        $pdata = array( //commission hotelid
            'Hotel_Sys_User_ID' => $this->huserid,
            'Hotel_ID' => $this->input->post('hotelid'),
            'Hotel_Commission' => $this->input->post('commission'),
            'Allow_4_B2B' => $this->input->post('b2b'),
            'Allow_4_B2C' => $this->input->post('b2c'),
            'State' => 1
            //state is temporary for just inserting
        );

        $this->db->set($pdata);
        if ($this->db->insert('hotel_provider')) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function hotelinsert()
    {
        /*
        Array ( [hoteluser] => 6 [arhotelname] => [فندق حياة النزهة] [enhotelname] => Hayat Al Nozha Hotel [hotelcountry] => 682 [hotelcity] => 3793 [hoteladdress] => Old Makkah Jeddah Rd, Al Diyafah, Makkah 24221 7308, Saudi Arabia [hotel_stars] => 1 [hotelemail] => email@hotel10.com [hotelphone] => +1 (312) 668-4475 [hotelfax] => [hoteldescription] => Old Makkah Jeddah Rd, Al Diyafah, Makkah 24221 7308, Saudi Arabia [hotelplace_id] => ChIJPSIx4EIbwhURXIqboSrNn-c [hotellat] => 21.434142 [hotellng] => 39.798675 )

        hotels ->    Hotel_ID    hslug    H_Sys_User_ID     Hotel_Name    Hotel_Description    Main_Photo    Hotel_Country_ID    Hotel_City_ID    Hotel_Address    Hotel_Email    Hotel_Fax    Hotel_Phone    State    Star_Nums

        hotelmap --> id    hotel_id    lat    lng    placeid

        $this->session->userdata('H_User_ID')
        */
    //$this->input->post('arhotelname'),

        $hotel_data = array(
            'hslug' => slugify($this->input->post('enhotelname')),
            'H_Sys_User_ID' => $this->huserid,
            'Hotel_Name' => $this->input->post('enhotelname'),
            'Hotel_Description' => $this->input->post('hoteldescription'),
            'Hotel_Country_ID' => $this->input->post('hotelcountry'),
            'Hotel_City_ID' => $this->input->post('hotelcity'),
            'Hotel_Address' => $this->input->post('hoteladdress'),
            'Hotel_Email' => $this->input->post('hotelemail'),
            'Hotel_Fax' => $this->input->post('hotelfax'),
            'Hotel_Phone' => $this->input->post('hotelphone'),
            'Star_Nums' => $this->input->post('hotel_stars'),
        );
        $trdesc = trans($this->input->post('hoteldescription'), detectlang($this->input->post('hoteldescription')), 'ar');
        $this->db->set($hotel_data);
        $h_user_insert = $this->db->insert('hotels');
        if ($h_user_insert) {
            $hotelid = $this->db->insert_id();
            $trhotelname = $this->trm->add_hotel(['S_ID'=> $hotelid, 'S_TYPE'=>'hotelname', 'en'=>$this->input->post('enhotelname'), 'ar'=>$this->input->post('arhotelname')]);
            $trhoteldesc = $this->trm->add_hotel(['S_ID'=> $hotelid, 'S_TYPE'=>'hoteldesc', 'en'=>$this->input->post('hoteldescription'), 'ar'=>$trdesc]); 
            // if(!$trhotelname || !$trhoteldesc){
            //     show_error("not translated");
            // }
            $this->db->reset_query();
            $hotelmap_data = [
                'hotel_id' => $hotelid,
                'lat' => $this->input->post('hotellat'),
                'lng' => $this->input->post('hotellng'),
                'placeid' => $this->input->post('hotelplace_id'),
            ];
            $this->db->set($hotelmap_data);
            $hotelmap_insert = $this->db->insert('hotelmap');
            if ($hotelmap_insert) {
                return $hotelid;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function hotel_mainphoto($hotelid)
    {
        $this->db->where('Hotel_ID', $hotelid);
        $query = $this->db->get('hotels');
        if ($query->num_rows() > 0) {
            if (!$query->row()->Main_Photo || $query->row()->Main_Photo == null) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function gethotel($hotelid)
    {
        $this->db->where('Hotel_ID', $hotelid);
        $hotel = $this->db->get('hotels');
        if ($hotel->num_rows() > 0) {
            return $hotel->row();
        } else {
            return false;
        }
    }

    public function hotel_subphotos($hotelid)
    {
        $this->db->where('Hotel_ID', $hotelid);
        $query = $this->db->get('hotel_photos');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function userhotels()
    {
        $this->db->where('H_Sys_User_ID', $this->huserid);
        $hotels = $this->db->get('hotels');
        if ($hotels->num_rows() > 0) {
            return $hotels->result();
        } else {
            return false;
        }
    }

    public function checkUserExist($user_id)
    {
        $this->db->where('H_User_ID', $user_id);
        $query = $this->db->get('hotel_sys_users');
        if ($query->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function checkhUserExist($uhname)
    {
        $this->db->where('H_UserName', $uhname);
        $query = $this->db->get('hotel_sys_users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkhmailExist($uhmail)
    {
        $this->db->where('H_User_Email', $uhmail);
        $query = $this->db->get('hotel_sys_users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
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

    //insert hotel photos multi
    public function add_hotel_photos($data = array())
    {
        $insert = $this->db->insert_batch('hotel_photos', $data);
        return $insert ? true : false;
    }
    //insert hotel photos multi
    public function add_hotel_photo($hotel_id, $hotel_photo, $i)
    {
        $dataimg = array(
            'hotel_ID' => $hotel_id,
            'Photo_Name' => $hotel_photo,
            'Photo_Order' => $i,
        );
        $insert = $this->db->insert('hotel_photos', $dataimg);
        return $insert ? true : false;
    }

    public function get_photos($hotel_id)
    {
        $this->db->where('hotel_ID', $hotel_id);
        $this->db->order_by('Photo_Order', 'ASC');
        $query = $this->db->get('hotel_photos');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function del_photo($photo_id, $photo_name)
    {
        $this->db->where('Photo_ID', $photo_id);
        $deleted = $this->db->delete('hotel_photos');
        if ($deleted) {
            if (delimg($photo_name, 'hotels')) {
                return true;
            } else {
                echo " No Unlik";
            }
        } else {
            return false;
        }
    }

    public function setdef_photo($hotelid, $photo_name)
    {
        $this->db->where('hotel_ID', $hotelid);
        $this->db->set('Main_Photo', $photo_name);
        $updated = $this->db->update('hotels');
        if ($updated) {
            return true;
        } else {
            return false;
        }
    }

    public function getdef_photo($hotelid)
    {
        $this->db->where('hotel_ID', $hotelid);
        $hotel = $this->db->get('hotels');
        if ($hotel->num_rows() > 0) {
            return $hotel->row()->Main_Photo;
        } else {
            return false;
        }
    }

    public function hotel_photo_count($hotel_id)
    {
        $this->db->where('hotel_ID', $hotel_id);
        $exist = $this->db->get('hotel_photos');
        return $exist->num_rows();
    }
    public function hotel_photo_order($photo_id, $order)
    {
        $this->db->set('Photo_Order', $order);
        $this->db->where('Photo_ID', $photo_id);
        if ($this->db->update('hotel_photos')) {
            return true;
        } else {
            return false;
        }
    }
    public function hotel_photo_delete($photo_id)
    {
        $this->db->where('Photo_ID', $photo_id);
        if ($this->db->delete('hotel_photos')) {
            return true;
        } else {
            return false;
        }
    }

}
