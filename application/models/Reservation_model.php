<?php

use function PHPSTORM_META\exitPoint;

defined('BASEPATH') or exit('No direct script access allowed');

class Reservation_model extends CI_Model
{

    private $provider_id;
    private $periodid;
    private $pAvRooms;
    private $allpData;
    private $p_reservation;
    private $presdetails;
    private $AvUpdates;
    private $presid;
    private $final;

    public function avRooms()
    {
        $this->db->select("`room_type_availability`.`ID`, `room_type`.`R_Type_Sn`, `room_type_availability`.`Available_Count`");
        $this->db->join('room_type', "room_type_availability.R_Type_ID = room_type.R_Type_ID", 'LEFT');
        $this->db->where('providerID', $this->provider_id);
        $this->pAvRooms = $this->db->get('room_type_availability')->result();
    }

    public function spRequests()
    {
        if (userlang() == 'ar') {
            $this->db->select('special_requests.specialreq_id, special_requests.specialreq_code, special_requests.specialreq_ar as specialreq');
        }
        return $this->db->get('special_requests')->result();
    }

    public function hotelAmenities($hotelid)
    {
        $this->db->select('amenity_name as name, amenityid as am_code');
        $this->db->join('amenties', "hotel_amenities.amenityid = amenties.amenity_lotsid");
        return $this->db->get_where('hotel_amenities', "hotelid = $hotelid")->result();
    }

    public function prId($alldata = array())
    {
        $this->provider_id = $alldata['pvid'];
        $this->allpData = $alldata;
        $this->p_reservation['reservation_ref'] = $alldata['ref'];
        $this->p_reservation['P_UserId'] = $alldata['puid'];
        $this->p_reservation['ProviderId'] = $this->provider_id;
        //$this->p_reservation['guest_name'] = $alldata['guest_name'];
        // $this->p_reservation['guest_email'] = $alldata['guest_email'];
        $this->p_reservation['TotalPrice'] = $alldata['totalprice'];
        $this->p_reservation['TotalRoomCount'] = $alldata['totalcount'];
        $this->p_reservation['Discountid'] = $alldata['discountid'];
        $this->p_reservation['NetPrice'] = $alldata['netprice'];
        $this->p_reservation['CheckInDate'] = $alldata['cin_date'];
        $this->p_reservation['CheckOutDate'] = $alldata['cout_date'];
        $this->p_reservation['Payment_method'] = $alldata['pmethod'];
        //$this->avRooms();
        $this->set_res();
        return $this;
    }

    public function get_finished()
    {
        return $this->final;
    }

    public function res($resdata)
    {
        foreach ($this->pAvRooms as $avroom) {

            $presdetail = array(
                'ResId' => $this->presid,
                'RoomType' => '',
                'MealType' => '',
                'RoomCount' => 0,
                'NightPrice' => 0,
                'NightsCount' => $this->allpData['no_ofnights'],
            );
            foreach ($this->allpData as $tdatakey => $value) {
                $count = '_count';
                $price = '_price';
                $found_count = preg_match("/$count/i", $tdatakey);
                $found_price = preg_match("/$price/i", $tdatakey);
                if ($found_count >= 1) {
                    $presdetail['RoomType'] = $this->identify(explode("_", $tdatakey)[1], '', 1);
                    $presdetail['MealType'] = $this->identify('', explode("_", $tdatakey)[0], 2);

                    $presdetail['RoomCount'] = $this->allpData[$tdatakey];
                    $name = $presdetail['MealType'] . "_" . $presdetail['RoomType'];
                    $this->presdetails[$name] = $presdetail;
                }
                if ($found_price >= 1) {
                    $this->presdetails[$name]['NightPrice'] = $this->allpData[$tdatakey];
                }
            }
        }
        //$this->calcAv();
        return $this;
    }


    public function set_res($resdata)
    {
        $resdata['reservation_ref'] = $this->toolset->genid();
        $this->db->set($resdata);
        // show_error($this->db->get_compiled_insert('p_reservation'));
        $insres = $this->db->insert('p_reservation');

        if ($insres) {
            return $this->db->insert_id();
        } else {
            show_error($this->db->error());
        }
    }

