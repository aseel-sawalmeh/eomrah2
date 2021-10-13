<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');
/*
 * This Library to deal with Lots Api
 * Auther Gebriel Alkhayal
 * LotsOfHotelsApi Version 1.1
 */
class Lotsofhotels
{
    private $currency;
    private $country;
    private $nationality;

    public function __construct()
    {
        $this->inst = &get_instance();
        $this->inst->load->model('Geo_model', 'geo');
        $this->geo_init();
        $this->currency = usercurLots();
    }

    public function geo_init()
    {
        $this->country = $this->inst->input->get('country') ?? $this->inst->geo->get_country_code(vistorCountry());
        $this->nationality = $this->inst->input->get('nationality') ?? $this->inst->geo->get_country_code(vistorCountry());
    }
    public function getAvailability($hotels, $city, $fromDate, $toDate, $roomsVal, $rates = [])
    {
        // $this->lotsLogs('test1', 'test text contents');
        $xml = $this->makexml('searchhotels', 'hotel');
        $bookingDetials = $xml->request->addChild('bookingDetails');
        $bookingDetials->addChild('fromDate', $fromDate);
        $bookingDetials->addChild('toDate', $toDate);
        $bookingDetials->addChild('currency', $this->currency);
        $rooms = $bookingDetials->addChild('rooms');

        for ($i = 0; $i < count($roomsVal); $i++) {
            $room = $rooms->addChild('room');
            $room->addAttribute('runno', $i);
            $room->addChild('adultsCode', $roomsVal[$i]['adults']);
            $children = $room->addChild('children', ' ');
            $children->addAttribute('no', $roomsVal[$i]['children']);
            if ($roomsVal[$i]['children']) foreach ($roomsVal[$i]['age'] as $key => $age) {
                $child = $children->addChild('child', $age);
                $child->addAttribute('runno', $key);
            }
            $room->addChild('rateBasis', 1);
            $room->addChild('passengerNationality', $this->nationality);
            $room->addChild('passengerCountryOfResidence', $this->country);
        }
        $xreturn = $xml->request->addChild('return');
        $filters = $xreturn->addChild('filters');
        $filters->addAttribute('xmlns:xmlns:a', 'http://us.dotwconnect.com/xsd/atomicCondition');
        $filters->addAttribute('xmlns:xmlns:c', 'http://us.dotwconnect.com/xsd/complexCondition');
        $filters->addChild('city', $city);
        if (!empty($rates) && count($rates) >= 1) {
            $c_condition = $filters->addChild('c:condition');
            $a_condition = $c_condition->addChild('a:condition');
            $a_condition->addChild('fieldName', 'rating');
            $a_condition->addChild('fieldTest', 'in');
            $fieldValues = $a_condition->addChild('fieldValues');
            foreach ($rates as $rate) {
                $fieldValues->addChild('fieldValue', $rate);
            }
        }

        $result = $this->makeRequest($xml->asXML());
        $error = isset($result->request->error->details) ? (string)$result->request->error->details : 'unknown error';
        return isset($result->hotels) ? $result->hotels : $error;
    }

