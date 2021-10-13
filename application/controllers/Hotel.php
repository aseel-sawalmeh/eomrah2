<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hotel extends Front_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Reservation_model', 'rsm');
        $this->load->model('Product_Categories_model');
        $this->load->model('fendmodels/fendproducts_model');
        $this->load->model('fendmodels/fphoto_model');
        $this->load->model('translate/Translate_model');
        $this->load->model('Multimedia_model');
        $this->load->model('Hotel_model', 'ht');
        $this->load->model('Products_model');
    }

    public function index()
    {

        $data['title'] = "classified hotel list here";
        $data['hotels'] = [];
        $this->render_view('fendprod/hotels', $data);
    }

    public function details($hslug, $checkin = null, $checkout = null)
    {
       

       
        // print_r($this->session->tmpWallet);
        // $this->output->enable_profiler(TRUE);
        // $this->load->library('magician');
        $this->load->library('Lotsofhotels');
        $this->load->library('toolset');
        $data['countriesNationalities'] = $this->geo->get_countries();
        // $this->load->library('table');
        $hotel = $this->ht->get_hotel_slug($hslug);

        $rooms = [];
        $totaladults = 0;
        $totalchilds = 0;
        $data['adults'] = $totaladults;
        $data['checkin'] = date('Y-m-d', strtotime('now +1 days'));
        $data['checkout'] = date('Y-m-d', strtotime('now +3 days'));
        $data['childs'] = $totalchilds;
        $data['nights'] = datediff($checkin, $checkout);
        $adultset = []; //temporary to get the minimum adult per room
        $data['roomsSearch'] = $this->input->get('rooms') ?? '1_adults';
        $roomsraw = explode('*', $this->input->get('rooms'));
        foreach ($roomsraw as $rowroom) {
            $room = ['adults' => 0, 'children' => 0, 'age' => []];
            $rw = explode(',', $rowroom);
            foreach ($rw as $r) {
                if (preg_match("/_adults/i", $r, $match)) {
                    $room['adults'] = explode('_', $r)[0];
                    $totaladults += intval($room['adults']);
                    $adultset[] = intval($room['adults']);
                }
                if (preg_match("/_child/i", $r, $match)) {
                    $room['children'] = explode('_', $r)[0];
                    $totalchilds += intval($room['children']);
                }
                if (preg_match("/_age/i", $r, $match)) {
                    $room['age'] = explode('-', explode('_', $r)[0]);
                }
            }
            $rooms[] = $room;

        }
        $data['datatable'] = false; //temp patch for datatable undefined
        if ($hotel) {
            $hotelrooms = null;
            $data['imgs'] = $this->ht->hotel_photos($hotel->Hotel_ID);
            $data['metaImg'] = himg($hotel->Main_Photo);
            $data['hotel_amenities'] = $this->ht->hotelAmenities($hotel->hotelLotsId);
            $data['hotel_leisures'] = $this->ht->hotelLeisures($hotel->hotelLotsId);
            $data['transportaion'] = $this->ht->hotelTransportation($hotel->hotelLotsId);
            $hotel->Hotel_Name = tolang($hotel->Hotel_ID, 'hotelname') ? tolang($hotel->Hotel_ID, 'hotelname') : $hotel->Hotel_Name;
            $hotel->Hotel_Description = tolang($hotel->Hotel_ID, 'hoteldesc') ? tolang($hotel->Hotel_ID, 'hoteldesc') : $hotel->Hotel_Description;
            $hotel->Hotel_Address = tolang($hotel->Hotel_ID, 'hoteladdress') ? tolang($hotel->Hotel_ID, 'hoteladdress') : $hotel->Hotel_Address;
            $data['hotel'] = $hotel;
            $data['title'] = $hotel->Hotel_Name;
            if (is_numeric($totaladults) && $checkin != null && $checkout != null) {
                $localrooms = $this->ht->HotelRooms($hotel->hotelLotsId);
                $hotelrooms = $this->lotsofhotels->getAvailabeRooms($hotel->hotelLotsId, $checkin, $checkout, $rooms, $localrooms);
                $data['adults'] = $totaladults;
                $data['checkin'] = $checkin;
                $data['checkout'] = $checkout;
                $data['childs'] = $totalchilds;
                $data['nights'] = datediff($checkin, $checkout);
                $data['datatable'] = $hotelrooms ? $this->lotsofhotels->generateRoomTable($hotelrooms, $data['nights']) : false;
            }
            if (!empty($hotelrooms)) {
                $refreshRooms = $this->ht->refreshRooms($hotel->hotelLotsId, $hotelrooms);
                log_message('debug', "refresh rooms  $refreshRooms");
            }
            $this->render_view('hotels/hotel_details', $data);
        } else {
            
            // show_timed_error("the page is not found");
        }
    }

    public function map($hslug)
    {
        $hotelmap = $this->ht->get_hotelmap_slug($hslug);
        $data = [];
        if ($hotelmap) {
            $data['status'] = true;
            $data['data']['lat'] = $hotelmap->lat;
            $data['data']['lng'] = $hotelmap->lng;
            $data['data']['place_id'] = $hotelmap->placeid;
        } else {
            echo " Reservation error ";
        }
        $this->toolset->jsonfy($data);
    }


    public function book()
    {
        $this->load->library('Lotsofhotels');

        if (!$this->input->post('rooms')) {
            echo "not found";
        } else {
            if (strpos($this->input->post('rooms'), "*")) {
                $rooms = explode('*', $this->input->post('rooms'));
            } else {
                $rooms = [$this->input->post('rooms')];
            }
            $wallet = ['total_items' => 0, 'total_tax' => 0, 'total_municipalityTax' => 0, 'total_price' => 0, 'net_price' => 0];
            $wallet['prvid'] = $this->input->post('prvid');
            $wallet['nonights'] = $this->input->post('nonights');
            $wallet['hid'] = $this->input->post('hid');
            $wallet['lhid'] = $this->input->post('lhid');
            $wallet['adults'] = $this->input->post('adults');
            $wallet['childrens'] = $this->input->post('childrens');
            $wallet['checkin'] = $this->input->post('checkin');
            $wallet['checkout'] = $this->input->post('checkout');
            // getting idendifier from session and dist data
            $tmpWallet = $this->session->tmpWallet;
            foreach ($rooms as $room) {
                $el = $tmpWallet[$room];
                $blockRooms[] = $el;
                $el['roomcount'] = 1;
                $el['bedpref'] = 0;
                $el['gname'] = '';
                $el['gemail'] = '';
                $el['sp_requests'] = [];
                $wallet['total_items'] += 1;
                $wallet['total_tax'] += $el['vat'];
                $wallet['total_municipalityTax'] += $el['manTax'];
                $wallet['total_price'] += $el['price'];
                $wallet['net_price'] += $el['price'] + $el['vat'] + $el['manTax'];
                $wallet['items'][] = $el;
            }
            $wallet['total_price'] = round($wallet['total_price'], 2);
            $wallet['total_tax'] = round($wallet['total_tax'], 2);
            $wallet['total_municipalityTax'] = round($wallet['total_municipalityTax'], 2);
            $wallet['net_price']  = round($wallet['net_price'], 2);

            if ($this->session->dsval != null) {
                ///calcualtion review
                $wallet['dis_amount'] = changeMargin(intval($this->session->dsval['damount']), '%', '-', intval($wallet['total_price']));
                $wallet['discountid'] = $this->session->dsval['dsid'];
                $wallet['after_discount'] = $wallet['net_price'] + $wallet['dis_amount'];
                $this->session->unset_userdata('dsval');
            }
            $this->session->unset_userdata('tmpWallet');
            $this->session->set_userdata('wallet', serialize($wallet));
            $this->toolset->jsonfy(['status' => true]);
        }
    }
    //block it separatly
    public function blockroom()
    {
        $this->load->library('Lotsofhotels');

        //get it from js and check before adding to the wallet there
        $room = $this->input->post('room'); //from post request
        $tmpWallet = $this->session->tmpWallet;
        $roomData = [];
        if (isset($tmpWallet[$room])) {
            $roomData = $tmpWallet[$room];
        } else {
            echo $room;
            print_r($tmpWallet);
            $this->toolset->jsonfy(['status' => false, 'error'=>'room index undefined']);
            exit();
        }
        //check first if the selected rooms $wallet['items'] are blockable
        // print_r($wallet);
        $roomBlocked = $this->lotsofhotels->blockAvailabeRooms($this->input->post('lhid'), $this->input->post('checkin'), $this->input->post('checkout'), $roomData);
        $this->toolset->jsonfy(['status' => $roomBlocked]);

        //$hotelid, $fromDate, $toDate, $roomsVal,
    }
    public function htest()
    {
        if ($this->session->wallet !== null) {
            $wallet = unserialize($this->session->wallet);
            $data = ['result' => true, 'wallet' => $wallet, 'refid' => $this->session->userdata('refid')];

            $this->toolset->jsonfy($data);
        } else {
            $this->toolset->jsonfy(['result' => false, 'refid' => $this->session->userdata('refid')]);
        }
    }
    public function dtest()
    {
        if ($this->input->post('action')) {
            $del = $this->input->post('action');
            if ($del == 'delete') {
                $this->session->unset_userdata('wallet');
                $this->toolset->jsonfy(['result' => true]);
            }
        } else {
            $this->toolset->jsonfy(['result' => false]);
        }
    }
    public function jdiscodecheck()
    {
        $this->load->model('Hperiods_model');
        $err = [];
        $matches = false;
        $pvid = intval($this->input->get('pvid'));
        if (!$pvid) {
            $err['error'] = 'pvid is missing - ';
        }
        if (!$this->input->get('dt1')) {
            $err['error'] .= ' dt1 is missing - ';
        }
        if (!$this->input->get('dt2')) {
            $err['error'] .= ' dt2 is missing - ';
        }
        if (empty($err)) {
            $discountset = $this->Hperiods_model->get_discountcodes_typed($pvid, 'b2c');
            $match = discountAvailability($this->input->get('dt1'), $this->input->get('dt2'), $discountset);
            if ($match && count($match) > 0) {
                $matches = ["available" => true, 'dis_val' => $match[0]['discount_amount']];
            } else {
                $matches = ["available" => false];
            }
        }
        $res = $matches ? $matches : $err;
        $this->toolset->jsonfy($res);
    }

    public function jdiscodecheckav()
    {
        $this->load->model('Hperiods_model');
        $err = [];
        $matches = false;
        if (!$this->input->get('pvid')) {
            $err['error'] = 'pvid is missing - ';
        }
        if (!$this->input->get('dt1')) {
            $err['error'] .= ' dt1 is missing - ';
        }
        if (!$this->input->get('dt2')) {
            $err['error'] .= ' dt2 is missing - ';
        }
        if (!$this->input->get('tprice')) {
            $err['error'] .= ' the total price amount is missing - ';
        }
        if (!$this->input->get('discount')) {
            $err['error'] = '';
            $err['error'] ? $err['error'] = ' Please Enter The Code ' : $err['error'] .= ' Please Enter The Code ';
        }
        if (empty($err)) {

            $discountset = $this->Hperiods_model->get_discountcodes_typed((int) $this->input->get('pvid'), 'b2c');
            $match = discountAvailability($this->input->get('dt1'), $this->input->get('dt2'), $discountset);
            if (count($match) > 0) {
                if ($this->input->get('discount') == $match[0]['discount_codes']) {
                    $dsp = $match[0]['discount_amount'];
                    $tp = $this->input->get('tprice');
                    $matches["codestatus"] = true;
                    $matches["discount"] = $match[0]['discount_id'];
                    $matches["discount_val"] = $match[0]['discount_amount'];
                    $matches['discount_amount'] = changeMargin(intval($dsp), '%', '-', intval($this->input->get('tprice')));
                    $matches["discounted"] = intval($this->input->get('tprice')) + $matches['discount_amount'];
                    $this->session->set_userdata('dsval', ['damount' => $dsp, 'dsid' => $match[0]['discount_id']]);
                } else {
                    $matches = ["codestatus" => false];
                }
            } else {
                $matches[0] = ["available_dates" => 'Not Available'];
            }
        }
        $res = $matches ? $matches : $err;
        $this->toolset->jsonfy($res);
    }
}
