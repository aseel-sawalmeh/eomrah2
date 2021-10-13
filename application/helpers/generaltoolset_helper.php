<?php defined('BASEPATH') or exit('No direct script access allowed');


if (!function_exists('roomoccmaxed')) {
    function roomoccmaxed($room_id)
    {
        $CI = get_instance();
        $CI->load->model('Hotel_model');
        return $CI->Hotel_model->roomfullfilled($room_id);
        if ($CI->Hotel_model->roomfullfilled($room_id)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('changeMargin')) {

    //Determine the math operation and Calculate the effect on the total
    function changeMargin($baseFactorAmount = 0, $factor_type, $factor_mop, $targetTotalAmount = '')
    {
        if ($baseFactorAmount !== 0 && !empty($baseFactorAmount) && $baseFactorAmount !== null && $baseFactorAmount !== '') {
            if ($factor_type == '#') {
                return intval($factor_mop . $baseFactorAmount);
            } elseif ($factor_type == '%') {
                $calculated_value = ($factor_mop . (($baseFactorAmount / 100) * $targetTotalAmount));
                return $calculated_value;
            }
        } else {
            return;
        }
    }
}

if (!function_exists('show_timed_error')) {
    function show_timed_error($message, $url = '/', $seconds = 5, $title = null, $status_code = null)
    {
        $url = base_url($url);
        if ($title == null) {
            $err_title = "Bad request <span onload=''></span>";
        } else {
            $err_title = $title;
        }
        if ($status_code !== null) {
            $code = $status_code;
        } else {
            $code = 500;
        }
        $time = $seconds * 1000;
        $message .= ", you will be redirected to home page after {$seconds}, seconds ";
        $err_body = "<script>setTimeout(function () { window.location.href= '$url';}, $time); </script>";
        show_error($message . $err_body, $code, $err_title);
    }
}

if (!function_exists('datediff')) {
    function datediff($sdate1, $sdate2)
    {
        $date1 = date_create($sdate1);
        $date2 = date_create($sdate2);
        $diff = date_diff($date1, $date2);
        return (int) $diff->format("%R%a days");
    }
}

if (!function_exists('qrCode')) {
    function qrCode($lat, $lng)
    {
        // //https://www.google.com/maps/search/?api=1&query=$lat,$lng
        // $curl = curl_init();

        // curl_setopt_array($curl, [
        //     CURLOPT_URL => "https://qrcode-monkey.p.rapidapi.com/qr/custom?data=https://www.google.com/maps/search/?api=1&query=$lat,$lng&size=600&file=png&config=%7B%22bodyColor%22%3A%20%22%230277BD%22%2C%20%22body%22%3A%22mosaic%22%7D",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_HTTPHEADER => [
        //         "x-rapidapi-host: qrcode-monkey.p.rapidapi.com",
        //         "x-rapidapi-key: dcffe9651fmsha9730c88ff47e2fp197e3ajsnf010c91a1b7b"
        //     ],
        // ]);

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);

        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {
        //     echo $response;
        // }
        return base_url('public_designs/assets/img/eomrahqr.png');
    }
}

if (!function_exists('nightsOfDates')) {
    function nightsOfDates($start, $end)
    {
        $startdate = new DateTime($start);
        $enddate = new DateTime($end);
        return $startdate->diff($enddate)->format('%a');
    }
}

if (!function_exists('mxrs')) {
    function mxrs($remainingavs)
    {
        if ($remainingavs > 0) {
            if ($remainingavs < MAXRES) {
                return $remainingavs;
            } else {
                return MAXRES;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('slugify')) {

    function slugify($str, $limit = null)
    {
        if ($limit) {
            $str = mb_substr($str, 0, $limit, "utf-8");
        }
        $text = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
}

if (!function_exists('dateconflict')) {
    function dateconflict($currentsdate, $currentedate, $periodset = [])
    {
        $conflicts = [];
        $conflictsmax = ['conflict' => " <h4> There is a lot of conflicts check your dates again </h4> "];
        $check_dates = date_range($currentsdate, $currentedate);
        foreach ($periodset as $period) {
            $dateset = date_range($period->dateFrom, $period->dateTo);
            if ($check_dates) {
                foreach ($check_dates as $check_date) {
                    if (in_array($check_date, $dateset)) {
                        $conflict = array('conflict' => " date Conflicts at $check_date in the period $period->periodName ");
                        $conflicts[] = $conflict;
                    }
                }
            } else {
                return false;
            }
        }

        if (count($conflicts) > 0 && count($conflicts) < 8) {
            return $conflicts;
        } elseif (count($conflicts) >= 8) {
            return $conflictsmax;
        }
    }
}

if (!function_exists('checkAvailability')) {
    function checkAvailability($currentsdate, $currentedate, $periodset = array())
    {
        $matches = [];
        $available_matches = [];
        $check_dates = date_range($currentsdate, $currentedate);
        foreach ($periodset as $period) {
            $datefrom = date('Y-m-d', strtotime("$period->dateFrom +" . (int) $period->periodRelease . " DAYS"));
            $dateset = date_range($datefrom, $period->dateTo);
            if ($check_dates && $dateset) {
                foreach ($check_dates as $check_date) {
                    if (in_array($check_date, $dateset)) {
                        $index_push = "period_" . $period->hperiodID;
                        if (!in_array($index_push, $matches)) {
                            $matches[$index_push] = array(
                                'provider_id' => $period->providerID,
                                'period_id' => $period->hperiodID,
                                'period Name' => $period->periodName,
                                'available_dates' => array(),
                            );
                            $matches[] = $index_push;
                        }
                        $matches[$index_push]['available_dates'][] = $check_date;
                    }
                }
            } else {
                return false;
            }
        }
        if (count($matches) > 0) {
            $needed = count(date_range($currentsdate, $currentedate));
            if ($matches) {
                foreach ($matches as $match) {
                    if (is_array($match)) {
                        if (count($match['available_dates']) == $needed) {
                            $available_matches[] = $match;
                        }
                    }
                }
                return $available_matches;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('checkAvailabilityU')) {
    function checkAvailabilityU($currentsdate, $currentedate, $periodset = array())
    {
        $matches = [];
        $check_dates = date_range($currentsdate, $currentedate);
        if (!$check_dates) {
            return false;
        }
        //show_error(print_r($check_dates));
        //show_error(print_r($periodset));
        foreach ($periodset as $period) {
            $datefrom = date('Y-m-d', strtotime("$period->dateFrom +" . (int) $period->periodRelease . " DAYS"));
            $dateset = date_range($datefrom, $period->dateTo);
            //show_error(print_r($dateset));
            if ($dateset) {
                $intersect = array_intersect($check_dates, $dateset);
                $diff = array_diff($check_dates, $intersect);
                $indexed = "period_" . $period->hperiodID;
                if (!empty($diff) && !empty($intersect)) {
                    $matches['mixed'][$indexed] = array(
                        'provider_id' => $period->providerID,
                        'period_id' => $period->hperiodID,
                        'period Name' => $period->periodName,
                        'available_dates' => $intersect,
                    );
                } elseif (empty($diff) && !empty($intersect)) {
                    $matches[$indexed] = array(
                        'provider_id' => $period->providerID,
                        'period_id' => $period->hperiodID,
                        'period Name' => $period->periodName,
                        'available_dates' => $intersect,
                    );
                }
            } else {
                return false;
            }
        }
        if (isset($matches['mixed']) && count($matches['mixed']) < 2) {
            $mix = array_shift($matches['mixed']);
            $matches['period_' . $mix['period_id']] = $mix;
            $matches['period_' . $mix['period_id']]['fav'] = array_values($mix['available_dates'])[0];
            $matches['period_' . $mix['period_id']]['available_dates'] = array_values($mix['available_dates']);
            unset($matches['mixed']);
        }
        return $matches;
    }
}

if (!function_exists('discountAvailability')) {
    function discountAvailability($currentsdate, $currentedate, $dicountset = array())
    {
        $matches = array();
        $available_matches = array();
        $check_dates = date_range($currentsdate, $currentedate);
        foreach ($dicountset as $dicount) {
            $dateset = date_range($dicount->StartDate, $dicount->EndDate);
            if ($check_dates && $dateset) {
                foreach ($check_dates as $check_date) {
                    if (in_array($check_date, $dateset)) {
                        $index_push = "discount_" . $dicount->ID;
                        if (!in_array($index_push, $matches)) {
                            $matches[$index_push] = array(
                                'provider_id' => $dicount->providerID,
                                'discount_id' => $dicount->ID,
                                'discount_codes' => $dicount->discountCode,
                                'discount_amount' => $dicount->Price,
                                'period_desc' => $dicount->Description,
                                'available_dates' => array(),
                            );
                            array_push($matches, $index_push);
                        }
                        array_push($matches[$index_push]['available_dates'], $check_date);
                    }
                }
            } else {
                return false;
            }
        }
        if (count($matches) > 0) {
            $needed = count(date_range($currentsdate, $currentedate));
            if ($matches) {
                foreach ($matches as $match) {
                    if (is_array($match)) {
                        if (count($match['available_dates']) == $needed) {
                            array_push($available_matches, $match);
                        }
                    }
                }
                return $available_matches;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('RoomAvailability')) {
    function RoomAvailability($currentsdate, $currentedate, $Roomsets = array())
    {
        $matches = array();
        $available_matches = array();
        $check_dates = date_range($currentsdate, $currentedate);
        foreach ($Roomsets as $Roomset) {
            $dateset = date_range($Roomset->StartDate, $Roomset->EndDate);
            if ($check_dates && $dateset) {
                foreach ($check_dates as $check_date) {
                    if (in_array($check_date, $dateset)) {
                        $matches[$Roomset->ID] = $Roomset;
                    }
                }
            } else {
                return false;
            }
        }
        if (count($matches) > 0) {
            if ($matches) {
                foreach ($matches as $match) {
                    //show_error(var_dump($match));
                    if (!empty($match)) {
                        $available_matches[] = $match;
                    }
                }
                return $available_matches;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('img')) {
    function img($img = null, $type = null)
    {
        if ($img == null || strlen($img) == 0) {
            if ($type == null) {
                return base_url("assets/images/products/noimage.png");
            } else {
                return base_url("assets/images/products/noimage_thumb.png");
            }
        } else {
            $thump = substr_replace($img, "_thumb", stripos($img, '.'), 0);
            if ($type == null) {
                return base_url("assets/images/products/$img");
            } else {
                return base_url("assets/images/products/$thump");
            }
        }
    }
}

if (!function_exists('pimg')) {
    function pimg($img = null, $type = null)
    {
        if ($img == null || strlen($img) == 0) {
            if ($type == null) {
                return base_url("assets/images/products/noimage.png");
            } else {
                return base_url("assets/images/products/noimage_thumb.png");
            }
        } else {
            $thump = substr_replace($img, "_thumb", stripos($img, '.'), 0);
            if ($type == null) {
                return base_url("assets/images/products/$img");
            } else {
                return base_url("assets/images/products/$thump");
            }
        }
    }
}

if (!function_exists('himg')) {
    function himg($img = null, $type = null)
    {
        if ($img == null || strlen($img) == 0) {
            if ($type == null) {
                return base_url("assets/images/hotels/noimage.png");
            } else {
                return base_url("assets/images/hotels/noimage_thumb.png");
            }
        } else {
            if (preg_grep("/^poze_hotel/i", [$img])) return 'https://www.dotwconnect.com/' . $img;

            $thump = substr_replace($img, "_thumb", stripos($img, '.'), 0);
            if ($type == null) {
                return base_url("assets/images/hotels/$img");
            } else {
                return base_url("assets/images/hotels/$thump");
            }
        }
    }
}

if (!function_exists('delimg')) {
    function delimg($img, $folder)
    {
        $file = "./assets/images/$folder/$img";
        if (is_file($file)) {
            if (unlink($file)) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('banner')) {
    function banner($img = null)
    {
        $rand = rand(1, 9);
        if ($img == null) {
            return base_url("assets/banners/$rand.jpeg");
        } else {
            if (file_exists(FCPATH . "/assets/banners/$img.png")) {
                return base_url("assets/banners/$img.png");
            } elseif (file_exists(FCPATH . "/assets/banners/$img.jpeg")) {
                return base_url("assets/banners/$img.jpeg");
            } elseif (file_exists(FCPATH . "/assets/banners/$img.jpg")) {
                return base_url("assets/banners/$img.jpg");
            }
        }
    }
}

if (!function_exists('initcurrency')) {
    function initcurrency()
    {
        if (get_cookie('eomrah_currency') == null) {
            set_cookie('currency', 'SAR', 86400);
        }
    }
}

if (!function_exists('usercur')) {
    function usercur()
    {
        return get_cookie('eomrah_currency') ? get_cookie('eomrah_currency') : "SAR";
    }
}
if (!function_exists('userCurShort')) {
    function userCurShort()
    {
        $cur = get_cookie('eomrah_currency') ? get_cookie('eomrah_currency') : "SAR";
        if (userlang() == 'ar') {
            return usercurLots('short_ar');
        } else {
            return usercurLots('short');
        }
    }
}

if (!function_exists('vistorCountry')) {
    function vistorCountry()
    {
        try {
            $ip = $_SERVER['REMOTE_ADDR'];
            $data = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            return ($data->geoplugin_countryName ?? 'Saudi Arabia');
        } catch (Exception $error) {
            log_message('debug', 'geo get visitor country' . $error);
            return 'Saudi Arabia';
        }
    }
}

if (!function_exists('usercurLots')) {
    function usercurLots($field = 'code')
    {
        $cur = get_cookie('eomrah_currency') ? get_cookie('eomrah_currency') : "SAR";
        $currencies = [
            'AUD' => array('code' => '373', 'shortcut' => 'AUD', 'short' => '$', 'short_ar' => '$', 'name' => 'Australian Dollars', 'name_ar' => 'دولار استرالي', 'rate' => NULL),
            'BHD' => array('code' => '381', 'shortcut' => 'BHD', 'short' => 'BHD', 'short_ar' => 'د.ب', 'name' => 'Bahraini Dinars', 'name_ar' => 'دينار بحريني', 'rate' => NULL),
            'AED' => array('code' => '366', 'shortcut' => 'AED', 'short' => 'AED', 'short_ar' => 'د.إ', 'name' => 'United Arab Emirates Dirhams', 'name_ar' => 'درهم إماراتي', 'rate' => NULL),
            'QAR' => array('code' => '487', 'shortcut' => 'QAR', 'short' => 'QAR', 'short_ar' => 'ر.ق', 'name' => 'Qatari Riyals', 'name_ar' => 'ريال قطري', 'rate' => NULL),
            'SAR' => array('code' => '492', 'shortcut' => 'SAR', 'short' => 'SAR', 'short_ar' => 'ر.س', 'name' => 'Saudi Arabian Riyals', 'name_ar' => 'ريال سعودي', 'rate' => NULL),
            'SEK' => array('code' => '496', 'shortcut' => 'SEK', 'short' => 'kr', 'short_ar' => 'kr', 'name' => 'Sweden Kronor', 'name_ar' => 'كرون سويدي', 'rate' => NULL),
            'THB' => array('code' => '509', 'shortcut' => 'THB', 'short' => 'THB', 'short_ar' => 'THB', 'name' => 'Thai Baht', 'name_ar' => 'بات تايلاندي', 'rate' => NULL),
            'USD' => array('code' => '520', 'shortcut' => 'USD', 'short' => '$', 'short_ar' => '$', 'name' => 'US Dollars', 'name_ar' => 'دولار أمريكي', 'rate' => NULL),
            'ZAR' => array('code' => '537', 'shortcut' => 'ZAR', 'short' => 'R', 'short_ar' => 'R', 'name' => 'South African Rand', 'name_ar' => 'راند جنوب أفريقيا', 'rate' => NULL),
            'KWD' => array('code' => '769', 'shortcut' => 'KWD', 'short' => 'KWD', 'short_ar' => 'د.ك', 'name' => 'Kuwaiti Dinars', 'name_ar' => 'دينار كويتي', 'rate' => NULL),
            'NOK' => array('code' => '770', 'shortcut' => 'NOK', 'short' => 'Kr', 'short_ar' => 'kr', 'name' => 'Norwegian Kroner', 'name_ar' => 'كرونة نرويجية', 'rate' => NULL),
            'MYR' => array('code' => '471', 'shortcut' => 'MYR', 'short' => 'RM', 'short_ar' => 'RM', 'name' => 'Malaysian Ringgits', 'name_ar' => 'رينغيت ماليزي', 'rate' => NULL),
            'KRW' => array('code' => '446', 'shortcut' => 'KRW', 'short' => '₩', 'short_ar' => '₩', 'name' => 'South Korea Won', 'name_ar' => 'وون كوريا الجنوبية', 'rate' => NULL),
            'JPY' => array('code' => '440', 'shortcut' => 'JPY', 'short' => '¥', 'short_ar' => '¥', 'name' => 'Japanese Yen', 'name_ar' => 'الين الياباني', 'rate' => NULL),
            'CHF' => array('code' => '394', 'shortcut' => 'CHF', 'short' => 'CHF', 'short_ar' => 'CHF', 'name' => 'Swiss Francs', 'name_ar' => 'فرنك سويسري', 'rate' => NULL),
            'DKK' => array('code' => '406', 'shortcut' => 'DKK', 'short' => 'kr', 'short_ar' => 'kr', 'name' => 'Denmark Kroner', 'name_ar' => 'كرونر دنماركي', 'rate' => NULL),
            'EUR' => array('code' => '413', 'shortcut' => 'EUR', 'short' => '€', 'short_ar' => '€', 'name' => 'Euro', 'name_ar' => 'اليورو', 'rate' => NULL),
            'GBP' => array('code' => '416', 'shortcut' => 'GBP', 'short' => '£', 'short_ar' => '£', 'name' => 'UK Pounds Sterling', 'name_ar' => 'الجنيه الاسترليني', 'rate' => NULL),
            'HKD' => array('code' => '425', 'shortcut' => 'HKD', 'short' => 'HK$', 'short_ar' => 'HK$', 'name' => 'Hong Kong Dollars', 'name_ar' => 'دولار هونج كونج', 'rate' => NULL),
            'IDR' => array('code' => '430', 'shortcut' => 'IDR', 'short' => 'Rp', 'short_ar' => 'Rp', 'name' => 'Indonesian Rupiahs', 'name_ar' => 'الروبية الاندونيسية', 'rate' => NULL),
            'INR' => array('code' => '433', 'shortcut' => 'INR', 'short' => '₹', 'short_ar' => '₹', 'name' => 'Indian Rupees', 'name_ar' => 'روبية هندية', 'rate' => NULL),
            'JOD' => array('code' => '439', 'shortcut' => 'JOD', 'short' => 'JOD', 'short_ar' => 'د.أر', 'name' => 'Jordanian Dinars', 'name_ar' => 'دينار أردني', 'rate' => NULL),
            'CNY' => array('code' => '2524', 'shortcut' => 'CNY', 'short' => '元', 'short_ar' => '元', 'name' => 'Chinese Yuan', 'name_ar' => 'اليوان الصيني ', 'rate' => NULL)
        ];
        return $currencies[$cur][$field];
    }
}

if (!function_exists('currencies')) {
    function currencies()
    {
        return array(
            'AUD' => array('code' => '373', 'shortcut' => 'AUD', 'name' => 'Australian Dollars', 'name_ar' => 'دولار استرالي', 'rate' => NULL),
            'BHD' => array('code' => '381', 'shortcut' => 'BHD', 'name' => 'Bahraini Dinars', 'name_ar' => 'دينار بحريني', 'rate' => NULL),
            'AED' => array('code' => '366', 'shortcut' => 'AED', 'name' => 'United Arab Emirates Dirhams', 'name_ar' => 'درهم إماراتي', 'rate' => NULL),
            'QAR' => array('code' => '487', 'shortcut' => 'QAR', 'name' => 'Qatari Riyals', 'name_ar' => 'ريال قطري', 'rate' => NULL),
            'SAR' => array('code' => '492', 'shortcut' => 'SAR', 'name' => 'Saudi Arabian Riyals', 'name_ar' => 'ريال سعودي', 'rate' => NULL),
            'SEK' => array('code' => '496', 'shortcut' => 'SEK', 'name' => 'Sweden Kronor', 'name_ar' => 'كرون سويدي', 'rate' => NULL),
            'THB' => array('code' => '509', 'shortcut' => 'THB', 'name' => 'Thai Baht', 'name_ar' => 'بات تايلاندي', 'rate' => NULL),
            'USD' => array('code' => '520', 'shortcut' => 'USD', 'name' => 'US Dollars', 'name_ar' => 'دولار أمريكي', 'rate' => NULL),
            'ZAR' => array('code' => '537', 'shortcut' => 'ZAR', 'name' => 'South African Rand', 'name_ar' => 'راند جنوب أفريقيا', 'rate' => NULL),
            'KWD' => array('code' => '769', 'shortcut' => 'KWD', 'name' => 'Kuwaiti Dinars', 'name_ar' => 'دينار كويتي', 'rate' => NULL),
            'NOK' => array('code' => '770', 'shortcut' => 'NOK', 'name' => 'Norwegian Kroner', 'name_ar' => 'كرونة نرويجية', 'rate' => NULL),
            'MYR' => array('code' => '471', 'shortcut' => 'MYR', 'name' => 'Malaysian Ringgits', 'name_ar' => 'رينغيت ماليزي', 'rate' => NULL),
            'KRW' => array('code' => '446', 'shortcut' => 'KRW', 'name' => 'South Korea Won', 'name_ar' => 'وون كوريا الجنوبية', 'rate' => NULL),
            'JPY' => array('code' => '440', 'shortcut' => 'JPY', 'name' => 'Japanese Yen', 'name_ar' => 'الين الياباني', 'rate' => NULL),
            'CHF' => array('code' => '394', 'shortcut' => 'CHF', 'name' => 'Swiss Francs', 'name_ar' => 'فرنك سويسري', 'rate' => NULL),
            'DKK' => array('code' => '406', 'shortcut' => 'DKK', 'name' => 'Denmark Kroner', 'name_ar' => 'كرونر دنماركي', 'rate' => NULL),
            'EUR' => array('code' => '413', 'shortcut' => 'EUR', 'name' => 'Euro', 'name_ar' => 'اليورو', 'rate' => NULL),
            'GBP' => array('code' => '416', 'shortcut' => 'GBP', 'name' => 'UK Pounds Sterling', 'name_ar' => 'الجنيه الاسترليني', 'rate' => NULL),
            'HKD' => array('code' => '425', 'shortcut' => 'HKD', 'name' => 'Hong Kong Dollars', 'name_ar' => 'دولار هونج كونج', 'rate' => NULL),
            'IDR' => array('code' => '430', 'shortcut' => 'IDR', 'name' => 'Indonesian Rupiahs', 'name_ar' => 'الروبية الاندونيسية', 'rate' => NULL),
            'INR' => array('code' => '433', 'shortcut' => 'INR', 'name' => 'Indian Rupees', 'name_ar' => 'روبية هندية', 'rate' => NULL),
            'JOD' => array('code' => '439', 'shortcut' => 'JOD', 'name' => 'Jordanian Dinars', 'name_ar' => 'دينار أردني', 'rate' => NULL),
            'CNY' => array('code' => '2524', 'shortcut' => 'CNY', 'name' => 'Chinese Yuan', 'name_ar' => 'اليوان الصيني ', 'rate' => NULL)
        );
    }
}

if (!function_exists('curcal')) {
    function curcal($amount)
    {
        //usercur
        $uc = usercur();
        $namount = (float)currencies()['eqls'][$uc]['rate'] * $amount;
        return round($namount, 2);
    }
}

if (!function_exists('calcPrices')) {

    /**
     * Calculating Eomrah lots prices
     * 
     *  adding Eomrah markup and removing taxes and fees
     * 
     * the benefits is to make marketable prices
     * @param float $amount
     * @param bool $format
     * @return float
     */

    function calcPrices(float $amount, bool $fomrat = false)
    {  
        $markup = $amount * MARKUP;
        $markedamount = $amount + $markup;
        $vat = $markedamount * VAT;
        $manicipality = $markedamount * MANICIPALITY_TAX;
        $markedamount = $markedamount - $vat - $manicipality;
        return $fomrat ? round($markedamount, 2) . ' ' . userCurShort() : round($markedamount, 2);
    }
}

if (!function_exists('b2bcalcPrices')) {

    /**
     * Calculating Eomrah lots prices
     * 
     *  adding Eomrah markup and removing taxes and fees
     * 
     * the benefits is to make marketable prices
     * @param float $amount
     * @param bool $format
     * @return float
     */

    function b2bcalcPrices(float $amount, bool $fomrat = false)
    {  
        $markup = $amount * B2BMARKUP;
        $markedamount = $amount + $markup;
        $vat = $markedamount * VAT;
        $manicipality = $markedamount * MANICIPALITY_TAX;
        $markedamount = $markedamount - $vat - $manicipality;
        return $fomrat ? round($markedamount, 2) . ' ' . userCurShort() : round($markedamount, 2);
    }
}


if (!function_exists('calcFines')) {

    /**
     * Calculating Eomrah lots Fines For cancelations and amendments
     * 
     *  adding Eomrah markup and removing taxes and fees
     * 
     * the benefits is to make marketable prices
     * @param float $amount
     * @param bool $format
     * @return float
     */

    function calcFines(float $amount, bool $fomrat = false)
    {
        $markup = $amount * MARKUP;
        $markedamount = $amount + $markup;
        // $vat = $markedamount * VAT;
        // $manicipality = $markedamount * MANICIPALITY_TAX;
        // $markedamount = $markedamount - $vat - $manicipality;
        return $fomrat ? round($markedamount, 2) . ' ' . userCurShort() : round($markedamount, 2);
    }
}


if (!function_exists('calcPriceTaxFees')) {

    /**
     * Calculating Eomrah lots price Taxes and fees
     * 
     *  adding Eomrah markup and removing taxes and fees
     * 
     * the benefits is to make marketable prices
     * @param float $amount
     * @param bool $format
     * @return float rounded taxes
     */

    function calcPriceTaxFees(float $amount, bool $format = false)
    {
        $markup = $amount * MARKUP;
        $markedamount = $amount + $markup;
        $vat = $markedamount * VAT;
        $manicipality = $markedamount * MANICIPALITY_TAX;
        $fees = $vat + $manicipality;
        return $format ? round($fees, 2) . ' ' . userCurShort() : round($fees, 2);
    }
}



if (!function_exists('b2bcalcPriceTaxFees')) {

    /**
     * Calculating Eomrah lots price Taxes and fees
     * 
     *  adding Eomrah markup and removing taxes and fees
     * 
     * the benefits is to make marketable prices
     * @param float $amount
     * @param bool $format
     * @return float rounded taxes
     */

    function b2bcalcPriceTaxFees(float $amount, bool $format = false)
    {
        $markup = $amount * B2BMARKUP;
        $markedamount = $amount + $markup;
        $vat = $markedamount * VAT;
        $manicipality = $markedamount * MANICIPALITY_TAX;
        $fees = $vat + $manicipality;
        return $format ? round($fees, 2) . ' ' . userCurShort() : round($fees, 2);
    }
}

if (!function_exists('calcVat')) {

    /**
     * Calculating Eomrah lots price Taxes and fees
     * 
     *  adding Eomrah markup and removing taxes and fees
     * 
     * the benefits is to make marketable prices
     * @param float $amount
     * @param bool $format
     * @return float rounded taxes
     */

    function calcVat(float $amount, bool $format = false)
    {
        $markup = $amount * MARKUP;
        $markedamount = $amount + $markup;
        $vat = $markedamount * VAT;
        return $format ? round($vat, 2) . ' ' . userCurShort() : round($vat, 2);
    }
}


if (!function_exists('b2bcalcVat')) {

    /**
     * Calculating Eomrah lots price Taxes and fees
     * 
     *  adding Eomrah markup and removing taxes and fees
     * 
     * the benefits is to make marketable prices
     * @param float $amount
     * @param bool $format
     * @return float rounded taxes
     */

    function b2bcalcVat(float $amount, bool $format = false)
    {
        $markup = $amount * B2BMARKUP;
        $markedamount = $amount + $markup;
        $vat = $markedamount * VAT;
        return $format ? round($vat, 2) . ' ' . userCurShort() : round($vat, 2);
    }
}

if (!function_exists('calcManicTax')) {

    /**
     * Calculating Eomrah lots price Taxes and fees
     * 
     *  adding Eomrah markup and removing taxes and fees
     * 
     * the benefits is to make marketable prices
     * @param float $amount
     * @param bool $format
     * @return float rounded taxes
     */

    function calcManicTax(float $amount, bool $format = false)
    {
        $markup = $amount * MARKUP;
        $markedamount = $amount + $markup;
        $manTax = $markedamount * MANICIPALITY_TAX;
        return $format ? round($manTax, 2) . ' ' . userCurShort() : round($manTax, 2);
    }
}


if (!function_exists('b2bcalcManicTax')) {

    /**
     * Calculating Eomrah lots price Taxes and fees
     * 
     *  adding Eomrah markup and removing taxes and fees
     * 
     * the benefits is to make marketable prices
     * @param float $amount
     * @param bool $format
     * @return float rounded taxes
     */

    function b2bcalcManicTax(float $amount, bool $format = false)
    {
        $markup = $amount * B2BMARKUP;
        $markedamount = $amount + $markup;
        $manTax = $markedamount * MANICIPALITY_TAX;
        return $format ? round($manTax, 2) . ' ' . userCurShort() : round($manTax, 2);
    }
}

if (!function_exists('phonecodes')) {
    /**
     * Countries phonecodes options
     * 
     * the benefits is to make marketable prices
     * @return string
     */
    function phonecodes()
    {
        return $phoneCodes = <<<EOT
                        <option data-countryCode="GB" value="44">UK (+44)</option>
                        <option data-countryCode="US" value="1">USA (+1)</option>
                        <option data-countryCode="DZ" value="213">Algeria (+213)</option>
                        <option data-countryCode="AD" value="376">Andorra (+376)</option>
                        <option data-countryCode="AO" value="244">Angola (+244)</option>
                        <option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                        <option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                        <option data-countryCode="AR" value="54">Argentina (+54)</option>
                        <option data-countryCode="AM" value="374">Armenia (+374)</option>
                        <option data-countryCode="AW" value="297">Aruba (+297)</option>
                        <option data-countryCode="AU" value="61">Australia (+61)</option>
                        <option data-countryCode="AT" value="43">Austria (+43)</option>
                        <option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                        <option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                        <option data-countryCode="BH" value="973">Bahrain (+973)</option>
                        <option data-countryCode="BD" value="880">Bangladesh (+880)</option>
                        <option data-countryCode="BB" value="1246">Barbados (+1246)</option>
                        <option data-countryCode="BY" value="375">Belarus (+375)</option>
                        <option data-countryCode="BE" value="32">Belgium (+32)</option>
                        <option data-countryCode="BZ" value="501">Belize (+501)</option>
                        <option data-countryCode="BJ" value="229">Benin (+229)</option>
                        <option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                        <option data-countryCode="BT" value="975">Bhutan (+975)</option>
                        <option data-countryCode="BO" value="591">Bolivia (+591)</option>
                        <option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                        <option data-countryCode="BW" value="267">Botswana (+267)</option>
                        <option data-countryCode="BR" value="55">Brazil (+55)</option>
                        <option data-countryCode="BN" value="673">Brunei (+673)</option>
                        <option data-countryCode="BG" value="359">Bulgaria (+359)</option>
                        <option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                        <option data-countryCode="BI" value="257">Burundi (+257)</option>
                        <option data-countryCode="KH" value="855">Cambodia (+855)</option>
                        <option data-countryCode="CM" value="237">Cameroon (+237)</option>
                        <option data-countryCode="CA" value="1">Canada (+1)</option>
                        <option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                        <option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                        <option data-countryCode="CF" value="236">Central African Republic (+236)</option>
                        <option data-countryCode="CL" value="56">Chile (+56)</option>
                        <option data-countryCode="CN" value="86">China (+86)</option>
                        <option data-countryCode="CO" value="57">Colombia (+57)</option>
                        <option data-countryCode="KM" value="269">Comoros (+269)</option>
                        <option data-countryCode="CG" value="242">Congo (+242)</option>
                        <option data-countryCode="CK" value="682">Cook Islands (+682)</option>
                        <option data-countryCode="CR" value="506">Costa Rica (+506)</option>
                        <option data-countryCode="HR" value="385">Croatia (+385)</option>
                        <option data-countryCode="CU" value="53">Cuba (+53)</option>
                        <option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                        <option data-countryCode="CY" value="357">Cyprus South (+357)</option>
                        <option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                        <option data-countryCode="DK" value="45">Denmark (+45)</option>
                        <option data-countryCode="DJ" value="253">Djibouti (+253)</option>
                        <option data-countryCode="DM" value="1809">Dominica (+1809)</option>
                        <option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                        <option data-countryCode="EC" value="593">Ecuador (+593)</option>
                        <option data-countryCode="EG" value="20">Egypt (+20)</option>
                        <option data-countryCode="SV" value="503">El Salvador (+503)</option>
                        <option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                        <option data-countryCode="ER" value="291">Eritrea (+291)</option>
                        <option data-countryCode="EE" value="372">Estonia (+372)</option>
                        <option data-countryCode="ET" value="251">Ethiopia (+251)</option>
                        <option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                        <option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                        <option data-countryCode="FJ" value="679">Fiji (+679)</option>
                        <option data-countryCode="FI" value="358">Finland (+358)</option>
                        <option data-countryCode="FR" value="33">France (+33)</option>
                        <option data-countryCode="GF" value="594">French Guiana (+594)</option>
                        <option data-countryCode="PF" value="689">French Polynesia (+689)</option>
                        <option data-countryCode="GA" value="241">Gabon (+241)</option>
                        <option data-countryCode="GM" value="220">Gambia (+220)</option>
                        <option data-countryCode="GE" value="7880">Georgia (+7880)</option>
                        <option data-countryCode="DE" value="49">Germany (+49)</option>
                        <option data-countryCode="GH" value="233">Ghana (+233)</option>
                        <option data-countryCode="GI" value="350">Gibraltar (+350)</option>
                        <option data-countryCode="GR" value="30">Greece (+30)</option>
                        <option data-countryCode="GL" value="299">Greenland (+299)</option>
                        <option data-countryCode="GD" value="1473">Grenada (+1473)</option>
                        <option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                        <option data-countryCode="GU" value="671">Guam (+671)</option>
                        <option data-countryCode="GT" value="502">Guatemala (+502)</option>
                        <option data-countryCode="GN" value="224">Guinea (+224)</option>
                        <option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                        <option data-countryCode="GY" value="592">Guyana (+592)</option>
                        <option data-countryCode="HT" value="509">Haiti (+509)</option>
                        <option data-countryCode="HN" value="504">Honduras (+504)</option>
                        <option data-countryCode="HK" value="852">Hong Kong (+852)</option>
                        <option data-countryCode="HU" value="36">Hungary (+36)</option>
                        <option data-countryCode="IS" value="354">Iceland (+354)</option>
                        <option data-countryCode="IN" value="91">India (+91)</option>
                        <option data-countryCode="ID" value="62">Indonesia (+62)</option>
                        <option data-countryCode="IR" value="98">Iran (+98)</option>
                        <option data-countryCode="IQ" value="964">Iraq (+964)</option>
                        <option data-countryCode="IE" value="353">Ireland (+353)</option>
                        <option data-countryCode="IL" value="972">Israel (+972)</option>
                        <option data-countryCode="IT" value="39">Italy (+39)</option>
                        <option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                        <option data-countryCode="JP" value="81">Japan (+81)</option>
                        <option data-countryCode="JO" value="962">Jordan (+962)</option>
                        <option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                        <option data-countryCode="KE" value="254">Kenya (+254)</option>
                        <option data-countryCode="KI" value="686">Kiribati (+686)</option>
                        <option data-countryCode="KP" value="850">Korea North (+850)</option>
                        <option data-countryCode="KR" value="82">Korea South (+82)</option>
                        <option data-countryCode="KW" value="965">Kuwait (+965)</option>
                        <option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                        <option data-countryCode="LA" value="856">Laos (+856)</option>
                        <option data-countryCode="LV" value="371">Latvia (+371)</option>
                        <option data-countryCode="LB" value="961">Lebanon (+961)</option>
                        <option data-countryCode="LS" value="266">Lesotho (+266)</option>
                        <option data-countryCode="LR" value="231">Liberia (+231)</option>
                        <option data-countryCode="LY" value="218">Libya (+218)</option>
                        <option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                        <option data-countryCode="LT" value="370">Lithuania (+370)</option>
                        <option data-countryCode="LU" value="352">Luxembourg (+352)</option>
                        <option data-countryCode="MO" value="853">Macao (+853)</option>
                        <option data-countryCode="MK" value="389">Macedonia (+389)</option>
                        <option data-countryCode="MG" value="261">Madagascar (+261)</option>
                        <option data-countryCode="MW" value="265">Malawi (+265)</option>
                        <option data-countryCode="MY" value="60">Malaysia (+60)</option>
                        <option data-countryCode="MV" value="960">Maldives (+960)</option>
                        <option data-countryCode="ML" value="223">Mali (+223)</option>
                        <option data-countryCode="MT" value="356">Malta (+356)</option>
                        <option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                        <option data-countryCode="MQ" value="596">Martinique (+596)</option>
                        <option data-countryCode="MR" value="222">Mauritania (+222)</option>
                        <option data-countryCode="YT" value="269">Mayotte (+269)</option>
                        <option data-countryCode="MX" value="52">Mexico (+52)</option>
                        <option data-countryCode="FM" value="691">Micronesia (+691)</option>
                        <option data-countryCode="MD" value="373">Moldova (+373)</option>
                        <option data-countryCode="MC" value="377">Monaco (+377)</option>
                        <option data-countryCode="MN" value="976">Mongolia (+976)</option>
                        <option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                        <option data-countryCode="MA" value="212">Morocco (+212)</option>
                        <option data-countryCode="MZ" value="258">Mozambique (+258)</option>
                        <option data-countryCode="MN" value="95">Myanmar (+95)</option>
                        <option data-countryCode="NA" value="264">Namibia (+264)</option>
                        <option data-countryCode="NR" value="674">Nauru (+674)</option>
                        <option data-countryCode="NP" value="977">Nepal (+977)</option>
                        <option data-countryCode="NL" value="31">Netherlands (+31)</option>
                        <option data-countryCode="NC" value="687">New Caledonia (+687)</option>
                        <option data-countryCode="NZ" value="64">New Zealand (+64)</option>
                        <option data-countryCode="NI" value="505">Nicaragua (+505)</option>
                        <option data-countryCode="NE" value="227">Niger (+227)</option>
                        <option data-countryCode="NG" value="234">Nigeria (+234)</option>
                        <option data-countryCode="NU" value="683">Niue (+683)</option>
                        <option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                        <option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                        <option data-countryCode="NO" value="47">Norway (+47)</option>
                        <option data-countryCode="OM" value="968">Oman (+968)</option>
                        <option data-countryCode="PW" value="680">Palau (+680)</option>
                        <option data-countryCode="PA" value="507">Panama (+507)</option>
                        <option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                        <option data-countryCode="PY" value="595">Paraguay (+595)</option>
                        <option data-countryCode="PE" value="51">Peru (+51)</option>
                        <option data-countryCode="PH" value="63">Philippines (+63)</option>
                        <option data-countryCode="PL" value="48">Poland (+48)</option>
                        <option data-countryCode="PT" value="351">Portugal (+351)</option>
                        <option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                        <option data-countryCode="QA" value="974">Qatar (+974)</option>
                        <option data-countryCode="RE" value="262">Reunion (+262)</option>
                        <option data-countryCode="RO" value="40">Romania (+40)</option>
                        <option data-countryCode="RU" value="7">Russia (+7)</option>
                        <option data-countryCode="RW" value="250">Rwanda (+250)</option>
                        <option data-countryCode="SM" value="378">San Marino (+378)</option>
                        <option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                        <option data-countryCode="SA" value="966" selected> Saudi Arabia (+966)</option>
                        <option data-countryCode="SN" value="221">Senegal (+221)</option>
                        <option data-countryCode="CS" value="381">Serbia (+381)</option>
                        <option data-countryCode="SC" value="248">Seychelles (+248)</option>
                        <option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                        <option data-countryCode="SG" value="65">Singapore (+65)</option>
                        <option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                        <option data-countryCode="SI" value="386">Slovenia (+386)</option>
                        <option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                        <option data-countryCode="SO" value="252">Somalia (+252)</option>
                        <option data-countryCode="ZA" value="27">South Africa (+27)</option>
                        <option data-countryCode="ES" value="34">Spain (+34)</option>
                        <option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                        <option data-countryCode="SH" value="290">St. Helena (+290)</option>
                        <option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                        <option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                        <option data-countryCode="SD" value="249">Sudan (+249)</option>
                        <option data-countryCode="SR" value="597">Suriname (+597)</option>
                        <option data-countryCode="SZ" value="268">Swaziland (+268)</option>
                        <option data-countryCode="SE" value="46">Sweden (+46)</option>
                        <option data-countryCode="CH" value="41">Switzerland (+41)</option>
                        <option data-countryCode="SI" value="963">Syria (+963)</option>
                        <option data-countryCode="TW" value="886">Taiwan (+886)</option>
                        <option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                        <option data-countryCode="TH" value="66">Thailand (+66)</option>
                        <option data-countryCode="TG" value="228">Togo (+228)</option>
                        <option data-countryCode="TO" value="676">Tonga (+676)</option>
                        <option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                        <option data-countryCode="TN" value="216">Tunisia (+216)</option>
                        <option data-countryCode="TR" value="90">Turkey (+90)</option>
                        <option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                        <option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                        <option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                        <option data-countryCode="TV" value="688">Tuvalu (+688)</option>
                        <option data-countryCode="UG" value="256">Uganda (+256)</option>
                        <option data-countryCode="GB" value="44">UK (+44)</option>
                        <option data-countryCode="UA" value="380">Ukraine (+380)</option>
                        <option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                        <option data-countryCode="UY" value="598">Uruguay (+598)</option>
                        <option data-countryCode="US" value="1">USA (+1)</option> 
                        <option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                        <option data-countryCode="VU" value="678">Vanuatu (+678)</option>
                        <option data-countryCode="VA" value="379">Vatican City (+379)</option>
                        <option data-countryCode="VE" value="58">Venezuela (+58)</option>
                        <option data-countryCode="VN" value="84">Vietnam (+84)</option>
                        <option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                        <option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                        <option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                        <option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                        <option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                        <option data-countryCode="ZM" value="260">Zambia (+260)</option>
                        <option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                    EOT;
    }
}
