<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends  B2b_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reservation_model', 'rsv');
        $this->load->model('b2b/Users_model', 'b2b_users');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = lang('home');
        $this->render_view('hotels/b2b/inc/search', $data);

    }

    public function profile(){
        $id = $this->session->userdata('C_ID');
        $data['title'] = lang('prof');
        $data['user'] = $this->b2b_users->get_profile($id);
        $this->render_view("hotels/b2b/profile", $data);


    }

    public function update_profile(){
        $data['title'] = lang('prof');
        $id = $this->session->userdata('C_ID');
        $data['user'] = $this->b2b_users->get_profile($id);
       
        $this->form_validation->set_rules('c_phone', 'Company Phone', 'required');
        $this->form_validation->set_rules('c_email', 'Company Email', 'required');
        $this->form_validation->set_rules('c_fullname', 'Full Name', 'required');
        $this->form_validation->set_rules('c_mobile', 'Mobile Number', 'required');

        if($this->form_validation->run() == false){
            $this->render_view("hotels/b2b/profile", $data);
        }else{
            if($this->b2b_users->update_profile($id)){
                $success = "Profile updated successfully";
                $this->session->set_flashdata('b2b_updated', "<h4 class='text-success text-center'>$success</h4>");
                $this->render_view("hotels/b2b/profile", $data);
            }else{
                $failed = "Error while updating the profile";
                $this->session->set_flashdata('b2b_updated', "<h4 class='text-danger text-center'>$failed</h4>");
                $this->render_view("hotels/b2b/profile", $data);
            }
        }
    }

    public function changepassword(){
        $data['title'] = "Change Password";
        $this->render_view('hotels/b2b/changepass', $data);
    }

    public function update_pass(){
        $data['title'] = "Password Update";
        $this->form_validation->set_rules("oldpass", "Password", "required|callback_match_pass");
        $this->form_validation->set_rules("pass", "New Password", "required");
        $this->form_validation->set_rules("confirm_pass", "Confirm Password", "required|matches[pass]");
        $this->form_validation->set_error_delimiters("<p class='text-danger'>", "</p>"); 
        if($this->form_validation->run() == false)
        {
            $this->render_view("hotels/b2b/changepass", $data);
        }else{
            if($this->b2b_users->update_password()){
                $success = "Password updated successfully";
                $this->session->set_flashdata('pass_updated', "<h4 class='text-success text-center'>$success</h4>");
                $this->render_view("hotels/b2b/changepass", $data);
            }else{
                $failed = "Error while updating the password";
                $this->session->set_flashdata('pass_updated', "<h4 class='text-danger text-center'>$failed</h4>");
                $this->render_view("hotels/b2b/changepass", $data);

            }
        }
    }

    public function match_pass(){
        $match = $this->b2b_users->match_pass();
        if($match){
            return true;
        }else{
            return false;
        }
    }

    public function pinv($invrid)
    {
        $reservationData = $this->rsv->get_b2binvoice($invrid);
        if ($reservationData) {
            $data['h_name'] = $this->rsv->get_r_h($invrid);
            $data['idetails'] = $this->rsv->get_invoice_details($reservationData->resrefid);
        }
        $data['title'] = 'Reservation Invoice No. ' . $invrid;
        $data['idata'] = $reservationData;
        $this->render_view('hotels/b2b/invoice', $data);
    }

    public function check_login(){
        if ($this->session->userdata() != null && $this->session->userdata('b2bvalidated') == true) {
            $this->toolset->jsonfy(['status' => true]);
        }

    }
}