    public function getAvailabeRooms($hotelid, $fromDate, $toDate, $roomsVal, $localrooms) //note the localrooms if only names get only names
    {
        $xml = $this->makexml('getrooms', 'hotel');
        $bookingDetials = $xml->request->addChild('bookingDetails');
        $bookingDetials->addChild('fromDate', $fromDate);
        $bookingDetials->addChild('toDate', $toDate);
        $bookingDetials->addChild('currency', $this->currency);
        $rooms = $bookingDetials->addChild('rooms');
        $rooms->addAttribute('no', count($roomsVal));

        for ($i = 0; $i < count($roomsVal); $i++) {
            $room = $rooms->addChild('room');
            $room->addAttribute('runno', $i);
            $room->addChild('adultsCode', $roomsVal[$i]['adults']);
            $children = $room->addChild('children', ' ');
            $children->addAttribute('no', $roomsVal[$i]['children']);
            if ($roomsVal[$i]['children']) foreach ($roomsVal[$i]['age'] as $key => $age) {
                $child = $children->addChild('child', $age);
                $child->addAttribute('runno', $key);
            }
            $room->addChild('rateBasis', 1);
            $room->addChild('passengerNationality', $this->country);
            $room->addChild('passengerCountryOfResidence', $this->country);
        }

        $bookingDetials->addChild('productId', $hotelid);
        $response = $this->makeRequest($xml->asXML());
        // var_dump($response->hotel->extraMeals);
        $roomsRows = [];
        $hotelrooms = [];
        $tmpWallet = []; //tempwallet
        if ((string)$response->successful == 'TRUE') {
            $hotelrooms = $response->hotel->rooms->room;
            // print_r($hotelrooms);exit();
            foreach ($hotelrooms as $room) {
                $roomrow['lookedForText'] = $room->lookedForText;
                $roomrow['extraMeals'] = $response->hotel->extraMeals;
                foreach ($room->roomType as $roomType) {
                    $tmpWalletItem = [];
                    $roomrow['roomtypecode'] = (int) $roomType->attributes()->roomtypecode;
                    $roomrow['name'] = (string) $roomType->name; //call name dynamic according to lang
                    if (userlang() == 'ar') {
                        $roomrow['name'] = $localrooms[array_search($roomrow['roomtypecode'], array_column($localrooms, 'rid'))]['name_ar'] ?? $roomrow['name'];
                    }
                    $roomrow['sleeps'] = (array) $roomType->roomInfo;
                    $roomrow['amenities'] = (array) $roomType->roomAmenities->amenity;
                    $roomrow['specials'] = (array) $roomType->specials->special;
                    $roomrow['rateBasis'] = [];
                    $basisCounter = 0;
                    foreach ($roomType->rateBases->rateBasis as $base) {
                        // echo (float)$base->totalMinimumSelling;
                        $total = (isset($base->totalMinimumSelling) && !empty((float)$base->totalMinimumSelling)) ? (float)$base->totalMinimumSelling : (float)$base->total;
                        $row['baseid'] = (int)$base->attributes()->id;
                        $row['allocationDetails'] = (string)$base->allocationDetails;
                        $row['status'] = (string)$base->status;
                        
                        if(get_cookie('eomrah_b2b_prices') != ''){
                            $row['prices'] = b2bcalcPrices($total);
                            $row['taxesfees'] = b2bcalcPriceTaxFees($total);
                            $row['vat'] = b2bcalcVat($total);
                            $row['manTax'] = b2bcalcManicTax($total);
                            $row['totalformated'] = b2bcalcPrices($total, true);
                        }else{
                            $row['prices'] = calcPrices($total);
                            $row['taxesfees'] = calcPriceTaxFees($total);
                            $row['vat'] = calcVat($total);
                            $row['manTax'] = calcManicTax($total);
                            $row['totalformated'] = calcPrices($total, true);
                        }
                        $row['night'] = (int)$base->dates->attributes()->count;
                        $row['meal'] = lang((string)$base->attributes()->description); //meals included fix rate from lang key so edit to be translated here
                        $row['rateid'] = (int)$base->attributes()->id;
                        $row['rateDescription'] = (string)$base->attributes()->description;
                        $row['dates'] = (array)$base->dates;
                        $row['tariffNotes'] = (string)$base->tariffNotes;
                        $row['withinCancellationDeadline'] = $base->withinCancellationDeadline == 'yes' ? true : false;
                        $row['cancellationRules'] = (array)$base->cancellationRules;
                        $row['cancellation'] = lang('cancelb4') . '<br>';
                        $row['cancellation']  .= empty((string)$base->cancellationRules->rule[0]->fromDateDetails) ? (string)$base->cancellationRules->rule[0]->toDateDetails : (string)$base->cancellationRules->rule[0]->fromDateDetails;
                        $row['minStay'] = (string)$base->minStay;
                        $row['dateApplyMinStay'] = (string)$base->dateApplyMinStay;
                        $row['allowsExtraMeals'] = (bool)$base->allowsExtraMeals;
                        $row['isBookable'] =  $base->isBookable == 'yes' ? true : false;
                        $row['leftToSell'] = (int)$base->leftToSell;
                        $row['lookedForText'] = $room->lookedForText;
                        // $row['including'] =
                        $row['changedOccupancy'] = (array)$base->changedOccupancy;
                        $row['allowsSpecialRequests'] = (bool)$base->allowsSpecialRequests;
                        $row['allowsBeddingPreferencies'] = (bool)$base->allowsBeddingPreferencies;
                        $row['extraBed'] = (string)$base->extraBed;
                        $row['extraBedOccupant'] = (string)$base->extraBedOccupant;
                        $row['leftToSell'] = (int)$base->leftToSell;

                        // $row['including'] =
                        // new session tmpWallet edit
                        $tmpWalletItem = array(
                            'rtype' => $roomrow['roomtypecode'],
                            'ratebase' => $row['baseid'],
                            'rname' => $roomrow['name'],
                            'adults' => (int)$room->attributes()->adults,
                            'children' => ($room->attributes()->childrenages ? (string)$room->attributes()->childrenages : 0),
                            'meal' => $row['meal'],
                            'price' => $row['prices'],
                            'taxesfees' => $row['taxesfees'],
                            'vat' => $row['vat'],
                            'manTax' => $row['manTax'],
                            'allocation' => $row['allocationDetails'],
                            'cancelline' => $row['cancellation'],
                            'cancelref' => $this->cancelationRules($row['cancellationRules']['rule']),
                            'allowsSpecialRequests' => $row['allowsSpecialRequests'],
                            'allowsBeddingPreferencies' => $row['allowsBeddingPreferencies'],
                        );
                        // print_r($tmpWalletItem);exit();
                        if (isset($tmpWallet[$roomrow['roomtypecode'] . '_' . $row['baseid']]) && isset($tmpWallet[$roomrow['roomtypecode'] . '_' . $row['baseid'] . '_' . $basisCounter])) {
                            $basisCounter++;
                            $tmpWallet[$roomrow['roomtypecode'] . '_' . $row['baseid'] . '_' . $basisCounter] = $tmpWalletItem;
                            $row['rid'] = $roomrow['roomtypecode'] . '_' . $row['baseid'] . '_' . $basisCounter;
                        } elseif ((isset($tmpWallet[$roomrow['roomtypecode'] . '_' . $row['baseid']]) && !isset($tmpWallet[$roomrow['roomtypecode'] . '_' . $row['baseid'] . '_' . $basisCounter]))) {
                            $basisCounter++;
                            $tmpWallet[$roomrow['roomtypecode'] . '_' . $row['baseid'] . '_' . $basisCounter] = $tmpWalletItem;
                            $row['rid'] = $roomrow['roomtypecode'] . '_' . $row['baseid'] . '_' . $basisCounter;
                        } else {
                            $tmpWallet[$roomrow['roomtypecode'] . '_' . $row['baseid']] = $tmpWalletItem;
                            $row['rid'] = $roomrow['roomtypecode'] . '_' . $row['baseid'];
                        }
                        // $el['ratebase'] = $rm[2];
                        // $el['price'] = $rm[4];
                        // $el['allocation'] = $rm[5];
                        // $el['cancelline'] = $rm[6];
                        // $el['cancelref'] = $rm[7];
                        // table contents
                        $roomrow['rateBasis'][] = $row;
                    }
                    $roomsRows[] = $roomrow;
                }
            }
            // print_r($tmpWallet);exit();
            $this->inst->session->unset_userdata('tmpWallet');

            $this->inst->session->set_userdata('tmpWallet', $tmpWallet);
        }
        return $roomsRows ?? False;
    }





