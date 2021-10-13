<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model Calav_model
 *
 * This Model for dealing and managing periods
 *
 * @package  ExtendedClass
 * @category Model
 * @author   Gebriel Alkhayal
 * @param    ...
 * @return   ...
 */

class Calav_model extends CI_Model
{
    // ------------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    public function get_providersReports()
    {
        $user_id = $this->session->userdata('H_User_ID');
        $this->db->select('hperiodID');
        $this->db->from('hp_periods');
        $this->db->join('hotel_provider', "hotel_provider.Provider_ID = hp_periods.providerID");
        $this->db->where('hotel_provider.Hotel_Sys_User_ID', $user_id);
        $providers = $this->db->get();
        return $providers->result();
    }

    public function getperiods($providerid)
    {
        $this->db->where('providerID', $providerid);
        $res = $this->db->get("hp_periods");
        return $res ? $res->result() : false;
    }

    public function reservations($providerid)
    {
        $this->db->join('presdetails', "presdetails.ResId=p_reservation.ID", 'LEFT');
        $this->db->join('room_type', "presdetails.RoomType = room_type.R_Type_ID", 'LEFT');
        $this->db->where('ProviderId', $providerid);
        $res = $this->db->get("p_reservation");
        //show_error(print_r($res->result()));
        //return $res ? $res->result() : false;
        $rdata = [];
        if ($res) {
            foreach ($res->result() as $rsd) {
                if (isset($rdata[$rsd->R_Type_Sn])) {
                    $rdata[$rsd->R_Type_Sn]['count'] += $rsd->count;
                    continue;
                } else {
                    $rdata[$rsd->R_Type_Sn]['type'] = $rsd->R_Type_Sn;
                    $rdata[$rsd->R_Type_Sn]['refid'] = $rsd->reservation_ref;
                    $rdata[$rsd->R_Type_Sn]['start'] = $rsd->CheckInDate;
                    $rdata[$rsd->R_Type_Sn]['end'] = $rsd->CheckOutDate;
                    $rdata[$rsd->R_Type_Sn]['count'] = $rsd->count;
                }
            }
            return $rdata;
        } else {
            return false;
        }
    }

    public function hotel_rooms($hotelid)
    {
        return $this->db->get_where('rooms', "Hotel_ID = {$hotelid}")->result();
    }

    public function getHotel($providerid)
    {
        $this->db->select('Hotel_ID');
        $hotelid = $this->db->get_where('hotel_provider', "Provider_ID = {$providerid}")->row();
        $this->db->reset_query();
        return $this->db->get_where('hotels', "Hotel_ID = {$hotelid->Hotel_ID}")->row();
    }

    public function get_ava($rsntype)
    {
        $this->db->join('room_type', "room_type_availability.R_Type_ID = room_type.R_Type_ID", 'left');
        $this->db->where('R_Type_Sn', $rsntype);
        return $this->db->get('room_type_availability')->row();
        $this->db->reset_query();
    }

    public function get_hotel_mealsgroupedtype($hotelid)
    {
        $this->db->join('meals', "hotel_meals.Meal_ID = meals.Meal_ID");
        $this->db->group_by('hotel_meals.Meal_ID');
        return $this->db->get_where('hotel_meals', "Hotel_ID = {$hotelid}")->result();
    }

    public function get_RoomSupptypes($providerid)
    {
        $rst = $this->db->get_where('r_supptypes', "Provider_ID = {$providerid}");
        return $rst ? $rst->result() : false;
    }

    public function set_RoomSupptype()
    {
        $room_supp_data = ['R_SuppType_Name' => $this->input->post('rsupp_name'), 'Provider_ID' => $this->input->post('provider_id')];
        $this->db->set($room_supp_data);
        return $this->db->insert('r_supptypes') ? true : false;
    }

    public function edit_RoomSupptype()
    {
        $room_supp_data = ['R_SuppType_Name' => $this->input->post('rsupp_name')];
        $this->db->set($room_supp_data);
        $this->db->where('R_SuppType_ID', $this->input->post('rsupp_id'));
        return $this->db->update('r_supptypes') ? true : false;
    }

    public function del_RoomSupptype($rsupptypeid)
    {
        $this->db->where('R_SuppType_ID', $rsupptypeid);
        return $this->db->delete('r_supptypes') ? true : false;
    }

