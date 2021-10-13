<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * Controller Provider Eomrah
 *
 * This controller for Maintain Eomrah Providers settings
 * This code is ownded by Eomrah you have no rights to copy or redistribute.
 * @package   CodeIgniter
 * @category  Controller
 * @author    Gebriel Alkhayal
 *
 */

class Provider extends Hotel_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('provider_model', 'pm');
        $this->load->model('HotelSysUsers_model');
        $this->load->library('form_validation');
        $this->load->model('hotel_model');
        $this->load->model('geo_model');
    }

    public function active_list()
    {

        $data['title'] = "Hotel List Management";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'chotel/provider/active_list';
        $config['total_rows'] = $this->pm->active_providers_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] ='</li>';
        $config['next_link'] = '<span class="page-link">Next</span>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<span class="page-link">Prev</span>';
        $config['first_link'] = '<li class="page-item"><span class="page-link">First</span></li>';
        $config['last_link']  = '<li class="page-item"><span class="page-link">Last</span></li>';
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $data['active_providers'] = $this->pm->fetch_active_provider($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links();
        $this->render_view('hotels/chotel/providers/active_list', $data);
    }

    public function search_hotels()
    {
        $search = [];
        $search['status'] = false;
        $find = html_escape($this->input->get('text'));
        if (!$this->session->userdata('Suser')) {
            $results = $this->pm->provider_result($find);
        } else {
            $results = $this->pm->provider_list($find);
        }

        foreach ($results as $res) {
            $search['status'] = true;
            $str = "<td>" . $this->HotelSysUsers_model->get_that_h_user($res->HuserID)->H_UserName . "</td>";
            $str .= "<td>" . $res->Hotel_Name . "</td>";
            $str .= "<td>" . $res->Hotel_Address . "</td>";
            $str .= "<td>" . $res->Hotel_Phone . "</td>";
            $str .= "<td>" . $res->Hotel_Email . "</td>";
            $str .= "<td>" . "Active" . "</td>";
            if (!$this->session->userdata('Suser')) {
                $str .= "<td>" . "<a class='btn btn-outline-danger' href='" . base_url("chotel/provider/delete_request/$res->Provider_ID") . "'>Delete</a> <a class='btn btn-outline-success' href='" . base_url("chotel/hotel_manage/comset/$res->Provider_ID") . "'>
          Pricing and Availability</a></td></tr>";
            } else {
                $str .= "<td>" . "<a class='btn btn-outline-danger' href='" . base_url("gman/providers/admin_delete/$res->Provider_ID") . "'>Delete</a>";
                $str .= "<a href='" . base_url("gman/providers/provider_stats/$res->HuserID") . "'>Details</a></td></tr>";
            }

            $search['data'][] = $str;
        }
        $this->toolset->jsonfy($search);
    }

    public function pending_list()
    {
        $data['title'] = "Hotel List Management";
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'chotel/provider/pending_list';
        $config['total_rows'] = $this->pm->inactive_providers_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] ='</li>';
        $config['next_link'] = '<span class="page-link">Next</span>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<span class="page-link">Prev</span>';
        $config['first_link'] = '<li class="page-item"><span class="page-link">First</span></li>';
        $config['last_link']  = '<li class="page-item"><span class="page-link">Last</span></li>';
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['inactive_providers'] = $this->pm->fetch_inactive_provider($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links();
        $this->render_view('hotels/chotel/providers/pending_list', $data);
    }

    public function activate($provider_id)
    {
        $this->form_validation->set_rules('markup', 'markup Value', 'required');
        $this->form_validation->set_rules('discount', 'discount Value', 'required');
        $data['title'] = "Hotel Activation";
        $data['admin_user_id'] = $this->session->userdata('Admin_User_ID');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/chotel/providers/activate', $data);
        } else {
            if ($this->pm->activate($provider_id)) {
                $this->session->set_flashdata('provider_mgs', " The Hotel Activated SuccessFully ");
                if (!$this->session->userdata('Suser')) {
                    redirect('chotel/provider/active_list');
                } else {
                    redirect('gman/providers/active_list');
                }
            } else {
                $this->session->set_flashdata('provider_mgs', " An Error Happened While the activation process Please Send an error report ");
                if (!$this->session->userdata('Suser')) {
                    redirect('chotel/provider/pending_list');
                } else {
                    redirect('gman/providers/pending_list');
                }

            }
        }
    }

    public function add_provider()
    {
        $this->form_validation->set_rules('hotels', 'hotels selection', 'required');
        $data['title'] = "Hotel Activation";
        $data['hotels'] = $this->hotel_model->get_active_hotels();
        $data['activate_for'] = (!$this->session->userdata('Suser')) ? $this->session->userdata('H_User_ID') : false;
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/chotel/providers/add_provider', $data);
        } else {
            if ($this->pm->request_list()) {
                $this->session->set_flashdata('provider_mgs', " The Hotel added To Your List SuccessFully ");
                redirect('chotel/provider/pending_list');
            } else {
                $this->session->set_flashdata('provider_mgs', " An Error Happened While requesting the hotel providing Please Send an error report ");
                redirect('chotel/provider/pending_list');
            }
        }
    }

    public function hotel_inform()
    {
        $this->form_validation->set_rules('hotelName', 'hotel Name', 'required');
        $this->form_validation->set_rules('hotelCountry', 'Country', 'required');
        $this->form_validation->set_rules('hotelCity', 'City', 'required');
        $this->form_validation->set_rules('hotelRegion', 'Region', 'required');
        $this->form_validation->set_rules('hotelPhone', 'Phone Number', 'required');
        $this->form_validation->set_rules('hotelEmail', 'Email', 'required');
        $this->form_validation->set_rules('hotelAddress', 'Hotel Adress', 'required');
        $this->form_validation->set_rules('hotelDescription', 'Description', 'required');
        $this->form_validation->set_error_delimiters('<div style="text-align:center;color:red;">', '</div>');
        $data['title'] = "Hotel Request Add";
        $data['hotels'] = $this->hotel_model->get_active_hotels();
        $data['informedBy'] = $this->session->userdata('H_User_ID');
        $data['countries'] = $this->geo_model->get_countries();
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/chotel/providers/hotel_inform', $data);
        } else {
            if ($this->pm->hotelRequest()) {
                $this->session->set_flashdata('hotelinform_mgs', " The Hotel Requested added SuccessFully, We Appreciate your share ");
                redirect('chotel/hotel/pending_requests');
            } else {
                $this->session->set_flashdata('hotelinform_mgs', "An Error Happened While requesting the hotel adding Please Send an error report");
                redirect('chotel/hotel/pending_requests');
            }
        }
    }

    public function delete_request($provider_id)
    {
        if ($this->pm->delete_request($provider_id)) {
            $this->session->set_flashdata('provider_mgs', "Your delete request sumbited SuccessFully");
            redirect('chotel/provider/active_list');
        } else {
            $this->session->set_flashdata('provider_mgs', "An Error Happened while requesting delete providing Please Send an error report");
            redirect('chotel/provider/active_list');
        }
    }

    public function cancel_delete($provider_id)
    {
        if ($this->pm->cancel_delete($provider_id)) {
            $this->session->set_flashdata('provider_mgs', " Your delete request Canceled SuccessFully ");
            redirect('chotel/provider/active_list');
        } else {
            $this->session->set_flashdata('provider_mgs', " An Error Happened Please Send an error report ");
            redirect('chotel/provider/active_list');
        }
    }

    public function confirm_delete_request($provider_id)
    {
        if ($this->pm->confirm_delete_request($provider_id)) {
            $this->session->set_flashdata('provider_mgs', " Provider deleted successfully SuccessFully ");
            redirect('gman/providers/active_list');
        } else {
            $this->session->set_flashdata('provider_mgs', "An Error Happened Please Send an error report");
            redirect('gman/providers/active_list');
        }
    }

    public function delete($provider_id)
    {
        if ($this->pm->delete($provider_id)) {
            $this->session->set_flashdata('provider_mgs', "The Hotel Removed From Your List SuccessFully");
            redirect('chotel/provider/pending_list');
        } else {
            $this->session->set_flashdata('provider_mgs', "An Error Happened Please Send an error report");
            redirect('chotel/provider/pending_list');
        }
    }

    public function aj_country_cities($country_id)
    {
        if ($this->geo_model->get_country_governorates($country_id)) {
            foreach ($this->geo_model->get_country_governorates($country_id) as $governorate) {
                echo "<option value='$governorate->Governorate_ID'>$governorate->Governorate_Name</option>";
            }
        } else {
            echo "<option> No Result </option>";
        }
    }

    public function aj_country_regions($governorate_id)
    {
        if ($this->geo_model->get_governorate_cities($governorate_id)) {
            foreach ($this->geo_model->get_governorate_cities($governorate_id) as $city) {
                echo "<option value='$city->City_ID'>$city->City_Name</option>";
            }
        } else {
            echo "<option> No Result </option>";
        }
    }

}
