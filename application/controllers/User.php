<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php'; 


class User extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('User_model', 'um');
        //$this->load->library('pagination');
        $this->load->model('Reservation_model', 'rsv');
        $this->load->model('Users_Mails_model', 'usm');
        $this->load->model('Geo_model', 'geo');
    }

      public function register()
    {
        $data['title'] = lang('u_reg');
        $data['countries'] = $this->geo->get_countries();
        $this->form_validation->set_rules('name', lang('uname'), 'required|callback_check_name_exists|callback_name_regex');
        $this->form_validation->set_rules('password', lang('pass'), 'required');
        $this->form_validation->set_rules('pass_conf', lang('pass'), 'required|matches[password]');
        $this->form_validation->set_rules('fullname', lang('fullname'), 'required|callback_fullname_regex');
        $this->form_validation->set_rules('email', lang('em'), 'required|valid_email|callback_check_email_exists');
        $this->form_validation->set_rules('address', lang('adr'), 'required');
        // $this->form_validation->set_rules('zip', lang('zip'), 'required');
        $this->form_validation->set_rules('phone', lang('contact'), 'required|numeric|callback_check_phone_exist');
        $this->form_validation->set_rules('country', lang('country'), 'required');
        $this->form_validation->set_rules('city', lang('city'), 'required');

        if ($this->form_validation->run() === false) {
            $this->render_view(['hotels/register'], $data);
        } else {
            $en_password = md5($this->input->post('password'));
            $reciever =  $this->input->post('email');
            
            if ($this->um->register($en_password) && $this->usm->usendemail($reciever)) {
                $success = lang("hotelemailverify");
                $this->session->set_flashdata('user_registerd', "<h4 class='text-success text-center'>$success</h4>");
            } else {
                $failed = lang("hotelemailfailed");
                $this->session->set_flashdata('user_registerd', "<h4 class='text-danger text-center'> $failed</h4>");
            }
            $this->load->view('hotels/inc/header', $data);
            $this->load->view('hotels/user_reg');
        }
    }

    
    public function name_regex($name){
        if(!empty($name)){
            if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,10}$/', $name)){
                $this->form_validation->set_message("name_regex", lang('unchar_username'));
                return false;
            }else{
                return true;
            }

        }
       
       
    }


    public function fullname_regex($name){
        if(!empty($name)){
            $match = '/^[\p{Arabic}a-zA-Z\- .ـ]+$/u';
            if (!preg_match($match, $name)){
                $this->form_validation->set_message("fullname_regex", lang('unchar_fullname'));
                // show_error(var_dump($match));
                return false;
            }else{
                return true;
            }

        }
    }



    public function check_email_exists($email)
    {
        $this->form_validation->set_message('check_email_exists', lang("emailtaken"));
        if ($this->um->check_email_exists($email)) {
            return false;
        } else {
            return true;
        }
    }

    public function email_check()
    {
        $email = $this->input->get('email');
        if ($this->um->check_email_exists($email)) {
            $this->toolset->jsonfy(['status'=> true, 'error' => lang("emailtaken")]);
        } else {
            $this->toolset->jsonfy(['error' => false]);
        }
    }
    
    public function emailexist()
    {
        $this->toolset->jsonfy(['exist' => $this->um->endemailcheck($this->input->post('mail'))]);
    }


    public function emailconfirmaccess()
    {
        // $this->toolset->sendemail($useremail, "User Invoice", 'resmail', ['u_details' => $data['user_details']]);
        //generate token link it to the email session it 
        $email = $this->input->post('mail');
        $emailexist = $this->um->endemailcheck($email, true);
        if ($emailexist) {
            $mailtoken = rand(100000, 999999);
            $mailSent = $this->toolset->sendemail($email, "Confirm Email", 'mailtoken', ['userFname' => $emailexist, 'code' => $mailtoken]);
            if ($mailSent) {
                $this->session->set_userdata('mailtoken', ['email' => $email, 'token' => $mailtoken]);
                $this->toolset->jsonfy(['sent' => true]);
            } else {
                $this->toolset->jsonfy(['sent' => false]);
            }
        }
    }


    public function emailtokenconfirm()
    {
        // $this->toolset->sendemail($useremail, "User Invoice", 'resmail', ['u_details' => $data['user_details']]);
        //check the genarated token link it to the email session it 
        // then sign user in send true to reaload the page
        $email = $this->input->post('mail');
        $token = $this->input->post('token');

        if ($this->session->has_userdata('mailtoken') && $this->session->userdata('mailtoken')['email'] == $email && $this->session->userdata('mailtoken')['token'] == $token) {
            //unset the session data and grant him access
            $this->session->unset_userdata('mailtoken');
            //granting hime access and reply susccess for the reload
            $userLogged = $this->um->recoverlogin($email);
            if ($userLogged) {
                $this->toolset->jsonfy(['ulogged' => true]);
            } else {
                $this->toolset->jsonfy(['ulogged' => false, 'error' => "user information error"]);
            }
        } else {
            $this->toolset->jsonfy(['ulogged' => false, 'error' => 'incorrect token']);
        }
    }


    public function phoneexist()
    {
        $this->toolset->jsonfy(['exist' => $this->um->phonecheck($this->input->post('phone'))]);
    }

    
    public function check_phone_exist($phone){
        if($this->um->phonecheck($phone)){
            $this->form_validation->set_message('check_phone_exist', lang('phone_exists'));
            return false;
        }else{
            return true;
        }

    }


    public function check_name_exists($name)
    {
        $this->form_validation->set_message('check_name_exists', lang('usernametaken'));
        if ($this->um->check_name_exists($name)) {
            return false;
        } else {
            return true;
        }
    }

   public function user_check()
    {
        $name = $this->input->get('name');
        if ($this->um->check_name_exists($name)) {
            $this->toolset->jsonfy(['status'=> true, 'error' => lang('usernametaken')]);
        } else {
            $this->toolset->jsonfy(['error' => false]);
        }
    }




    public function profile()
    {
        $this->load->library('pagination');

        $data['title'] = $this->session->userdata('User_data')['userFname'];
        $data['countries'] = $this->geo->get_countries();

        $data['user_info'] = $this->um->get_user();
        $config['base_url'] = site_url() . 'user/profile';
        $config['total_rows'] = $this->um->user_res_head($this->session->userdata('user_data')['userID']);
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
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['first_link'] = '<li class="page-item"><span class="page-link">First</span></li>';
        $config['last_link']  = '<li class="page-item"><span class="page-link">Last</span></li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['resheads'] = $this->um->resheads($this->session->userdata('user_data')['userID'], $config['per_page'], $page);

        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $data['title'] = 'User Profile';

        $this->load->view('hotels/inc/header', $data);
        $this->load->view('hotels/user_profile');
    }

    public function user_invoices()
    {
        $config['base_url'] = site_url() . 'user/profile';
        $config['total_rows'] = $this->pum->user_res_head($this->session->userdata('User_data')['userID']);
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
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['resheads'] = $this->um->resheads($this->session->userdata('User_data')['userID'], $config['per_page'], $page);
        $data['title'] = "Profile Page";
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $data['user_info'] = $this->um->get_user();

        $this->load->view('hotels/inc/header', $data);
        $this->load->view('hotels/user_profile');
    }

    public function invoice($invrid = null, $mail = null)
    {
        // needs cleaning and restructure ....
        // controlling update the profile notify users
        // $invrid
        // if ($invrid && $mail) {
        $reservationData = $this->rsv->get_invoice($invrid);
        //checking user email equal to hash
        if (md5($reservationData->Public_UserEmail) == $mail) {
            $unCompeltedProfile = false;
            if (
                $reservationData &&
                is_null($reservationData->Public_UserName) ||
                empty($reservationData->Public_UserName) ||
                is_null($reservationData->Public_UserPassword) ||
                empty($reservationData->Public_UserPassword) ||
                is_null($reservationData->address) ||
                empty($reservationData->address) ||
                is_null($reservationData->country) ||
                empty($reservationData->country) ||
                is_null($reservationData->city) ||
                empty($reservationData->city)
            ) {
                $this->load->model('geo_model', 'geo');
                $unCompeltedProfile = true;
                $countries = $this->geo->get_countries();
                // $cities = $this->geo->get_cities();
                $data['editProfile'] = $this->load->view('hotels/users/completeProfile', ['user' => $reservationData, 'countries' => $countries], true);
            };
            if ($reservationData) {
                $data['h_name'] = $this->rsv->get_r_h($invrid);
                $data['mailHash'] = $mail;
                $data['idetails'] = $this->rsv->get_invoice_details($reservationData->resrefid);
            }
            $data['title'] = 'Reservation Invoice No. ' . $invrid;
            $data['idata'] = $reservationData;
            $data['profileUpdate'] = $unCompeltedProfile;
            $this->render_view('hotels/invoice', $data);
        } else {
            redirect('user/check_invoice');
        }
        // } else {
        //     redirect('/user/check_invoice');
        // }



    }



    public function check_invoice()
    {
        // Check if the ref number and mail match hash it resent it to the invoice page easy
        // needs cleaning and restructure ....
        // controlling update the profile notify users

        //$invrid will come from the form along with the mail
        // exit('no data form yet');
        // $reservationData = $this->rsv->get_invoice($invrid);

        $data['title'] = lang('resDetails');
        $resno = $this->input->post('resno');
        $this->form_validation->set_rules('email', lang('em'), ['required', ['resamil_check', function ($email) use ($resno) {
            $this->form_validation->set_message('resamil_check', lang('datanotmatch'));
            $reservationData = $this->rsv->get_invoice($resno);
            if ($reservationData && $reservationData->Public_UserEmail == $email) {
                return true;
            } else {
                return false;
            }
        }]]);
        $this->form_validation->set_rules(
            'resno',
            lang('resno'),
            'required|numeric'
        );

        if ($this->form_validation->run() === false) {
            $this->render_view('hotels/check_invoice', $data);
        } else {
            $resref = $this->input->post('resno');
            $hashMail = md5($this->input->post('email'));
            redirect("user/invoice/$resref/$hashMail");
        }
    }

    public function res_cancel($resref = null, $hashMail = null, $confirm = 'no')
    {
        $this->load->library('Lotsofhotels');
        if ($resref && $hashMail) {
            $pResId = $this->input->post('resrefid');
            $serviceCode = $this->input->post('servicecode');
            $charge = $this->input->post('charge');
            $resdetails = $this->rsv->get_confirmedRes($pResId);

            $pResRef = $this->input->post('resref');
            $pResMail = $this->input->post('resmail');
            if ($resref == $pResRef && $pResMail == $hashMail) {
                $cancelConfrim = $this->lotsofhotels->cancelBooking($resdetails, $confirm, ['servicecode'=> $serviceCode, 'charge'=>$charge]);
                if($cancelConfrim['result']){
                    $cancelBooking = $this->rsv->cancelBooking($pResId);
                    if($cancelBooking){
                        redirect("user/invoice/$resref/$hashMail");
                    }else{
                        echo "error updating the db cancel part";
                    }
                }else{
                    print_r($cancelConfrim['error']);
                }
            } else {
                echo "data is not accurate";
            }
        } else {
            echo "invalid data";
        }
    }

    public function res_cancelCheck()
    {
        //$resref=null, $hashMail=null, $confirm = 'no' post values
        $resref = $this->input->post('resfref');
        $hashMail = $this->input->post('hmail');
        $this->load->library('Lotsofhotels');
        if ($resref && $hashMail) {
            $pResId = $this->input->post('resfrefd');
            $resdetails = $this->rsv->get_confirmedRes($pResId);
            $canceldetails = $this->lotsofhotels->cancelBooking($resdetails);
            if ($canceldetails['result']) {
                $service = $canceldetails['data']->services->service;
                // print_r($canceldetails);
                // var_dump($service);
                $this->toolset->jsonfy(['result' => true, 'data' => ['serviceCode' => (int)$service->attributes()->code, 'charge' => (float)$service->cancellationPenalty->charge, 'currencyShort'=>(string)$service->cancellationPenalty->currencyShort]]);
            } else {
                $this->toolset->jsonfy(['result' => false, 'error' => $canceldetails['error']]);
            }
        } else {
            $this->toolset->jsonfy(['result' => false, 'error' => "inavlid data"]);
        }
    }

    //test only remove it
    public function makexml($command, $product = 'h')
    {
        // $product = 'hotel';
        $xml = '<?xml version="1.0" encoding="UTF-8"?><customer><username>medhat85</username><password>70607b42ce7f60b923b05744b2ec5b73</password><id>1742725</id><source>1</source>'
            . (!empty($product) ? "<product>$product</product>" : '') . '<request command="cancelbooking"></request></customer>';
        $xml = new SimpleXMLElement($xml);
        // $xml->addChild('product', 'hotel');
        $xml->request->attributes()->command = $command;
        $xml->request->addChild('bookingDetails');
        $xml->request->bookingDetails->addChild('bookingType', ' ');
        $xml->request->bookingDetails->addChild('bookingCode', 2020);
        // echo "<pre>";
        print_r($xml->asXml());
        // echo "</pre>";
    }

    public function get_cities($country = null)
    {
        if (!is_numeric($country) || is_null($country)) show_404();
        $this->load->model('geo_model', 'geo');
        $cities = $this->geo->get_cities($country);
        if ($cities) {
            $this->toolset->jsonfy(['result' => true, 'cities' => $cities]);
        } else {
            $this->toolset->jsonfy(['result' => false, 'error' => 'no cities found']);
        }
    }



    public function changepassword()
    {
        $data['title'] = "Change Your Password";
        $this->lang->load('form_validation');
        $this->form_validation->set_rules('Pass', lang('pass'), 'required|callback_check_pass_exist');
        $this->form_validation->set_rules('password', lang('newpass'), 'required');
        $this->form_validation->set_rules('newpass', lang('pass'), 'required|matches[password]');
        $this->form_validation->set_error_delimiters("<p class='text-danger'>", "</p>");
        if ($this->form_validation->run() == false) {
            $this->render_view(['hotels/inc/banner', 'hotels/change_pass'], $data);
        } else {
            $newpass = md5($this->input->post('newpass'));
            $update = $this->um->pass_update($newpass);
            if ($update) {
                $this->toolset->sendemail($this->session->userdata('user_data')['userEmail'], 'eomrah change password', 'changepass', null);
                redirect('user/profile');
            } else {

                redirect('home');
            }
        }
    }

    public function check_pass_exist($Pass)
    {
        if ($this->um->check_pass($Pass)) {
            return true;
        } else {
            $this->form_validation->set_message('check_pass_exist', lang('passmatch'));
            return false;
        }
    }

   

    public function update()
    {
        if ($this->input->post('password') == $this->input->post('confirmpassword')) {
            $userUpdated =  $this->um->profileUpdate();
            if ($userUpdated) {
                $this->toolset->sendemail($this->input->post('email'), "Eomrah Verifiy Email", 'usermarkedmail', ['receiver' => $this->input->post('email')]);
                $this->toolset->jsonfy(['result' => true, 'message' => "your account has been updated successfully"]);
            } else {
                $this->toolset->jsonfy(['result' => false, 'message' => "sorry updating the account does not successed"]);
            }
        } else {
            $this->toolset->jsonfy(['result' => false, 'message' => 'Password MisMatch']);
        }
    }

  

    //checking mials view
    public function uv()
    {
        $userTest = new stdClass();
        $userTest->reservation_ref = '2615161651651';
        $userTest->confirm = true;
        $userTest->Paid = false;
        $userTest->Hotel_Name = 'test inv mail';
        $userTest->Hotel_Address = 'test inv mail address';
        $userTest->lat = '32.2325';
        $userTest->lng = '21.3333';
        $userTest->Public_UserFullName = 'Test User Name';
        $userTest->Public_UserPhone = '966123121213';
        $userTest->CheckInDate = '2021-03-15';
        $userTest->CheckOutDate = '2021-03-17';
        $userTest->Discountid = '15';
        $userTest->TotalRoomCount = '2';
        $userTest->TotalPrice = '22500';
        $userTest->NetPrice = '33200';
        $idetails = [];
        for ($i = 0; $i < 5; $i++) {
            $room = new stdClass();
            $room->name = 'roome ' . $i;
            $room->ratebase = $i;
            $room->guest_name = 'guest name' . $i;
            $room->NightPrice = 1200 + $i;
            $room->cancellation = 'cancelation for 5415151 on date ' . $i;
            $idetails[] = $room;
        }
        $this->load->view('email/resmail', ['idata' => $userTest, 'idetails' => $idetails]);
    }

    public function pdf($invrid){
        require 'vendor/autoload.php'; 
        $data['h_name'] = $this->rsv->get_r_h($invrid);
        $reservationData = $this->rsv->get_invoice($invrid);
        $data['idata'] = $reservationData;
        $data['idetails'] = $this->rsv->get_invoice_details($reservationData->resrefid);
        $data['title'] = $invrid;
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view('pdf_invoice',$data,true);
        $mpdf->WriteHTML($html);
        if(userlang() == 'ar'){
            $mpdf->SetDirectionality('rtl');
        }else{
            $mpdf->SetDirectionality('ltr');
        }
        $mpdf->Output();

        
    }
}
