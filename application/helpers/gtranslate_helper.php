<?php defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('trans')) {
    function trans($text, $from, $to)
    {
        if (!empty($text) || !empty($from) || !empty($to)) {
            if ($from != $to) {
                
                $url = 'https://www.googleapis.com/language/translate/v2?key=' . API_GOOGLE . '&source=' . $from . '&target=' . $to . '&q=' . rawurlencode($text);
                $handle = curl_init($url);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                //ssl certficate temporary skip
                curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($handle);
                if(!$response){
                    print_r(curl_error($handle));
                    return;
                }
                $responseDecoded = json_decode($response, true);
                curl_close($handle);
                return $responseDecoded['data']['translations'][0]['translatedText'];
              
            } else {
                return $text;
            }
        } else {
            return "Nothing to translate";
        }
    }
}

if (!function_exists('sendwhats')) {

    function sendwhats($message = null)
    {
        $fresult = '';
        $content = [
            'from' => array("type" => "whatsapp", "number" => "14157386170"),
            'to' => array("type" => "whatsapp", "number" => "966566845427"),
            'message' => array("content" => ['type' => 'text', 'text' => $message]),
        ];

        $data = json_encode($content);

        $ch = curl_init("https://messages-sandbox.nexmo.com/v0.1/messages");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, "966c8c99:bAOiGz5s7eGHGVLx");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            show_error(var_dump(curl_error($ch)));
        } else {
            $fresult = $result;
        }
        curl_close($ch);
        return $fresult;
    }
}

if (!function_exists('sendsms2')) {

    function sendsms2($message = null, $to)
    {
        $curl = curl_init();
        // $to = "966562062327";
        // $message = "Welcome to eomrah.h";
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rest-api.d7networks.com/secure/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n\t\"to\":\"{$to}\",\n\t\"content\":\"{$message}\",\n\t\"from\":\"eomrah.h\",\n\t\"dlr\":\"yes\",\n\t\"dlr-method\":\"GET\", \n\t\"dlr-level\":\"2\", \n\t\"dlr-url\":\"http://eomrah.h\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "Authorization: Basic aGJhazQ0MzI6U1k4SDZPT2U=",

               
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response);
        return $response;
        // if(explode('"', $res->data)[0] == 'Success'){
        //     return true;
        // }else{
        //     return false;
        // }
    }
}

if (!function_exists('detectlang')) {
    function detectlang($text)
    {
        // temporary deisbaled by returning default en - to save quata in test
        return 'en';
        if (!empty($text)) {
            //AIzaSyBSCfgR69EE4ti7-__n3jWXTQdGnYCHkKM&q=hello
            $url = 'https://translation.googleapis.com/language/translate/v2/detect?key=' . API_GOOGLE . '&q=' . rawurlencode($text);
            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handle);
            $responseDecoded = json_decode($response, true);
            curl_close($handle);
            // print_r($responseDecoded);
            if (isset($responseDecoded['error'])) {
                log_message('error', 'detecting language function google api file' . __File__ . __line__ . ' message' . $responseDecoded['error']['message']);
                return 'en';
            };
            return $responseDecoded['data']['detections'][0][0]['language'];
        } else {
            return false;
        }
    }
}

if (!function_exists('langs_codes')) {
    function langs_codes()
    {
        return $codes = array(
            'fr' => '',
            'en' => '',
            'it' => '',
            'de' => '',
            'ar' => '',
            'es' => '',
            'ru' => '',
            'lv' => '',
            'bg' => '',
            'lt' => '',
            'ca' => '',
            'mk' => '',
            'zh' => '',
            'ms' => '',
            'zh-CN' => '',
            'mt' => '',
            'zh-TW' => '',
            'no' => '',
            'hr' => '',
            'fa' => '',
            'cs' => '',
            'pl' => '',
            'da' => '',
            'pt' => '',
            'nl' => '',
            'pt-PT' => '',
            'sq' => '',
            'ro' => '',
            'et' => '',
            'be' => '',
            'fil' => '',
            'sr' => '',
            'fi' => '',
            'sk' => '',
            'af' => '',
            'sl' => '',
            'gl' => '',
            'ko' => '',
            'ja' => '',
            'sw' => '',
            'el' => '',
            'sv' => '',
            'ht' => '',
            'tl' => '',
            'iw' => '',
            'th' => '',
            'hi' => '',
            'tr' => '',
            'hu' => '',
            'uk' => '',
            'is' => '',
            'vi' => '',
            'id' => '',
            'cy' => '',
            'ga' => '',
            'yi' => '',
            'ur' => '',
        );
    }
}

