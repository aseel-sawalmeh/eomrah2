<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reservation extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reservation_model', 'rsm');
        $this->load->model('Product_Categories_model');
        $this->load->model('fendmodels/fendproducts_model');
        $this->load->model('fendmodels/fphoto_model');
        $this->load->model('translate/Translate_model');
        $this->load->model('Multimedia_model');
        $this->load->model('Hotel_model', 'ht');
        $this->load->model('Products_model');
        $this->load->model('User_model', 'pum');
        //$this->userLogged();
    }

    // public function userLogged(){
    //     if (!$this->session->userdata('user_data')['loggedIn']){
    //         redirect('/');
    //     }
    // }

    public function index()
    {
        if ($this->session->wallet != null) {
            $this->load->library('magician');
            $wallet = unserialize($this->session->wallet);
            $hotel = $this->ht->get_that_hotel($wallet['hid']);
            $hotel->Hotel_Name = tolang($hotel->Hotel_ID, 'hotelname') ? tolang($hotel->Hotel_ID, 'hotelname') : $hotel->Hotel_Name;
            $hotel->Hotel_Description = tolang($hotel->Hotel_ID, 'hoteldesc') ? tolang($hotel->Hotel_ID, 'hoteldesc') : $hotel->Hotel_Name;
            $data = [
                'title' => 'Reservation Details',
                'p_methods' => $this->rsm->payment_Methods($wallet['prvid']),
                'wallet' => $wallet, 'hotel' => $hotel,
                'imgs' => $this->ht->hotel_photos($hotel->Hotel_ID),
                'sp_requests' => $this->rsm->spRequests(),
                'hotel_amenities' => $this->rsm->hotelAmenities($hotel->Hotel_ID)
            ];
            // $this->session->set_userdata('res_mail', $data);
            $this->render_view(['hotels/inc/banner', 'hotels/reservation'], $data);
        } else {
            redirect('');
        }
    }

    public function checkout()
    {
        // $this->toolset->jsonfy(['result' => true, 'rd' => site_url("user/pinv/645165165156516"), 'sms' =>false]);
        // exit();
        $puserid = '';
        $userphone = '';
        $userfname = '';
        $useremail = '';

        if ($this->session->user_data == null) {
            // print_r($this->input->post('rooms'));
            // exit();
            if ($this->input->post('fname') && $this->input->post('email') && $this->input->post('phone')) {
                $user['Public_UserFullName'] = $this->input->post('fname') . ' ' . $this->input->post('fmname');
                $user['Public_UserEmail'] = $this->input->post('email');
                $useremail = $this->input->post('email');
                $user['Public_UserPhone'] = $this->input->post('phone');
                $userphone = $this->input->post('phone');
                $userfname = $this->input->post('fname') . ' ' . $this->input->post('fmname');
                $reguser = $this->pum->reg_user($user);
                if ($reguser) {
                    $puserid = $reguser;
                } else {
                    $this->toolset->jsonfy(['result' => false, 'error' => lang('User Info already exist')]);
                    exit();
                }
            } else {
                $this->toolset->jsonfy(['result' => false, 'error' => lang('wrong_info')]);
                exit();
            }
        } else {
            $puserid = $this->session->user_data['userID'];
            $userphone = $this->session->user_data['userphone'];
            $userfname = $this->session->user_data['userFname'];
            $useremail = $this->session->user_data['userEmail'];
        }

        $wallet = unserialize($this->session->wallet);
        $res['hotelid'] = $wallet['lhid'];
        $res['P_UserId'] = $puserid;
        $res['ProviderId'] = $wallet['prvid'];
        $res['TotalPrice'] = $wallet['total_price'];
        $res['TotalRoomCount'] = $wallet['total_items'];
        $res['Discountid'] = $wallet['discountid'] ?? 0;
        $res['NetPrice'] = $wallet['after_discount'] ?? $wallet['net_price'];
        $res['CheckInDate'] = $wallet['checkin'];
        $res['CheckOutDate'] = $wallet['checkout'];
        $res['nights'] = $wallet['nonights'];
        $res['Payment_method'] = $this->input->post('pmethod');

        if ($this->input->post('gname') && $this->input->post('gemail')) {
            $res['guest_name'] = $this->input->post('gname');
            $res['guest_email'] = $this->input->post('gemail');
        }

        $resid = $this->rsm->set_res($res);

        if ($resid) {
            //needs to be secured as in session only
            $proRooms = json_decode($this->input->post('rooms'));
            $addresrooms = $this->rsm->set_resdetails($proRooms, $resid);
            $saveTransaction = $this->rsm->saveTransaction(['user_id' => $puserid, 'service_type' => 'reservation', 'service_id' => $resid, 'amount' => $res['NetPrice'], 'paid' => 0]);
            if (!$saveTransaction) show_error('transaction error'); //log_message('error', 'Trans for reservation ' . $resid . 'save fail');
            if ($addresrooms) {
                $refid = $this->rsm->get_resref($resid);

                if (userlang() == 'ar') {
                    $message = "عزيزى العميل : $userfname لقد تم حجز الفندق بنجاح رقم الحجز هو : $refid شكرا لاستخدامك eomrah.com";
                } else {
                    $message = "Dear Customer: $userfname You have successfully booked, booking reference: $refid, thank you for using eomrah.com Services :-D";
                }
                $sms = false;
                if (!empty($userphone)) {
                    $smsr = sendsms2($message, $userphone);
                    $whatsm = sendwhats($message);
                    $this->session->set_flashdata('sms', $sms);
                    $this->session->set_flashdata('whatsm', $whatsm);
                    if ($smsr) {
                        $sms = true;
                    }
                }
                $data['idata'] = $this->rsm->get_invoice($refid);
                $data['idetails'] = $this->rsm->get_invoice_details($data['idata']->ID);
                // $this->toolset->sendemail($useremail, "User Invoice", 'resmail', $data);
                // redirect(site_url("user/pinv/$refid"));
                $this->toolset->jsonfy(['result' => true, 'rd' => site_url("user/invoice/$refid/".md5($useremail)), 'sms' => $sms]);
            } else {
                echo "error address rooms not set";
            }
        } else {
            echo "error res not set";
        }
    }

    public function items_test()
    {
        if ($this->session->wallet !== null) {
            $wallet = unserialize($this->session->wallet);
            $data = ['result' => true, 'wallet' => $wallet];
            $this->toolset->jsonfy($data);
        } else {
            $this->toolset->jsonfy(['result' => false]);
        }
    }

    public function bookwallet()
    {
        if ($this->session->wallet !== null) {
            $wallet = unserialize($this->session->wallet);
            $data = ['result' => true, 'wallet' => $wallet];
            $this->toolset->jsonfy($data);
        } else {
            $this->toolset->jsonfy(['result' => false]);
        }
    }

    public function jdiscodecheck()
    {
        $this->load->model('Hperiods_model');
        $err = [];
        $matches = false;
        $perid = intval($this->input->get('checkprid'));
        if (!$perid) {
            $err['error'] = 'checkprid is missing - ';
        }
        if (!$this->input->get('dt1')) {
            $err['error'] .= 'dt1 is missing - ';
        }
        if (!$this->input->get('dt2')) {
            $err['error'] .= 'dt2 is missing - ';
        }
        if (empty($err)) {
            $period = $this->Hperiods_model->gettperiod($perid);
            if ($period) {
                $discountset = $this->Hperiods_model->get_discountcodes_typed($period->providerID, 'b2c');
                $match = discountAvailability($this->input->get('dt1'), $this->input->get('dt2'), $discountset);
                if ($match && count($match) > 0) {
                    $matches = ["available" => true];
                } else {
                    $matches = ["available" => false];
                }
            } else {
                $matches = ["available" => false];
            }
        }
        $res = $matches ? $matches : $err;
        $this->toolset->jsonfy($res);
    }

    public function jdiscodecheckav()
    {
        $this->load->model('Hperiods_model');
        $err = [];
        $matches = false;
        if (!$this->input->get('prid')) {
            $err['error'] = 'prid is missing - ';
        }
        if (!$this->input->get('dt1')) {
            $err['error'] .= 'dt1 is missing - ';
        }
        if (!$this->input->get('dt2')) {
            $err['error'] .= 'dt2 is missing - ';
        }
        if (!$this->input->get('tprice')) {
            $err['error'] .= 'the total price amount is missing- ';
        }
        if (!$this->input->get('discount')) {
            $err['error'] = '';
            $err['error'] ? $err['error'] = 'Please Enter The Code' : $err['error'] .= 'Please Enter The Code';
        }
        if (empty($err)) {
            $period = $this->Hperiods_model->gettperiod($this->input->get('prid'));
            if ($period) {
                $discountset = $this->Hperiods_model->get_discountcodes_typed($period->providerID, 'b2c');
                $match = discountAvailability($this->input->get('dt1'), $this->input->get('dt2'), $discountset);
                if (count($match) > 0) {
                    if ($this->input->get('discount') == $match[0]['discount_codes']) {
                        $dsp = $match[0]['discount_amount'];
                        $tp = $this->input->get('tprice');
                        $matches["codestatus"] = true;
                        $matches["discount"] = $match[0]['discount_id'];
                        $matches['discount_amount'] = changeMargin(intval($dsp), '%', '-', intval($this->input->get('tprice')));
                        $matches["discounted"] = intval($this->input->get('tprice')) + $matches['discount_amount'];
                    } else {
                        $matches = ["codestatus" => false];
                    }
                } else {
                    $matches[0] = ["available_dates" => 'Not Available'];
                }
            } else {
                $matches[0] = ["available_dates" => 'Not Available'];
            }
        }
        $res = $matches ? $matches : $err;
        $this->toolset->jsonfy($res);
    }
}
