<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends  MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('b2b/Users_model', 'b2b_users');
        $this->load->helper('cookie');
        $this->load->library('form_validation');
    }

    public function index()
    {

        $this->form_validation->set_rules('company_email', lang("em"), 'required|callback_login_check');
        $this->form_validation->set_rules('company_password', lang("pass"), 'required|callback_pass_check');
        $this->form_validation->set_error_delimiters('<div style="color:red">', '</div>');
        $data['title'] = "B2b System Login";
        if (!$this->session->userdata("b2bvalidated")) {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('hotels/inc/header', $data);
                $this->load->view('hotels/b2b/login');
                $this->load->view('hotels/inc/footer');
            } else {
                set_cookie('b2b_prices',md5($this->session->userdata('C_FullName')),80234);
                $this->b2b_users->validate();
                
                redirect('b2b');
            }
        } else {
            redirect('b2b');
        }
    }


    public function login_check($email)
    {

        if ($this->b2b_users->check_login($email)) {
            return true;
        } else {
            return false;
        }
    }

    public function pass_check($pass)
    {

        if ($this->b2b_users->check_pass($pass)) {
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        delete_cookie('b2b_prices');
        redirect("b2b");
    }
}