    //blocking rooms for booking multi rooms a time
    public function blockMultiRooms($hotelid, $fromDate, $toDate, $roomsv)
    {
        //TODO:base the keys on the runno but for the time sake keep normal keys
        $xml = $this->makexml('getrooms', 'hotel');
        $bookingDetials = $xml->request->addChild('bookingDetails');
        $bookingDetials->addChild('fromDate', $fromDate);
        $bookingDetials->addChild('toDate', $toDate);
        $bookingDetials->addChild('currency', $this->currency);
        $rooms = $bookingDetials->addChild('rooms');
        $rooms->addAttribute('no', count($roomsv));
        $ind = 0;
        $checked = [];
        $unchecked = [];
        foreach ($roomsv as $key => $rv) {
            $room = $rooms->addChild('room');
            $room->addAttribute('runno', $ind);
            $room->addChild('adultsCode', $rv['adults']);
            $children = $room->addChild('children', ' ');
            if (!empty($rv['children'])) {
                $ages = explode(',', $rv['children']);
                $children->addAttribute('no', count($ages));
                foreach ($ages as $key => $age) {
                    $child = $children->addChild('child', $age);
                    $child->addAttribute('runno', $key);
                }
            } else {
                $children->addAttribute('no', 0);
            }
            $room->addChild('rateBasis', 1);
            $room->addChild('passengerNationality', $rv['nationality']);
            $room->addChild('passengerCountryOfResidence', $rv['country']);
            $roomTypeSelected = $room->addChild('roomTypeSelected');
            $roomTypeSelected->addChild('code', $rv['rtype']);
            $roomTypeSelected->addChild('selectedRateBasis', $rv['ratebase']);
            $roomTypeSelected->addChild('allocationDetails', $rv['allocation']);
            $ind++;
        }

        $bookingDetials->addChild('productId', $hotelid);
        $response = $this->makeRequest($xml->asXML());
        if ((bool)$response->successful) {
            $hotelrooms = $response->hotel->rooms->room;
            $this->lotsLogs("multiRooms_blockroom_req", $xml->asXML());
            $this->lotsLogs("multiRooms_blockroom_resp", $response->asXML());
            foreach ($hotelrooms as $room) {
                foreach ($room->roomType as $roomType) {
                    $roomrow['roomtypecode'] = (int) $roomType->attributes()->roomtypecode;
                    foreach ($roomType->rateBases->rateBasis as $base) {
                        $row['baseid'] = (int)$base->attributes()->id;
                        $row['allocationDetails'] = (string)$base->allocationDetails;
                        $row['status'] = (string)$base->status;
                        $rid = $roomrow['roomtypecode'] . '_' . $row['baseid'] . '_' . (int)$room->attributes()->runno . '_' .
                            (int)$roomType->attributes()->runno . '_'
                            . (int)$base->attributes()->runno;
                        // $roomrow['name'] = $roomsv[array_search($rid, array_column($roomsv, 'rid'))]['name_ar']
                        if ((string)$base->status == 'checked') {
                            $checked[$rid] = (string)$base->allocationDetails;
                            break;
                        } elseif (isset($roomsv[$rid]) && (string)$base->status == 'checked') {
                            $unchecked[$rid] = false;
                        }
                    }
                }
            }
            if (!empty($checked)) {
                $checked = !empty($unchecked)?array_merge($checked, $unchecked):$checked;
                return ['status' => true, 'newAllocations' => $checked];
            } else {
                $this->lotsLogs("noChecks_blockroom_req", $xml->asXML());
                $this->lotsLogs("noChecks_blockroom_resp", $response->asXML());
                return ['status' => false];
            }
        } else {
            // print_r($response);
            $this->lotsLogs("failed_blockrooms_req", $xml->asXML());
            $this->lotsLogs("failed_blockrooms_resp", $response->asXML());
            log_message('debug', "blocking room: {$rv['rtype']}_{$rv['ratebase']} error: " . $response->request->error->details . ' response :' . $response->request->error->postXml);
        }
    }


