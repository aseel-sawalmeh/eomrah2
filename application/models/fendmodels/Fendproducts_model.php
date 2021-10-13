<?php defined('BASEPATH') or exit('No direct script access allowed');

class Fendproducts_model extends CI_Model
{
    protected $tdb;
    public $login_error = TRUE;
    public function __construct(){
        parent::__construct();
        $this->tdb = $this->load->database('translatedb', TRUE);
    }
    
    public function featured_hotels(){
        //future filter for futured hotels
        $this->db->limit(8);
        $products = $this->db->get_where('hotels', "Main_Photo IS NOT NULL AND preferred=1");

        if($products && $products->num_rows() > 0){
            return $products->result();
        }else{
            return FALSE;
        }
    }
   
    public function city_hotels($city = null){
        //future filter for futured hotels
        $this->db->limit(16);
        $hotels = $this->db->get_where('hotels', "Hotel_City_ID = $city");
        if($hotels && $hotels->num_rows() > 0){
            return $hotels->result();
        }else{
            return FALSE;
        }
    }

    public function top_products(){
        $this->db->reset_query();
        $this->db->select('P_ID');
        $this->db->select('P_Name');
        $this->db->select('Description');
        $this->db->select('Product_Main_Photo as img');
        $this->db->join('products', 'position_block_items.Product_ID = products.P_ID');
        $this->db->where('Position_Block_ID', '1');
        $this->db->order_by('Item_Order', 'ASC');
        $products = $this->db->get('position_block_items');
        //show_error($products);
        if($products->num_rows() > 0){
            return $products->result();
        }else{
            return FALSE;
        }
    }

    public function fetch_data($pid)
    {
        //SELECT * FROM products INNER JOIN hotels ON products.P_Item_ID = hotels.Hotel_ID WHERE P_Category_ID = 1 AND Hotel_Name LIKE '%dam%'
        //SELECT * FROM `products` INNER JOIN `hotels` ON `products`.`P_Item_ID` = `hotels`.`Hotel_ID` WHERE `P_Category_ID` = 1 AND `Hotel_Name` LIKE '%mos%'
    
        $this->db->join('hotels', 'products.P_Item_ID = hotels.Hotel_ID', 'inner');
        $this->db->where('P_Category_ID', 1);
        $this->db->where('products.P_ID', $pid);
        $products = $this->db->get('products')->row();
        return $products;
    }

    public function cat_products($catid){
        $this->db->select('*');
        $this->db->select('Product_Main_Photo as img');
        $this->db->where('P_Category_ID', $catid);
        $this->db->order_by('P_ID', 'DESC');
        return $this->db->get('products')->result();
    }
    public function fprod($pid){
        $this->db->where('P_ID', $pid);
        $prod = $this->db->get('Products');
        if($prod->num_rows() > 0){
            return $prod->row();
        }else{
            return FALSE;
        }
    }
}