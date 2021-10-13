<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blog_model extends CI_Model{
    public function __construct(){
        parent::__construct();
    }

    public function home_blogs()
    {
        $this->db->where('P_Category_ID', 8);
        $this->db->where('state', 1);
        $this->db->limit(3);
        $query = $this->db->get('products');
        if($query){
            return $query->result();
        }else{
            return false;
        }
    }
    public function get_blog()
    {
        $this->db->where('P_Category_ID', 8);
        $this->db->where('state', 1);
        $query = $this->db->get('products');
        if($query){
            return $query->result();
        }else{
            return false;
        }
    }

    public function get_blog_id()
    {
        $this->db->where('P_Category_ID', 8);
        $this->db->where('state', 1);
        $query = $this->db->get('products');
        if($query){
            return $query->row()->P_ID;
        }else{
            return false;
        }
    }

    public function get_blog_detail($slug)
    {
        $this->db->where('b_slug', $slug);
        $query =  $this->db->get('products');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }

    public function featured_blog()
    {
        $this->db->order_by('P_ID','desc');
        $this->db->limit(1);
       $query =  $this->db->get('products');
       if($query->num_rows() > 0)
       {
           return $query->row();
       }else{
           return false;
       }
    }
}