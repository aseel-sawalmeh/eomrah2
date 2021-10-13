<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
* Category Controller alkhayal ver.CodeiGniter
* G.A
*/

class Category extends Front_Controller
{
    public function list($catid)
    {
        initlang();
        $this->load->model('fendmodels/fendproducts_model');
        $this->load->model('Product_Categories_model');
        $this->load->model('Products_model');
        $this->load->library('toolset');
        $data['langs'] = langs();
        $data['title'] = comtrans('hotels');
        $data['list_hotels'] = $this->Products_model->get_active_allproducts(); 
        $data['products'] = $this->fendproducts_model->cat_products($catid);
        $data['category'] = $this->Product_Categories_model->get_that_Pcat($catid);
        $this->load->view('inc/header', $data);
        $this->load->view('inc/search_area');
        $this->load->view('category');
        $this->load->view('inc/footer');
    }

    public function getproductlist($catid)
    {
        initlang();
        $this->load->model('fendmodels/fendproducts_model');
        $products = $this->fendproducts_model->cat_products($catid);
        foreach($products as $product)
        {
            echo "<option>$product->P_Name</option>";
        }
    }

}