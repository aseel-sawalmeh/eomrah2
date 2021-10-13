<?php defined('BASEPATH') or exit('No direct script access allowed');

class Handle_404 extends My_Controller
{
    public function index()
    {
        $data['title'] = "Error Please Report";
        $this->load->view('hotels/inc/header', $data);
        $this->load->view('errors/handle/err_404');
        $this->load->view('hotels/inc/footer');
    }
}
