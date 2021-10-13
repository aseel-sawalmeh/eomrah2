<?php defined('BASEPATH') or exit('No direct script access allowed');

class Multimedia_model extends CI_Model
{
    public $login_error = TRUE;
    public function __construct(){
        parent::__construct();
    }

    public function get_multimedia_cats()
    {
        $multimedia_cats = $this->db->get('multimedia_categories');
        if($multimedia_cats->num_rows() > 0){
            return $multimedia_cats->result();   
        }else{
            return FALSE;
        }
        
    }
    public function get_product_multimedia($product_id)
    {
        $this->db->join('multimedia_categories', 'product_multimedia.MultiMedia_Category_ID = multimedia_categories.MC_ID');
        $this->db->where('M_Product_ID', $product_id);
        $users = $this->db->get('product_multimedia');
        if($users->num_rows() > 0){
        return $users->result();
        }else{
            return FALSE;
        }
    }
    public function set_product_mediadata($product_id)
    {
        $media_text = html_escape($this->input->post('mediatext'));
        $media_cat = $this->input->post('mediacategory');
        $exist = $this->db->get_where('product_multimedia', array('M_Product_ID'=> $product_id, 'MultiMedia_Category_ID'=>$media_cat));
        //check if there is any data for this cat just update else do insert
        if($exist->num_rows() == 1){
            //exist found so update
            $media = array('Cat_Val'=> $media_text);
            $this->db->where('M_Product_ID', $product_id);
            $this->db->where('MultiMedia_Category_ID', $media_cat);
            if($this->db->update('product_multimedia', $media)){
                return TRUE;
            }else{
                return FALSE;
            }
        }elseif($exist->num_rows() > 1){
            $media = array('M_Product_ID'=> $product_id, 'MultiMedia_Category_ID'=>$media_cat, 'Cat_Val'=> $media_text);
            $this->db->where('M_Product_ID', $product_id);
            $this->db->where('MultiMedia_Category_ID', $media_cat);
            if($this->db->delete('product_multimedia')){
                if($this->db->insert('product_multimedia', $media)){
                    return TRUE;
                }else{
                    return FALSE;
                }
            }else{
                return FALSE;
            }
        }else{
        $media = array('M_Product_ID'=> $product_id, 'MultiMedia_Category_ID'=>$media_cat, 'Cat_Val'=> $media_text);
        if($this->db->insert('product_multimedia', $media)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
}
?>