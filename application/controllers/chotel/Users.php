<?php defined('BASEPATH') or exit('No Direct Access is Allowed');

class Users extends Hotel_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('hotelSysUsers_model');
        $this->load->library('form_validation');
    }

    public function profile()
    {
        if ($this->session->userdata('Suser')) {
            show_error("This is only allowed to hotel users Super Admin User May use their Panels", 503, "This Error is propably Because You are using Super User");
        }
        $this->form_validation->set_rules('fullname', 'User Full Name', 'required');
        $this->form_validation->set_rules('username', 'User Name', 'required');
        $this->form_validation->set_rules('userphone', 'User Phone Number', 'required');
        $this->form_validation->set_rules('email', 'Email Address', 'required');
        $user_id = $this->session->userdata('H_User_ID');
        $user_exist = $this->hotelSysUsers_model->checkUserExist($user_id);
        $user_data = $this->hotelSysUsers_model->get_that_h_user($user_id);
        $data['h_user'] = $user_exist ? $user_data : false;
        $data['title'] = "User Profile";
        if ($this->form_validation->run() == false){
            $this->render_view('chotel/users/profile', $data);
        } else {
            if ($this->hotelSysUsers_model->edit($user_id)) {
                $this->session->set_userdata('profile_msg', "The User Details Updated SuccessFully");
                redirect('chotel/users/profile');
            } else {
                $this->session->set_userdata('profile_msg', "Sorry Something Went Wrong, please try again Or report");
                redirect('hotels/chotel/users/profile');
            }
        }
    }
}
