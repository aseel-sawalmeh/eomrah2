<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auto_model extends CI_Model
{

    public function __costruct()
    {
        parent::__construct();
    }

    public function find($name)
    {
        //needs hotel name search arabic integration
        $alldata = [];
        $this->db->select('hotels.Hotel_ID, hotels.hotelLotsId, hotels.Hotel_Name, hotels.Hotel_City_ID, cities.city_code, cities.city_name, cities.city_name_ar, hotel_locations.id as locationId, hotel_locations.locationName, hotel_locations.locationName_ar');
        $this->db->join('cities', "hotels.Hotel_City_ID = cities.city_code", 'LEFT');
        $this->db->join('hotel_locations', "hotels.hotelLotsId = hotel_locations.hotelId", 'LEFT');
        $this->db->like('hotels.Hotel_Name', $name, 'both');
        $this->db->or_like('cities.city_name', $name, 'both');
        $this->db->or_like('cities.city_name_ar', $name, 'both');
        $this->db->or_like('hotel_locations.locationName', $name, 'both');//add this location filter to hotelsFindresults
        $this->db->or_like('hotel_locations.locationName_ar', $name, 'both');
        // $this->db->group_by('hotels.Hotel_Name');
        $this->db->limit(30);
      
//   print_r($this->db->get_compiled_select('hotels'));
        //   exit();
// print_r($this->db->get_compiled_select('hotels'));exit;
        //add locations from hotels locations
        $hotels = $this->db->get('hotels');
        // print_r($hotels);
        if ($hotels->num_rows() > 0) {
            $hotels = $hotels->result();
            $city = (userlang() == 'ar') ? $hotels[0]->city_name_ar : $hotels[0]->city_name;
            $alldata['locations'][] = ['title' => $city, 'titledd' => $city, 'type' => 'locality', 'tid' => (int)$hotels[0]->city_code, 'city' => (int)$hotels[0]->city_code, 'label'=>lang('city')];
            $locations = [];
            foreach ($hotels as $hotel) {
                if($hotel->locationName && !in_array($hotel->locationName, $locations) && !is_numeric($hotel->locationName)){
                    $locations[]= $hotel->locationName;
                    $alldata['locations'][] = ['title' => $hotel->locationName, 'titledd' => $hotel->locationName, 'type' => 'area', 'tid' => (int)$hotels[0]->locationId, 'city' => (int)$hotels[0]->city_code, 'label' => lang('area')];
                }
                $alldata['hotels'][] = ['title' =>$hotel->Hotel_Name, 'titledd' => tolang($hotel->Hotel_ID, 'hotelname'), 'type' => 'lodging', 'tid' => (int)$hotel->Hotel_ID, 'city'=> (int)$hotel->Hotel_City_ID, 'label' => lang('hotel')]; //pushing strategy needs more optimizing
            }
            unset($locations);
            return $alldata;
            // return array_unique($alldata);
        } else {
            // print_r($this->db->error());
            return false;
        }
    }


    //-------------------------------------------
    /**
     * for hotel serach (deprecated) use find_hotels instead
     * 
     * left for cleaning and compatibility of other calls
     * 
     * @param int $stars if requested
     * @param string $destination City from the get request
     * @return array $hotels sets  found 
     * 
     */
    public function fetch_data($stars = null)
    {
        $txtfind = $this->input->get("dest");
        $this->db->select('hotels.*, hotelclassification.stars as starno, cities.city_name');
        $this->db->join('hotelclassification', "hotels.Star_Nums = hotelclassification.hclass_code", 'left');
        $this->db->join('cities', "hotels.Hotel_City_ID = cities.city_id OR hotels.Hotel_City_ID = cities.city_code", 'left');

        $this->db->like('Hotel_Name', $txtfind, 'both');
        if (detectlang($txtfind) == 'ar') {
            $hotelnames = hotel2en($txtfind);
            if ($hotelnames) {
                //show_error(print_r($hotelnames));
                foreach ($hotelnames as $hn) {
                    $this->db->or_like('Hotel_Name', $hn->en, 'both');
                }
            }
        }
        $this->db->or_like('City_Name', $txtfind, 'both');
        $this->db->or_like('city_name_ar', $txtfind, 'both');

        if ($stars != null) {
            $starscount = explode('_', $stars);
            if (sizeof($starscount) > 1) {
                for ($i = 0; $i < count($starscount); $i++) {
                    if ($i == 0) {
                        $this->db->having('hotels.Star_Nums', $starscount[$i]);
                    } else {
                        $this->db->or_having('hotels.Star_Nums', $starscount[$i]);
                    }
                }
            } else {
                $this->db->having('hotels.Star_Nums', $stars);
            }
        }

        $hotels = $this->db->get('hotels');
        // $hotelsd = $this->db->get_compiled_select('hotels');
        // show_error(print_r($hotels));
        // show_error(print_r($this->db->error()));
        return $hotels ? $hotels->result_array() : false;
    }

    //-------------------------------------------
    /**
     * Find hotels with city name 
     * left for cleaning and compatibility of other calls
     * 
     * @param int $stars if requested
     * @param string $destination City from the get request
     * @return array $hotels sets  found 
     * 
     */
    public function find_hotels($stars = null)
    {
        // dev code with new edits to focus on ids
        if($this->input->get("desttype") && $this->input->get("destid")){
            if($this->input->get("desttype") == 'locality'){
                $this->db->select('hotels.*, hotelclassification.stars as starno, cities.city_name, cities.city_name_ar ');
                $this->db->join('hotelclassification', "hotels.Star_Nums = hotelclassification.hclass_code", 'left');
                $this->db->join('cities', "hotels.Hotel_City_ID = cities.city_code", 'left');
                $this->db->where('hotels.Hotel_City_ID', $this->input->get("destid"));

            }elseif($this->input->get("desttype") == 'lodging'){
                $this->db->select('hotels.*, hotelclassification.stars as starno, cities.city_name, cities.city_name_ar');
                $this->db->join('hotelclassification', "hotels.Star_Nums = hotelclassification.hclass_code", 'left');
                $this->db->join('cities', "hotels.Hotel_City_ID = cities.city_code", 'left');
                $this->db->where('hotels.Hotel_ID', $this->input->get("destid"));
            }elseif($this->input->get("desttype") == 'area'){
                $this->db->select('hotels.*, hotelclassification.stars as starno, cities.city_name, cities.city_name_ar, hotel_locations.locationCode, hotel_locations.locationName, hotel_locations.locationName_ar');
                $this->db->join('hotelclassification', "hotels.Star_Nums = hotelclassification.hclass_code", 'left');
                $this->db->join('cities', "hotels.Hotel_City_ID = cities.city_code", 'left');
                $this->db->join('hotel_locations', "hotels.hotelLotsId = hotel_locations.hotelid", 'left');
                $this->db->where('hotel_locations.id', $this->input->get("destid"));
                $this->db->or_where('hotel_locations.locationName', $this->input->get("dest"));
                $this->db->or_where('hotel_locations.locationName_ar', $this->input->get("dest"));
            }
        }else{
            $txtfind = $this->input->get("dest");
            $this->db->select('hotels.*, hotelclassification.stars as starno, cities.city_name, cities.city_name_ar');
            $this->db->join('hotelclassification', "hotels.Star_Nums = hotelclassification.hclass_code", 'left');
            $this->db->join('cities', "hotels.Hotel_City_ID = cities.city_code", 'left');

            $this->db->like('Hotel_Name', $txtfind, 'both');
            if (detectlang($txtfind) == 'ar') {
                $hotelnames = hotel2en($txtfind);
                if ($hotelnames) {
                    //show_error(print_r($hotelnames));
                    foreach ($hotelnames as $hn) {
                        $this->db->or_like('Hotel_Name', $hn->en, 'both');
                    }
                }
            }
            $this->db->or_like('City_Name', $txtfind, 'both');
            $this->db->or_like('city_name_ar', $txtfind, 'both');

            // if ($stars != null) {
            //     $starscount = explode('_', $stars);
            //     if (sizeof($starscount) > 1) {
            //         for ($i = 0; $i < count($starscount); $i++) {
            //             if ($i == 0) {
            //                 $this->db->having('hotels.Star_Nums', $starscount[$i]);
            //             } else {
            //                 $this->db->or_having('hotels.Star_Nums', $starscount[$i]);
            //             }
            //         }
            //     } else {
            //         $this->db->having('hotels.Star_Nums', $stars);
            //     }
            // }
        }
        

        $hotels = $this->db->get('hotels');
        // $hotelsd = $this->db->get_compiled_select('hotels');
        // show_error(print_r($hotelsd));
        // show_error(print_r($this->db->error()));
        return $hotels ? $hotels->result_array() : false;
    }

    public function get_product($providerid)
    {
        $hotelid = $this->db->get_where('hotel_provider', "Provider_ID = $providerid")->row()->Hotel_ID;
        $this->db->reset_query();
        $this->db->where('P_Item_ID', $hotelid);
        $this->db->where('P_Category_ID', 1);
        $product = $this->db->get('products');
        if ($product) {
            return $product->row();
        } else {
            false;
        }
    }

    public function get_hotels($providerid)
    {
        $hotelid = $this->db->get_where('hotel_provider', "Provider_ID = $providerid")->row()->Hotel_ID;
        $this->db->reset_query();
        $this->db->where('Hotel_ID', $hotelid);
        $hotel = $this->db->get('hotels');
        if ($hotel) {
            return $hotel->row();
        } else {
            false;
        }
    }
    public function get_hotel_geo($hotelid)
    {
        $this->db->select('lat, lng, placeid');
        $this->db->where('hotel_id', $hotelid);
        $hotel = $this->db->get('hotelmap');
        if ($hotel) {
            return $hotel->row();
        } else {
            false;
        }
    }

    public function get_lotshotel_geo($hotelid)
    {
        $this->db->select('lat, lng, placeid');
        $this->db->where('hotel_lotsid', $hotelid);
        $hotel = $this->db->get('hotelmap');
        if ($hotel) {
            return $hotel->row();
        } else {
            false;
        }
    }

    public function get_product_pics($productid)
    {
        $this->db->where('Product_ID', $productid);
        $photos = $this->db->get('product_photos');
        return $photos ? $photos->result() : false;
    }
}
