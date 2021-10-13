<?php defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->library('user_agent');
    }
    public function index()
    {
        $this->load->model('translate/Translate_model');
        $data['title'] = "Google translation test";
        $text = $this->input->post('transtxt');
        $from = $this->input->post('fromlang');
        $to = $this->input->post('tolang');
        $data['txtsource'] = $text;
        //$data['langs'] = langs_codes();
        $data['translated'] = trans($text, $from, $to);
        $data['cols'] = $this->Translate_model->get_cols();
        $data['langs'] = $this->Translate_model->get_langs();
        $this->load->view('api_view', $data);
    }

    public function prefs()
    {
        $key = $this->input->get('authkey');
        $api = 'df311e248416b7d25ac3abeeff284735ec96e817ffe8';

        if ($key == $api) {
            $prefs = array('lang' => userlang(), 'currency' => usercur(), 'base_url' => site_url());
            $this->toolset->jsonfy($prefs);

        } else {
            header('HTTP/1.0 401 Unauthorized');
            die("Not authorized");
        }

    }

    public function langtr()
    {
        $key = $this->input->get('authkey');
        $trkey = $this->input->get('key');
        $api = 'df311e248416b7d25ac3abeeff284735ec96e817ffe8';

        if ($key == $api && $trkey !== null) {
            $this->lang->load('general', userlang());
            $prefs = array('txt' => lang($trkey));
            $this->toolset->jsonfy($prefs);

        } else {
            //header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            die("Not authorized");

        }

    }
}