if (!function_exists('langs')) {
    function langs()
    {
        $CI = get_instance();
        $CI->load->model('translate/fendtranslate_model');
        return $CI->fendtranslate_model->get_langs();
    }
}

if (!function_exists('tolang')) {
    function tolang($id, $type)
    {
        $CI = get_instance();
        $CI->load->model('translate/fendtranslate_model');
        // $userlang = get_cookie('eomrah_language') ?? 'en';
        $txt = $CI->fendtranslate_model->lng($id, $type, userlang());
        if ($txt) {
            return $txt;
        } else {
            return FALSE;
        }
    }
}

if (!function_exists('rapidTranslate')) {
    function rapidTranslate($text, $tolang)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://just-translated.p.rapidapi.com/?lang=$tolang&text=" . urlencode($text),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: just-translated.p.rapidapi.com",
                "x-rapidapi-key: dcffe9651fmsha9730c88ff47e2fp197e3ajsnf010c91a1b7b"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}


if (!function_exists('userlang')) {
    function userlang()
    {
        return get_instance()->lang->is_loaded['general_lang.php'];
    }
}

if (!function_exists('ulflag')) {
    function ulflag()
    {
        $userlang = userlang() ?? "en";
        return base_url("public_designs/assets/flags/$userlang.png");
    }
}

if (!function_exists('lflag')) {
    function lflag($lng)
    {
        return base_url("public_designs/assets/flags/$lng.png");
    }
}

if (!function_exists('langlabel')) {
    function langlabel()
    {
        $userlang = userlang() ?? "en";
        if ($userlang == 'ar') {
            return 'English';
        } else {
            return 'العربية';
        }
    }
}

if (!function_exists('chlang')) {
    function chlang()
    {
        $userlang = userlang() ?? "en";
        if ($userlang == 'ar') {
            return 'en';
        } else {
            return 'ar';
        }
    }
}

if (!function_exists('chLang2')) {
    function chLang2()
    {
        $altlang = (userlang() == 'ar') ? 'en' : 'ar';
        $ifget = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
        return base_url() . $altlang . '/' . str_replace('/index', '', get_instance()->uri->ruri_string()) . $ifget;
    }
}

if (!function_exists('detectUserLang')) {
    function detectUserLang()
    {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $acceptLang = ['ar', 'en'];
        $lang = in_array($lang, $acceptLang) ? $lang : 'en';
        //if thereis lang
        $userlang = userlang();
        if ($userlang) return;
        set_cookie('language', $lang, 86400);
    }
}

if (!function_exists('initlang')) {
    function initlang()
    {
        $sevlang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])? $_SERVER['HTTP_ACCEPT_LANGUAGE']:'english';
        $lang = substr($sevlang, 0, 2);
        $acceptLang = ['ar', 'en'];
        $lang = in_array($lang, $acceptLang) ? $lang : 'en';
        //if thereis lang
        if (isset($_SESSION['language'])) return;
        $_SESSION['language'] =  $lang;
        set_cookie('language', $lang, 86400);

        // if (get_cookie('eomrah_language') == NULL) {
        //     set_cookie('language', 'en', 86400);
        // }
    }
}

if (!function_exists('comtrans')) {
    function comtrans($key)
    {
        $CI = get_instance();
        $CI->load->model('translate/comtranslate');
        $userlang = userlang()?? "en";
        if ($CI->comtranslate->lng($key, $userlang)) {
            return $CI->comtranslate->lng($key, $userlang);
        } else {
            return FALSE;
        }
    }
}

if (!function_exists('hotel2en')) {
    function hotel2en($txt)
    {
        $CI = get_instance();
        $CI->load->model('translate/Fendtranslate_model', 'ftr');

        $found = $CI->ftr->hotel2en($txt);
        if ($found) {
            return $found;
        } else {
            return FALSE;
        }
    }
}

if (!function_exists('cat2lng')) {
    function cat2lng($id, $name)
    {
        if (tolang($id, 'pcattitle')) {
            return tolang($id, 'pcattitle');
        } else {
            return $name;
        }
    }
}