    public function persistPaidRes($resid, $res_update, $res_confirmation)
    {
        // chunk the persist proccess update the res table then isnert the res detials confirmed
        $this->db->where('ID', $resid);
        $this->db->set($res_update);
        $resUpdated = $this->db->update('p_reservation');
        if ($resUpdated) {
            $this->db->reset_query();
            $saveConfrimation = $this->db->insert_batch('res_confirm', $res_confirmation);
            return $saveConfrimation ? True : False;
        }

        return $this->db->error();
        // return show_error($this->db->error());
    }

    public function ResPaid($resid)
    {
        // chunk the persist proccess update the res table then isnert the res detials confirmed
        $this->db->where('reservation_ref', $resid);
        $this->db->set('Paid', 1);
        $resUpdated = $this->db->update('p_reservation');
        return $resUpdated ? True : False;
        // return $this->db->error();
        // return show_error($this->db->error());
    }

    public function log_lots_error($resdata)
    {
        // chunk the persist proccess update the res table then isnert the res detials confirmed
        $this->db->set($resdata);
        $logInserted = $this->db->insert('lots_logs');
        return $logInserted ? True : False;
        // return $this->db->error();
        // return show_error($this->db->error());
    }

    public function saveTransaction($transactiondata)
    {
        $this->db->set($transactiondata);
        $transactionInserted = $this->db->insert('user_transactions');
        return $transactionInserted ? True : false;
    }

    public function confirmTransaction($servid)
    {
        $this->db->where(['service_id' => $servid, 'service_type' => 'reservation']);
        $this->db->set('paid', 1);
        $transactionConfirmed = $this->db->update('user_transactions');
        return $transactionConfirmed ? True : false;
    }

    public function get_resref($resid)
    {
        $res = $this->db->get_where('p_reservation', "ID = $resid");
        if ($res->num_rows() > 0) {
            return $res->row()->reservation_ref;
        } else {
            return false;
        }
    }

    public function get_confirmedRes($resid)
    {
        $res = $this->db->get_where('res_confirm', "resID = $resid");
        if ($res->num_rows() > 0) {
            return $res->row();
        } else {
            return false;
        }
    }

    public function set_resdetails($items, $resid)
    {
        // print_r($items);
        // exit();
        if (!empty($items) && count($items) >= 1) {
            $rooms = array_map(function ($el) use ($resid) {
                $el = (array)$el;
                $el2['ResId'] = $resid;
                $el2['roomid'] = $el['rtype'];
                $el2['ratebase'] = $el['ratebase'];
                $el2['RoomType'] = $el['ratebase'];
                $el2['MealType'] = $el['mtype'] ?? 0;
                $el2['NightPrice'] = $el['price'];
                $el2['count'] = $el['roomcount'];
                $el2['allocationDetails'] = $el['allocation'];
                //new edtis to include room guest details development only
                $el2['adults'] = $el['adults'] ?? null;
                $el2['children'] = $el['children'] ?? null;
                $el2['guest_name'] = $el['gname'];
                $el2['guest_email'] = $el['gemail'] ?? null;
                $el2['bedpref'] = $el['bedpref'];
                $el2['cancellation'] = $el['cancelref'];
                $el2['specialreqs'] = trim(array_reduce($el['sp_requests'], function ($v1, $v2) {
                    return $v1 . ',' . $v2;
                }), ',');

                return $el2;
            }, $items);
            $ins_restult = $this->db->insert_batch('presdetails', $rooms);
            if ($ins_restult) {
                return true;
            } else {
                show_error($this->db->error());
                show_error("resdetails Failed");
            }
        } else {
            // show_error('Items are empty');
            // log_message('error', "empty or null items in file ".__FILE__.' line ' .__LINE__);
            show_error($this->db->error());
            exit();
            return false;
        }
    }
    public function cancelBooking($resid)
    {
        $this->db->set(['cancelDate' => date('Y-m-d H:i:s'), 'confirm' => 3]);
        $this->db->where('ID', $resid);
        $rescanceled = $this->db->update('p_reservation');
        return $rescanceled ? true : show_error(print_r($this->db->error()));
    }
    public function identify($tr, $choose = 0)
    {
        if ($choose == 1) {
            $this->db->where('R_Type_Sn', $tr);
            $res = $this->db->get('room_type')->row_array()['R_Type_ID'];
            return $res;
        } elseif ($choose == 2) {
            $this->db->where('Meal_Sn', $tr);
            return $this->db->get('meals')->row_array()['Meal_ID'];
        } else {
            return false;
        }
    }

