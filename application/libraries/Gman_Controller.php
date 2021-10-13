<?php

class Gman_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_gvalidated();
    }
    
    public function check_gvalidated()
    {
        if (!$this->session->userdata('gvalidated')) {
            redirect('gman/login');
        }
    }

    public function render_view($view, $data)
    {
        $this->load->view('hotels/gman/inc/header', $data);
        $this->load->view("$view");
        $this->load->view('hotels/gman/inc/footer');

    }
}
?>
