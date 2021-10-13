<?php
class Plogin extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'um');
    }


    public function booklogin()
    {
        $res = [];
        $password = md5($this->input->post('password'));
        $email = $this->input->post('email');
        var_dump($email);
        var_dump($password);
        $this->toolset->jsonfy(['status'=>false]);
        exit();
        if(empty($email)&& empty($password)){

            $res['status'] = false;
            $res['result'] = "Login Failed";
        }else{
            if($this->um->login($email, $password)){
                $res['status'] = true;
                $res['result'] = "You Are Now Logged In, Happy booking";
            }else{
                $res['status'] = false;
                $res['result'] = "Login Failed";
            }
        }
        $this->toolset->jsonfy($res);

    }

    public function glogin()
    {
        $res = [];
        $password = $this->input->post('password');
        $email = $this->input->post('email');
        if(!empty($email) && !empty($password)){
            $password = md5($password);
            if($this->um->login($email, $password)){
                $res['status'] = true;
                $res['result'] = "You Are Now Logged In, Happy booking";
            }else{
                $res['status'] = false;
                $res['result'] = "Login Failed";
            }

        }else{
            $res['status'] = false;
            $res['result'] = "Login Failed";
        }
        $this->toolset->jsonfy($res);
    }

    public function check_email($email)
    {
        $password = md5($this->input->post('password'));
        if(!$this->um->login($email, $password)){
            $this->form_validation->set_message('check_email', 'Login Invalid');
            return false;
        }else{
            return true;
        }
    }

    public function puserlogout()
    {
        $this->session->unset_userdata('user_data');
        $this->session->set_flashdata('user_loggedout', "you have singed out");
        redirect('home');
    }

}   