if (!function_exists('desdir')) {
    function desdir()
    {
        foreach (langs() as $lang) {
            if ($lang->code == userlang()) {
                if ($lang->dir == 'rtl') {
                    return $dir = "rtl";
                } else {
                    return $dir = "ltr";
                }
            }
        }
    }
}

if (!function_exists('pcats')) {
    function pcats()
    {
        $PCI = get_instance();
        $PCI->load->model('Product_Categories_model');
        $cats = $PCI->Product_Categories_model->get_pcategories();
        $menu = '';
        $cname = '';
        foreach ($cats as $cat) {
            if ($cat->P_Category_Parent == 0) {
                $cid = $cat->P_Category_ID;
                $cname = $cat->P_Category_Name;
                echo '<li class="submenu">';
                echo "<a href='javascript:void(0);' class='show-submenu'>";
                //echo ctolang($cid, $cname);
                //echo tolang($cid, 'pcattitle')?tolang($cid, 'pcattitle'):$cname;
                echo "<i class='icon-down-open-mini'></i></a>";
            }
            //sub category
            if ($PCI->Product_Categories_model->get_subcategories($cid)) {
                echo "<ul>";
                foreach ($PCI->Product_Categories_model->get_subcategories($cat->P_Category_ID) as $subcat) {
                    echo "<li><a href='cats/$subcat->P_Category_ID'>$subcat->P_Category_Name<i class='icon-down-open-mini'></i></a>";
                    //sub of sub
                    if ($PCI->Product_Categories_model->get_subcategories($subcat->P_Category_ID)) {
                        echo "<ul>";
                        foreach ($PCI->Product_Categories_model->get_subcategories($subcat->P_Category_ID) as $subofsubcat) {
                            echo "<li><a href='cats/$$subofsubcat->P_Category_ID'>$subofsubcat->P_Category_Name</a></li>";
                        }
                        echo "</ul>";
                    }
                    echo "</li>";
                    // sub of sub
                }
                echo "</ul></li>";
            }
            //sub category
            echo "</li>";
        }
    }
}

if (!function_exists('arraypcats')) {
    function arraypcats()
    {
        $PCI = get_instance();
        $PCI->load->model('Product_Categories_model');
        $cats = $PCI->Product_Categories_model->get_pcategories();
        $menu = array();
        $menu['cats'] = array();
        $menu['subcats'] = array();
        $menu['subsubcat'] = array();
        foreach ($cats as $cat) {
            if ($cat->P_Category_Parent == 0) {
                $catcol = array('id' => $cat->P_Category_ID, 'name' => $cat->P_Category_Name);
                array_push($menu['cats'], $catcol);
            }
            //sub category
            if ($PCI->Product_Categories_model->get_subcategories($cat->P_Category_ID)) {
                foreach ($PCI->Product_Categories_model->get_subcategories($cat->P_Category_ID) as $subcat) {
                    $subcatcol = array('pid' => $subcat->P_Category_Parent, 'id' => $subcat->P_Category_ID, 'name' => $subcat->P_Category_Name);
                    array_push($menu['subcats'], $subcatcol);
                    //sub of sub
                    if ($PCI->Product_Categories_model->get_subcategories($subcat->P_Category_ID)) {
                        foreach ($PCI->Product_Categories_model->get_subcategories($subcat->P_Category_ID) as $subofsubcat) {
                            $subsubcatcol = array('pid' => $subofsubcat->P_Category_Parent, 'id' => $subofsubcat->P_Category_ID, 'name' => $subofsubcat->P_Category_Name);
                            array_push($menu['subsubcat'], $subsubcatcol);
                        }
                    }
                    // sub of sub
                }
            }
            //sub category
        }
        return $menu;
    }
}

if (!function_exists('psubcats')) {
    function psubcats($key)
    {
        $CI = get_instance();
        $CI->load->model('translate/comtranslate');
        $userlang = userlang()?? "en";
        if ($CI->comtranslate->lng($key, $userlang)) {
            return $CI->comtranslate->lng($key, $userlang);
        } else {
            return FALSE;
        }
    }
}

if (!function_exists('lngdir')) {
    function lngdir()
    {
        foreach (langs() as $lang) {
            if ($lang->code == userlang()) {
                return $lang->dir;
            }
        }
    }
}