    //blocking rooms for booking one room at a time
    public function blockAvailabeRooms($hotelid, $fromDate, $toDate, $rv)
    {
        $xml = $this->makexml('getrooms', 'hotel');
        $bookingDetials = $xml->request->addChild('bookingDetails');
        $bookingDetials->addChild('fromDate', $fromDate);
        $bookingDetials->addChild('toDate', $toDate);
        $bookingDetials->addChild('currency', $this->currency);
        $rooms = $bookingDetials->addChild('rooms');
        $rooms->addAttribute('no', 1);
        $room = $rooms->addChild('room');
        $room->addAttribute('runno', 0);
        $room->addChild('adultsCode', $rv['adults']);
        $children = $room->addChild('children', ' ');
        if (!empty($rv['children'])) {
            $ages = explode(',', $rv['children']);
            $children->addAttribute('no', count($ages));
            foreach ($ages as $key => $age) {
                $child = $children->addChild('child', $age);
                $child->addAttribute('runno', $key);
            }
        } else {
            $children->addAttribute('no', 0);
        }
        $room->addChild('rateBasis', 1);
        $room->addChild('passengerNationality', $rv['nationality']);
        $room->addChild('passengerCountryOfResidence', $rv['country']);
        $roomTypeSelected = $room->addChild('roomTypeSelected');
        $roomTypeSelected->addChild('code', $rv['rtype']);
        $roomTypeSelected->addChild('selectedRateBasis', $rv['ratebase']);
        $roomTypeSelected->addChild('allocationDetails', $rv['allocation']);
        $bookingDetials->addChild('productId', $hotelid);
        // $bookingDetials->addChild('roomModified', $rv['runno']); // to detect which room out of total sent to block in the same request [only if sending multi rooms]
        // print_r($xml->asXML());
        // exit;
        $response = $this->makeRequest($xml->asXML());
        $roomBlocked = false;
        $roomAllocation = '';
        if ((bool)$response->successful) {
            $hotelrooms = $response->hotel->rooms->room;
            // print_r($hotelrooms);
            // exit();
            $this->lotsLogs("{$rv['rtype']}_{$rv['ratebase']}_blockroom_req", $xml->asXML());
            $this->lotsLogs("{$rv['rtype']}_{$rv['ratebase']}_blockroom_resp", $response->asXML());
            $noChecks = false;
            $noChecksData = '';
            foreach ($hotelrooms as $room) {
                foreach ($room->roomType as $roomType) {
                    $roomrow['roomtypecode'] = (int) $roomType->attributes()->roomtypecode;
                    foreach ($roomType->rateBases->rateBasis as $base) {
                        $row['baseid'] = (int)$base->attributes()->id;
                        $row['allocationDetails'] = (string)$base->allocationDetails;
                        $row['status'] = (string)$base->status;
                        if ((int)$base->attributes()->id == $rv['ratebase'] && (int) $roomType->attributes()->roomtypecode == $rv['rtype'] && (string)$base->status == 'checked') {
                            $roomBlocked = true;
                            $roomAllocation = (string)$base->allocationDetails;
                            $noChecks = false;
                            break;
                        } else {
                            $noChecks = true;
                            $noChecksData = "{$rv['rtype']}_{$rv['ratebase']}";
                        }
                    }
                }
            }
            if ($noChecks) {
                $this->lotsLogs("noChecks_{$noChecksData}_blockroom_req", $xml->asXML());
                $this->lotsLogs("noChecks_{$noChecksData}_blockroom_resp", $response->asXML());
            }
        } else {
            // print_r($response);
            $this->lotsLogs("failed_{$rv['rtype']}_{$rv['ratebase']}_blockroom_req", $xml->asXML());
            $this->lotsLogs("failed_{$rv['rtype']}_{$rv['ratebase']}_blockroom_resp", $response->asXML());
            log_message('debug', "blocking room: {$rv['rtype']}_{$rv['ratebase']} error: " . $response->request->error->details . ' response :' . $response->request->error->postXml);
        }
        return ['status' => $roomBlocked, 'newAllocation' => $roomAllocation];
    }

