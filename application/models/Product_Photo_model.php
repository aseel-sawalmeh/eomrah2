<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

class Product_Photo_model extends CI_Model
{

    public function __construct(){
        parent::__construct();
    }

    public function add_product_photos($data = array()){
        $insert = $this->db->insert_batch('product_photos', $data);
        return $insert?true:false;
    }
   
    public function add_product_photo($product_id, $product_photo, $i){
        $dataimg = array(
        'Product_ID' => $product_id,
        'Photo_Name' => $product_photo,
        'Photo_Order' => $i
        );
        $insert = $this->db->insert('product_photos', $dataimg);
        return $insert?true:false;
    }
    
    public function get_that_product_photos($product_id){
        $this->db->where('Product_ID', $product_id);
        $this->db->order_by('Photo_Order', 'ASC');
        $query  = $this->db->get('product_photos');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }
    public function product_photo_count($product_id){
        $this->db->where('Product_ID', $product_id);
        $exist = $this->db->get('product_photos');
        return $exist->num_rows();
    }
    public function product_photo_order($photo_id, $order){
        $this->db->set('Photo_Order', $order);
        $this->db->where('Photo_ID', $photo_id);
        if($this->db->update('product_photos')){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    public function product_photo_delete($photo_id){
        $this->db->where('Photo_ID', $photo_id);
        if($this->db->delete('product_photos')){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}