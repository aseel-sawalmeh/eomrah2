<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');
/*
 * This Library to add some tools customized for The work
 * Made to extend the core as Version one additional toolset
 * PHP Version 7.3
 * Auther Gebriel Alkhayal
 * magiMGan Version 1.0
 */
class Magician
{
    private $MG;
    public $av = [];
    public $rec_result;
    public $ResHeads = [];
    private $Puser_id;
    private $ResRef;
    private $adultcount;
    private $av_periods;
    public $sorted_res;
    public $hotel_props;

    public function __construct()
    {
        $this->MG = &get_instance();
        $this->MG->load->model('searchs/Sengine');
        $this->MG->load->model('Auto_model', 'p');
        $this->makeResRef();
    }

    public function setPuserId($puserid)
    {
        $this->Puser_id = $puserid;
    }

    private function makeResRef()
    {
        $date = new DateTime();
        $date->setTimestamp(now());
        $time_based_name = $date->format('YmdHis');
        $this->ResRef = $time_based_name . rand(1, 9);
    }

    public function do_magic($hotel_id, $startdate, $enddate, $adultcount)
    {
        $this->adultcount = $adultcount;
        $hotel_providers = $this->MG->Sengine->allhotel_providers($hotel_id);
        foreach ($hotel_providers as $hotel_provider) {
            $providerperiod = $this->MG->Sengine->getperiods($hotel_provider->Provider_ID);
            $available_set = false;
            if ($providerperiod) {
                $available_set = checkAvailabilityU($startdate, $enddate, $providerperiod);
            }
            //show_error(var_dump($available_set));
            if ($available_set) {
                if (isset($available_set['mixed'])) {
                    $this->subMixpro($available_set['mixed'], $hotel_provider->Provider_ID, $startdate, $enddate);
                    unset($available_set['mixed']);
                }
                $this->subpro($available_set, $hotel_provider->Provider_ID, $startdate, $enddate);
            }
        }
        $this->rec_result = $this->getrooms($this->av, $adultcount);
        $this->sorted_res = $this->filter_results();
        $this->h_props();
    }

    private function subpro($available_set = [], $providerId, $startdate, $enddate)
    {
        foreach ($available_set as $available_period) {
            $this->av_periods[] = $available_period['period_id'];
            $resHead = array(
                'Provider_ID' => $available_period['provider_id'],
                'ResRef' => $this->ResRef,
                'PuserID' => $this->Puser_id,
                'checkIn' => $available_period['available_dates'][0],
                'checkOut' => $available_period['available_dates'][count($available_period['available_dates']) - 1],
                'NoOfNights' => count($available_period['available_dates']) - 1,
            );
            $this->ResHeads = $resHead;
            $found_meal_type_room = $this->MG->Sengine->av_mealed_rooms($available_period['period_id']);
            $av_room_suplements = $this->MG->Sengine->av_room_suplement($available_period['period_id']);
            $av_room_daysuplement = $this->MG->Sengine->av_room_daysuplement($available_period['period_id'])[0];
            $room_type_occs = $this->MG->Sengine->room_type_maxocc($available_period['period_id']);
            $room_availabality = RoomAvailability($startdate, $enddate, $this->MG->Sengine->room_availabality($providerId));

            if (!$room_availabality) {
                show_error("nnnnnnnnnnnn2");
                continue;
            }
            $days = $available_period['available_dates'];
            $types = ['SGL', 'DBL', 'TRPL', 'Quad', 'Queen', 'KNG', 'TWN', 'HtwN', 'DBLTWN', 'STD', 'Suite'];
            foreach ($found_meal_type_room as $mtroomprice) {
                foreach ($types as $type) {
                    if (!$room_type_occs) {
                        show_error("nnnnnnnnnnnnn");
                        continue;
                    }
                    $res = $this->domagic($available_period['provider_id'], $available_period['period_id'], $type, $mtroomprice->{$type}, $mtroomprice->Meal_Name, $mtroomprice->Meal_Sn, $av_room_daysuplement, $av_room_suplements, $room_availabality, $days, $suppbase = 0, $room_type_occs);
                    if ($res !== null) {

                        if (isset($available_period['fav'])) {
                            $res['fav'] = $available_period['fav'];
                        }
                        $this->av[] = $res;
                    }
                }
            }
        }
    }

