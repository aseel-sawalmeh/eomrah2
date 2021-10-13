<?php defined('BASEPATH') or exit('No direct script access allowed');

class Fphoto_model extends CI_Model
{
    protected $tdb;
    public $login_error = TRUE;
    public function __construct(){
        parent::__construct();
        $this->tdb = $this->load->database('translatedb', TRUE);
    }
    public function photos($prodid){
        $this->db->where('Product_ID', $prodid);
        $this->db->order_by('Photo_Order', 'ASC');
        $products = $this->db->get('product_photos');
        if($products->num_rows() > 0){
            return $products->result();
        }else{
            return FALSE;
        }
    }
}