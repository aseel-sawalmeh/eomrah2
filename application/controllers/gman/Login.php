<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('Gman_Login_model', 'gm');
        $this->load->library('form_validation');
    }

    public function index(){




        $this->form_validation->set_rules('login_username', 'UserName', 'callback_User_check');
        $this->form_validation->set_rules('login_password', 'Password', 'callback_Pass_check');
        $this->form_validation->set_error_delimiters('<div style="color:red">', '</div>');

        $data['title'] = "Hotel System Login";




      if(!$this->session->userdata('gvalidated'))
      {


            if ($this->form_validation->run() == true){
              $this->gm->validate();
              redirect('gman/main');

            }else{

                $this->load->view('hotels/gman/login', $data);
            }

          }else {
            redirect('gman/main');


            }

        }

    public function User_check($h_username){


      if($h_username){
            $isexist = $this->gm->checkUserExist($h_username);

            if(!$isexist){
                $this->form_validation->set_message('77', 'Login Failed Please try again');
                return FALSE;
            }else {
              return true;
            }

      }else {
        $this->form_validation->set_message('User_check', 'The {field} Can not be empty');
        return FALSE;
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
