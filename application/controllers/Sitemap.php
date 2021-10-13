<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sitemap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reservation_model', 'rsm');
        $this->load->model('Product_Categories_model');
        $this->load->model('fendmodels/fendproducts_model');
        $this->load->model('fendmodels/fphoto_model');
        $this->load->model('translate/Translate_model');
        $this->load->model('Multimedia_model');
        $this->load->model('Hotel_model', 'ht');
        $this->load->model('Products_model');
        $this->load->model('User_model', 'pum');
        //$this->userLogged();
    }

    public function index()
    {
        $data['hotels'] = $this->ht->get_hotels();
        header("Content-type: text/xml");
        $this->load->view('sitemap', $data);
    }
}
