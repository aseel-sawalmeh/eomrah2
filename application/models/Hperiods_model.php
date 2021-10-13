<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model hperiods_model
 *
 * This Model for dealing and managing periods
 *
 * @package  ExtendedClass
 * @category Model
 * @author   Gebriel Alkhayal
 * @param    ...
 * @return   ...
 */

class Hperiods_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /*Hotel Invoices*/

     /*Function To Get The List Of Providers*/

    public function get_providers()
    {
        $user_id = $this->session->userdata('H_User_ID');
        $this->db->where('hotel_provider.Hotel_Sys_User_ID', $user_id);
        //show_error(print_r($this->db->get_compiled_select('p_reservation')));
        $providers = $this->db->get('hotel_provider');
        return $providers->result();
    }


    /*Function To Get The List Of Reservations Accroding To The Providers*/

    public function get_reports($p_id = null, $offset = null, $limit = null)
    {
        if ($this->session->userdata('H_User_ID') && !$this->session->userdata('Suser')) {
            $this->db->where('ProviderId', $p_id);
        }
        if ($offset != null) {
            $this->db->limit($offset, $limit);
        }
        $this->db->join('public_users', 'p_reservation.P_UserId = public_users.Public_User_ID');
        $this->db->where('b2b', 0);
        $this->db->order_by('ID', 'DESC');
        $query = $this->db->get('p_reservation');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

        /*Function To Get The List Of All Reservations Accroding To The Specific Hotel System User*/

        public function get_huser_reservations()
        {
            $this->db->join('hotel_provider', 'p_reservation.ProviderId = hotel_provider.Provider_ID', 'left');
            $this->db->join('public_users', 'p_reservation.P_UserId =  public_users.Public_User_ID', 'left');
            $this->db->where('hotel_provider.Hotel_Sys_User_ID', $this->session->userdata('H_User_ID'));
            $this->db->where('b2b', 0);
            $this->db->order_by('ID', 'DESC');
            $query = $this->db->get('p_reservation');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }

     /*Function To Search Through The B2c Reservation For Gman*/

    public function search_reports($str, $offset = null, $limit = null)
    {
        if ($offset != null) {
            $this->db->limit($offset, $limit);
        }
        $this->db->join('public_users', 'p_reservation.P_UserId = public_users.Public_User_ID');
        $this->db->like('p_reservation.reservation_ref', $str, 'both');
        $this->db->or_like('public_users.Public_UserPhone', $str, 'both');
        $this->db->or_like('public_users.Public_UserFullName', $str, 'both');
        $this->db->where('b2b', 0);
        // show_error($this->db->get_compiled_select('p_reservation'));

        $query = $this->db->get('p_reservation');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

  
    /*Function To Search Through  The List Of All Reservations For A Hotel System User*/
    public function search_huserb2c_reservations($str)
    {
        $this->db->join('hotel_provider', 'p_reservation.ProviderId = hotel_provider.Provider_ID', 'left');
        $this->db->join('public_users', 'p_reservation.P_UserId =  public_users.Public_User_ID', 'left');
        $this->db->where('hotel_provider.Hotel_Sys_User_ID', $this->session->userdata('H_User_ID'));
        $this->db->like('p_reservation.reservation_ref', $str, 'both');
        $this->db->or_like('public_users.Public_UserPhone', $str, 'both');
        $this->db->or_like('public_users.Public_UserFullName', $str, 'both');
        $this->db->where('b2b', 0);
        $this->db->order_by('ID', 'DESC');
        $query = $this->db->get('p_reservation');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_hotel($id){
        $this->db->where('Hotel_ID', $id);
        $query = $this->db->get('hotels');
        if($query->num_rows() > 0){
            return $query->row()->Hotel_Name;
        }else{
            return false;
        }
    }

    /*B2b Reservation List For Gman */
    public function get_b2b_list(){
        $this->db->join('b2b_users', 'p_reservation.P_UserId = b2b_users.C_ID', 'inner');
        $this->db->where('b2b', 1);
        $query = $this->db->get('p_reservation');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    
    /*B2b Reservation Search For Gman */

    public function search_b2b_list($str){
        $this->db->join('b2b_users', 'p_reservation.P_UserId = b2b_users.C_ID', 'inner');
        $this->db->like('p_reservation.reservation_ref', $str, 'both');
        $this->db->or_like('b2b_users.C_MobileNumber', $str, 'both');
        $this->db->or_like('b2b_users.C_FullName', $str, 'both');
        $this->db->where('b2b', 1);
       
        $query = $this->db->get('p_reservation');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    /*B2b Reservation List For Hotel User*/
    public function get_huserb2b_list(){
        $this->db->join('hotel_provider', 'p_reservation.ProviderId = hotel_provider.Provider_ID', 'left');
        $this->db->join('b2b_users', 'p_reservation.b2b = b2b_users.C_ID', 'inner');
        $this->db->where('hotel_provider.Hotel_Sys_User_ID', $this->session->userdata('H_User_ID'));
        $query = $this->db->get('p_reservation');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

     /*B2b Reservation List For Hotel User According To Provider*/

    public function get_b2b_details($p_id = null, $offset = null, $limit = null){
        $this->db->join('b2b_users','p_reservation.b2b = b2b_users.C_ID', 'inner');
        $this->db->where('p_reservation.ProviderId', $p_id);
        $this->db->limit($limit, $offset);
        $query = $this->db->get('p_reservation');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    /*B2b Reservation Search For Hotel User*/

    public function search_huserb2b_list($str){
        $this->db->join('hotel_provider', 'p_reservation.ProviderId = hotel_provider.Provider_ID', 'left');
        $this->db->join('b2b_users', 'p_reservation.b2b = b2b_users.C_ID', 'inner');
        $this->db->where('hotel_provider.Hotel_Sys_User_ID', $this->session->userdata('H_User_ID'));
        $this->db->like('p_reservation.reservation_ref', $str, 'both');
        $this->db->or_like('b2b_users.C_MobileNumber', $str, 'both');
        $this->db->or_like('b2b_users.C_FullName', $str, 'both');
        $query = $this->db->get('p_reservation');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

  
    
 
    /*SELECT * FROM p_reservation INNER JOIN b2b_users ON p_reservation.b2b = b2b_users.C_ID WHERE p_reservation.ProviderId = 4 */
  


    /*Ends Here */


    public function getperiods($providerid)
    {
        $this->db->where('providerID', $providerid);
        $res  = $this->db->get("hp_periods");
        return $res ? $res->result() : false;
    }

   

    public function gettperiod($periodid)
    {
        $p = $this->db->get_where('hp_periods', "hperiodID = {$periodid}");
        return $p ? $p->row() : false;
    }

    public function periodtypes()
    {
        $periodstypes = $this->db->get("hp_period_types");
        return ($periodstypes->num_rows() > 0) ? $periodstypes->result() : false;
    }

    public function whatperiodtype($hptypeid)
    {
        $periodstypes = $this->db->get_where('hp_period_types', "Hp_Type_ID = $hptypeid");
        return ($periodstypes->num_rows() > 0) ? $periodstypes->row() : false;
    }

    public function qpresetter()
    {
        $this->db->reset_query();
    }
    public function add_period()
    {
        $period_data = array(
            'providerID' => $this->input->post('providerid'),
            'periodName' => $this->input->post('periodname'),
            'periodType' => $this->input->post('period_type'),
            'dateFrom' => $this->input->post('startdate'),
            'dateTo' => $this->input->post('enddate'),
            'periodRelease' => $this->input->post('periodrelease'),
            'Reservation_Timeout' => $this->input->post('Reservation_Timeout'),
            'minStay' => $this->input->post('minstay'),
            'State' => 1,
        );
        $this->db->set($period_data);
        if ($this->db->insert('hp_periods')) {
            $this->session->set_userdata('perio_id', $this->db->insert_id());
            return true;
        } else {
            return false;
        }
    }

    public function edit_period($periodid)
    {
        $period_data = array(
            'providerID' => $this->input->post('providerid'),
            'periodName' => $this->input->post('periodname'),
            'periodType' => $this->input->post('period_type'),
            'dateFrom' => $this->input->post('startdate'),
            'dateTo' => $this->input->post('enddate'),
            'periodRelease' => $this->input->post('periodrelease'),
            'Reservation_Timeout' => $this->input->post('Reservation_Timeout'),
            'minStay' => $this->input->post('minstay'),
            'State' => 1,
        );
        $this->db->set($period_data);
        return $this->db->update('hp_periods', $period_data, array('hperiodID' => $periodid)) ? true : false;
    }

    public function addp_mealpricetype($mpricetyped)
    {
        return $this->db->insert_batch('rmovertype', $mpricetyped) ? true : false;
    }

    public function editp_mealpricetype($mpricetyped)
    {
        $go = true;
        foreach ($mpricetyped as $mpricetype) {
            $this->db->where('Period_ID', $mpricetype['Period_ID']);
            $this->db->where('Mtype_ID', $mpricetype['Mtype_ID']);
            $datamprice = array_slice($mpricetype, 2);
            $go = $this->db->update('rmovertype', $datamprice) ? true : false;
        }
        return $go ? true : false;
    }

    public function getPeriodRmPriceOverType($periodid)
    {
        $mealPriceForType =  $this->db->get_where('rmovertype', "Period_ID = {$periodid}");
        return $mealPriceForType ? $mealPriceForType->result() : false;
    }

    public function addp_roomcounttype($rcounttyped = [])
    {
        //show_error(print_r($rcounttyped));
        $done = false;
        foreach ($rcounttyped as $roomav) {
            /* $this->db->where('providerID', $roomav['providerID']);
            $this->db->where('R_Type_ID', $roomav['R_Type_ID']);
            $this->db->where('calref', 0);
             $found = $this->db->get('room_type_availability');
            if ($found->num_rows() > 0) {
                $dataup = ['Available_Count' => $roomav['Available_Count'], 'EndDate' => $roomav['EndDate']];
                $this->db->set($dataup);
                $this->db->where('providerID', $roomav['providerID']);
                $this->db->where('R_Type_ID', $roomav['R_Type_ID']);
                $this->db->where('calref', 0);
                $up = $this->db->update('room_type_availability');
                $done = $up;
            } else {
                $this->db->reset_query(); */
                $this->db->set($roomav);
                $ins = $this->db->insert('room_type_availability');
                $done = $ins;
            //}
        }
        return $done;
    }

    public function editp_roomcounttype($rcounttyped = [])
    {
        $this->db->where('Period_ID', $rcounttyped['Period_ID']);
        $rcounttype = array_slice($rcounttyped, 1);
        return $this->db->update('rtcount', $rcounttype) ? true : false;
    }

    public function getRoomTypeCount($periodid)
    {
        $rtCount = $this->db->get_where('rtcount', "Period_ID = {$periodid}");
        return $rtCount ? $rtCount->row() : false;
    }

    public function addp_roomsupp($rsupp = [])
    {
        if ($this->db->insert_batch('room_supplements', $rsupp)) {
            return true;
        } else {
            return false;
        }
    }

    public function editp_roomsupp($rsupp = [])
    {
        $go = true;
        foreach ($rsupp as $rsuppd) {
            $this->db->where('Period_ID', $rsuppd['Period_ID']);
            $this->db->where('R_SuppType_ID', $rsuppd['R_SuppType_ID']);
            $datarsuppd = array_slice($rsuppd, 2);
            $go = $this->db->update('room_supplements', $datarsuppd) ? true : false;
        }
        return $go ? true : false;
    }

    public function get_roomSuppPrices($periodid)
    {
        $rsprices = $this->db->get_where('room_supplements', "Period_ID = {$periodid}");
        return $rsprices ? $rsprices->result() : false;
    }

    public function addp_roomsuppdays($Rsuppdays = [])
    {
        $this->db->set($Rsuppdays);
        return $this->db->insert('pdaysupplement') ? true : false;
    }

    public function editp_roomsuppdays($Rsuppdays = [])
    {
        $this->db->where('Period_ID', $Rsuppdays['Period_ID']);
        $dataRsuppdays = array_slice($Rsuppdays, 1);
        return $this->db->update('pdaysupplement', $dataRsuppdays) ? true : false;
    }

    public function get_PdaySupp($periodid)
    {
        $PdaySupp = $this->db->get_where('pdaysupplement', "Period_ID = {$periodid}");
        return $PdaySupp ? $PdaySupp->row() : false;
    }

    public function del_period($period_id)
    {
        $this->db->where('hperiodID', $period_id);
        return $this->db->delete('hp_periods') ? true : false;
    }

    public function get_hotel_roomsgroupedtype($hotelid)
    {
        $this->db->join('room_type', "rooms.room_type = room_type.R_Type_ID");
        $this->db->group_by('room_type');
        return $this->db->get_where('rooms', "Hotel_ID = {$hotelid}")->result();
        
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

    public function set_RoomAvailability($providerid, $set)
    {
        if ($this->input->post('roomcategory') == 0) {
            return $this->room_av_insertAll($providerid, $set);
        } else {
            return $this->room_av_insert($providerid);
        }
    }

    private function room_av_insert($providerid): bool
    {
        $availability_data = array(
            'providerID' => $providerid,
            'R_Type_ID' => $this->input->post('roomcategory'),
            'Available_Count' => $this->input->post('roomsavcount'),
            'StartDate' => $this->input->post('startdate'),
            'EndDate' => $this->input->post('enddate'),
            'calref' => 1,
        );
        $this->db->set($availability_data);
        return $this->db->insert('room_type_availability') ? true : false;
    }

    private function room_av_insertAll($providerid, $set = []): bool
    {
        $done = false;
        foreach ($set as $s) {
            $availability_data = array(
                'providerID' => $providerid,
                'R_Type_ID' => $s->R_Type_ID,
                'Available_Count' => $this->input->post('roomsavcount'),
                'StartDate' => $this->input->post('startdate'),
                'EndDate' => $this->input->post('enddate'),
                'calref' => 1,
            );

            $this->db->set($availability_data);
            $ins = $this->db->insert('room_type_availability') ? true : false;
            $done = $ins;
        }
        return $done;
    }

    public function set_payAvailability($providerid)
    {
        if ($this->input->post('paymethod') == 0) {

            $availability_data = array(
                'StartDate' => $this->input->post('startdate'),
                'EndDate' => $this->input->post('enddate'),
                'State' => $this->input->post('paymethodstate')
            );

            $this->db->set($availability_data);
            $this->db->where('providerID', $providerid);
            return $this->db->update('hp_paymethods_ava') ? true : false;

        } else {

            $availability_data = array(
                'providerID' => $providerid,
                'Paymethod_typeid' => $this->input->post('paymethod'),
                'StartDate' => $this->input->post('startdate'),
                'EndDate' => $this->input->post('enddate'),
                'State' => $this->input->post('paymethodstate')
            );

            $this->db->set($availability_data);
            return $this->db->insert('hp_paymethods_ava') ? true : false;
        }
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

    public function roomAvdates($providerid)
    {
        $this->db->select('*, MONTH(StartDate) as nsmonth, MONTH(EndDate) as nemonth, MONTHNAME(StartDate) as tsmonth, MONTHNAME(EndDate) as temonth');
        $this->db->join('room_type', "room_type.R_Type_ID = room_type_availability.R_Type_ID", 'LEFT');
        $this->db->where('providerID', $providerid);
        $result = $this->db->get('room_type_availability');
        return $result ? $result->result() : false;
    }

    public function ProomAvdates($providerid)
    {
        $this->db->select('*, MONTH(dateFrom) as nsmonth, MONTH(dateTo) as nemonth, MONTHNAME(dateFrom) as tsmonth, MONTHNAME(dateTo) as temonth');
        $this->db->join('room_type_availability', "hp_periods.providerID = room_type_availability.providerID", 'LEFT');
        $this->db->join('room_type', "room_type.R_Type_ID = room_type_availability.R_Type_ID", 'LEFT');
        $this->db->where('hp_periods.providerID', $providerid);
        $result = $this->db->get('hp_periods');
        //show_error(print_r($this->db->get_compiled_select('hp_periods')));
        return $result ? $result->result() : false;
    }

    public function payAvdates($providerid)
    {
        $this->db->select('*, hp_paymethods_ava.ID as paytimeid, MONTH(StartDate) as nsmonth, MONTH(EndDate) as nemonth, MONTHNAME(StartDate) as tsmonth, MONTHNAME(EndDate) as temonth');
        $this->db->join('hp_payment_methods', "hp_paymethods_ava.Paymethod_typeid = hp_payment_methods.ID", 'LEFT');
        $this->db->where('hp_paymethods_ava.providerID', $providerid);
        $result = $this->db->get('hp_paymethods_ava');
        return $result ? $result->result() : false;
    }

    public function roomAvdates_update()
    {
        $avdates_data = array(
            'StartDate' => $this->input->post('startdate'),
            'EndDate' => $this->input->post('enddate'),
            'Available_Count' => $this->input->post('roomsavcount'),
        );
        $this->db->set($avdates_data);
        $this->db->where('ID', $this->input->post('ravid'));
        return $this->db->update('room_type_availability') ? true : false;
    }

    public function payAvdates_update()
    {
        $avdates_data = array(
            'StartDate' => $this->input->post('startdate'),
            'EndDate' => $this->input->post('enddate'),
        );

        $this->db->set($avdates_data);
        $this->db->where('ID', $this->input->post('payavid'));
        return $this->db->update('hp_paymethods_ava') ? true : false;
    }

    public function add_payment_method($providerid)
    {
        $payment_data = array(
            'providerID' => $providerid,
            'Pay_m_name' => $this->input->post('paymentname'),
            'is_online' => $this->input->post('is_online')
        );
        $this->db->set($payment_data);
        return $this->db->insert('hp_payment_methods') ? true : false;
    }

    public function del_payment_method($paymethodid)
    {
        $this->db->where('ID', $paymethodid);
        return $this->db->delete('hp_payment_methods') ? true : false;
    }

    public function get_payment_method()
    {
        $methods = $this->db->get('hp_payment_methods');
        return $methods ? $methods->result() : false;
    }

    public function roomAvdates_delete($avaid)
    {
        $this->db->where('ID', $avaid);
        return $this->db->delete('room_type_availability') ? true : false;
    }

    public function payAvdates_delete($avaid)
    {
        $this->db->where('ID', $avaid);
        return $this->db->delete('hp_paymethods_ava') ? true : false;
    }

    public function roomAvMonths($providerid)
    {
        $q = $this->db->query("SELECT MONTHNAME(StartDate) as tmonth, MONTH(StartDate) as nMonth, YEAR(StartDate) as ryear FROM `room_type_availability` WHERE providerID = $providerid UNION SELECT MONTHNAME(EndDate), MONTH(EndDate), YEAR(EndDate) FROM `room_type_availability` WHERE providerID = $providerid ORDER BY ryear ASC, nMonth ASC");
        return $q ? $q->result() : false;
    }

    public function ProomAvMonths($providerid)
    {
        $q = $this->db->query("SELECT MONTHNAME(dateFrom) as tmonth, MONTH(dateFrom) as nMonth, YEAR(dateFrom) as ryear FROM `hp_periods` WHERE providerID = $providerid UNION SELECT MONTHNAME(dateTo), MONTH(dateTo), YEAR(dateTo) FROM `hp_periods` WHERE providerID = $providerid ORDER BY ryear ASC, nMonth ASC");
        return $q ? $q->result() : false;
    }

    public function payAvMonths($providerid)
    {
        $q = $this->db->query("SELECT MONTHNAME(StartDate) as tmonth, MONTH(StartDate) as nMonth, YEAR(StartDate) as ryear FROM `Hp_paymethods_ava` UNION SELECT MONTHNAME(EndDate), MONTH(EndDate), YEAR(EndDate) FROM `hp_paymethods_ava` WHERE providerID = $providerid ORDER BY ryear ASC, nMonth ASC ");
        return $q ? $q->result() : false;
    }

    public function add_discountcode($providerid)
    {
        $discount_data = array(
            'providerID' => $providerid,
            'discountCode' => $this->input->post('discode'),
            'StartDate' => $this->input->post('startdate'),
            'EndDate' => $this->input->post('enddate'),
            'Price' => $this->input->post('disamount'),
            'Description' => $this->input->post('discount-description')
        );

        if ($this->input->post('allowb2b') == null) {
            $discount_data['AllowB2B'] = 0;
        } else {
            $discount_data['AllowB2B'] = $this->input->post('allowb2b');
        }

        if ($this->input->post('allowb2c') == null) {
            $discount_data['AllowB2C'] = 0;
        } else {
            $discount_data['AllowB2C'] = $this->input->post('allowb2c');
        }
        $this->db->set($discount_data);
        return $this->db->insert('discountcodes') ? true : false;
    }

    public function edit_discountcode()
    {
        $discodeid = $this->input->post('discodeid');
        $discount_data = array(
            'discountCode' => $this->input->post('discode'),
            'StartDate' => $this->input->post('startdate'),
            'EndDate' => $this->input->post('enddate'),
            'Price' => $this->input->post('disamount'),
            'Description' => $this->input->post('discount-description')
        );

        if ($this->input->post('allowb2b') == null) {
            $discount_data['AllowB2B'] = 0;
        } else {
            $discount_data['AllowB2B'] = $this->input->post('allowb2b');
        }

        if ($this->input->post('allowb2c') == null) {
            $discount_data['AllowB2C'] = 0;
        } else {
            $discount_data['AllowB2C'] = $this->input->post('allowb2c');
        }
        $this->db->set($discount_data);
        $this->db->where('ID', $discodeid);
        return $this->db->update('discountcodes') ? true : false;
    }

    public function get_discountcodes($providerid)
    {
        $codes = $this->db->get_where('discountcodes', "providerID = {$providerid}");
        return $codes ? $codes->result() : false;
    }

    public function get_discountcodes_typed($providerid, $type)
    {
        if ($type == 'b2c') {
            $so = "AND AllowB2C = 1";
        } elseif ($type == 'b2b') {
            $so = "AND AllowB2B = 1";
        }
        $codes = $this->db->get_where('discountcodes', "providerID = {$providerid} {$so}");
        return $codes ? $codes->result() : false;
    }

    public function discountcode($discodeid)
    {
        $this->db->where('ID', $discodeid);
        $codes = $this->db->get('discountcodes');
        return $codes ? $codes->row() : false;
    }

    public function discountAvMonths($providerid)
    {
        $q = $this->db->query("SELECT MONTHNAME(StartDate) as tmonth, MONTH(StartDate) as nMonth, YEAR(StartDate) as ryear FROM `discountcodes` UNION SELECT MONTHNAME(EndDate), MONTH(EndDate), YEAR(EndDate) FROM `discountcodes` WHERE providerID = $providerid ORDER BY ryear ASC, nMonth ASC ");
        return $q ? $q->result() : false;
    }

    public function discountsAvdates($providerid)
    {
        $this->db->select('*, MONTH(StartDate) as nsmonth, MONTH(EndDate) as nemonth, MONTHNAME(StartDate) as tsmonth, MONTHNAME(EndDate) as temonth');
        $this->db->where('providerID', $providerid);
        $result = $this->db->get('discountcodes');
        return $result ? $result->result() : false;
    }

    public function get_rooms_number()
    {
        $this->db->where('providerID', $providerid);
        $query =  $this->db->get('room_type_availability');
        if ($query) {
            return $query->result();
        } else {
            return false;
        }
    }

}
