<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hotels extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Hotels_model', 'hms');
        $this->load->model('Auto_model', 'am');
        $this->load->model('fendmodels/fendproducts_model', 'fm');
        $this->load->model('Product_Categories_model');
        $this->load->model('Products_model');
        $this->load->library('toolset');
        $this->load->library('magician');
        $this->load->library('Lotsofhotels');
    }

    public function index()
    {
        //$data['cities'] = $this->hms->get_sorted_cities();
        //$data['url2'] = site_url('hotels/cities');
        $data['title'] = lang('list') ? lang('list') : 'hotels list';
        $data['makkah'] = $this->fm->city_hotels(164);
        $data['madinah'] = $this->fm->city_hotels(174);
        //$data['hotels'] = [];
        $this->render_view(['hotels/inc/search_area', 'hotels/hotels'], $data);
    }

    public function city($city = null)
    {
        $cityid = 0;
        $data['city'] = lang('mak');
        if ($city != null) {
            if ($city == 'makkah') {
                $cityid = 164;
                $data['city'] = lang('mak');
            } elseif ($city == 'madinah') {
                $data['city'] = lang('mad');
                $cityid = 174;
            }
            $hotels = $this->hms->get_hotels_by_city($cityid);
            $checkin = date('Y-m-d');
            $checkout = new DateTime($checkin);
            $checkout->modify('+2 days');
            $data['startdate'] = $checkin;
            $data['enddate'] = $checkout->format('Y-m-d');
            $data['adultscount'] = 1;
            if ($hotels) {
                $data['title'] = $city . ' - ' . lang('hotels');
                $data['hotels'] = $hotels;
                $this->render_view('hotels/cities', $data);
            } else {
                echo "not found";
            }
        } else {
            $data['title'] = "list of all available cities";
            // $data['cities'] = $this->hms->get_all_cities();
            redirect('hotels');
        }
    }

    public function search()
    {
        // $this->output->enable_profiler(TRUE);
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
        $this->render_view(['hotels/inc/search_area', 'hotels/search_results'], $data);
    }

    public function ajsearch()
    {
        $rooms = [['adults' => 1, 'children' => 0, 'age' => []]];
        $totaladults = 0;
        $totalchilds = 0;
        $nights = 1;
        //dev code to design
        // $this->toolset->jsonfy(['params' => ['adults' => $totaladults, 'children' => $totalchilds, 'nights' => $nights], 'hotels' => ['result' => false], 'error' => lang('invaliddest')]);
        // exit();

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
        // $country = $this->input->get('country') ?? 164;
        // $nationality = $this->input->get('nationality') ?? 164;
        $localhotels = $this->am->find_hotels(); //stars removed for compatibility issues
        $rates = $stars ? explode('_', $stars) : null; //dev code
        $availableHotels = $this->lotsofhotels->getAvailability($localhotels, $city, $startdate->format('Y-m-d'), $enddate->format('Y-m-d'), $rooms, $rates);
        // var_dump($availableHotels);
        // die();
        // No hotels Nothing to filter
        if (is_null($availableHotels) || empty($availableHotels) || !isset($availableHotels->hotel)) {
            $this->toolset->jsonfy(['params' => ['adults' => $totaladults, 'children' => $totalchilds, 'nights' => $nights], 'hotels' => ['result' => false], 'erro' => $availableHotels]);
            log_message('debug', $availableHotels);
            exit();
        }
        // $availableHotelset = (array)$availableHotels;
        // $availableHotels = $availableHotelset['hotel'];
        $availableHotels = $availableHotels->xpath('//hotel');
        //dev code only for fast packing
        // increasing the the hotels 
        $filteredhotels = $this->filterHotels($localhotels, $availableHotels);
        // was for increasing available result deprecated
        // if (count($filteredhotels) == 1) {
        //     $filteredhotels[0]['recommended'] = true;
        //     $recapRoom = [['adults' => 1, 'children' => 0, 'age' => []]];
        //     $availableHotelsRecap = $this->lotsofhotels->getAvailability($localhotels, $city, $startdate->format('Y-m-d'), $enddate->format('Y-m-d'), $recapRoom, $rates)->hotel;
        //     $availableHotelsRecapSet = $this->filterHotels($localhotels, $availableHotelsRecap);
        //     // No hotels Nothing to filter
        //     if (!is_null($availableHotelsRecapSet) || !empty($availableHotelsRecapSet)) {
        //         $filteredhotels = array_merge($filteredhotels, $availableHotelsRecapSet);
        //     }
        // }
        // $this->toolset->jsonfy($availableHotels);
        // die();
        // to be moved to the library it self

        $this->toolset->jsonfy(['params' => ['adults' => $totaladults, 'children' => $totalchilds, 'nights' => $nights], 'hotels' => $filteredhotels, 'result' => true] ?? ['result' => false]);
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
                // echo "hotel not match locally" . (int)$avroom->attributes()->hotelid . "br>";
                // continue;
            };
            $avroomlist = [];
            $totalroomsprice = 0;
            $totalroomstaxes = 0;
            $lastinsert = false;
            $roomsCount = count($avroom->rooms->room);
            foreach ($avroom->rooms->room as $avr) {
                $tPrice = $avr->roomType[0]->rateBases[0]->rateBasis->totalMinimumSelling != null && !empty((float)$avr->roomType[0]->rateBases[0]->rateBasis->totalMinimumSelling) ? (float)$avr->roomType[0]->rateBases[0]->rateBasis->totalMinimumSelling : (float)$avr->roomType[0]->rateBases[0]->rateBasis->total;
                $totalPrice = calcPrices($tPrice);
                $totaltaxex = calcPriceTaxFees($tPrice);
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
        // print_r($filteredhotels);
        // exit();
        $localhotels = array_map(function ($localhotel) {
            //unset unneeded fields 
            return ['hotel' => $localhotel];
        }, $localhotels);
        usort($localhotels, function ($h1, $h2) {
            if (($h1['hotel']['totals'] && $h2['hotel']['totals'])) {
                return $h1['hotel']['totals'] > $h2['hotel']['totals'];
            } else {
                return $h1['hotel']['totals'] < $h2['hotel']['totals'];
            }
        });
        // print_r($localhotels);
        // exit();
        return $localhotels;
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

    public function s_bar()
    {
        $this->load->helper('gmaps');
        //$this->load->model('Auto_model', 'am');
        $txt = html_escape($this->input->get('dest'));
        if (preg_match('/^[\p{Arabic}a-zA-Z0-9_ .\-\p{N}]+\h?[\p{N}\p{Arabic}a-zA-Z0-9_ .\-]*$/u', $txt)) {
            $resdata = $this->am->find($txt) ? $this->am->find($txt) : false;
            if ($resdata) {
                // $resdata[] = ['title' => 'Mecca', 'type' => 'locality', 'tid'=>164, 'city'=>164];
                // $resdata[] = ['title' => 'Makkah', 'type' =>'locality', 'tid' => 164, 'city' => 164];
                // $resdata[] = ['title' => 'madinah', 'type' =>'locality', 'tid' => 174, 'city' => 174];
                // $resdata[] = ['title' => 'Al Madinah', 'type' =>'locality', 'tid' => 174, 'city' => 174];
                // $resdata[] = ['title' => 'almadinah almunawwarah', 'type' =>'locality', 'tid' => 174, 'city' => 174];
                // $resdata[] = ['title' => 'mecca al mukarramah', 'type' =>'locality', 'tid' => 164, 'city' => 164];
                // $resdata[] = ['title' => 'Makkah Al-Mukarramah', 'type' =>'locality', 'tid' => 164, 'city' => 164];
                // $gplaces = goPlaces($txt, 'autodetect');
                // // print_r($gplaces);
                // if ($gplaces !== null && is_array($gplaces)) {
                //     for ($i = 0; $i < count($gplaces); $i++) {
                //         $type = $gplaces[$i]['types'][0] ?? 'non-typed';
                //         $resdata[] = ['title' => $gplaces[$i]['name'], 'type' => $type];
                //         $resdata[] = ['title' => $gplaces[$i]['formatted_address'], 'type' => $type];
                //     }
                // }
               $this->toolset->jsonfy($resdata);
            //    
            } else {
                $this->toolset->jsonfy([['title' => 'NO Results', 'titledd' => 'NO Results', 'type' => 'none', 'tid' => 0, 'city' => 0, 'label' => 'None']]);
            }
        } else {
            $this->toolset->jsonfy(['error' => lang('invaliddest')]);
        }
    }

    public function tr()
    {
        initlang();
        $txt = $this->input->get('text');
        if ($txt) {
            $res['status'] = true;
            $res['result'] = comtrans($txt);
        } else {
            $res['status'] = false;
            $res['result'] = "No Translation Available";
        }
        $this->toolset->jsonfy($res);
    }
}