    public function stringfy($tr = 0, $choose = 0)
    {
        if ($choose == 1) {
            $this->db->where('R_Type_ID', $tr);
            return $this->db->get('room_type')->row()->R_Type_Sn;
        } elseif ($choose == 2) {
            $this->db->where('Meal_ID', $tr);
            return $this->db->get('meals')->row()->Meal_Sn;
        } else {
            return false;
        }
    }

    public function payment_Methods($provider_id)
    {
        $this->db->join('hp_payment_methods', 'hp_paymethods_ava.Paymethod_typeid = hp_payment_methods.ID', 'left');
        $this->db->where("hp_paymethods_ava.providerID = $provider_id");
        $p_methods = $this->db->get('hp_paymethods_ava');
        if ($p_methods->num_rows() > 0) {
            return $p_methods->result();
        } else {
            return false;
        }
    }

    public function get_invoice($invid)
    {
        $this->db->select('p_reservation.*, p_reservation.ID as resrefid, hotels.Hotel_ID, hotels.Hotel_Name, hotels.Hotel_Address, hotelmap.lat, hotelmap.lng, public_users.*');
        $this->db->join('hotels', 'p_reservation.hotelid = hotels.hotelLotsId OR p_reservation.hotelid = hotels.Hotel_ID');
        $this->db->join('hotelmap', 'p_reservation.hotelid = hotelmap.hotel_lotsid OR p_reservation.hotelid = hotelmap.hotel_id');
        $this->db->join('public_users', 'p_reservation.P_UserId = public_users.Public_User_ID', 'left');
        $this->db->where("reservation_ref", $invid);
       $p_res = $this->db->get('p_reservation');
        
        if ($p_res->num_rows() > 0) {
            return $p_res->row();
        } else {
            return false;
        }
    }

    public function res_details($invid)
    {
        $res = null;
        $this->db->select('p_reservation.ID, p_reservation.reservation_ref, p_reservation.hotelid, p_reservation.CheckInDate, p_reservation.CheckOutDate, p_reservation.TotalRoomCount, public_users.Public_UserFullName as fullname');
        $this->db->from('p_reservation');
        // $this->db->join('presdetails', 'p_reservation.ID = presdetails.ResId', 'left');
        $this->db->join('public_users', 'p_reservation.P_UserId = public_users.Public_User_ID', 'left');
        $this->db->where("reservation_ref", $invid);
        $p_res = $this->db->get();
        if ($p_res->num_rows() > 0) {
            $res = $p_res->row();
            $this->db->reset_query();
            $rooms = $this->db->get_where('presdetails', "ResId = $res->ID ")->result_array();
            $extracted = [];
            //needs rediting multi roomos is not allowed any way
            array_walk($rooms, function ($room) use (&$extracted) {
                if ($room['count'] > 1) {
                    for ($i = 1; $i < $room['count']; $i++) {
                        $extracted[] = $room;
                    }
                }
            });

            $res->rooms = array_merge($rooms, $extracted);
        }

        // echo "<pre>";
        // print_r($res);
        // echo "</pre>";
        // exit();
        return $res;
    }



    public function get_timeout($providerid)
    {
        $this->db->select('Reservation_Timeout');
        $this->db->where('ProviderId', $providerid);
        $this->db->order_by('hperiodID', 'DESC');
        $this->db->limit(1);
        $pr = $this->db->get('hp_periods');
        if ($pr) {
            return $pr->row()->Reservation_Timeout;
        } else {
            return false;
        }
    }

    public function get_invoice_details($invid)
    {
        $this->db->join('hotel_rooms', 'presdetails.roomid = hotel_rooms.roomTypeCode', 'LEFT');
        $this->db->join('ratebasis', 'presdetails.ratebase = ratebasis.ratebase_code', 'LEFT');
        $this->db->where('ResId', $invid);
        $p_res_det = $this->db->get('presdetails');
        if ($p_res_det) {
            // show_error(print_r($p_res_det->result()));

            return $p_res_det->result();
        } else {
            // show_error(print_r($this->db->error()));

            return false;
        }
    }

