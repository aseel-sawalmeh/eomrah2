<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Flights extends Front_Controller {

    public function index(){
        $data['title'] = 'flights result';
        $this->render_view(['hotels/inc/search_area', 'flights/result'], $data);
    }
}