    public function confirmBooking($booking)
    {
        // var_dump($this->country);exit;
        $xml = $this->makexml('confirmbooking', 'hotel');
        $bookingDetials = $xml->request->addChild('bookingDetails');
        $bookingDetials->addChild('fromDate', $booking->CheckInDate);
        $bookingDetials->addChild('toDate', $booking->CheckOutDate);
        $bookingDetials->addChild('currency', $this->currency);
        $bookingDetials->addChild('productId', $booking->hotelid);
        $bookingDetials->addChild('customerReference', $booking->reservation_ref);
        $rooms = $bookingDetials->addChild('rooms');
        $rooms->addAttribute('no', count($booking->rooms));
        foreach ($booking->rooms as $key => $room) {
            $changed = false;
            $extraBeds = 0;
            $changedOcc = [];
            $xroom = $rooms->addChild('room');
            $xroom->addAttribute('runno', $key);
            $xroom->addChild('roomTypeCode', $room['roomid']);
            $xroom->addChild('selectedRateBasis', $room['ratebase']);
            $xroom->addChild('allocationDetails', $room['allocationDetails']);
            //changed occupancy
            if ($room['changedOccupancy'] != null && !empty($room['changedOccupancy'])) {
                $changed = true;
                $changedOcc = explode(',', $room['changedOccupancy']);
                $extraBeds = explode('_', $changedOcc[3])[2];
                $xroom->addChild('adultsCode', $changedOcc[0]);
            } else {
                $xroom->addChild('adultsCode', ($room['adults'] ?? 1));
            }
            $xroom->addChild('actualAdults', ($room['adults'] ?? 1));

            $children = $xroom->addChild('children', ' ');
            $children->addAttribute('no', 0);

            $bookingChildren = [];
            if ($changed) {
                $children->addAttribute('no', $changedOcc[1]);
                if (!empty($changedOcc[2])) {
                    foreach (explode('_', $changedOcc[2]) as $key => $age) {
                        $child = $children->addChild('child', $age);
                        $child->addAttribute('runno', $key);
                    }
                }
            } else {
                // $children->addAttribute('no', $changedOcc[1]);
                if ($room['children']) {
                    $bookingChildren = explode(',', $room['children']);
                    // $bookingChildren = !empty($bookingChildren)? $bookingChildren:0;
                    // $children->addAttribute('no', count($bookingChildren)??0);
                    $children->attributes()->no = count($bookingChildren) ?? 0;
                    foreach ($bookingChildren as $key => $age) {
                        $child = $children->addChild('child', $age);
                        $child->addAttribute('runno', $key);
                    }
                }
            }


            $actualchildren = $xroom->addChild('actualChildren', ' ');
            $actualchildren->addAttribute('no', 0);
            if ($room['children']) {
                $bookingChildren = explode(',', $room['children']);
                $actualchildren->attributes()->no = count($bookingChildren);
                foreach ($bookingChildren as $key => $age) {
                    $actualChild = $actualchildren->addChild('actualChild', $age);
                    $actualChild->addAttribute('runno', $key);
                }
            }

            $xroom->addChild('extraBed', $extraBeds); //should be from booking details
            $xroom->addChild('passengerNationality', $room['nationality']);
            $xroom->addChild('passengerCountryOfResidence', $room['country']);
            $passengersDetails = $xroom->addChild('passengersDetails', ' ');
            $allPassengarsCount = intval(($room['adults'] ?? 1)) + count($bookingChildren);
            // $passengersDetails->addAttribute('no', $allPassengarsCount);
            $leading = true;
            for ($i = 0; $i < $allPassengarsCount; $i++) {
                $passenger = $passengersDetails->addChild('passenger', ' ');
                if ($leading) {
                    $passenger->addAttribute('leading', 'yes'); //should be dynamic
                    $leading = false;
                }
                // $passenger->addAttribute('runno', $i); //should be dynamic
                //translating names into en
                $room['guest_name'] = trans($room['guest_name'], 'ar', 'en')??$room['guest_name'];
                $guestname = explode(' ', $room['guest_name']);
                $passenger->addChild('salutation', 147); //dynamic
                $passenger->addChild('firstName', $guestname[0]); //dynamic
                $passenger->addChild('lastName', $guestname[1] ?? $guestname[0]); //dynamic
            }
            if (!empty($room['specialreqs'])) {
                $reqs = explode(',', $room['specialreqs']);
                $specialRequests = $xroom->addChild('specialRequests', ' ');
                $specialRequests->addAttribute('count', count($reqs));
                foreach ($reqs as $idx => $rq) {
                    $req = $specialRequests->addChild('req', $rq);
                    $req->addAttribute('runno', $idx);
                }
            } else {
                $specialRequests = $xroom->addChild('specialRequests', ' ');
                $specialRequests->addAttribute('count', 0);
            }
            $xroom->addChild('beddingPreference', $room['bedpref']);
        }

        // print_r($xml->asXML());
        // exit();

        $response = $this->makeRequest($xml->asXML());
        if (!(bool)$response->successful) {
            // echo (string)$response->request->error->postXml;
            // print_r($response);
            // exit();
            $this->lotsLogs($booking->reservation_ref . '_failed_book_req', $xml->asXML());
            $this->lotsLogs($booking->reservation_ref . '_failed_book_resp', $response->asXML());
            return [
                "result" => false,
                "error" => "Booking From Provider Failed <br> Code: " . (string)$response->request->error->code . "<br> details:  " . (string)$response->request->error->details,
                "debug" => $response,
                "req" => (string)$response->request->error->postXml,
                "resID" => $booking->ID,
            ];
        } else {
            //logs for certifing
            //$booking->reservation_ref
            //making request file
            $this->lotsLogs($booking->reservation_ref . '_book_req', $xml->asXML());
            $this->lotsLogs($booking->reservation_ref . '_book_resp', $response->asXML());
            return [
                "result" => true,
                "resID" => $booking->ID,
                "details" => $response,
            ];
        }
    }

