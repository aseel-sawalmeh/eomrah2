<?php defined('BASEPATH') or exit('No direct script access allowed');

class Hotel_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_providers()
    {
        $user_id = $_SESSION['H_User_ID'];
        $this->db->where('Hotel_Sys_User_ID', $user_id);
        $providers = $this->db->get('hotel_provider');
        return $providers->result();
    }

    public function getRoomTypes()
    {
        return $this->db->get('room_type')->result();
    }

    public function whatRoomType($typeId)
    {
        $this->db->where('R_Type_ID', $typeId);
        return $this->db->get('room_type')->result();
    }

    public function getHotelRooms($hotel_id)
    {
        $this->db->join('room_type', "rooms.Room_Type = room_type.R_Type_ID", 'left');
        $this->db->where('Hotel_ID', $hotel_id);
        return $this->db->get('rooms')->result();
    }

    public function HotelRooms($hotel_id)
    {
        $this->db->select('hotel_rooms.roomTypeCode as rid, hotel_rooms.name_ar');
        $this->db->where('hotelid', $hotel_id);
        return $this->db->get('hotel_rooms')->result_array();
    }

    public function getRooms()
    {
        $result = $this->db->get('rooms');
        return $result ? $result->result() : false;
    }

    public function getThatRoom($room_id)
    {
        $this->db->join('products', 'Multi_Products.Subproduct_ID = Products.P_ID');
        $this->db->where('Room_ID', $room_id);
        $result = $this->db->get('rooms');
        return $result? $result->result():false;
    }

    public function getMeals()
    {
        return $this->db->get('meals')->result();
    }

    public function hotelAmenities($hotelid)
    {
        if(userlang() == 'ar'){
            $this->db->select('amenity_name_ar as name, amenityid as am_code, amenity_icon as icon');
        }else{
            $this->db->select('amenity_name as name, amenityid as am_code, amenity_icon as icon');

        }
        $this->db->join('amenties', "hotel_amenities.amenityid = amenties.amenity_lotsid");
        $result = $this->db->get_where('hotel_amenities', "hotelid = $hotelid");
        return $result? $result->result():false;
    }

    //geo of hotel

    public function hotelLeisures($hotelid)
    {
        $this->db->select('leisure_name as name');
        $this->db->join('leisures', "hotel_leisures.leisureid = leisures.leisure_code");
        $ls = $this->db->get_where('hotel_leisures', "hotelid = $hotelid");
        // show_error($this->db->error());
        return $ls ? $ls->result() : false;
    }


    public function hotelTransportation($hotelid)
    {
        $ls = $this->db->get_where('hotel_transportation', "hotelLotsId = $hotelid");
        // show_error($this->db->error());
        return $ls ? $ls->result() : false;
    }

    public function refreshRooms($hid, $rooms)

    {
        $query = 'INSERT IGNORE INTO hotel_rooms (roomTypeCode, hotelId, name, name_ar, twin, maxAdult, maxChildren, maxExtraBed, maxCapacity, allowedAdultsWithChildren, allowedAdultsWithoutChildren, updated) VALUES ';
        foreach($rooms as $room){
            $query .= '('. 
            $room['roomtypecode'] .', '.
            $hid .", '". 
            $room['name'] ."', '".
            trans($room['name'], 'en', 'ar') ."', ".
            (isset($room['twin'])?$room['twin']: '0') .', '. 
            $room['sleeps']['maxAdult'] .', '. 
            $room['sleeps']['maxChildren'].', '. 
            $room['sleeps']['maxExtraBed'] .', '. 
            $room['sleeps']['maxOccupancy'] .', '. 
            $room['sleeps']['maxAdultWithChildren'] .', '. 
            ($room['sleeps']['allowedAdultsWithoutChildren'] ?? '0') .', '.
            ' NOW()), ';
        }
        $query = rtrim($query, ", ");
        $query .= " DUPLICATE KEY UPDATE name_ar=VALUES(name_ar)";
        if($this->db->simple_query($query)){
            log_message('debug', "room refrshed hotelid $hid");
            return true;
        }else{
            log_message('error', "Error refreshing rooms hotelid $hid");

            return false;
        }
    }

    public function ins_occ_attr($roomId, $attrType, $maxAdult)
    {
        $attr_data = array(
            'Room_ID' => $roomId,
        );

        $action = '';
        $found = $this->db->get_where('room_occ_atrr', "Room_ID = {$roomId}");
        $foundresult = '';
        if ($found->num_rows() > 0) {
            $action = 'update';
            $foundresult = $found->result()[0];
            switch ($attrType) {
                case 1:
                    if ($maxAdult > $foundresult->Adlult_Count) {
                        $attr_data['Adlult_Count'] = $foundresult->Adlult_Count + 1;
                    } else {
                        $attr_data['Adlult_Count'] = $foundresult->Adlult_Count;
                    }
                    break;
                case 2:
                    $attr_data['Child_Count'] = $foundresult->Child_Count + 1;
                    break;
                case 3:
                    $attr_data['Infant_Count'] = $foundresult->Infant_Count + 1;
                    break;
                default:
                    $attr_data['Adlult_Count'] = $foundresult->Adlult_Count;
            }
        } else {
            $action = 'insert';
            switch ($attrType) {
                case 1:
                    $attr_data['Adlult_Count'] = 1;
                    break;
                case 2:
                    $attr_data['Child_Count'] = 1;
                    break;
                case 3:
                    $attr_data['Infant_Count'] = 1;
                    break;
                default:
                    $attr_data['Adlult_Count'] = 1;
            }
        }
        $this->db->reset_query();
        $this->db->set($attr_data);
        if ($action == 'update') {
            $this->db->where('Room_ID', $roomId);
            if ($this->db->update('room_occ_atrr')) {
                return true;
            } else {
                return false;
            }
        } elseif ($action == 'insert') {
            if ($this->db->insert('room_occ_atrr')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function del_occ_attr($roomId, $attrType)
    {
        $attr_data = array(
            'Room_ID' => $roomId,
        );
        $found = $this->db->get_where('room_occ_atrr', "Room_ID = {$roomId}");
        $foundresult = '';
        if ($found->num_rows() > 0) {
            $foundresult = $found->result()[0];
            switch ($attrType) {
                case 1:
                    if ($foundresult->Adlult_Count > 0) {
                        $attr_data['Adlult_Count'] = $foundresult->Adlult_Count - 1;
                    } else {
                        $attr_data['Adlult_Count'] = 0;
                    }
                    break;
                case 2:
                    if ($foundresult->Child_Count > 0) {
                        $attr_data['Child_Count'] = $foundresult->Child_Count - 1;
                    } else {
                        $attr_data['Child_Count'] = 0;
                    }
                    break;
                case 3:
                    if ($foundresult->Infant_Count > 0) {
                        $attr_data['Infant_Count'] = $foundresult->Infant_Count - 1;
                    } else {
                        $attr_data['Infant_Count'] = 0;
                    }
                    break;
                default:
                    $attr_data['Adlult_Count'] = $foundresult->Adlult_Count;
            }
        }

        $this->db->reset_query();
        $this->db->set($attr_data);
        $this->db->where('Room_ID', $roomId);
        if ($this->db->update('room_occ_atrr')) {
            return true;
        } else {
            return false;
        }
    }

    public function add_hotel_meal($hotel_id, $meal_id)
    {
        $hotel_meal_data = array(
            'Hotel_ID' => $hotel_id,
            'Meal_ID' => $meal_id,
            'State' => 1,
        );
        $this->db->where('Hotel_ID', $hotel_id);
        $this->db->where('Meal_ID', $meal_id);
        if ($this->db->get_where('hotel_meals', "Hotel_ID = {$hotel_id} AND Meal_ID = {$meal_id}")->num_rows() > 0) {
            return false;
        } else {
            $this->db->reset_query();
            $this->db->set($hotel_meal_data);
            if ($this->db->insert('hotel_meals')) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function get_hotel_meals($hotel_id)
    {
        $this->db->join('meals', "hotel_meals.Meal_ID = meals.Meal_ID");
        return $this->db->get_where('hotel_meals', "Hotel_ID = {$hotel_id}")->result();
    }

    public function roomfullfilled($room_id)
    {
        $this->db->select('rooms.Max_Occ, room_occ_atrr.Adlult_Count');
        $this->db->join('room_occ_atrr', "rooms.Room_ID = room_occ_atrr.Room_ID");
        $this->db->where('rooms.Room_ID', $room_id);
        $rooms = $this->db->get('rooms');
        if ($rooms->num_rows() > 0) {
            $room = $rooms->result()[0];
            if ($room->Max_Occ == $room->Adlult_Count) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function del_hotel_meal($hm_id)
    {
        $this->db->where('HM_ID', $hm_id);
        if ($this->db->delete('hotel_meals')) {
            return true;
        } else {
            return false;
        }
    }

    public function getroomattr($roomId)
    {
        return $this->db->get_where('room_occ_atrr', "Room_ID = {$roomId}")->result();
    }
    public function providers_count()
    {
        if (!$this->session->userdata('Suser')) {
            $user_id = $this->session->userdata('H_User_ID');
            $this->db->where('Hotel_Sys_User_ID', $user_id);
        }
        return $this->db->count_all_results("hotel_provider");
    }
    public function active_providers_count()
    {
        if (!$this->session->userdata('Suser')) {
            $user_id = $this->session->userdata('H_User_ID');
            $this->db->where('Hotel_Sys_User_ID', $user_id);
        }
        $this->db->where('State', 1);
        $res = $this->db->get("hotel_provider");
        return $res->num_rows();
    }
    public function inactive_providers_count()
    {
        $user_id = $this->session->userdata('H_User_ID');
        $this->db->where('Hotel_Sys_User_ID', $user_id);
        $this->db->where('State', 0);
        $res = $this->db->get("hotel_provider");
        //show_error(var_dump($res->num_rows()));
        return $res->num_rows();
    }

    //migrations
    public function get_hotels()
    {
        $hotels = $this->db->get('hotels');
        return $hotels->result();
    }

    public function slughotels($hid, $slug)
    {
        $this->db->set('hslug', $slug);
        $this->db->where('Hotel_ID', $hid);
        $up = $this->db->update('hotels');
        return $up;
    }

    public function hotelmain($hid, $photoname)
    {
        $this->db->set('Main_Photo', $photoname);
        $this->db->where('Hotel_ID', $hid);
        $up = $this->db->update('hotels');
        return $up;
    }

    //EndMigrations

    public function get_active_hotels()
    { //SELECT * FROM `hotels` WHERE `hotels`.`State` = 1 AND `hotels`.`Hotel_ID` NOT IN (SELECT Hotel_ID FROM hotel_provider WHERE `hotel_provider`.`State` = 1 AND hotel_provider.Hotel_Sys_User_ID = 1)

        $this->db->where('hotels.State', 1);
        $this->db->where('hotels.Hotel_ID NOT IN(SELECT Hotel_ID FROM hotel_provider WHERE `hotel_provider`.`State` = 1 AND hotel_provider.Hotel_Sys_User_ID =' . $this->session->userdata('H_User_ID') . ')');
        $query = $this->db->get('hotels');
        //show_error($query);
        if ($query) {
            return $query->result();
        } else {
            return false;
        }
        //return $this->db->get('hotels')->result();
    }
    public function add_hotel($h_hotelname, $h_hotel_password, $h_hotel_full_name, $h_hotel_email, $h_hotel_phone)
    {
        $hotel_data = array(
            'Hotel_Name' => $h_hotelname,
            'H_Category_ID' => $h_hotel_password,
            'H_Sys_Users_ID' => $h_hotel_full_name,
            'Hotel_Description' => $h_hotel_full_name,
            'Hotel_Country_ID' => $h_hotel_email,
            'Hotel_Governorate_ID' => $h_hotel_phone,
            'Hotel_Region_ID' => $h_hotel_phone,
            'Hotel_Email' => $h_hotel_phone,
            'Hotel_Fax' => $h_hotel_phone,
            'Hotel_Phone' => $h_hotel_phone,
        );
        $this->db->set($hotel_data);
        if ($this->db->insert('hotel_Sys_users')) {
            return true;
        } else {
            return false;
        }
    }
    public function addRoom($hotel_id, $Rtype, $MxOcc)
    {
        if (!$this->RoomExists($hotel_id, $Rtype)) {
            $room_data = array(
                'Room_Type' => $Rtype,
                'Max_Occ' => $MxOcc,
                'Hotel_ID' => $hotel_id,
            );
            $this->db->set($room_data);
            if ($this->db->insert('rooms')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function RoomExists($hotel_id, $Rtype)
    {
        $this->db->where('Room_Type', $Rtype);
        $this->db->where('Hotel_ID', $hotel_id);
        $result = $this->db->get('rooms');
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_room_max($room_id, $MxOcc)
    {
        $this->db->set('Max_Occ', $MxOcc);
        $this->db->where('Room_ID', $room_id);
        return $this->db->update('rooms') ? true : false;
    }

    public function get_that_hotel($hotel_id)
    {
        $this->db->select('*')->from('hotels')->where('Hotel_ID', $hotel_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }
    public function get_hotel_by_user($user_id)
    {
        $this->db->select('*')->from('hotels')->where('H_Sys_User_ID', $user_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }
    public function get_hotel_slug($slug)
    {
        $this->db->select('hotels.*, hotelmap.lat, hotelmap.lng');
        $this->db->join('hotelmap', "hotels.hotelLotsId = hotelmap.hotel_lotsid");
        $this->db->where('hslug', $slug);
        $query = $this->db->get('hotels');
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function get_hotelmap_slug($slug)
    {
        $this->db->like('hslug', $slug);
        $query = $this->db->get('hotels');
        if ($query->num_rows() > 0) {
            $hid = $query->row()->Hotel_ID;
            $this->db->reset_query();
            $this->db->where('hotel_id', $hid);
            $map = $this->db->get('hotelmap');
            if ($map->num_rows() > 0) {
                return $map->row();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function hotel_photos($hotel_id)
    {
        $this->db->select('Photo_Name as image');
        $this->db->where('Hotel_ID', $hotel_id);
        $this->db->order_by('Photo_Order', 'ASC');
        $hotel_photos = $this->db->get('hotel_photos');
        if ($hotel_photos->num_rows() > 0) {
            return $hotel_photos->result();
        } else {
            return false;
        }
    }

    public function checkhotelExist($hotel_id)
    {
        $this->db->where('Hotel_ID', $hotel_id);
        $query = $this->db->get('hotels');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function set_age($hotel_id, $agetype, $age_from, $age_to)
    {
        $exist = false;
        if ($this->db->where('Hotel_ID', $hotel_id)->get('room_occuppants')->num_rows() > 0) {
            $exist = true;
            $this->db->reset_query();
        } else {
            $this->db->reset_query();
        }
        if ($agetype == 'a_age') {
            $occ_age = array(
                'A_age_from' => $age_from,
                'A_age_to' => $age_to,
            );
        } elseif ($agetype == 'c_age') {
            $occ_age = array(
                'C_age_from' => $age_from,
                'C_age_to' => $age_to,
            );
        } elseif ($agetype == 'i_age') {
            $occ_age = array(
                'infant_age_from' => $age_from,
                'infant_age_to' => $age_to,
            );
        }
        if (!$exist) {
            $occ_age['Hotel_ID'] = $hotel_id;
            $this->db->set($occ_age);
            if ($this->db->insert('room_occuppants')) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('Hotel_ID', $hotel_id);
            $this->db->set($occ_age);
            if ($this->db->update('room_occuppants')) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function update_age($hotel_id, $agetype, $age_from, $age_to)
    {
        $occ_age = [];
        if ($agetype == 'a_age') {
            $occ_age = array(
                'A_age_from' => $age_from,
                'A_age_to' => $age_to,
            );
        } elseif ($agetype == 'c_age') {
            $occ_age = array(
                'C_age_from' => $age_from,
                'C_age_to' => $age_to,
            );
        } elseif ($agetype == 'i_age') {
            $occ_age = array(
                'infant_age_from' => $age_from,
                'infant_age_to' => $age_to,
            );
        }
        $this->db->where('Hotel_ID', $hotel_id);
        $this->db->set($occ_age);
        if ($this->db->update('room_occuppants')) {
            return true;
        } else {
            return false;
        }
    }
    public function get_hotel_age($hotel_id)
    {
        $exist = $this->db->where('Hotel_ID', $hotel_id)->get('room_occuppants');
        if ($exist->num_rows() > 0) {
            return $exist->result();
        } else {
            return false;
        }
    }
    public function hotels_count()
    {
        if (!$this->session->userdata('Suser')) {
            $this->db->where('H_Sys_User_ID', $this->session->userdata('H_User_ID'));
        }
        return $this->db->get("hotels")->num_rows();
    }
    public function fetch_inactive_provider($limit, $start)
    {
        $user_id = $this->session->userdata('H_User_ID');
        $this->db->where('Hotel_Sys_User_ID', $user_id);
        $this->db->where('State', 0);
        $this->db->limit($limit, $start);
        return $this->db->get("hotel_provider")->result();
    }

    public function fetch_inactive_hotelreq($limit, $start)
    {
        $user_id = $this->session->userdata('H_User_ID');
        $this->db->select('*');
        $this->db->from('hotelrequests');
        $this->db->join('hotel_sys_users', 'hotelrequests.H_User_ID = hotel_sys_users.H_User_ID', 'left');
        $this->db->join('hotels', 'hotelrequests.Hotel_ID = hotels.Hotel_ID', 'left');
        if (!$this->session->userdata('Suser')) {
            $this->db->where('hotelrequests.H_User_ID', $user_id);
        }
        $this->db->where('hotelrequests.State', 0);
        $this->db->limit($limit, $start);
        //show_error($this->db->get_compiled_select());
        $results = $this->db->get();
        return $results ? $results->result() : false;
    }

    public function requests_count()
    {
        $user_id = $this->session->userdata('H_User_ID');
        $this->db->where('H_User_ID', $user_id);
        $this->db->where('State', 0);
        return $this->db->count_all_results("hotelrequests");
    }
    public function fetch_hotels($limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get("hotels");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
    public function fetch_active_hotels($limit, $Offset)
    {

        if (!$this->session->userdata('Suser')) {
            $user_id = $this->session->userdata('H_User_ID');
            $this->db->where('H_Sys_User_ID', $user_id);
        }
        $this->db->where('State', 1);
        $this->db->order_by('Hotel_ID', 'DESC');
        $this->db->limit($limit, $Offset);
        $user_hotels = $this->db->get('hotels');
        if ($user_hotels->num_rows() > 0) {
            return $user_hotels->result();
        }
        return false;
    }
    public function fetch_inactive_hotels($limit, $Offset)
    {
        if (!$this->session->userdata('Suser')) {
            $user_id = $this->session->userdata('H_User_ID');
            $this->db->where('H_Sys_User_ID', $user_id);
        }
        $this->db->where('State', 0);
        $this->db->order_by('Hotel_ID', 'DESC');
        $this->db->limit($limit, $Offset);
        $user_hotels = $this->db->get('hotels');
        if ($user_hotels->num_rows() > 0) {
            return $user_hotels->result();
        }
        return false;
    }
    public function delete_hotel_request($rid, $hid)
    {
        $this->db->where('H_R_ID', $rid);
        if ($this->db->delete('hotelrequests')) {
            $this->db->where('Hotel_ID', $hid);
            if ($this->db->delete('hotels')) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function update_hotel_request($huid, $rid, $hid)
    {
        //Admin_User_ID    Hotel_Sys_User_ID    Hotel_ID    MarkUp    Discount    Create_Date    Update_Date    Hotel_Base_Reservation    Allow_4_B2B    Allow_4_B2C    State
        $pdata = array(
            'Admin_User_ID' => $this->session->userdata('hotels/gman_id'),
            'Hotel_Sys_User_ID' => $huid,
            'Hotel_ID' => $hid,
            'State' => 1,
        );

        $this->db->where('H_R_ID', $rid);
        $this->db->set('State', 1);
        if ($this->db->update('hotelrequests')) {
            $this->db->where('Hotel_ID', $hid);
            $this->db->set('State', 1);
            if ($this->db->update('hotels')) {
                $this->db->set($pdata);
                if ($this->db->insert('hotel_provider')) {
                    return $this->db->insert_id();
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function request_info($r_id)
    {
        $this->db->join('hotel_sys_users', 'hotelrequests.H_User_ID = hotel_sys_users.H_User_ID', 'left');
        $this->db->join('hotels', 'hotelrequests.Hotel_ID = hotels.Hotel_ID', 'left');
        $this->db->where('hotelrequests.H_R_ID', $r_id);
        //show_error(print_r($this->db->get_compiled_select('hotelrequests')));
        $results = $this->db->get('hotelrequests');
        return $results ? $results->row() : false;
    }

    public function activate_hotel($huid, $hid, $rid)
    {
        $rdata = array(
            'State' => 1,
        );
        $this->db->where('H_R_ID', $rid);
        $update_request = $this->db->update('hotelrequests', $rdata);
        var_dump($update_request . " Request update");
        if ($update_request) {
            $h_data = array(
                'Hotel_Name' => $this->input->post('h_name'),
                'Star_Nums' => $this->input->post('s_num'),
                'Hotel_Country_ID' => $this->input->post('h_country'),
                'Hotel_Governorate_ID' => $this->input->post('hotelCity'),
                'Hotel_Region_ID' => $this->input->post('hotelRegion'),
                'Hotel_Phone' => $this->input->post('hotelPhone'),
                'Hotel_Fax' => $this->input->post('hotelFax'),
                'Hotel_Email' => $this->input->post('hotelEmail'),
                'Hotel_Address' => $this->input->post('hotelAddress'),
                'Hotel_Description' => $this->input->post('hotelDescription'),
            );
            $this->db->where('Hotel_ID', $hid);

            $update_hotel = $this->db->update('hotels', $h_data);

            if ($update_hotel) {
                $provider_data = array(
                    'Admin_User_ID' => $this->session->userdata('hotels/gman_id'),
                    'Hotel_Sys_User_ID' => $huid,
                    'Hotel_ID' => $hid,
                    'State' => 1,
                    'MarkUp' => $this->input->post('markup'),
                    'Discount' => $this->input->post('discount'),
                    'Allow_4_B2B' => $this->input->post('allowb2c') ?? 0,
                    'Allow_4_B2C' => $this->input->post('allowb2b') ?? 0,
                );
                $update_provider = $this->db->insert('hotel_provider', $provider_data);
                return $update_provider;
            }
        }
    }
}
