<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Config extends CI_Config
{

    public function __construct()
    {
        parent::__construct();
    }

    public function site_url($uri = '', $protocol = null)
    {
        if (is_array($uri)) {
            $uri = implode('/', $uri);
        }

        if (class_exists('CI_Controller')) {
            $uri = userlang() . '/' . $uri;
            //$uri = '/' . $uri;
        }
        return parent::site_url($uri);
    }
}
