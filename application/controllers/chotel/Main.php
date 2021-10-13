<?php defined('BASEPATH') or exit('No Direct Access is Allowed');

class Main extends Hotel_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Hotel_model');
        $this->load->model('HotelSysUsers_model', 'hsum');
        $this->load->model('Hperiods_model', 'hpm');
        $this->load->model('Reservation_model', 'rsm');
        $this->load->library('pagination');
    }


    public function index()
    {
        $data['active_hotels_count'] = $this->Hotel_model->active_providers_count();
        $data['inactive_hotels_count'] = $this->Hotel_model->inactive_providers_count();
        $data['title'] = "Hotel System Control";
        $data['User_FullName'] = $this->session->userdata('H_User_FullName');
        $this->render_view('hotels/chotel/hotels/index', $data);
    }


    /*B2C RESERVATION METHODS STARTS FROM HERE */


    public function b2c_invoices($providerid = null)
    {
        if ($providerid != null) {
            $config['base_url'] = base_url() . "chotel/main/b2c_invoices/$providerid/";
            $config['uri_segment'] = 6;
            $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
        } else {
            $config['base_url'] = base_url() . "chotel/main/b2c_invoices";
            $config['uri_segment'] = 5;
            $config['enable_query_strings'] = true;
            $config['page_query_string'] = true;
            $config['query_string_segment'] = 'page';
            $page = $this->input->get('page');
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
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['first_link'] = '<li class="page-item"><span class="page-link">First</span></li>';
        $config['last_link']  = '<li class="page-item"><span class="page-link">Last</span></li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $data['title'] = "Hotel Reports";
        $data['reports'] = [];
        $paidres = [];
        $unpaidres = [];
        $totalpaidres = 0;
        $resv = [];
        if ($providerid == null) {
            $resv = $this->hpm->get_huser_reservations();
        } else {
            $resv = $this->hpm->get_reports($providerid);
        }
        if ($resv) {
            for ($i = 0; $i < count($resv); $i++) {
                $data['reports'][] = $resv[$i];
                if ($resv[$i]->Paid == 1) {
                    $paidres[] = $resv[$i];
                    $totalpaidres += $resv[$i]->TotalPrice;
                } else {
                    $unpaidres[] = $resv[$i];
                }
            }
        }
        $data['totalpaidres'] = $totalpaidres;
        $data['paidrescount'] = count($paidres);
        $data['unpaidrescount'] = count($unpaidres);
        $config['total_rows'] = count($data['reports']);
        $data['reports'] = array_slice($data['reports'], $page, $config['per_page']);
        $data['providers'] = $this->hpm->get_providers();
        $data['hotelname'] = function ($hotel_id) {
            return $this->hpm->get_hotel($hotel_id);
        };
        $this->pagination->initialize($config);
        $data['paginationlinks'] =  $this->pagination->create_links();
        $this->render_view('hotels/chotel/hotels/reports', $data);
    }

    public function search_b2c()
    {
        $res = [];
        $res['status'] = false;
        $ref = $this->input->get('ref');
        $page = $this->input->get('page') ?? 0;
        $str = '';
        $resv = [];
        $res['data'] = [];
        $resv = $this->hpm->search_huserb2c_reservations($ref);
        if ($resv) {
            for ($i = 0; $i < count($resv); $i++) {
                $res['status'] = true;
                $str = "<tr><td><a href='" . site_url('chotel/main/invoice_details/') . $resv[$i]->reservation_ref . "'>" . $resv[$i]->reservation_ref . "</a></td>";
                $str .= "<td>" . $resv[$i]->Public_UserFullName . "</td>";
                $str .= "<td>" . $resv[$i]->Public_UserPhone . "</td>";
                $str .= "<td>" . $resv[$i]->ResDate . "</td>";
                $str .= "<td>" . $resv[$i]->CheckInDate . "</td>";
                $str .= "<td>" . $resv[$i]->CheckOutDate . "</td>";
                $str .= "<td>" . $resv[$i]->TotalRoomCount . "</td>";
                $str .= "<td>" . $resv[$i]->TotalPrice . "</td>";
                if ($resv[$i]->Payment_method == 1) {
                    $str .= "<td class='bg-warning'>" . "Pay At Hotel" . "</td>";
                } else {
                    $str .= "<td class='bg-info'>" . "Online" . "</td>";
                }

                if ($resv[$i]->Paid == 0) {
                    $str .= "<td class='bg-danger'>" . "Un Paid" . "</td>";
                } else {
                    $str .= "<td class='bg-success'>" . "Paid" . "</td>";
                }

                if ($resv[$i]->confirm == 0) {
                    $str .= "<td class='bg-danger'>" . "No Confirmed" . "</td>";
                } else {
                    $str .= "<td class='bg-success'>" . "Confirmed" . "</td></tr>";
                }
                $res['data'][] = $str;
            }
        }
        $res1 = [];
        $pager = $page * 5;
        $res1['data'] = array_slice($res['data'], $pager, 5);
        $res1['totalpages'] = (count($res['data']) > 5) ? round(count($res['data']) / 5) : 0;

        $res1['status'] = $res['status'];
        $this->toolset->jsonfy($res1);
    }

    public function invoice_details($invrid)
    {
        $data['idata'] = $this->rsm->get_invoice($invrid);
        $data['title'] = $invrid;
        $data['rs_timeout'] = $this->rsm->get_timeout($data['idata']->ProviderId);
        $data['h_name'] = $this->rsm->get_r_h($invrid);
        $data['idetails'] = function ($invid) {
            return $this->rsm->get_invoice_details($invid);
        };
        $this->render_view('hotels/chotel/hotels/invoice', $data);
    }




    public function paid($invrid)
    {
        if ($this->rsm->markaspaid($invrid)) {
            $success = "Your invoice has been marked as confirmed";
            $this->session->set_flashdata('marked', "<h4 class='text-success'>$success</h4>");
            redirect('chotel/main/invoice_details/' . $invrid);
        } else {
            $failed = "Something went wrong";
            $this->session->set_flashdata('marked', "<h4 class='text-danger'>$failed</h4>");
            redirect('chotel/main/invoice_details/' . $invrid);
        }
    }


    public function confirm($invrid)
    {
        if ($this->rsm->ispaid($invrid)) {
            $confirm = $this->rsm->markasconfirmed($invrid);
            if ($confirm) {
                $success = "Your invoice has been marked as confirmed";
                $this->session->set_flashdata('marked', "<h4 class='text-success'>$success</h4>");
                redirect('chotel/main/invoice_details/' . $invrid);
            }
        } else {
            $failed = "Reservation amount has to be paid before confirming it";
            $this->session->set_flashdata('marked', "<h4 class='text-danger'>$failed</h4>");
            redirect('chotel/main/invoice_details/' . $invrid);
        }
    }

    /*B2B RESERVATION METHODS STARTS FROM HERE */

    public function b2b_invoices($providerid = null)
    {
        if ($providerid != null) {
            $config['base_url'] = base_url() . "chotel/main/b2b_invoices/$providerid/";
            $config['uri_segment'] = 6;
            $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
        } else {
            $config['base_url'] = base_url() . "chotel/main/b2b_invoices";
            $config['uri_segment'] = 5;
            $config['enable_query_strings'] = true;
            $config['page_query_string'] = true;
            $config['query_string_segment'] = 'page';
            $page = $this->input->get('page');
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
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['first_link'] = '<li class="page-item"><span class="page-link">First</span></li>';
        $config['last_link']  = '<li class="page-item"><span class="page-link">Last</span></li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $data['title'] = "Hotel Reports";
        $data['reports'] = [];
        $paidres = [];
        $unpaidres = [];
        $totalpaidres = 0;
        $resv = [];
        if ($providerid == null) {
            $resv = $this->hpm->get_huserb2b_list();
        } else {
            $resv = $this->hpm->get_b2b_details($providerid);
        }
        if ($resv) {
            for ($i = 0; $i < count($resv); $i++) {
                $data['reports'][] = $resv[$i];
                if ($resv[$i]->Paid == 1) {
                    $paidres[] = $resv[$i];
                    $totalpaidres += $resv[$i]->TotalPrice;
                } else {
                    $unpaidres[] = $resv[$i];
                }
            }
        }
        $data['totalpaidres'] = $totalpaidres;
        $data['paidrescount'] = count($paidres);
        $data['unpaidrescount'] = count($unpaidres);
        $config['total_rows'] = count($data['reports']);
        $data['reports'] = array_slice($data['reports'], $page, $config['per_page']);
        $data['providers'] = $this->hpm->get_providers();
        $data['hotelname'] = function ($hotel_id) {
            return $this->hpm->get_hotel($hotel_id);
        };
        $this->pagination->initialize($config);
        $data['paginationlinks'] =  $this->pagination->create_links();
        $this->render_view('hotels/chotel/hotels/b2b_reports', $data);
    }


    public function search_b2b()
    {
        $res = [];
        $res['status'] = false;
        $ref = html_escape($this->input->get('ref'));
        $page = $this->input->get('page') ?? 0;
        $str = '';
        $resv = [];
        $resv = $this->hpm->search_huserb2b_list($ref);
        if ($resv) {
            for ($i = 0; $i < count($resv); $i++) {
                    $res['status'] = true;
                    $str = "<tr><td><a href='" . site_url('chotel/main/b2b_details/') . $resv[$i]->reservation_ref . "'>" . $resv[$i]->reservation_ref . "</a></td>";
                    $str .= "<td>" . $resv[$i]->C_FullName . "</td>";
                    $str .= "<td>" . $resv[$i]->C_MobileNumber . "</td>";
                    $str .= "<td>" . $resv[$i]->ResDate . "</td>";
                    $str .= "<td>" . $resv[$i]->CheckInDate . "</td>";
                    $str .= "<td>" . $resv[$i]->CheckOutDate . "</td>";
                    $str .= "<td>" . $resv[$i]->TotalRoomCount . "</td>";
                    $str .= "<td>" . $resv[$i]->TotalPrice . "</td>";
                    if ($resv[$i]->Payment_method == 1) {
                        $str .= "<td class='bg-warning'>" . "Pay At Hotel" . "</td>";
                    } else {
                        $str .= "<td class='bg-info'>" . "Online" . "</td>";
                    }
                    if ($resv[$i]->Paid == 0) {
                        $str .= "<td class='bg-danger'>" . "Un Paid" . "</td>";
                    } else {
                        $str .= "<td class='bg-success'>" . "Paid" . "</td>";
                    }
                    if ($resv[$i]->confirm == 0) {
                        $str .= "<td class='bg-danger'>" . "No Confirmed" . "</td>";
                    } else {
                        $str .= "<td class='bg-success'>" . "Confirmed" . "</td></tr>";
                    }
                    $res['data'][] = $str;
                
            }
        }
        $res1 = [];
        $pager = $page * 5;
        $res1['data'] = array_slice($res['data'], $pager, 5);
        $res1['totalpages'] = (count($res['data']) > 5) ? round(count($res['data']) / 5) : 0;
        $res1['status'] = $res['status'];
        $this->toolset->jsonfy($res1);
    }

    public function b2b_paid($invrid)
    {
        if ($this->rsm->markaspaid($invrid)) {
            $success = "Your invoice has been marked as confirmed";
            $this->session->set_flashdata('marked', "<h4 class='text-success'>$success</h4>");
            redirect('chotel/main/b2b_details/' . $invrid);
        } else {
            $failed = "Something went wrong";
            $this->session->set_flashdata('marked', "<h4 class='text-danger'>$failed</h4>");
            redirect('chotel/main/b2b_details/' . $invrid);
        }
    }


    public function b2b_confirm($invrid)
    {
        if ($this->rsm->ispaid($invrid)) {
            $confirm = $this->rsm->markasconfirmed($invrid);
            if ($confirm) {
                $success = "Your invoice has been marked as confirmed";
                $this->session->set_flashdata('marked', "<h4 class='text-success'>$success</h4>");
                redirect('chotel/main/b2b_details/' . $invrid);
            }
        } else {
            $failed = "Reservation amount has to be paid before confirming it";
            $this->session->set_flashdata('marked', "<h4 class='text-danger'>$failed</h4>");
            redirect('chotel/main/b2b_details/' . $invrid);
        }
    }

    public function b2b_details($refid)
    {
        $data['idata'] = $this->rsm->get_b2binvoice($refid);
        $data['title'] = $refid;
        $data['rs_timeout'] = $this->rsm->get_timeout($data['idata']->ProviderId);
        $data['h_name'] = $this->rsm->get_r_h($refid);
        $data['idetails'] = function ($refid) {
            return $this->rsm->get_invoice_details($refid);
        };
        $this->render_view('hotels/chotel/hotels/b2b_invoice', $data);
    }

    public function profile(){
        $data['title'] = "User Profile";
        $data['user'] = $this->hsum->huser_profile();
        $this->render_view('hotels/chotel/hotels/profile', $data);
    }

    public function edit($id){
        $this->load->library('form_validation');
        $data['title'] = "User Profile";
        $data['user'] = $this->hsum->huser_profile();

        $this->form_validation->set_rules('husername', 'User Name', 'required');
        $this->form_validation->set_rules('hfullname', 'Full Name', 'required');
        $this->form_validation->set_rules('hemail', 'Email', 'required');
        $this->form_validation->set_rules('hphone', 'Mobile Number', 'required');

        if($this->form_validation->run() == false){
          
            $this->render_view('hotels/chotel/hotels/profile', $data);
        }else{
            if($this->hsum->update_huser($id)){
                $success = "Profile updated successfully";
                $this->session->set_flashdata('user_updated', "<h4 class='text-success text-center'>$success</h4>");
               redirect('chotel/main/profile');
            }else{
                $failed = "Error while updating the profile";
                $this->session->set_flashdata('user_updated', "<h4 class='text-danger text-center'>$failed</h4>");
                redirect('chotel/main/profile');
            }
        }
    }



    public function changepassword(){
        $this->load->library('form_validation');
        $data['title'] = "Password Updated";
        $this->form_validation->set_rules("oldpass", "Password", "required|callback_match_pass");
        $this->form_validation->set_rules("pass", "New Password", "required");
        $this->form_validation->set_rules("confirm_pass", "Confirm Password", "required|matches[pass]");
        $this->form_validation->set_error_delimiters("<p class='text-danger'>", "</p>"); 
        if($this->form_validation->run() == false)
        {
            $this->render_view("hotels/chotel/hotels/changepass", $data);
        }else{
            if($this->hsum->update_password()){
                $success = "Password updated successfully";
                $this->session->set_flashdata('huser_pass_updated', "<h4 class='text-success text-center'>$success</h4>");
                // $this->render_view("hotels/chotel/hotels/changepass", $data);
                redirect('chotel/main/changepassword');
            }else{
                $failed = "Error while updating the password";
                $this->session->set_flashdata('hsuer_pass_updated', "<h4 class='text-danger text-center'>$failed</h4>");
               // $this->render_view("hotels/chotel/hotels/changepass", $data);
                redirect('chotel/main/changepassword');

            }
        }
    }

    public function match_pass(){
        $match = $this->hsum->match_pass();
        if($match){
            return true;
        }else{
            return false;
        }
    }




}
