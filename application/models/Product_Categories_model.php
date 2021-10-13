<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_Categories_model extends CI_Model
{
    public $login_error = TRUE;
    public function __construct(){
        parent::__construct();
    }

    public function get_pcategories()
    {
        $this->db->reset_query();
        $this->db->where('P_Category_Parent', 0);
        return $this->db->get('product_category')->result();        
    }
 
    public function get_that_pcategory($id)
    {
        $this->db->reset_query();
        $this->db->where('P_Category_ID', $id);
        return $this->db->get('product_category')->result();        
    }   
    public function get_subcategories($parent_id)
    {
       $this->db->reset_query();
        $this->db->where('P_Category_Parent', $parent_id);
        return $this->db->get('product_category')->result();

    }
    //Adding New category
    public function add_Category($pcat_parent, $pcat_name, $pcat_desc, $pcat_order)
    {
        $user_data = array(
            'P_Category_Parent' => $pcat_parent,
            'P_Category_Name' => $pcat_name,
            'P_Category_Description' => $pcat_desc,
            'P_Category_Order' => $pcat_order
                          );
        $this->db->set($user_data);
        //check if the insertion done without any errors
        if($this->db->insert('product_category')){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    //edit New category
    public function edit_category($pcat_id)
    {
        //collect post data directly
        $p_cat_parent = html_escape($this->input->post('pcat_parent'));
        $p_cat_name = html_escape($this->input->post('pcat_name'));
        $p_cat_desc = html_escape($this->input->post('pcat_desc'));
        $p_cat_order = html_escape($this->input->post('pcat_order'));
        $category_data = array(
            'P_Category_Parent' => $p_cat_parent,
            'P_Category_Name' => $p_cat_name,
            'P_Category_Description' => $p_cat_desc,
            'P_Category_Order' => $p_cat_order
                          );
        $this->db->where('P_Category_ID', $pcat_id);
        //check if the insertion done without any errors
        if($this->db->update('product_category', $category_data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    

    public function get_that_Pcat($p_category_id)
    {

        $this->db->where('P_Category_ID', $p_category_id);
        // Run the query
        $query = $this->db->get('product_category');
        if($query->num_rows() == 1){
            return $query->row();
        }
    }
    

    public function checkPcatExist($p_category_id)
    {

        $this->db->where('P_Category_ID', $p_category_id);
        // Run the query
        $query = $this->db->get('product_category');
        if($query->num_rows() == 1){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function pcats_count() {
       return $this->db->count_all("product_category");
   }

   public function fetch_pcats($limit, $start) {
       $this->db->limit($limit, $start);
       $query = $this->db->get("product_category");
       if ($query->num_rows() > 0) {
        return $query->result();
       }
       return false;
   }
    //Pagination fetch
}
