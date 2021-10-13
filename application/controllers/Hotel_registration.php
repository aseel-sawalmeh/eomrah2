<?php defined('BASEPATH') or exit('No direct script access allowed');

class Hotel_registration extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Hotel_Registeration_model', 'hrm');
        $this->load->library('form_validation');
        $this->load->model('Users_Mails_model');
        $this->load->model('Product_Categories_model');
        $this->load->model('fendmodels/fendproducts_model');
        $this->load->model('geo_model');
    }

    public function index()
    {
        $data['title'] = lang('b_reg');
        $this->load->library('form_validation');
        $this->lang->load('form_validation', userlang());
        $this->form_validation->set_rules('FullName', lang('fullname'), 'required');
        $this->form_validation->set_message('FullName', 'This user already exist');
        $this->form_validation->set_rules('UserName', lang('uname'), 'required|callback_username_check');
        $this->form_validation->set_rules('Password', lang('pass'), 'required');
        $this->form_validation->set_rules('Passwordconfirm',lang('confirm').' '.lang('pass'), 'required|matches[Password]');
        $this->form_validation->set_rules('Email', lang('em'), 'required|valid_email|callback_check_email');
        $this->form_validation->set_rules('Phone', lang('contact'), 'required');
        $this->form_validation->set_rules('businesstype',lang('bt'), 'required');
        //$this->form_validation->set_rules('HotelName', 'Hotel Name', 'required|callback_hotel_check');
        //$this->form_validation->set_rules('HotelDescription', 'Hotel Descritpion', 'required|callback_hotel_check');
        //$this->form_validation->set_rules('country', 'Country Name', 'required');
        //$this->form_validation->set_rules('governorate', 'governorate Name', 'required');
        //$this->form_validation->set_rules('region', 'region Name', 'required');
        //$data['countries'] = $this->geo_model->get_countries();
        if ($this->form_validation->run() == false) { 
            $this->render_view(['hotels/b_register'], $data);
        } else {
            if ($this->input->post('businesstype') == 1) {
                //Dealing with Hotel Owner Registeration
                if ($this->hrm->chain_register_hotel_user()) {
                    if ($this->Users_Mails_model->sendemail($this->input->post('Email'))) {
                        $success_message=lang("hotelemailverify");
                        $this->session->set_flashdata('b_register', "<h4 class='text-success' style='text-align:center;'>$success_message</h4>");
                    } else {
                        $failed_message=lang("hotelemailfailed");
                        $this->session->set_flashdata('b_register', "<h4 class='text-danger' style='text-align:center;'> $failed_message</h4>");
                    }
                    $this->load->view('hotels/inc/header', $data);
                    $this->load->view('hotels/business_reg');
                } else {
                    $reg_trouble = lang("troubleregistering");
                    $this->session->set_flashdata('b_register', "<h4 class='text-danger' style='text-align:center;'> $reg_trouble</h4>");
                    $this->load->view('hotels/inc/header', $data);
                    $this->load->view('hotels/business_reg');
                }
            }
        }
    }

    public function username_check($hu_name)
    {
        if ($this->hrm->checkhUserExist($hu_name)) {
            if ($this->input->is_ajax_request()) {
                $res = ['error' => 'This user already exist'];
                $this->toolset->jsonfy($res);

            }
            $this->form_validation->set_message('username_check', 'This user already exist');
            return false;
        } else {
            return true;
        }
    }

    public function ajusername_check()
    {
        $hu_name = $this->input->get('name');
        if ($this->hrm->checkhUserExist($hu_name)) {
            $res = ['error' => 'This user is already exist'];
            $this->toolset->jsonfy($res);
        }
    }

    public function ajemail_check()
    {
        $hmail = $this->input->get('email');
        if ($this->hrm->checkhmailExist($hmail)) {
            $res = ['error' => 'This email already exist'];
            $this->toolset->jsonfy($res);
        }
    }

    public function check_email($email)
    {
        if ($this->hrm->checkhmailExist($email)) {
            $this->form_validation->set_message('check_email', 'This email is already Taken');
            return false;
        } else {
            return true;
        }
    }

    public function aj_country_cities()
    {
        $country_id = $this->input->get('country');
        $this->load->model('geo_model');
        $res = [];
        if ($this->geo_model->get_country_governorates($country_id)) {
            foreach ($this->geo_model->get_country_governorates($country_id) as $governorate) {
                $res['status'] = true;
                $res['city'][] = ['id' => $governorate->Governorate_ID, 'name' => $governorate->Governorate_Name];
            }
        } else {
            $res['status'] = false;
        }
        $this->toolset->jsonfy($res);
    }

    public function aj_country_regions()
    {
        $this->load->model('geo_model');
        $res = [];
        $governorate_id = $this->input->get('city');
        if ($this->geo_model->get_governorate_cities($governorate_id)) {
            foreach ($this->geo_model->get_governorate_cities($governorate_id) as $city) {
                $res['status'] = true;
                $res['city'][] = ['id' => $city->City_ID, 'name' => $city->City_Name];
            }
        } else {
            $res['status'] = false;
        }
        $this->toolset->jsonfy($res);
    }
}
