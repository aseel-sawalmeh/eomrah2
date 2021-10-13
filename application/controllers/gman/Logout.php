<?php defined('BASEPATH') OR exit ('No Direct Access Allowed');

class Logout extends Gman_Controller{
        public function index(){
        $this->session->sess_destroy();
        redirect('gman/login');
    }
}