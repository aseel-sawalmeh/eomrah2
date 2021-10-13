<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('geo_maps')){
    function geo_maps($text){
        if(!empty($text) || !empty($text) || !empty($text)){
                $apiKey = API_GOOGLE;
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?key='.$apiKey.'&address='.rawurlencode($text);
                $handle = curl_init($url);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($handle);
                $responseDecoded = json_decode($response, true);
                curl_close($handle);
                return $responseDecoded['results'][0]; //['data']['translations'][0]['translatedText'];
        }else{
            return "Nothing to locate";
        }
    }
}

if (!function_exists('goPlaces')){

    /**
     * 
     * google places auto complete lang detection
     * 
     * @param string place to search for
     * @return array places suggested
     */
    function goPlaces($text, $lng){
        if(!empty($text) || !empty($text) || !empty($text)){
                $apiKey = API_GOOGLE;
                $url1 = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=".rawurlencode($text)."&inputtype=textquery&fields=place_id,formatted_address,name,types,geometry&language=".$lng."&key=$apiKey";
                //$url = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?&key='.$apiKey.'&input='.rawurlencode($text).'&inputtype=textquery&fields=photos,formatted_address,name,rating,opening_hours,geometry';
                $handle = curl_init($url1);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($handle);
                $responseDecoded = json_decode($response, true);
                curl_close($handle);
                // var_dump($responseDecoded);
                if($responseDecoded['candidates']  != NULL && !is_countable($responseDecoded['candidates'])){
                    return $responseDecoded['status'];
                }else{
                    return $responseDecoded['candidates'];
                }
        }else{
            return "Please Enter A Place Name to Search For";
        }
    }
}

if (!function_exists('sgeo_maps')){
    function sgeo_maps($text){
        if(!empty($text) || !empty($text) || !empty($text)){
                $apiKey = API_GOOGLE;
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?key='.$apiKey.'&address='.rawurlencode($text);
                $handle = curl_init($url);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($handle);
                $responseDecoded = json_decode($response, true);
                curl_close($handle);
                return $responseDecoded['results']; //['data']['translations'][0]['translatedText'];
        }else{
            return "Nothing to locate";
        }
    }
}

if (!function_exists('geo_routes')){
    function geo_routes($text){
        if(!empty($text) || !empty($text) || !empty($text)){
                $apiKey = API_GOOGLE;
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?key='.$apiKey.'&address='.rawurlencode($text);
                $handle = curl_init($url);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($handle);
                $responseDecoded = json_decode($response, true);
                curl_close($handle);
                return $responseDecoded['results'][0]['data']['translations'][0]['translatedText'];
        }else{
            return "Nothing to locate";
        }
    }
}