    public function save_pay($resref)
    {
        $this->db->where('reservation_ref', $resref);
        $this->db->set('Paid', 1);
        if ($this->db->update('p_reservation')) {
            return true;
        } else {
            return false;
        }
    }

    public function get_r_h($invrid)
    {
        $this->db->select('hotels.Hotel_Name, hotels.Hotel_ID')->from('p_reservation');
        $this->db->join('hotel_provider', 'p_reservation.providerID = hotel_provider.Provider_ID');
        $this->db->join('hotels', 'hotel_provider.Hotel_ID = hotels.Hotel_ID');
        $this->db->where('p_reservation.reservation_ref', $invrid);
        $h = $this->db->get();
        if ($h->num_rows() > 0) {
            return $h->row()->Hotel_Name;
        } else {
            return false;
        }
    }

    public function ispaid($resid)
    {
        $this->db->where('reservation_ref', $resid);
        $query = $this->db->get('p_reservation');
        if ($query->num_rows() > 0) {
            if ($query->row()->Paid == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function markaspaid($resid)
    {
        $data = array(
            'Paid' => 1,
            'confirm' => 1
        );
        $this->db->where('reservation_ref', $resid);
        $query = $this->db->update('p_reservation', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    public function markasconfirmed($resid)
    {
        $data = array(
            'confirm' => 1
        );
        $this->db->where('reservation_ref', $resid);
        $this->db->where('Paid', 1);
        $query = $this->db->update('p_reservation', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }


    public function get_b2binvoice($invid)
    {
        $this->db->select('p_reservation.*, p_reservation.ID as resrefid, hotels.Hotel_ID, hotels.Hotel_Name, hotels.Hotel_Address, hotelmap.lat, hotelmap.lng, b2b_users.*');
        $this->db->join('hotels', 'p_reservation.hotelid = hotels.hotelLotsId OR p_reservation.hotelid = hotels.Hotel_ID');
        $this->db->join('hotelmap', 'p_reservation.hotelid = hotelmap.hotel_lotsid OR p_reservation.hotelid = hotelmap.hotel_id');
        $this->db->join('b2b_users', 'p_reservation.P_UserId = b2b_users.C_ID', 'left');
        $this->db->where("reservation_ref", $invid);
       $p_res = $this->db->get('p_reservation');
        
        if ($p_res->num_rows() > 0) {
            return $p_res->row();
        } else {
            return false;
        }
    }

    public function get_b2binvoicelist($limit, $Offset)
    {
        $id = $this->session->userdata('C_ID');
        $this->db->where('P_UserId', $id);
        $this->db->limit($limit, $Offset);
        $query = $this->db->get('p_reservation');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function b2b_invoicecount()
    {
        $id = $this->session->userdata('C_ID');
        $this->db->where('P_UserId', $id);
        $query = $this->db->get('p_reservation');
        if ($query) {
            return $query->num_rows();
        } else {
            return false;
        }
    }

    public function minus_b2b_deposit($amount){
        $id = $this->session->userdata('C_ID');
        $this->db->set('Deposit', 'Deposit-'.$amount.'',False);
        $this->db->where('C_ID', $id);
        $update = $this->db->update('b2b_users');
       if($update){
           return true;
       }else{
          return false;
       }

    }

    public function b2bres_details($invid)
    {
        $res = null;
        $this->db->select('p_reservation.ID, p_reservation.reservation_ref, p_reservation.hotelid, p_reservation.CheckInDate, p_reservation.CheckOutDate, p_reservation.TotalRoomCount, b2b_users.C_FullName as fullname');
        $this->db->from('p_reservation');
        $this->db->join('b2b_users', 'p_reservation.P_UserId = b2b_users.C_ID', 'left');
        $this->db->where("reservation_ref", $invid);
        $p_res = $this->db->get();
        if ($p_res->num_rows() > 0) {
            $res = $p_res->row();
            $this->db->reset_query();
            $rooms = $this->db->get_where('presdetails', "ResId = $res->ID ")->result_array();
            $extracted = [];
            //needs rediting multi roomos is not allowed any way
            array_walk($rooms, function ($room) use (&$extracted) {
                if ($room['count'] > 1) {
                    for ($i = 1; $i < $room['count']; $i++) {
                        $extracted[] = $room;
                    }
                }
            });

            $res->rooms = array_merge($rooms, $extracted);
        }
        return $res;
    }
}
