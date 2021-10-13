<?php defined('BASEPATH') or exit('No Direct Access Allowed');

class Logout extends MY_Controller
{
    public function index()
    {
        $this->session->sess_destroy();
        redirect('chotel/login');
    }
}
