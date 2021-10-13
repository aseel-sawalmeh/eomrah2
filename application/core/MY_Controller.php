<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

class MY_Controller extends CI_Controller
{
    //setting the data array
    public $data = array();

    public function __construct()
    {
        parent::__construct();
        initlang();
        initcurrency();
        $ifget = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
        $seg = explode('/', $this->uri->uri_string())[0];
        if (!in_array($seg, ['ar', 'en'], false) && $this->uri->segment(1) !== null) {
            header("Location: " . site_url($this->uri->uri_string(), PROTO) . $ifget, true, 301);
        } else {
            if ($seg !== userlang()) {
                $uri = explode('/', $this->uri->uri_string());
                unset($uri[0]);
                //edit url
                header("Location: " . site_url($uri, PROTO) . $ifget, true, 301);
            }
        }
        $this->data['errors'] = array();
        $this->data['site_name'] = config_item('site_name');
    }
}
