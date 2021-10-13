<?php defined('BASEPATH') or exit('No direct script access allowed');

class Txtblock_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function terms()
    {
        $this->db->where('name', 'condterms');
        $products = $this->db->get('pages');
        if ($products->num_rows() > 0) {
            return $products->row();
        } else {
            return false;
        }
    }
}
