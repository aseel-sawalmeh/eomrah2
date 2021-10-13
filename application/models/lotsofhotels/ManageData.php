<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ManageData extends CI_Model
{
    public function __costruct()
    {
        parent::__construct();
    }

    public function fillTable($data, $table)
    {
        $this->db->trans_begin();

        $this->db->insert_batch($table, $data);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            show_error($this->db->error());
            return False;
        } else {
            $this->db->trans_commit();
            return True;
        }
        //transaction end
    }

    public function insertHotel($data)
    {
        $this->db->set($data);
        $hotelinserted = $this->db->insert('hotels');
        if($hotelinserted){
            // echo "hotel inserted with id ". $this->db->insert_id().'  ';
            return $this->db->insert_id();
        }
        show_error($this->db->error());
        return False;
    }

    public function hotelExist($hotelid)
    {
        $this->db->where('hotelLotsId', $hotelid);
        $hotel = $this->db->get('hotels');
        if($hotel->num_rows() > 0){
            // echo "hotel inserted with id ". $this->db->insert_id().'  ';
            return True;
        }
        // show_error($this->db->error());
        return False;
    }
    public function getHotels(){
        $this->db->select('Hotel_ID, Hotel_Name, Hotel_Description , Hotel_Address');
        return $this->db->get('hotels')->result();
    }
    public function getHotelRooms(){
        $this->db->select('roomId, name');
        return $this->db->get('hotel_rooms')->result();
    }
    public function nonArHotelRooms(){
        $this->db->select('roomId, name');
        return $this->db->get_where('hotel_rooms', "name_ar IS NULL")->result();
    }
    public function roomsTranslate($roomid, $room_ar){
        $this->db->set('name_ar', $room_ar);
        $this->db->where('roomId', $roomid);
        $update = $this->db->update('hotel_rooms');
        if($update){
            echo 'room: ' . $room_ar . " Done <br />";
            return TRUE;
         }else{
            echo 'room: ' . $room_ar . " Failed <br />";
            echo $this->db->error();
         }
        
    }
}
