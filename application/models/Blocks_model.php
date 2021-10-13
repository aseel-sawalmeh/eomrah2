<?php defined('BASEPATH') or exit('No direct script access allowed');
class Blocks_model extends CI_Model
{
    public $login_error = TRUE;
    public function __construct(){
        parent::__construct();
    }

    public function get_blocks(){
        $providers = $this->db->get('position_block');
        if($providers->num_rows() >0 ){
            return $providers->result();
        }else{
            return FALSE;
        }
    }

    public function block_items($block_id){
        $this->db->select('ID');
        $this->db->select('Item_Order');
        $this->db->select('products.P_Name');
        $this->db->join('products', 'position_block_Items.Product_ID = Products.P_ID', 'left');
        $this->db->where('Position_Block_ID', $block_id);
        $this->db->order_by('Item_Order', 'ASC');
       $providers = $this->db->get('position_block_items');
//show_error(print_r($this->db->get_compiled_select('position_block_items')));
if($providers->num_rows() > 0 ){
            return $providers->result();
        }else{
            return FALSE;
        }
    }
    public function get_products(){
        $this->db->select('P_ID');
        $this->db->select('P_Name');
        $providers = $this->db->get('products');
        if($providers->num_rows() >0 ){
            return $providers->result();
        }else{
            return FALSE;
        }
    }  
    public function set_max($block_id, $max){
        $this->db->set('Max_Items', $max);
        $this->db->where('Block_ID', $block_id);
        if($this->db->update('position_block')){
            return TRUE;
        }else{
            return FALSE;
        }
    }  
    public function set_order($prodid, $order){
        $this->db->set('Item_Order', $order);
        $this->db->where('ID', $prodid);
        if($this->db->update('position_block_items')){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    public function get_block_limit($block_id){
        $this->db->select('Max_Items');
        $this->db->where("Block_ID", $block_id);
        $items = $this->db->get('position_block');
        if($items->num_rows() > 0){
            $max = $items->row();
            return $max->Max_Items;
        }else{
            return FALSE;
        }
    }
    public function add_item($block_id, $prodid){
        $this->db->where("Position_Block_ID", $block_id);
        $items = $this->db->get('position_block_items');
        if($items->num_rows() >= $this->get_block_limit($block_id)){
            return False;
            exit;
        }elseif($items->num_rows() > 0){
            $count = $items->num_rows + 1;
        }else{
            $count = 0 + 1;
        }
        $this->db->set('Product_ID', $prodid);
        $this->db->set('Position_Block_ID', $block_id);
        $this->db->set('Item_Order', $count);
        if($this->db->insert('position_block_items')){
            return TRUE;
        }else{
            return FALSE;
        }
    }   
    public function del_item($itemid){
        $this->db->where("ID", $itemid);
        if($this->db->delete('position_block_items')){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
?>
