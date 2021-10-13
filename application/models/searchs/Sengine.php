<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sengine extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function hotelExist($hotel_id)
    {
        $hotel_found = $this->db->get_where('hotels', "Hotel_ID = $hotel_id ");
        if($hotel_found->num_rows > 0) {
            return true;
        }else{
            return false;
        }
    }

    public function hotel_providers($hotel_id)
    {
        $providers = $this->db->get_where('hotel_provider', "Hotel_ID = $hotel_id ");
        if($providers->num_rows() > 0 ) {
            return $providers->result();
        }else{
            return false;
        }
    }

    public function allhotel_providers($hotel_ids)
    {
        $all_hotel_providers = [];
        foreach($hotel_ids as $hotel_id){
            $this->db->reset_query();
            $this->db->where("Hotel_ID", $hotel_id['Hotel_ID']);
            $providers = $this->db->get('hotel_provider');
            if($providers) {
                foreach($providers->result() as $provider){
                    $all_hotel_providers[] = $provider;
                }
            }else{
                return false;
            }
        }
        return $all_hotel_providers;
    }

    public function getperiods($providerid)
    {
        $this->db->where('providerID', $providerid);
        $result = $this->db->get("hp_periods");
        return ($result->num_rows() > 0 )?$result->result():false;
    }
    
    public function av_mealed_rooms($period_id)
    {
        $this->db->join('meals', 'rmovertype.Mtype_ID = meals.Meal_ID', 'left');
        $this->db->where('Period_ID', $period_id);
        $result = $this->db->get('rmovertype');
        return $result?$result->result():false;
    }
    public function av_room_suplement($period_id)
    {
        $this->db->join('r_supptypes', 'room_supplements.R_SuppType_ID = r_supptypes.R_SuppType_ID');
        $this->db->where('Period_ID', $period_id);
        $results = $this->db->get('room_supplements');
        return $results?$results->result():false;
    }
    public function av_room_daysuplement($period_id)
    {
        $this->db->where('Period_ID', $period_id);
        $results = $this->db->get('pdaysupplement');
        return $results?$results->result():false;
    }

    public function room_availabality($povider_id)
    {
        $this->db->join('room_type', 'room_type_availability.R_Type_ID = room_type.R_Type_ID', 'left');
        $this->db->where('providerID', $povider_id);
        $results = $this->db->get('room_type_availability');
        return $results?$results->result():false;
        //show_error(var_dump($results->result()));
    }

    public function room_type_maxocc($period_id)
    {
        $results = $this->db->query("SELECT room_type.R_Type_Sn, room_occ_atrr.Adlult_Count,
         room_occ_atrr.Child_Count, room_occ_atrr.Infant_Count FROM `rooms` LEFT JOIN room_occ_atrr ON rooms.Room_ID = room_occ_atrr.Room_ID LEFT JOIN room_type ON rooms.room_type = room_type.R_Type_ID WHERE Hotel_ID = ANY ( SELECT Hotel_ID FROM hotel_provider WHERE hotel_provider.Provider_ID = ANY (SELECT providerID FROM hp_periods WHERE hperiodID = $period_id )) ");
        return $results?$results->result():false;
    }

}
/* End of file ModelName.php */