    public function amendBooking($booking)
    {
        $confirmCancellation = 'no';
        $xml = $this->makexml('updatebooking', 'hotel');
        $bookingDetials = $xml->request->addChild('bookingDetials');
        $bookingDetials->addChild('bookingType', 'dynamic');
        $bookingDetials->addChild('bookingCode', ' bookingCode dynamic');
        $bookingDetials->addChild('confirm', $confirmCancellation); //dynamic to be conditioned
        //if confirm is yes
        if ($confirmCancellation == 'yes') {
            $testPricesAndAllocation = $bookingDetials->addChild('testPricesAndAllocation');
            $service = $testPricesAndAllocation->addChild('service');
            $service->addAttribute('referencenumber', 'ref num'); //dynamic
            $service->addChild('penaltyApplied', 'penaltyValue'); //dynamic
        }


        $response = $this->makeRequest($xml->asXML());

        // if (!(bool)$response->successful) {
        //     return [
        //         "result" => false,
        //         "error" => "Booking From Provider Failed <br> Code: " . (string)$response->request->error->code . "<br> details:  " . (string)$response->request->error->details,
        //         "debug" => $response,
        //         "req" => (string)$response->request->error->postXml,
        //         "resID" => $booking->ID,
        //     ];
        // } else {
        //     return [
        //         "result" => true,
        //         "resID" => $booking->ID,
        //         "details" => $response,
        //     ];
        // }
    }


    public function cancelBooking($booking, $confirm = 'no', $penalty = [])
    {
        $xml = $this->makexml('cancelbooking');
        $bookingDetials = $xml->request->addChild('bookingDetails');
        $bookingDetials->addChild('bookingType', ($booking->bookingStatus == '2' ? 1 : $booking->bookingStatus));
        $bookingDetials->addChild('bookingCode', $booking->bookingCode);
        $bookingDetials->addChild('confirm', $confirm);
        if ($confirm == 'yes') {
            $testPricesAndAllocation = $bookingDetials->addChild('testPricesAndAllocation');
            $service = $testPricesAndAllocation->addChild('service');
            $service->addAttribute('referencenumber', $penalty['servicecode']); //dynamic
            $service->addChild('penaltyApplied', $penalty['charge']); //dynamic
        }

        $response = $this->makeRequest($xml->asXML());

        if ((bool)$response->successful) {
            //for logging perpose only
            if ($confirm == 'yes') {
                $this->lotsLogs($booking->bookingCode . '_cancelbooking_step2_req', $xml->asXML());
                $this->lotsLogs($booking->bookingCode . '_cancelbooking_step2_resp', $response->asXML());
            } else {
                $this->lotsLogs($booking->bookingCode . '_cancelbooking_step1_req', $xml->asXML());
                $this->lotsLogs($booking->bookingCode . '_cancelbooking_step1_resp', $response->asXML());
            }
            return [
                "result" => true,
                "data" => $response,
            ];
        } else {
            return [
                "result" => false,
                "error" => "Cancel Booking From Provider Failed <br> Code: " . (string)$response->request->error->code . "<br> details:  " . (string)$response->request->error->details,
                "debug" => $response,
                "req" => (string)$response->request->error->postXml,
            ];
        }
    }


