<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends  MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('b2b/Users_model', 'b2b_users');
        $this->load->library('form_validation');
    }


    public function index()
    {
        $this->lang->load('form_validation', userlang());
        $data['countries'] = $this->geo->get_countries();

        $data['title'] = lang('b2bregisteration')? lang('b2bregisteration'):'B2B registration';

        $this->form_validation->set_rules('company_email', lang('em'), 'required|callback_email_check');
        $this->form_validation->set_rules('company_name', lang('companyname'), 'required|callback_name_check');
        $this->form_validation->set_rules('iban', lang('iban'), 'required|callback_iban_check');
        $this->form_validation->set_rules('reg_number', lang('regnumber'), "required|callback_registration_check");


        $this->form_validation->set_rules('first_name', lang('first_name'), 'required');
        $this->form_validation->set_rules('last_name', lang('last_name'), 'required');

        $this->form_validation->set_rules('country', lang('country'), 'required');
        $this->form_validation->set_rules('mobile_number', lang('mobilenumber'), 'required');
        $this->form_validation->set_rules('office_number', lang('phone_number'), 'required');


        $this->form_validation->set_rules('password', lang('pass'), 'required');
        $this->form_validation->set_rules('password_confirm', lang('pass'), 'required|matches[password]');
        $this->form_validation->set_rules('vat_number', lang('vat_num'), 'required');

        $this->form_validation->set_rules('hajj_license', lang('license'));
        $this->form_validation->set_error_delimiters("<p class='text-danger'>", "</p>");

        if ($this->form_validation->run() === false) {
            $this->load->view("hotels/inc/header", $data);
            $this->load->view("hotels/b2b/register");
            $this->load->view("hotels/inc/footer");
        } else {
            $hashed_password = md5($this->input->post('password'));
            $useremail = $this->input->post('company_email');
            $data['name'] = $this->input->post('company_name');
            $data['msg'] = 'Eomrah B2b Registration';
           
            if ($this->b2b_users->register($hashed_password)) {
                $success = lang("b2b_reg_successfull");
                $this->session->set_flashdata('b2b_registerd', "<h4 class='text-success text-center'>$success</h4>");
                $this->toolset->sendemail($useremail, "B2b Register", 'b2bemail', $data);
            } else {
                $failed = lang("b2b_reg_failed");
                $this->session->set_flashdata('b2b_registerd', "<h4 class='text-danger text-center'> $failed</h4>");
            }
            $this->load->view('hotels/b2b_reg');
        }
    }

    public function email_check($email = null)
    {

        if ($this->input->get('email')) {
            $email = $this->input->get('email');

            if ($this->b2b_users->checkemail($email)) {
                $this->toolset->jsonfy(['error' => "This Email Is Already Taken"]);
            } else {
                $this->toolset->jsonfy(['error' => false]);
            }
        } else {
            if ($this->b2b_users->checkemail($email)) {

                return false;
            } else {

                return true;
            }
        }
    }

    public function name_check($name = null)
    {
        if ($this->input->get('name')) {
            $name = $this->input->get('name');
            if ($this->b2b_users->check_name($name)) {
                $this->toolset->jsonfy(['error' => "This Company Name Already Exist"]);
            } else {
                $this->toolset->jsonfy(['error' => false]);
            }
        } else {
            if ($this->b2b_users->check_name($name)) {

                return false;
            } else {

                return true;
            }
        }
    }

    public function iban_check($iban = null)
    {
        if ($this->input->get('iban')) {
            $iban = $this->input->get('iban');
            if ($this->b2b_users->check_iban($iban)) {
                $this->toolset->jsonfy(['error' => "This Iban Already Exist"]);
            } else {
                $this->toolset->jsonfy(['error' => false]);
            }
        } else {
            if ($this->b2b_users->check_iban($iban)) {

                return false;
            } else {

                return true;
            }
        }
    }


    public function registration_check($reg_no = null)
    {
        if ($this->input->get('reg_no')) {
            $reg_no = $this->input->get('reg_no');
            if ($this->b2b_users->check_registration($reg_no)) {
                $this->toolset->jsonfy(['error' => "This Registration Number Already Exist"]);
            } else {
                $this->toolset->jsonfy(['error' => false]);
            }
        }else{
            if ($this->b2b_users->check_registration($reg_no)) {

                return false;
            } else {

                return true;
            }
        }
    }
}