    private function subMixpro($available_set = [], $providerId, $startdate, $enddate)
    {

        $mixture = [];

        $n = 1;
        foreach ($available_set as $available_period) {
            $mixture['part' . $n] = [];
            $this->av_periods[] = $available_period['period_id'];
            $resHead = array(
                'Provider_ID' => $available_period['provider_id'],
                'PeriodID' => $available_period['period_id'],
                'ResRef' => $this->ResRef,
                'PuserID' => $this->Puser_id,
                'checkIn' => array_shift($available_period['available_dates']),
                'checkOut' => $available_period['available_dates'][count($available_period['available_dates']) - 1],
                'NoOfNights' => count($available_period['available_dates']) - 1,
            );
            $this->ResHeads = $resHead;
            $found_meal_type_room = $this->MG->Sengine->av_mealed_rooms($available_period['period_id']);
            $av_room_suplements = $this->MG->Sengine->av_room_suplement($available_period['period_id']);
            $av_room_daysuplement = $this->MG->Sengine->av_room_daysuplement($available_period['period_id'])[0];
            $room_type_occs = $this->MG->Sengine->room_type_maxocc($available_period['period_id']);
            $room_availabality = RoomAvailability($startdate, $enddate, $this->MG->Sengine->room_availabality($providerId));
            if (!$room_availabality) {
                continue;
            }

            $days = $available_period['available_dates'];
            $types = ['SGL', 'DBL', 'TRPL', 'Quad', 'Queen', 'KNG', 'TWN', 'HtwN', 'DBLTWN', 'STD', 'Suite'];

            foreach ($found_meal_type_room as $mtroomprice) {
                foreach ($types as $type) {
                    if (!$room_type_occs) {
                        continue;
                    }
                    $res = $this->domagic($available_period['provider_id'], $available_period['period_id'], $type, $mtroomprice->{$type}, $mtroomprice->Meal_Name, $mtroomprice->Meal_Sn, $av_room_daysuplement, $av_room_suplements, $room_availabality, $days, $suppbase = 0, $room_type_occs);
                    if ($res !== null) {
                        $mixture['part' . $n][] = $res;
                    }
                }
            }
            $n++;
        }
        if (!empty($mixture)) {
            for ($i = 0; $i < count($mixture['part1']); $i++) {
                $container = $mixture['part1'][$i];
                if ($mixture['part1'][$i]['av_count'] > $mixture['part2'][$i]['av_count']) {
                    $container['av_count'] = $mixture['part2'][$i]['av_count'];
                } else {
                    $container['av_count'] = $mixture['part1'][$i]['av_count'];
                }
                if ($mixture['part1'][$i]['price_per_night'] > $mixture['part2'][$i]['price_per_night']) {
                    $container['price_per_night'] = $mixture['part1'][$i]['price_per_night'];
                } else {
                    $container['price_per_night'] = $mixture['part2'][$i]['price_per_night'];
                }
                $this->av[] = $container;
            }
        }
    }

    private function domagic($providerid, $periodid, $rtype, $rtypemealprice, $mealname, $snmealname, $av_room_daysuplement, $av_room_suplements, $av_room_counts, $days, $suppbase = 0, $room_type_occs)
    {
        if ($mealname == 'RO(Room Only)') {
            $mealname = 'Not Included';
        }
        $av_typed = array('ProviderID' => $providerid, 'PeriodID' => $periodid, 'room_type' => $rtype, 'meal_type' => $mealname, 'snmeal_type' => $snmealname, 'av_count' => 0, 'roomsupp' => array(), 'price_per_night' => 0);
        if ($rtypemealprice > 0) {
            foreach ($av_room_counts as $av_room_count) {
                if ($av_room_count->R_Type_Sn == $rtype) {
                    $av_typed['av_count'] = $av_room_count->Available_Count;
                }
            }
            foreach ($room_type_occs as $room_type_occ) {
                if ($room_type_occ->R_Type_Sn == $rtype) {
                    $av_typed['Adlult_Count'] = $room_type_occ->Adlult_Count;
                    $av_typed['Child_Count'] = $room_type_occ->Child_Count;
                    $av_typed['Infant_Count'] = $room_type_occ->Infant_Count;
                }
            }
            $nights = count($days) - 1;
            $alldaystotalprice = 0;
            for ($i = 0; $i < $nights; $i++) {
                $dayname = date('l', strtotime($days[$i]));
                $daysuppfinal = changeMargin($av_room_daysuplement->{$dayname}, $av_room_daysuplement->{$dayname . 'Price_Type'}, $av_room_daysuplement->{$dayname . "Price_Mop"}, $rtypemealprice);
                $alldaystotalprice += $daysuppfinal;
            }
            $daysbaseprice = ($nights > 0) ? $alldaystotalprice / $nights : $alldaystotalprice;
            $suppbase = $daysbaseprice + $rtypemealprice;
            foreach ($av_room_suplements as $av_r_suplement) {
                if ($av_r_suplement->{$rtype} > 0) {
                    $supplSum = changeMargin($av_r_suplement->R_Supp_Price, $av_r_suplement->SupplPrice_Type, $av_r_suplement->SupplPrice_Mop, $daysbaseprice);
                    $suppbase += $supplSum;
                    $av_typed['roomsupp'][] = $av_r_suplement->R_SuppType_Name;
                }
            }
            $av_typed['price_per_night'] = curcal($suppbase);
            $av_typed['currency'] = lang(usercur());

            $av_typed['nights_count'] = $nights;
            $av_typed['all_nights_price'] = curcal(($suppbase * $nights));
            return $av_typed;
        }
    }