    public function roomAvearliestDate($providerid)
    {
        $this->db->select_min('StartDate');
        $this->db->where('providerID', $providerid);
        $result = $this->db->get('room_type_availability');
        return $result ? $result->row() : false;
    }

    public function payAvearliestDate($providerid)
    {
        $this->db->select_min('StartDate');
        $this->db->where('providerID', $providerid);
        $result = $this->db->get('hp_paymethods_ava');
        return $result ? $result->row() : false;
    }

    public function periodroomAvdates($providerid)
    {
        $this->db->select('*, MONTH(StartDate) as nsmonth, MONTH(EndDate) as nemonth, MONTHNAME(StartDate) as tsmonth, MONTHNAME(EndDate) as temonth');
        $this->db->join('room_type', "room_type.R_Type_ID = room_type_availability.R_Type_ID", 'LEFT');
        $this->db->where('providerID', $providerid);
        $this->db->where('room_type_availability.R_Type_ID IS NOT NULL');
        $result = $this->db->get('room_type_availability');
        //show_error($this->db->get_compiled_select('room_type_availability'));
        return $result ? $result->result() : false;
    }

    public function BaseRoomAvDates($providerid)
    {
        $this->db->select('*, MONTH(StartDate) as nsmonth, MONTH(EndDate) as nemonth, MONTHNAME(StartDate) as tsmonth, MONTHNAME(EndDate) as temonth');
        $this->db->join('room_type', "room_type.R_Type_ID = room_type_availability.R_Type_ID", 'LEFT');
        $this->db->where('providerID', $providerid);
        $this->db->where('room_type_availability.calref', 0);
        $result = $this->db->get('room_type_availability');
        return $result ? $result->result() : false;
    }

    public function RefRoomtype($providerid, $Rtypeid)
    {
        $this->db->select('*, MONTH(StartDate) as nsmonth, MONTH(EndDate) as nemonth, MONTHNAME(StartDate) as tsmonth, MONTHNAME(EndDate) as temonth');
        $this->db->join('room_type', "room_type.R_Type_ID = room_type_availability.R_Type_ID", 'LEFT');
        $this->db->where('providerID', $providerid);
        $this->db->where('room_type_availability.R_Type_ID', $Rtypeid);
        $this->db->where('room_type_availability.calref', 1);
        $result = $this->db->get('room_type_availability');
        return $result ? $result->result() : false;
    }

    public function roomAvdates($providerid)
    {
        $this->db->select('*, MONTH(StartDate) as nsmonth, MONTH(EndDate) as nemonth, MONTHNAME(StartDate) as tsmonth, MONTHNAME(EndDate) as temonth');
        $this->db->join('room_type', "room_type.R_Type_ID = room_type_availability.R_Type_ID", 'LEFT');
        $this->db->where('providerID', $providerid);
        $this->db->where('room_type_availability IS NOT NULL');
        $result = $this->db->get('room_type_availability');
        //show_error($this->db->get_compiled_select('room_type_availability'));
        return $result ? $result->result() : false;
    }

    public function roomAvPartDates($parentref)
    {
        $this->db->select('*, MONTH(StartDate) as nsmonth, MONTH(EndDate) as nemonth, MONTHNAME(StartDate) as tsmonth, MONTHNAME(EndDate) as temonth');
        $this->db->join('room_type', "room_type.R_Type_ID = room_type_availability.R_Type_ID", 'LEFT');
        $this->db->where('calref', $parentref);
        $this->db->where('room_type_availability.R_Type_ID IS NULL');
        $result = $this->db->get('room_type_availability');
        //show_error($this->db->get_compiled_select('room_type_availability'));
        return $result ? $result->result() : false;
    }

    public function Periods_roomAvdates($providerid)
    {
        $this->db->select('*, MONTH(dateFrom) as nsmonth, MONTH(dateTo) as nemonth, MONTHNAME(dateFrom) as tsmonth, MONTHNAME(dateTo) as temonth');
        $this->db->join('room_type_availability', "hp_periods.providerID = room_type_availability.providerID", 'LEFT');
        $this->db->join('room_type', "room_type.R_Type_ID = room_type_availability.R_Type_ID", 'LEFT');
        $this->db->where('hp_periods.providerID', $providerid);
        $result = $this->db->get('hp_periods');
        //show_error(print_r($this->db->get_compiled_select('hp_periods')));
        return $result ? $result->result() : false;
    }

}
