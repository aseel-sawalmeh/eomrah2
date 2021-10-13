<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

class B2b_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Hotelcontrol_model', 'hc');
        $this->load->model('Count', 'cm');
        $this->check_hvalidated();
        if($this->session->userdata('b2bvalidated') == null){
            delete_cookie('eomrah_b2b_prices');
        }
        //$this->hasproperty();
        //$this->propertyinitialized();
    }

    public function check_hvalidated()
    {
        if (!$this->session->userdata('b2bvalidated')) {
            redirect('b2b/login');
        }
    }

    public function hasproperty()
    {
        if (!$this->hc->userhotels()) {
            redirect('chotel/huserinit');
        }
    }

    /*public function propertyinitialized()
    {
        // check the property settings
        // hotel main photo and sub photos
        $userhotels = $this->hc->userhotels();
        foreach ($userhotels as $uhotel) {
            if (!$this->hc->hotel_mainphoto($uhotel->Hotel_ID)) {
                $this->session->set_flashdata('media_error', "<h6 class='alert alert-danger text-center'>It seems That you forgot to Select property Main Image</h6>");
                redirect("chotel/huserinit/hotelmedia/$uhotel->Hotel_ID");
            }
            if (!$this->hc->hasProvider($uhotel->Hotel_ID)) {
                $this->session->set_flashdata('settin_error', "<h6 class='alert alert-danger text-center'>It seems That you forgot to complete you property Settings</h6>");
                redirect("chotel/huserinit/hotelsetting/$uhotel->Hotel_ID");
            }
        }
    }*/

    public function render_view($views, $data)
    {
        if($this->session->userdata('b2bvalidated') == true){
            $data['deposit'] = $this->cm->b2bdeposit($this->session->userdata('C_ID'));

        }else{
            show_error('eroor please check the base controller');
        }
       
        $this->load->view('hotels/b2b/inc/header', $data);
        if (is_array($views)) {
            foreach ($views as $view) {
                $this->load->view($view);
            }
        } else {
            $this->load->view("$views");
        }
        $this->load->view('hotels/b2b/inc/footer');

    }
}