    private function filter_results()
    {
        $c_res = [];
        if ($this->av_periods != null) {
            foreach ($this->av_periods as $pr) {
                for ($i = 0; $i < count($this->rec_result); $i++) {
                    if ($this->rec_result[$i]['PeriodID'] == $pr) {
                        $c_res[$pr][] = $this->rec_result[$i];
                    }
                }
            }
            $res_set = [];
            function set_ref($a = [])
            {
                $a_all = 0;
                $a_res_count = $a['res_count'] ? $a['res_count'] : 1;
                if (isset($a['ref'])) {
                    $a_res_count_ref = $a['ref']['res_count'] ? $a['ref']['res_count'] : 1;
                    $a_all = ($a['price_per_night'] * $a['nights_count'] * $a_res_count) + ($a['ref']['price_per_night'] * $a['ref']['nights_count'] * $a_res_count_ref);
                } else {
                    $a_all = ($a['price_per_night'] * $a['nights_count'] * $a_res_count);
                }
                return $a_all;
            }
            function price_sort($aa, $b)
            {
                if (set_ref($aa) == set_ref($b)) {
                    return 0;
                }
                return (set_ref($aa) < set_ref($b)) ? -1 : 1;
            }
            foreach ($c_res as $ix) {
                usort($ix, "price_sort");
                $res_set[] = array_shift($ix);
            }
            usort($res_set, "price_sort");
            return $res_set;
        } else {
            return;
        }
    }

    private function getrooms($arrs = [], $wanted)
    {

        $match = [];
        $count = count($arrs);
        $deff = $wanted % 3;
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $hotel = $this->MG->p->get_hotels($arrs[$i]['ProviderID']);
                $hotel->Hotel_Name = tolang($hotel->Hotel_ID, 'hotelname') ? tolang($hotel->Hotel_ID, 'hotelname') : $hotel->Hotel_Name;
                $hotel->Hotel_Description = tolang($hotel->Hotel_ID, 'hoteldesc') ? tolang($hotel->Hotel_ID, 'hoteldesc') : $hotel->Hotel_Description;

                $hotel_geo = $this->MG->p->get_hotel_geo($hotel->Hotel_ID);
                if ($hotel_geo) {
                    $hotel->geo = $hotel_geo;
                } else {
                    $hotel->geo = [];
                }
                if ($arrs[$i]['Adlult_Count'] == $wanted) {
                    $arrs[$i]['res_count'] = 1;
                    if ($hotel) {
                        $arrs[$i]['hotel'] = $hotel;
                    }
                    $match[] = $arrs[$i];
                } elseif ($arrs[$i]['Adlult_Count'] == $wanted / 2) {
                    $arrs[$i]['res_count'] = 2;
                    if ($hotel) {
                        $arrs[$i]['hotel'] = $hotel;
                    }
                    $match[] = $arrs[$i];
                } elseif ($arrs[$i]['Adlult_Count'] == $wanted / 3 && $deff = 0) {
                    $arrs[$i]['res_count'] = 3;
                    if ($hotel) {
                        $arrs[$i]['hotel'] = $hotel;
                    }
                    $match[] = $arrs[$i];
                } elseif ($deff > 0 && $arrs[$i]['Adlult_Count'] == $deff) {
                    $arrs[$i]['res_count'] = 3;
                    if ($hotel) {
                        $arrs[$i]['hotel'] = $hotel;
                    }
                    $match[] = $arrs[$i];
                } elseif ($arrs[$i]['Adlult_Count'] == $wanted / 4) {
                    $arrs[$i]['res_count'] = 4;
                    if ($hotel) {
                        $arrs[$i]['hotel'] = $hotel;
                    }
                    $match[] = $arrs[$i];
                } elseif ($i < $count - 1 && ($arrs[$i]['Adlult_Count'] + $arrs[$i + 1]['Adlult_Count']) == $wanted && ($arrs[$i]['ProviderID'] = $arrs[$i + 1]['ProviderID'])) {
                    $arrs[$i]['ref'] = $arrs[$i + 1];
                    $arrs[$i]['ref']['res_count'] = 1;
                    $arrs[$i]['res_count'] = 1;
                    if ($hotel) {
                        $arrs[$i]['hotel'] = $hotel;
                    }
                    $match[] = $arrs[$i];
                }
            }
        }
        return $match;
    }

    private function h_props()
    {
        $classified = [];
        if (!empty($this->av)) {
            foreach ($this->av as $cl) {
                $classified['roomtypes'][] = $cl['room_type'];
                $classified[$cl['room_type']][] = $cl;
            }
            $classified['roomtypes'] = array_unique($classified['roomtypes']);
            $this->hotel_props = $classified;
        }
    }

}
