<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');
/*
 * This Library to add some tools customized for The work
 * Made to extend the core as Version one additional toolset
 * PHP Version 7.3
 * Auther Gebriel Alkhayal
 * toolset Version 1.0
 */
class Toolset
{
    private $ts;

    public function __construct()
    {
        // Assign by reference with "&" so we don't create a copy
        $this->ts = &get_instance();
        $this->ts->load->library('email');
    }
    // used to make a thumb name from the uploaded photos
    public function img_thumb($image_name)
    {
        //The Way It work Is only for thumb make
        return substr_replace($image_name, "_thumb", stripos($image_name, '.'), 0);
    }

    //Generate random string used as a coupon
    public function coupon_generate($length)
    {
        //$length = 9;
        $code = (strtoupper(substr(md5(time()), 0, $length)));
        return $code;
    }
    //Generate random string used as a coupon
    public function genid()
    {
        $date = new DateTime();
        $date->setTimestamp(now());
        $time_based_name = $date->format('YmdHis');
        return $time_based_name . rand(1, 9);
    }

    public function jsonfyo($data)
    {
        $corsOrigin = $this->ts->output->get_header('Origin');
        $corsMethod = $this->ts->output->get_header('Access-Control-Request-Method');
        $corsHeaders = $this->ts->output->get_header('Access-Control-Request-Headers');
        if ($corsOrigin == null || $corsOrigin == "null") {
            $corsOrigin = "*";
        }
        $this->ts->output
            ->set_status_header(200)
            ->set_header("Access-Control-Allow-Origin", $corsOrigin) //development only future update
            ->set_header("Access-Control-Request-Method", $corsMethod) //development only future update
            ->set_header("Access-Control-Request-Headers", $corsHeaders) //development only future update
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function jsonfy($data = [])
    {
        // $ts = &get_instance();
        $heads[] = "HTTP/1.1 200 OK";
        $heads[] = "Content-Type: application/json;charset=utf-8";
        $heads[] = "Accept: application/json";
        $heads[] = "X-Powered-By: eomrah";
        $heads[] = "Vary: Origin, Accept-Encoding";
        $heads[] = "Access-Control-Allow-Origin: *";
        foreach ($heads as $head) {
            header($head);
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function sendemail($receiver, $subject = null, $view, $data = [])
    {
        $from = "contact@eomrah.com";
        if ($subject == null) {
            $subject = "eomrah Verifiy Email address";
        } else {
            $subject = $subject;
        }
        $config['protocol'] = 'mail';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = 'TRUE';
        $this->ts->email->set_header('MIME-Version', '1.0; charset=utf-8');
        $this->ts->email->set_header('Content-type', 'text/html');
        $this->ts->email->set_newline("\r\n");
        $this->ts->email->initialize($config);
        $this->ts->email->from($from, 'Eomrah');
        $this->ts->email->to($receiver);
        $this->ts->email->subject($subject);
        $data['receiver'] = $receiver;
        $message = $this->ts->load->view("email/$view", $data, true);
        $this->ts->email->message($message);
        if ($this->ts->email->send()) {
            return true;
        } else {
            show_error(print_r($this->ts->email->print_debugger(array('headers'))));
            return false;
        }
    }

}
