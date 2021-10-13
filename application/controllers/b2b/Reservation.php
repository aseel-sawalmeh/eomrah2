<?php
defined('BASEPATH') or exit('No direct scripts allowed');

class Reservation extends B2b_Controller{

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
    }
    
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
            $this->render_view(['hotels/b2b/reservation'], $data);
        } else {
            redirect('');
        }
    }

    public function checkout()
    {
        $puserid = '';
        $userphone = '';
        $userfname = '';
        $useremail = '';
       
        $puserid = $this->session->userdata('C_ID');
        $userphone = $this->session->userdata('C_PhoneNumber');
        $userfname = $this->session->userdata('C_FullName');
        $useremail = $this->session->userdata('C_Email');
    
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
        $res['b2b'] = $wallet['b2b'];
        $res['Payment_method'] = $this->input->post('pmethod');
        
    
        if ($this->input->post('gname') && $this->input->post('gemail')) {
            $res['guest_name'] = $this->input->post('gname');
            $res['guest_email'] = $this->input->post('gemail');
        }

        $resid = $this->rsm->set_res($res);
        if ($resid) {
            $proRooms = json_decode($this->input->post('rooms'));
            $addresrooms = $this->rsm->set_resdetails($proRooms, $resid);
            $saveTransaction = $this->rsm->saveTransaction(['user_id' => $puserid, 'service_type' => 'reservation', 'service_id' => $resid, 'amount' => $res['NetPrice'], 'paid' => 0]);
            if (!$saveTransaction) show_error('transaction error');
            if ($addresrooms) {
                $refid = $this->rsm->get_resref($resid);
                $data['idata'] = $this->rsm->get_invoice($refid);
                $data['idetails'] = $this->rsm->get_invoice_details($data['idata']->ID);
                
                $this->toolset->jsonfy(['result' => true, 'rd' => site_url("b2b/dashboard/pinv/$refid")]);
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