<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
    }

    public function index(){   

        $this->load->model('Hotel_Login_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('login_username', 'UserName', 'callback_User_check');
        $this->form_validation->set_rules('login_password', 'Password', 'callback_Pass_check');
        $this->form_validation->set_error_delimiters('<div style="color:red">', '</div>');
        $data['title'] = "Hotel System Login";
        if(!$this->session->userdata('hvalidated')){
            if ($this->form_validation->run() == FALSE){
            $this->load->view('hotels/chotel/login', $data);
            }else{
            $this->Hotel_Login_model->validate();
            redirect('chotel/main');
            }
        }else{
            redirect('chotel/main');
        }
    }
     
    public function User_check($h_username){
        $User_exist = $this->Hotel_Login_model->checkUserExist($h_username);
        $mail_confirmed = $this->Hotel_Login_model->checkUserEmailConfirmed($h_username);
        if(empty($h_username)){
            $this->form_validation->set_message('User_check', 'The {field} Can not be empty');
            return FALSE;
        }else{

            if (!$User_exist){
                $this->form_validation->set_message('User_check', 'Login Failed Please try again');
                return FALSE;
            }elseif($mail_confirmed == FALSE){
                $this->form_validation->set_message('User_check', 'User Email Not Confirmed');
                return FALSE;
            }
        }
    }
    
    public function Pass_check($h_password){
        if(empty($h_password)){
            $this->form_validation->set_message('Pass_check', 'The {field} Can not be empty');
            return FALSE;
        }
    }
}

?>