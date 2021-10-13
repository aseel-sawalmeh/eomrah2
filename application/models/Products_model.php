<?php
/**
 * This File Holds the products model Class
 * php version 7.3
 */
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @Author Gebriel Alkhayal
 */
class Products_model extends CI_Model
{
    public $login_error = true;
    public function __construct()
    {
        parent::__construct();
    }
    public function add_product($product_photo)
    {
        $p_name = $this->input->post('product_name');
        if(userlang() == 'ar'){
            $text = trans($p_name, 'ar','en');
            $slug = explode(' ', $text);
            $slug1 = implode('-', $slug);
        }else{
            $slug = explode(' ', $this->input->post('product_name'));
            $slug1 = implode('-', $slug);
        }
        $registered_by = html_escape($this->input->post('Registered_By'));
        $product_cat = html_escape($this->input->post('pcat'));
        $p_state = html_escape($this->input->post('product_state'));
        $product_desc = $this->input->post('product_desc');
        $product_data = array(
            'P_Reference' => "gman",
            'P_Name' => $p_name,
            'Registered_By' => $registered_by,
            'P_Category_ID' => $product_cat,
            'P_Item_ID' => 0,
            'Product_Main_Photo' => $product_photo,
            'b_slug' => $slug1,
            'State' => $p_state,
            'Description' => $product_desc,
        );
        $this->db->set($product_data);
        if ($this->db->insert('products')) {
            $insert_id = $this->db->insert_id();
            return $insert_id;
         
        } else {
            show_error($this->db->last_query());
            die;
          
        }
    }

    public function edit_product($product_id, $product_photo)
    {
        $p_name =$this->input->post('product_name');
        if(userlang() == 'ar'){
            $text = trans($p_name, 'ar','en');
            $slug = explode(' ', $text);
            $slug1 = implode('-', $slug);
        }else{
            $slug = explode(' ', $this->input->post('product_name'));
            $slug1 = implode('-', $slug);
        }
       
        $registered_by = html_escape($this->input->post('Registered_By'));
        $product_cat = html_escape($this->input->post('pcat'));
        $p_state = html_escape($this->input->post('product_state'));
     
        $product_desc = $this->input->post('product_desc');
        if (!$product_photo) {
            $product_data = array(
                'P_Reference' => "gman",
                'P_Name' => $p_name,
                'Registered_By' => $registered_by,
                'P_Category_ID' => $product_cat,
                'P_Item_ID' => 0,
                'State' => $p_state,
                'Description' => $product_desc,
                'b_slug'=> $slug1
            );
        } else {
            $product_data = array(
                'P_Reference' => "gman",
                'P_Name' => $p_name,
                'Registered_By' => $registered_by,
                'P_Category_ID' => $product_cat,
                'P_Item_ID' => 0,
                'Product_Main_Photo' => $product_photo,
                'State' => $p_state,
                'Description' => $product_desc,
            );
        }
        
        $this->db->where('P_ID', $product_id);
        if ($this->db->update('products',$product_data)) {
            return true;
           
        } else {
            show_error($this->db->last_query());
            die;
        }
    }

    public function get_that_product($product_id)
    {
        $this->db->where('P_ID', $product_id);
        $query = $this->db->get('products');
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function get_product_by_hotel($hotel_id)
    {
        $this->db->where('P_Item_ID', $hotel_id);
        $query = $this->db->get('products');
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function selects_product()
    {
        $this->db->select('P_Name');
        $this->db->select('P_ID');
        $query = $this->db->get('products');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getproducts()
    {
        $query = $this->db->get('products');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_subproducts($main_product_id)
    {
        $this->db->select('P_Name');
        $this->db->select('ID');
        $this->db->select('multi_products.Order');
        $this->db->order_by('Order', 'ASC');
        $this->db->join('products', 'multi_products.Subproduct_ID = products.P_ID');
        $this->db->where('Product_ID', $main_product_id);
        $sub_products = $this->db->get('multi_products');
        if ($sub_products->num_rows() > 0) {
            return $sub_products->result();
        } else {
            return false;
        }
    }
    public function sub_product_add($main_product_id, $subproduct_id)
    {
        $this->db->where('Product_ID', $main_product_id);
        $sub_products = $this->db->get('multi_products');
        if ($sub_products->num_rows() > 0) {
            $count_order = $sub_products->num_rows();
            $order = $count_order + 1;
        } else {
            $order = 1;
        }
        $add_sub = array('Product_ID' => $main_product_id, 'Subproduct_ID' => $subproduct_id, 'Order' => $order);
        if ($this->db->insert('multi_products', $add_sub)) {
            return true;
        } else {
            return false;
        }
    }

    public function sub_prod_order($subproduct_id, $order)
    {
        $this->db->set('Order', $order);
        $this->db->where('ID', $subproduct_id);
        if ($this->db->update('multi_products')) {
            return true;
        } else {
            return false;
        }
    }
    public function sub_product_delete($sub_product_id)
    {
        $this->db->where('ID', $sub_product_id);
        if ($this->db->delete('multi_products')) {
            return true;
        } else {
            return false;
        }
    }
    public function get_that_cat_products($cat_id)
    {
        $this->db->where('P_Category_ID', $cat_id);
        $query = $this->db->get('products');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checkproductExist($product_id)
    {
        $this->db->where('P_ID', $product_id);
        $query = $this->db->get('products');
        if ($query->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }
    public function products_count()
    {
        return $this->db->count_all("products");
    }
    public function active_products_count()
    {
        $this->db->where('State', 1);
        $active = $this->db->get("products");
        return $active->num_rows();
    }
    public function inactive_products_count()
    {
        $this->db->where('State', 0);
        $active = $this->db->get("products");
        return $active->num_rows();
    }
    public function fetch_products($limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get("products");
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }
    public function get_active_products()
    {
        $this->db->where('State', 1);
     
        $query = $this->db->get("products");
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }
    public function get_active_allproducts()
    {
        $this->db->where('State', 1);
        $query = $this->db->get("products");
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }

    public function get_inactive_products()
    {
        $this->db->where('State', 0);
        
        $query = $this->db->get("products");
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }
}
