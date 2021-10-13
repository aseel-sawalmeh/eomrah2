<?php
defined('BASEPATH') or exit('No direct scripts allowed');

class Hotel extends B2b_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auto_model', 'am');
        $this->load->library('toolset');
   
        $this->load->helper('gmaps');
        $this->load->model('Hotel_model', 'ht');
        $this->load->model('Reservation_model', 'rsm');
        $this->load->model('translate/Translate_model');
        $this->load->model('Multimedia_model');
        $this->load->model('fendmodels/fendproducts_model');
        $this->load->model('fendmodels/fphoto_model');
        $this->load->model('Count', 'cm');
        $this->load->library('magician');
        $this->load->library('Lotsofhotels');
    }



    public function index()
    {
        $data['title'] = "Search Hotels";
        $this->render_view('hotels/b2b/inc/search', $data);
    }

    public function search()
    {
        $rooms = [['adults' => 1, 'children' => 0, 'age' => []]];
        $totaladults = 0;
        $totalchilds = 0;
        if ($this->input->get('dt1') && $this->input->get('dt2') && $this->input->get('rooms') && !empty($this->input->get('dest'))) {
            $roomsraw = explode('*', $this->input->get('rooms'));
            $rooms = [];
            foreach ($roomsraw as $rowroom) {
                $room = ['adults' => 0, 'children' => 0, 'age' => []];
                $rw = explode(',', $rowroom);
                foreach ($rw as $r) {
                    if (preg_match("/_adults/i", $r, $match)) {
                        $room['adults'] = explode('_', $r)[0];
                        $totaladults += intval($room['adults']);
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
        }

       
        $data['totaladults'] = $totaladults;
        $data['rooms'] = json_encode($rooms);
        $data['title'] = 'search results';
        $this->render_view(['hotels/b2b/inc/search', 'hotels/b2b/results'], $data);
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
            $this->render_view('hotels/b2b/details', $data);
        } else {
            
            // show_timed_error("the page is not found");
        }
    }
    public function invoice()
    {
        $id = $this->session->user_data('C_ID');
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'b2b/hotel/invoice';
        $config['total_rows'] = $this->rsm->b2b_invoicecount();
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] ='</li>';
        $config['next_link'] = '<span class="page-link">Next</span>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<span class="page-link">Prev</span>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['first_link'] = '<span class="page-link">First</span>';
        $config['last_link']  = '<span class="page-link">Last</span>';
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $data['list'] = $this->rsm->get_b2binvoicelist($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links();
        $data['title'] = "User Invoice";
        $data['paidres'] = $this->cm->paidb2bres($id);
        $data['unpaidres'] = $this->cm->unpaidb2bres($id);
        $data['confirmres'] = $this->cm->confirmedb2bres($id);
        $data['sales'] = $this->cm->b2bsales($id);
        $data['deposit'] = $this->cm->b2bdeposit($id);
        $this->render_view('hotels/b2b/invoice_list', $data);
    }

    public function s_bar()
    {
        $this->load->helper('gmaps');
        //$this->load->model('Auto_model', 'am');
        $txt = html_escape($this->input->get('dest'));
        if (preg_match('/^[\p{Arabic}a-zA-Z0-9_ .\-\p{N}]+\h?[\p{N}\p{Arabic}a-zA-Z0-9_ .\-]*$/u', $txt)) {
            $resdata = $this->am->find($txt) ? $this->am->find($txt) : false;
            if ($resdata) {
               
               $this->toolset->jsonfy($resdata);
            //    
            } else {
                $this->toolset->jsonfy([['title' => 'NO Results', 'titledd' => 'NO Results', 'type' => 'none', 'tid' => 0, 'city' => 0, 'label' => 'None']]);
            }
        } else {
            $this->toolset->jsonfy(['error' => lang('invaliddest')]);
        }
    }


    public function pagereso()
    {
        //to list all lang args and inject to doc body 
        $data = array(
            'from' => lang('from'),
            'total' => lang('total'),
            'vatinc' => lang('vatinc'),
            'nights' => lang('nights'),
            'night' => lang('night'),
            'book' => lang('book'),
            'freecancelation' => lang('freecancelation'),
            'bookingpossible' => lang('bookingpossible'),
            'adults' => lang('adults'),
            'adults' => lang('adult'),
            'child' => lang('childs'),
            'child' => lang('child'),
            'room' => lang('room'),
        );
        $this->toolset->jsonfy($data);
    }

    public function filterHotels($localhotels, $availableHotels)
    {
        $filteredhotels = [];
        $localhotels = array_map(function ($localhotel) {
            $localhotel['rooms'] = [];
            $localhotel['roomscount'] = 0;
            $localhotel['totaltest'] = 0;
            $localhotel['totals'] = 0;
            $localhotel['taxes'] = 0;
            $localhotel['Main_Photo'] = himg($localhotel['Main_Photo']);
            $localhotel['geo'] = $this->am->get_hotel_geo($localhotel['Hotel_ID']);
            $localhotel['currency'] = userCurShort();
            $localhotel['recommended'] = false;
            $localhotel['Hotel_Name'] = tolang($localhotel['Hotel_ID'], 'hotelname');
            $localhotel['Hotel_Address'] = tolang($localhotel['Hotel_ID'], 'hoteladdress');
            $localhotel['city_name'] = (userlang() == 'ar') ? $localhotel['city_name_ar'] : $localhotel['city_name'];
            return $localhotel;
        }, $localhotels);

        foreach ($availableHotels as $avroom) {
            $localindex = array_search((int)$avroom->attributes()->hotelid, array_column($localhotels, 'hotelLotsId'));
            if (!$localindex) {
                log_message('debug', 'new hotel not locally exist ' . (int)$avroom->attributes()->hotelid);
            };
            $avroomlist = [];
            $totalroomsprice = 0;
            $totalroomstaxes = 0;
            $lastinsert = false;
            $roomsCount = count($avroom->rooms->room);
            foreach ($avroom->rooms->room as $avr) {
                $tPrice = $avr->roomType[0]->rateBases[0]->rateBasis->totalMinimumSelling != null && !empty((float)$avr->roomType[0]->rateBases[0]->rateBasis->totalMinimumSelling) ? (float)$avr->roomType[0]->rateBases[0]->rateBasis->totalMinimumSelling : (float)$avr->roomType[0]->rateBases[0]->rateBasis->total;
                $totalPrice = b2bcalcPrices($tPrice);
                $totaltaxex = b2bcalcPriceTaxFees($tPrice);
                if ($lastinsert) {
                    if ($lastinsert['roomtype'] == (int)$avr->roomType[0]->attributes()->roomtypecode && $lastinsert['rateType'] == $avr->roomType[0]->rateBases->rateBasis->rateType) {
                        $totalroomsprice += $totalPrice;
                        $totalroomstaxes += $totaltaxex;
                        continue;
                    }
                }
                $listroom['name'] = (string)$avr->roomType[0]->name;
                $listroom['roomtype'] = (int)$avr->roomType[0]->attributes()->roomtypecode;
                $listroom['attr']['adults'] = (int)$avr->attributes()->adults;
                $listroom['attr']['children'] = (int)$avr->attributes()->children;
                $listroom['attr']['extrabeds'] = (int)$avr->attributes()->extrabeds;
                if (is_iterable($avr->roomType[0]->rateBases->rateBasis)) {
                    $listroom['rateType'] = (int)$avr->roomType[0]->rateBases->rateBasis[0]->rateType;
                    $listroom['total'] = $totalPrice;
                    $totalroomsprice += $totalPrice;
                    $totalroomstaxes += $totaltaxex;
                } else {
                    $match['rate'] = (int)$avr->roomType[0]->rateBases->rateBasis->rateType;
                    $listroom['rateType'] = $avr->roomType[0]->rateBases->rateBasis->rateType;
                    $listroom['total'] = $totalPrice;
                    $totalroomsprice += $totalPrice;
                    $totalroomstaxes += $totaltaxex;
                }
                $lastinsert = $listroom;
                $avroomlist[] = $listroom;
            }
            $localhotels[$localindex]['rooms'] = $avroomlist;
            $localhotels[$localindex]['roomscount'] = $roomsCount;
            $localhotels[$localindex]['totaltest'] = round($totalroomsprice, 2);
            $localhotels[$localindex]['totals'] = $totalroomsprice;
            $localhotels[$localindex]['taxes'] = $totalroomstaxes;
            $localhotels[$localindex]['geo'] = $this->am->get_hotel_geo($localhotels[$localindex]['Hotel_ID']);
            $localhotels[$localindex]['currency'] = userCurShort();
            $localhotels[$localindex]['recommended'] = false;
            $localhotels[$localindex]['Hotel_Name'] = tolang($localhotels[$localindex]['Hotel_ID'], 'hotelname');
            $localhotels[$localindex]['Hotel_Address'] = tolang($localhotels[$localindex]['Hotel_ID'], 'hoteladdress');
            $localhotels[$localindex]['city_name'] = (userlang() == 'ar') ? $localhotels[$localindex]['city_name_ar'] : $localhotels[$localindex]['city_name'];
            $filteredhotels[] = ['hotel' => $localhotels[$localindex]];
        }
       
        $localhotels = array_map(function ($localhotel) {
          
            return ['hotel' => $localhotel];
        }, $localhotels);
        usort($localhotels, function ($h1, $h2) {
            if (($h1['hotel']['totals'] && $h2['hotel']['totals'])) {
                return $h1['hotel']['totals'] > $h2['hotel']['totals'];
            } else {
                return $h1['hotel']['totals'] < $h2['hotel']['totals'];
            }
        });
       
        return $localhotels;
    }


    public function ajsearch()
    {
        $rooms = [['adults' => 1, 'children' => 0, 'age' => []]];
        $totaladults = 0;
        $totalchilds = 0;
        $nights = 1;
       

        if ($this->input->get('dt1') && $this->input->get('dt2') && $this->input->get('rooms') && !empty($this->input->get('dest'))) {
            $rooms = [];
            $roomsraw = explode('*', $this->input->get('rooms'));
            foreach ($roomsraw as $rowroom) {
                $room = ['adults' => 0, 'children' => 0, 'age' => []];
                $rw = explode(',', $rowroom);
                foreach ($rw as $r) {
                    if (preg_match("/_adults/i", $r, $match)) {
                        $room['adults'] = explode('_', $r)[0];
                        $totaladults += intval($room['adults']);
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
            $startdate = new DateTime($this->input->get('dt1'));
            if ($startdate < new DateTime('NOW') && $startdate->diff(new DateTime('NOW'))->format('%a') > 0) {
                $this->toolset->jsonfy(['result' => false, 'error' => "Invalid Dates", 'diff' => $startdate->diff(new DateTime('NOW'))->format('%a')]);
                die();
            }
            $enddate = new DateTime($this->input->get('dt2'));
            $nights = $startdate->diff($enddate)->format('%a');
        } else {
            $this->toolset->jsonfy(['params' => ['adults' => $totaladults, 'children' => $totalchilds, 'nights' => $nights], 'hotels' => ['result' => false], 'error' => lang('invaliddest')]);
            exit();
        }

        $stars = $this->input->get('stars');
        $city = $this->input->get('city') ?? 164;
     
        $localhotels = $this->am->find_hotels(); 
        $rates = $stars ? explode('_', $stars) : null; //dev code
        $availableHotels = $this->lotsofhotels->getAvailability($localhotels, $city, $startdate->format('Y-m-d'), $enddate->format('Y-m-d'), $rooms, $rates);
        
        if (is_null($availableHotels) || empty($availableHotels) || !isset($availableHotels->hotel)) {
            $this->toolset->jsonfy(['params' => ['adults' => $totaladults, 'children' => $totalchilds, 'nights' => $nights], 'hotels' => ['result' => false], 'erro' => $availableHotels]);
            log_message('debug', $availableHotels);
            exit();
        }
       
        $availableHotels = $availableHotels->xpath('//hotel');
      
        $filteredhotels = $this->filterHotels($localhotels, $availableHotels);
       

        $this->toolset->jsonfy(['params' => ['adults' => $totaladults, 'children' => $totalchilds, 'nights' => $nights], 'hotels' => $filteredhotels, 'result' => true] ?? ['result' => false]);
    }


    public function blockroom()
    {
        $this->load->library('Lotsofhotels');
        $room = $this->input->post('room'); 
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
        $roomBlocked = $this->lotsofhotels->blockAvailabeRooms($this->input->post('lhid'), $this->input->post('checkin'), $this->input->post('checkout'), $roomData);
        $this->toolset->jsonfy(['status' => $roomBlocked]);

        
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
            $wallet['b2b'] = 1;
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

    public function payment($invid){
       $details = $this->rsm->get_b2binvoice($invid);
       $inv_price = $details->NetPrice;
       $deduct_deposit = $this->rsm->minus_b2b_deposit($inv_price);
        if($deduct_deposit){
            $token = bin2hex(random_bytes(20));
            $this->session->set_userdata('transcode', $token);
            redirect("b2b/hotel/success/$token/$invid");
        }
    }


    public function success($transcode, $resref)
    {
        if ($this->session->has_userdata('transcode') && $this->session->userdata('transcode') == $transcode) {
            $this->load->model('Reservation_model', 'rsm');
            $this->load->library('Lotsofhotels');

            $booking = $this->rsm->b2bres_details($resref);
            $confirmBooking = $this->lotsofhotels->confirmBooking($booking);

            $this->rsm->confirmTransaction($booking->ID);
            $this->session->unset_userdata('transcode');
            if ($confirmBooking['result']) {
                $res_update = [
                    'lotsResId' => (int)$confirmBooking['details']->returnedCode,
                    'Paid' => 1,
                    'confirm' => (int)$confirmBooking['details']->bookings->booking->bookingStatus
                ];
                $resAllConfirmations = [];
                if (count($confirmBooking['details']->bookings->booking) > 1) {
                    foreach ($confirmBooking['details']->bookings->booking as $booked) {
                        $res_confirmation = [
                            'resId' => $confirmBooking['resID'],
                            'retunedCode' => (int)$confirmBooking['details']->returnedCode,
                            'confirmationText' => (string)$confirmBooking['details']->confirmationText,
                            'bookingCode' => (int)$booked->bookingCode,
                            'bookingRefNumber' => (string)$booked->bookingReferenceNumber,
                            'bookingStatus' => (int)$booked->bookingStatus,
                            'servicePrice' => (float)$booked->servicePrice,
                            'mealsPrice' => (float)$booked->mealsPrice,
                            'price' => (float)$booked->price,
                            'currency' => (int)$booked->currency,
                            'type' => (string)$booked->type,
                            'voucher' => (string)$booked->voucher,
                            'paymentGuaranteedBy' => (string)$booked->paymentGuaranteedBy,
                            'emergencyContacts' => (string)$booked->emergencyContacts,
                        ];
                        $resAllConfirmations[] = $res_confirmation;
                    }
                } else {
                    $res_confirmation = [
                        'resId' => $confirmBooking['resID'],
                        'retunedCode' => (int)$confirmBooking['details']->returnedCode,
                        'confirmationText' => (string)$confirmBooking['details']->confirmationText,
                        'bookingCode' => (int)$confirmBooking['details']->bookings->booking->bookingCode,
                        'bookingRefNumber' => (string)$confirmBooking['details']->bookings->booking->bookingReferenceNumber,
                        'bookingStatus' => (int)$confirmBooking['details']->bookings->booking->bookingStatus,
                        'servicePrice' => (float)$confirmBooking['details']->bookings->booking->servicePrice,
                        'mealsPrice' => (float)$confirmBooking['details']->bookings->booking->mealsPrice,
                        'price' => (float)$confirmBooking['details']->bookings->booking->price,
                        'currency' => (int)$confirmBooking['details']->bookings->booking->currency,
                        'type' => (string)$confirmBooking['details']->bookings->booking->type,
                        'voucher' => (string)$confirmBooking['details']->bookings->booking->voucher,
                        'paymentGuaranteedBy' => (string)$confirmBooking['details']->bookings->booking->paymentGuaranteedBy,
                        'emergencyContacts' => (string)$confirmBooking['details']->bookings->booking->emergencyContacts,
                    ];
                    $resAllConfirmations[] = $res_confirmation;
                }
                $saveBookingConfirmation = $this->rsm->persistPaidRes($confirmBooking['resID'], $res_update, $resAllConfirmations);
                if ($saveBookingConfirmation) {
                    redirect("b2b/dashboard/pinv/$resref");
                }
            } else {
                $this->rsm->ResPaid($resref);
                // record the error for later fix
                // log_id 	resId 	error 	postXml 	solved 
                $this->rsm->log_lots_error(['resId'=> $confirmBooking['resID'], 'error'=> $confirmBooking['error'], 'postXml'=> $confirmBooking['req'], 'solved'=>0]);
                log_message('error', 'resid '.$resref.' ' .$confirmBooking['error']);
                redirect("b2b/dashboard/pinv/$resref");
            }
        } else {
            show_error('not allowed or expired code', 500, 'reservation code expired');
        }
    }



   
}