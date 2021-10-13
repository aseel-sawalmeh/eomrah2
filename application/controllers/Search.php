<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auto_model', 'am');
        $this->load->model('fendmodels/fendproducts_model');
        $this->load->model('Product_Categories_model');
        $this->load->model('Products_model');
        $this->load->library('toolset');
        $this->load->library('magician');
        $this->load->helper('gmaps');
    }

    public function index()
    {
        $data['startdate'] = '';
        $data['enddate'] = '';
        $data['adultscount'] = 0;
        $data['hotels'] = $this->am->fetch_data();
        $hotelids = $data['hotels'];
        if (!empty($this->input->get('book-date'))) {
            if ($this->input->get('book-date') && $this->input->get('adults') && !empty($this->input->get('book-date')) && !empty($this->input->get('find'))) {
                $book_date = explode('-', $this->input->get('book-date')) ? explode('-', $this->input->get('book-date')) : [1, 1];
                $data['startdate'] = date('d-m-Y', strtotime($book_date[0]));
                $data['enddate'] = date('d-m-Y', strtotime($book_date[1]));
                $data['adultscount'] = $this->input->get('adults');
                $this->magician->setPuserId($this->session->userdata('User_data')['userID']);
                $this->magician->do_magic($hotelids, $data['startdate'], $data['enddate'], $data['adultscount']);
            } else {
                redirect('home', 'refresh');
            }
        } elseif ($this->input->get('dt1') && $this->input->get('dt2') && $this->input->get('adults') && !empty($this->input->get('find'))) {
            $data['startdate'] = date('d-m-Y', strtotime($this->input->get('dt1')));
            $data['enddate'] = date('d-m-Y', strtotime($this->input->get('dt2')));
            $data['adultscount'] = $this->input->get('adults');
            $this->magician->setPuserId($this->session->userdata('User_data')['userID']);
            $this->magician->do_magic($hotelids, $data['startdate'], $data['enddate'], $data['adultscount']);
        } else {
            redirect('home', 'refresh');
        }
        $data['rec_results'] = $this->magician->sorted_res;
        $data['rec_js'] = json_encode($this->magician->sorted_res);
        $data['title'] = 'search results';
        $this->render_view('hotels/search_results', $data);
    }

    public function s_bar()
    {
        $txt = html_escape($this->input->get('find'));
        $resdata = $this->am->find($txt) ? $this->am->find($txt) : array('res' => "sorry no result");
        $gres = gplaces2($txt, 'autodetect');
        //$gresulng = gplaces2($txt, userlang());
        //show_error(print_r($gres));
        if ($gres !== null && is_countable($gres)) {
            for ($i = 0; $i < count($gres); $i++) {
                $resdata[] = $gres[$i]['name'];
                $resdata[] = $gres[$i]['formatted_address'];
                // $resdata[] = $gresulng[$i]['name'];
                // $resdata[] = $gresulng[$i]['formatted_address'];
                //show_error(print_r($gres[$i]['name']));
            }
        }
        $this->toolset->jsonfy($resdata);
    }

    public function tr()
    {
        initlang();
        $txt = $this->input->get('text');
        if ($txt) {
            $res['status'] = true;
            $res['result'] = comtrans($txt);
        } else {
            $res['status'] = false;
            $res['result'] = "No Translation Available";
        }
        $this->toolset->jsonfy($res);
    }
}
