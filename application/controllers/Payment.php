<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reservation_model', 'rsm');
    }

    public function index()
    {
        redirect('');
    }

    public function success($transcode, $resref, $mailHash)
    {
        if ($this->session->has_userdata('transcode') && $this->session->userdata('transcode') == $transcode) {
            $this->load->model('Reservation_model', 'rsm');
            $this->load->library('Lotsofhotels');

            $booking = $this->rsm->res_details($resref);
            $confirmBooking = $this->lotsofhotels->confirmBooking($booking);

            $this->rsm->confirmTransaction($booking->ID);
            $this->session->unset_userdata('transcode');
            if ($confirmBooking['result']) {
                $res_update = [
                    'lotsResId' => (int)$confirmBooking['details']->returnedCode,
                    'Paid' => 1,
                    'confirm' => (int)$confirmBooking['details']->bookings->booking->bookingStatus
                ];
                $resAllConfirmations = [];
                if (count($confirmBooking['details']->bookings->booking) > 1) {
                    foreach ($confirmBooking['details']->bookings->booking as $booked) {
                        $res_confirmation = [
                            'resId' => $confirmBooking['resID'],
                            'retunedCode' => (int)$confirmBooking['details']->returnedCode,
                            'confirmationText' => (string)$confirmBooking['details']->confirmationText,
                            'bookingCode' => (int)$booked->bookingCode,
                            'bookingRefNumber' => (string)$booked->bookingReferenceNumber,
                            'bookingStatus' => (int)$booked->bookingStatus,
                            'servicePrice' => (float)$booked->servicePrice,
                            'mealsPrice' => (float)$booked->mealsPrice,
                            'price' => (float)$booked->price,
                            'currency' => (int)$booked->currency,
                            'type' => (string)$booked->type,
                            'voucher' => (string)$booked->voucher,
                            'paymentGuaranteedBy' => (string)$booked->paymentGuaranteedBy,
                            'emergencyContacts' => (string)$booked->emergencyContacts,
                        ];
                        $resAllConfirmations[] = $res_confirmation;
                    }
                } else {
                    $res_confirmation = [
                        'resId' => $confirmBooking['resID'],
                        'retunedCode' => (int)$confirmBooking['details']->returnedCode,
                        'confirmationText' => (string)$confirmBooking['details']->confirmationText,
                        'bookingCode' => (int)$confirmBooking['details']->bookings->booking->bookingCode,
                        'bookingRefNumber' => (string)$confirmBooking['details']->bookings->booking->bookingReferenceNumber,
                        'bookingStatus' => (int)$confirmBooking['details']->bookings->booking->bookingStatus,
                        'servicePrice' => (float)$confirmBooking['details']->bookings->booking->servicePrice,
                        'mealsPrice' => (float)$confirmBooking['details']->bookings->booking->mealsPrice,
                        'price' => (float)$confirmBooking['details']->bookings->booking->price,
                        'currency' => (int)$confirmBooking['details']->bookings->booking->currency,
                        'type' => (string)$confirmBooking['details']->bookings->booking->type,
                        'voucher' => (string)$confirmBooking['details']->bookings->booking->voucher,
                        'paymentGuaranteedBy' => (string)$confirmBooking['details']->bookings->booking->paymentGuaranteedBy,
                        'emergencyContacts' => (string)$confirmBooking['details']->bookings->booking->emergencyContacts,
                    ];
                    $resAllConfirmations[] = $res_confirmation;
                }
                $saveBookingConfirmation = $this->rsm->persistPaidRes($confirmBooking['resID'], $res_update, $resAllConfirmations);
                if ($saveBookingConfirmation) {
                    redirect("user/invoice/$resref/$mailHash");
                }
                
            } else {
                $this->rsm->ResPaid($resref);
                // record the error for later fix
                // log_id 	resId 	error 	postXml 	solved 
                $this->rsm->log_lots_error(['resId'=> $confirmBooking['resID'], 'error'=> $confirmBooking['error'], 'postXml'=> $confirmBooking['req'], 'solved'=>0]);
                log_message('error', 'resid '.$resref.' ' .$confirmBooking['error']);
                redirect("user/invoice/$resref/$mailHash");
            }
        } else {
            show_error('not allowed or expired code', 500, 'reservation code expired');
        }
    }

    public function cancel($resref, $mailHash)
    {
        redirect("user/invoice/$resref/$mailHash");
    }

    public function decline($resref, $mailHash)
    {
        redirect("user/invoice/$resref/$mailHash");
    }

    public function pay($resref, $mailHash)
    {
        $this->load->library('payme');
        $this->load->model('Reservation_model', 'rsm');
        $redhead = $this->rsm->get_invoice($resref);
        $token = bin2hex(random_bytes(20));
        $this->session->set_userdata('transcode', $token);

        // redirect directly to make reservation test only
        redirect("payment/success/$token/$resref/$mailHash");

        //skip telr for lots test
        // $this->payme->init_payment('create', 'sale', $redhead, $token, $mailHash);
        // $paid = $this->payme->make_payment();
        // if (!$paid) {
        //     redirect("user/invoice/$resref/$mailHash");
        // }
    }

    public function refund()
    {
        $this->load->library('payme');
        $this->payme->init_payment('create', 'refund');
        // work needed for the refund after calcualting the fines and it`s managing panel ability
        $order_ref = get_cookie('res_order_ref');
        $paid = $this->payme->make_payment();
        if(!$paid){
            
        }
    }
}