    public function makexml($command, $product = '')
    {
        /**
         * make lots xml string template
         * @param string $command
         * @param string $hotel
         * @return SimpleXMLElement $xml
         **/

        $xml = '<?xml version="1.0" encoding="UTF-8"?><customer><username>medhat85</username><password>46CC325148DC9A6A4B4CE977EB8D601F</password><id>1742725</id><source>1</source>'
            . (!empty($product) ? "<product>$product</product>" : '') . '<request command="cancelbooking"></request></customer>';
        $xml = new SimpleXMLElement($xml);
        $xml->request->attributes()->command = $command;
        return $xml;
    }

    public function makeRequest($requestBody)
    {
        $url = 'https://us.dotwconnect.com/gatewayV4.dotw';
        // $url = 'https://us.dotwconnect.com/gatewayV4.dotw';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=UTF-8', 'Connection: close', 'Compression: Gzip', 'Accept-Enconding: gzip, deflate'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }
        curl_close($curl);
        // print_r($result);
        return simplexml_load_string($result);
    }


    public function generateRoomTable($rooms = null, $nights = 2)
    {
        //New Edits to restrict the selection to one room only for each rate base
        $rows = '';
        foreach ($rooms as $room) {

            $row = '';
            foreach ($room['rateBasis'] as $ratebase) {
                $row .= "<div class='row my-2 py-2 roomrow'> 
                <div class='col'><div class='row'>";
                $row .= '<p class="col-sm-12 col-md roomdetails"><span class="room-name">' . "{$room['name']}  </span>(<small class='text-center'>{$ratebase['meal']}</small>) <a class='clink' data-toggle='collapse' href='#d{$ratebase['rid']}' role='button' aria-expanded='false' aria-controls='d{$ratebase['rid']}'>
                    <i class='fas fa-exclamation-circle fa-lg'> </i>
                </a> <br> {$ratebase['lookedForText']}</p>";
                $row .= " <span class='col-sm-12 col-md text-center'>{$ratebase['totalformated']}  <br> <span class='mcolor'>" . (($nights > 1) ? $nights . ' ' . lang('nights') : $nights . ' ' . lang('night')) . '</span></span> '; //<br> ( + '. lang('taxesfees').' '.$ratebase['taxesfees'].')</span>';
                //options part
                $row .= " <span class='col-sm-12 col-md text-center'><small class='text-center'> {$ratebase['cancellation']} </small><br><small>";
                $policies = $this->cancelationRules($ratebase['cancellationRules']['rule']);
                $row .= "</small>
                <div class='etooltip' @click='ttip'>
                    <i class='fa fa-calendar " . (empty($ratebase['minStay']) ? 'dis-icon' : '') . "'></i>
                     <small id='tts' class='etooltiptext' @focus=\"centerttip()\"> Minimum stay requirements {$ratebase['minStay']}  <br> {$ratebase['dateApplyMinStay']} {{centerttip(this)}}</small>
                </div>
                <div class='etooltip' @click='ttip'>
                    <i class='fas fa-exclamation-circle'> </i>
                    <small class='etooltiptext'> {$policies} </small>
                </div>
                <div class='etooltip' @click='ttip'>
                    <i class='far fa-comment-alt' > </i>
                    <small class='etooltiptext'> " . lang('tariffrate') . " </small>
                </div>
                </span>";

                $row .= "<div class='col-sm-12 col-md text-center p-2'><button id='block{$ratebase['rid']}' v-if='ready' :key='{$ratebase['rid']}' :class=\"(selected.r{$ratebase['rid']}?'removebtn':'addbtn')+' align-self-center'\" data-rid='{$ratebase['rid']}' data-price='{$ratebase['prices']}' data-texesfees='{$ratebase['taxesfees']}' data-allodetails='{$ratebase['allocationDetails']}' data-rname='{$room['name']}'  data-ratebase='{$ratebase['baseid']}' data-rtype='{$room['roomtypecode']}' data-cancelline='{$ratebase['cancellation']}' data-cancelref='{$policies}'  @click.prevent.stop='price_calc' v-html='buttonToggle(selected.r{$ratebase['rid']})'></button></div>
                </div><!--row -->
                <div class='row'>
                <div class='col'>
                <div class='collapse' id='d{$ratebase['rid']}'>
                <div class='card card-body'>
                    <h6>{{dataParams.trkeys.roomOcc}}:</h6> 
                    <ul class='list-inline border p-2'>
                        <li class='list-inline-item'> {{dataParams.trkeys.mxadults}}: <span class='mcolor'>{$room['sleeps']['maxAdult']} </span></li>
                        <li class='list-inline-item'> {{dataParams.trkeys.mxchilds}}: <span class='mcolor'>{$room['sleeps']['maxChildren']}</span> </li>
                        <li class='list-inline-item'> {{dataParams.trkeys.mxbeds}}: <span class='mcolor'>{$room['sleeps']['maxExtraBed']} </span></li>
                    </ul>" . ($ratebase['withinCancellationDeadline'] ? "<p>within Cancellation Deadline: <span class='mcolor'>Yes</span></p>" : '') . (($ratebase['leftToSell'] < 10) ? "<p>Left to sell: <span class='mcolor'>{$ratebase['leftToSell']}</span></p>" : '') .
                    '</div>
                </div>
                </div>
                </div><!--row-->
                </div>
                </div>';
                // $row .= "<p> validForOccupancy " . print_r($ratebase['validForOccupancy'], true) . "</p>";
                // $row .= "<p> changedOccupancy " . print_r($ratebase['changedOccupancy1'], true) . "</p>";
                // <p> Specials " . print_r((array)$room['specials'], true) . "</p>
                // <p> extrameals " . print_r((array)$room['extraMeals'], true) . "</p>";
            }
            $rows .= $row;
        }
        return $rows;
    }

