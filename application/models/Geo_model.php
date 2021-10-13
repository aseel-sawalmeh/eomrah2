<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * Model Geo_model
 *
 * This Model for dealing with all geo data
 * 
 */

class Geo_model extends CI_Model
{

  // ------------------------------------------------------------------------

  public function __construct()
  {
    parent::__construct();
  }

  // ------------------------------------------------------------------------


  public function get_countries()
  {
    // $this->db->cache_on();
    $countries = $this->db->get('countries');
    return $countries? $countries->result():false;
  }

  public function get_country_code($country)
  {
    $this->db->like('country_name', $country, 'BOTH');
    $code = $this->db->get('countries');
    return ($code && $code->num_rows() > 0)? $code->row()->country_code:show_error($this->db->error());
  }
  

  public function get_cities($country_id = null)
  {
    if(is_null($country_id) || empty($country_id)){
      return $this->db->get('cities')->result();

    }else{
      return $this->db->get_where('cities', "country_code = $country_id")->result();
    }
    return false;
  }
  

  public function get_governorates()
  {
    return $this->db->get('governorates')->result();
  }

  public function get_governorates_country($country_id)
  {
    return $this->db->get_where('governorates', "Country_ID = {$country_id}")->result();
  }

  public function get_regions_governo($governorate_id)
  {
    return $this->db->get_where('cities', "Governorate_ID = {$governorate_id}")->result();
  }

  public function get_regions()
  {
    return $this->db->get('cities')->result();
  }

  public function get_country_governorates($country_id)
  {
    $this->db->where('Country_ID', $country_id);
    $goveroprate = $this->db->get('governorates');
    if ($goveroprate->num_rows() > 0) {
      return $goveroprate->result();
    } else {
      return false;
    }
  }
  
  public function get_governorate_cities($governorate_id)
  {
    $this->db->where('Governorate_ID', $governorate_id);
    $city = $this->db->get('cities');
    if ($city->num_rows() > 0) {
      return $city->result();
    } else {
      return false;
    }
  }
  // ------------------------------------------------------------------------

}

/* End of file Geo_model_model.php */
/* Location: ./application/models/Geo_model_model.php */
