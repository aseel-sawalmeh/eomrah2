<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Currencies_model extends CI_Model
{

    public function __costruct()
    {
        parent::__construct();
    }
  

    public function get_all()
    {
        return $this->db->get("currencies")->result();
    }

}
