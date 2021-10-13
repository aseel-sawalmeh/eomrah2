<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * Controller Provider
 *
 * This controller for ...
 *
 * @package   CodeIgniter
 * @category  Controller
 * @author    Gebriel Alkhayal
 * @param     ...
 * @return    ...
 *
 */

class Providers extends Gman_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('provider_model', 'pm');
        $this->load->model('HotelSysUsers_model');
        $this->load->library('pagination');
        $this->load->model('Hotel_model');
        $this->load->library('form_validation');
        $this->load->model('geo_model');
    }

    public function index()
    {
        echo "Provider Home";
    }

    public function active_list()
    {
        $data['title'] = "Hotel List Management";

        $config['base_url'] = base_url() . 'gman/providers/active_list';
        $config['total_rows'] = $this->pm->active_providers_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
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
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/chotel/providers/active_list', $data);
    }

    public function provider_res($provider_id)
    {

        if ($provider_id != null) {
            $config['base_url'] = base_url() .  "gman/providers/provider_res/$provider_id";
            $config['uri_segment'] = 6;
            $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
        }
        $config['per_page'] = 5;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
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
        $data['title'] = "Hotel Reports";
        $data['reports'] = [];
        $paidres = [];
        $unpaidres = [];
        $totalpaidres = 0;
        $resvs = $this->pm->get_stats($provider_id);
        foreach ($resvs as $resv) {
            $data['reports'][] = $resv;
            if ($resv->Paid == 1) {
                $paidres[] = $resv;
                $totalpaidres += $resv->TotalPrice;
            } else {
                $unpaidres[] = $resv;
            }
        }
        $data['totalpaidres'] = $totalpaidres;
        $data['paidrescount'] = count($paidres);
        $data['unpaidrescount'] = count($unpaidres);
        $config['total_rows'] = count($data['reports']);
        $count_hotel = $this->pm->provider_hotellist_count($provider_id);
        $data['provider_id'] = $provider_id;
        $data['h_list'] =  $count_hotel;
        $data['reports'] = array_slice($data['reports'], $page, $config['per_page']);
        $this->pagination->initialize($config);
        $data['paginationlinks'] =  $this->pagination->create_links();
        $this->render_view('hotels/gman/p_reports', $data);
    }

    public function provider_hotels($hid)
    {
        $data['title'] = 'Provider Hotel List';
        $config['base_url'] = base_url() . 'gman/providers/provider_hotels/' . $hid;
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['total_rows'] =  $this->pm->provider_hotellist_count($hid);
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
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
        $data['provider_hotels'] = $this->pm->provider_hotellist($hid, $config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('gman/p_hotels_list', $data);
    }

    public function pending_list()
    {
        $data['title'] = "Hotel List Management";
        $config['base_url'] = base_url() . 'gman/providers/pending_list';
        $config['total_rows'] = $this->pm->inactive_providers_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
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
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/chotel/providers/pending_list', $data);
    }

    public function requested_hotel()
    {
        $data['title'] = "Hotel requests Management";
        $config['base_url'] = base_url() . 'chotel/hotel/pending_requests';
        $config['total_rows'] = $this->Hotel_model->requests_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
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
        $data['hotel_resquests'] = $this->Hotel_model->fetch_inactive_hotelreq($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/gman/requests', $data);
    }

    public function activate($provider_id)
    {
        $this->form_validation->set_rules('markup', 'markup Value', 'required');
        $this->form_validation->set_rules('discount', 'discount Value', 'required');
        $data['title'] = "Hotel Activation";
        $data['admin_user_id'] = $this->session->userdata('Admin_User_ID');
        $data['p_id'] = $provider_id;

        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/chotel/providers/activate', $data);
        } else {
            if ($this->pm->activate($provider_id)) {
                $this->session->set_flashdata('provider_mgs', "The Hotel Activated SuccessFully");
                redirect('gman/providers/active_list');
            } else {
                $this->session->set_flashdata('provider_mgs', "An Error Happened While the activation process Please Send an error report");
                redirect('gman/providers/pending_list');
            }
        }
    }

    public function add_provider()
    {
        $this->form_validation->set_rules('hotels', 'hotels selection', 'required');
        $data['title'] = "Hotel Activation";
        $data['hotels'] = $this->hotel_model->get_active_hotels();
        $data['activate_for'] = (!$this->session->userdata('Suser')) ? $this->session->userdata('H_User_ID') : FALSE;
        if ($this->form_validation->run() == false) {
            $this->render_view('chotel/providers/add_provider', $data);
        } else {
            if ($this->pm->request_list()) {
                $this->session->set_flashdata('provider_mgs', "The Hotel added To Your List SuccessFully");
                redirect('gman/providers/pending_list');
            } else {
                $this->session->set_flashdata('provider_mgs', "An Error Happened While requesting the hotel providing Please Send an error report");
                redirect('gman/providers/pending_list');
            }
        }
    }

    public function delete_request($provider_id)
    {

        if ($this->pm->delete_request($provider_id)) {
            $this->session->set_flashdata('provider_mgs', "Your delete request sumbited SuccessFully");
            redirect('gman/providers/active_list');
        } else {
            $this->session->set_flashdata('provider_mgs', "An Error Happened Please Send an error report");
            redirect('gman/providers/active_list');
        }
    }

    public function cancel_delete($provider_id)
    {
        if ($this->pm->cancel_delete($provider_id)) {
            $this->session->set_flashdata('provider_mgs', "Your delete request Canceled SuccessFully");
            redirect('gman/providers/active_list');
        } else {
            $this->session->set_flashdata('provider_mgs', "An Error Happened Please Send an error report");
            redirect('gman/providers/active_list');
        }
    }

    public function confirm_delete_request($provider_id)
    {
        if ($this->pm->confirm_delete_request($provider_id)) {
            $this->session->set_flashdata('provider_mgs', "Provider deleted successfully SuccessFully");
            redirect('gman/providers/active_list');
        } else {
            $this->session->set_flashdata('provider_mgs', "An Error Happened Please Send an error report");
            redirect('gman/providers/active_list');
        }
    }

    public function admin_delete($provider_id)
    {
        $goto = "gman/providers/active_list";
        $this->delete($provider_id, $goto);
    }

    public function delete($provider_id, $goto = null)
    {
        $goto2 = ($goto !== null) ? $goto : 'gman/providers/pending_list';
        if ($this->pm->delete($provider_id)) {
            $this->session->set_flashdata('provider_mgs', "The Hotel Removed From Your List SuccessFully");
            redirect($goto2);
        } else {
            $this->session->set_flashdata('provider_mgs', "An Error Happened Please Send an error report");
            redirect($goto2);
        }
    }



    public function search_res()
    {
        $paid = [];
        $paid['status'] = false;
        $res = $this->input->get('paid');
        $prvid = $this->input->get('hid');
        $paid['error'] = "No Result Found";
        $rps = $this->pm->searchp_fullstats($res, $prvid);
        if ($rps) {
            foreach ($rps as $rp) {
                if ($rp) {
                    $paid['status'] = true;
                    if($rp->b2b == 0){
                        $str = "<tr><td>".'<a target="_blank" href="'.site_url('gman/main/invoice_details/'.$rp->reservation_ref).'">' . $rp->reservation_ref . "</a></td>";
                    }else{
                        $str = "<tr><td>".'<a target="_blank" href="'.site_url('gman/main/b2b_details/'.$rp->reservation_ref).'">' . $rp->reservation_ref . "</a></td>";
                    }
                    $str .= '<td>' . $rp->ResDate . '</td>';
                    $str .= '<td>' . $rp->CheckInDate . '</td>';
                    $str .= '<td>' . $rp->CheckOutDate . '</td>';
                    $str .= '<td>' . $rp->TotalRoomCount . '</td>';
                    $str .= '<td>' . $rp->TotalPrice . '</td>';
                    if ($rp->Payment_method == 1) {
                        $str .= '<td class="bg-warning">Pay At Hotel</td>';
                    } else {
                        $str .= '<td class="bg-info">Online</td>';
                    }
                    if ($rp->Paid == 1) {
                        $str .= '<td class="bg-success">Paid</td>';
                    } else {
                        $str .= '<td class="bg-danger">Not Paid</td>';
                    }

                    if ($rp->b2b == 0) {
                        $str .= '<td class="bg-primary">B2C</td>';
                    } else {
                        $str .= '<td class="bg-secondary">B2B</td>';
                    }

                    if ($rp->confirm == 1) {
                        $str .= '<td class="bg-success">Confirmed</td>';
                    } else {
                        $str .= '<td class="bg-danger">Not Confirmed</td><tr>';
                    }

                    $paid['data'][] = $str;
                }
            }
        }
        $this->toolset->jsonfy($paid);
    }


    public function hotel_request_delete($rid, $hid)
    {
        if ($this->Hotel_model->delete_hotel_request($rid, $hid)) {
            $this->session->set_flashdata('hotelinform_mgs', "<span style='color:red'>Has Been Deleted Successfully</span>");
            redirect("gman/providers/pending_requests");
        } else {
            show_error("Not Deleted");
        }
    }

    public function validate_request($rid)
    {
        $data['countries'] = $this->geo_model->get_countries();
        $data['gov'] = $this->geo_model->get_governorates();
        $data['regions'] = $this->geo_model->get_regions();
        $data['title'] = 'Hotel Update';
        $data['hotel_info'] = $this->Hotel_model->request_info($rid);
        $this->render_view('hotels/gman/requested_hotel', $data);
    }

    public function activate_request($rid)
    {
        $hid = $this->input->post('hid');
        $huid = $this->input->post('huid');
        $this->form_validation->set_rules('mark', 'Mark Up', 'required');
        $this->form_validation->set_rules('discount', 'Discount', 'required');
        $data['countries'] = $this->geo_model->get_countries();
        $data['gov'] = $this->geo_model->get_governorates();
        $data['regions'] = $this->geo_model->get_regions();
        $data['title'] = 'Hotel Update';
        $data['hotel_info'] = $this->Hotel_model->request_info($rid);
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/requested_hotel', $data);
        } else {
            $hotel_activation = $this->Hotel_model->activate_hotel($huid, $hid, $rid);
            if ($hotel_activation) {
                $this->session->set_userdata('hotelinfo', $data['hotel_info']);
                $this->session->set_flashdata('a_r', "<div style='text_align:center; color:green;'>Hotel Activiated SuccessFully</div>");
                redirect('gman/products/add');
            }
        }
    }
}
