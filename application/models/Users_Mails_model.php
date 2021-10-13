<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_Mails_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    public function sendemail($receiver)
    {
        $from = "register@eomrah.com";
        $subject = "eomrah Verifiy Email address";
        $config['protocol'] = 'mail';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = 'TRUE';
        $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
        $this->email->set_header('Content-type', 'text/html');
        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $this->email->from($from, 'Eomrah');
        $this->email->to($receiver);
        $this->email->subject($subject);
        $data['receiver'] = $receiver;
        $message = $this->load->view('email/markedmail.php', $data, true);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            show_error(print_r($this->email->print_debugger(array('headers'))));
            return false;
        }
    }

    public function usendemail($receiver)
    {
        $from = "register@eomrah.com";
        $subject = "eomrah Verifiy Email address";
        $config['protocol'] = 'mail';
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = 'TRUE';
        $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
        $this->email->set_header('Content-type', 'text/html');
        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $this->email->from($from, 'Eomrah');
        $this->email->to($receiver);
        $this->email->subject($subject);
        $data['reciever'] = $receiver;
        $message = $this->load->view('email/usermarkedmail.php', $data, true);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    public function invoice_email($reciever, $invid)
    {
        $from = "eomrah";
        $subject = "eomrah Reservation Invoice";
        $config['protocol'] = 'mail';
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = 'TRUE';
        $this->load->model('Reservation_model', 'rsv');
        $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
        $this->email->set_header('Content-type', 'text/html');
        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $this->email->from($from, 'Eomrah');
        $this->email->to($reciever);
        $this->email->subject($subject);
        $data['idata'] = $this->rsv->get_invoice($invid);
        $data['rs_timeout'] = $this->rsv->get_timeout($data['idata']->ProviderId);
        $data['h_name'] = $this->rsv->get_r_h($invid);
        $data['idetails'] = function ($invid) {
            return $this->rsv->get_invoice_details($invid);
        };
        $data['reciever'] = $reciever;
        $message = $this->load->view('invoice', $data, true);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    public function confirmuemail($hashedkey)
    {
        $this->db->set('state', 1);
        $this->db->where('md5(Public_UserEmail)', $hashedkey);
        return $this->db->update('public_users');
    }

    public function confirmemail($hashedkey)
    {
        $this->db->set('H_Email_Verify', 1);
        $this->db->where('md5(H_User_Email)', $hashedkey);
        $confirmed = $this->db->update('hotel_sys_users');
        if ($confirmed) {
            $this->db->reset_query();
            $this->db->where('md5(H_User_Email)', $hashedkey);
            //print_r($this->db->get_compiled_select('public_users'));
            $user = $this->db->get('hotel_sys_users');
            if($user && $user->num_rows() > 0){
                $sent = $this->toolset->sendemail($user->row()->H_User_Email, "Email Confirmed successfully", 'husermailconfirmed');
                if ($sent) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public function emailexist($email)
    {
        $this->db->where('H_User_Email', $email);
        $user_mail = $this->db->get('hotel_sys_users');
        if ($user_mail->num_rows() > 0) {
            return true;
        }
    }
}
