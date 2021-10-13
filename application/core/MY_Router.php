<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Router extends CI_Router
{

    protected function _parse_routes()
    {
        // Language detection over URL
        if ($this->uri->segments[1] == $this->config->config['language']) {
            unset($this->uri->segments[1]);
        }elseif (array_search($this->uri->segments[1], $this->config->config['languages'])) {
            $lng = array_search($this->uri->segments[1], $this->config->config['languages']);
            $this->config->config['language'] = $this->config->config['languages'][$lng];
            unset($this->uri->segments[1]);
            //array_splice($this->uri->segments, 1, 1);
        }
        // Return default function
        return parent::_parse_routes();
    }
}