    public function cancelationRules($rules)
    {
        /**
         *  Generates a well formated cancellation and amendments rules 
         * 
         * @param array $rules
         * @return string $formatedrules
         * 
         */

        $policies = '';
        foreach ($rules as $key => $rule) {
            $ruleDetails = '<div class="cancel-rule">';
            // $ruleDetails = '<div class="cancel-rule"> Rule ' . ($key + 1) . '<br>';
            if (isset($rule->fromDateDetails) && isset($rule->toDateDetails)) {
                $ruleDetails .= '<p>' . lang('from') . ': ' . $rule->fromDateDetails . ' - ' . lang('to') . ': ' . $rule->toDateDetails . '</p>';
                $ruleDetails .= '<p>' . ($rule->amendCharge ? lang('amendCharge') . ':' . calcFines((float)$rule->amendCharge) : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->cancelCharge ? lang('cancelCharge') . ':' . calcFines((float)$rule->cancelCharge) : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->amendRestricted ? lang('amendRestricted')  : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->cancelRestricted ? lang('cancelRestricted') : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->noShowPolicy ? lang('noShowPolicy') . ' ' . lang('charges') . calcFines((float)$rule->charge) : ' ') . '</p>';
            } elseif (isset($rule->fromDateDetails) && !isset($rule->toDateDetails)) {
                $ruleDetails .= '<p>' . lang('from') . ': ' . $rule->fromDateDetails . '</p>';
                $ruleDetails .= '<p>' . ($rule->amendCharge ? lang('amendCharge') . ':' . calcFines((float)$rule->amendCharge) : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->cancelCharge ? lang('cancelCharge') . ':' . calcFines((float)$rule->cancelCharge) : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->amendRestricted ? lang('amendRestricted')  : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->cancelRestricted ? lang('cancelRestricted') : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->noShowPolicy ? lang('noShowPolicy') . ' ' . lang('charges') . calcFines((float)$rule->charge) : ' ') . '</p>';
            } elseif (!isset($rule->fromDateDetails) && isset($rule->toDateDetails)) {
                $ruleDetails .= '<p>' . lang('to') . ': ' . $rule->toDateDetails . '</p>';
                $ruleDetails .= '<p>' . ($rule->amendCharge ? lang('amendCharge') . ':' . calcFines((float)$rule->amendCharge) : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->cancelCharge ? lang('cancelCharge') . ':' . calcFines((float)$rule->cancelCharge) : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->amendRestricted ? lang('amendRestricted')  : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->cancelRestricted ? lang('cancelRestricted') : ' ') . '</p>';
                $ruleDetails .= '<p>' . ($rule->noShowPolicy ? lang('noShowPolicy') . ' ' . lang('charges') . calcFines((float)$rule->charge) : ' ') . '</p>';
            }
            $ruleDetails .= '</div>';
            $policies .= $ruleDetails;
        }
        return $policies;
    }


    public function lotsLogs($name, $txt)
    {
        $myfile = fopen(APPPATH . "filedb/xml/$name.xml", "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
    }
}
