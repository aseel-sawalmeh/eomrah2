<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');
/**
 * @package  Hotel_Manage
 * @category Controller
 * @author   Gebriel Alkhayal
 * @param    comp()
 **/

class Hotel_manage extends Hotel_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('provider_model');
        $this->load->model('Hotel_model');
        $this->load->model('HotelSysUsers_model');
        $this->load->model('Hperiods_model');
        $this->load->library('form_validation');
        $this->load->library('calendar');
    }

    public function comset($providerid = 0)
    {
        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }

        $data['provider'] = $this->provider_model->getProvider($providerid)[0];
        $data['providerid'] = $providerid;
        $data['providedhotel'] = $this->Hotel_model->get_that_hotel($data['provider']->Hotel_ID);
        $data['hperiods'] = $this->Hperiods_model->getperiods($providerid);
        $data['hperiodtypes'] = $this->Hperiods_model->periodtypes();
        $data['tyrooms'] = $this->Hperiods_model->get_hotel_roomsgroupedtype($data['provider']->Hotel_ID);
        $data['tymeals'] = $this->Hperiods_model->get_hotel_mealsgroupedtype($data['provider']->Hotel_ID);
        $data['roomSuppTypes'] = $this->Hperiods_model->get_RoomSupptypes($providerid);
        $data['title'] = "{$data['providedhotel']->Hotel_Name} Hotel Management";
        $this->form_validation->set_rules('periodname', ' Period Name ', 'required');
        $this->form_validation->set_rules('enddate', ' Period End Date ', "callback_datesdiff_check");
        $this->form_validation->set_rules('startdate', ' Period End Date ', "callback_dateconflict_check");
        if ($this->form_validation->run() == false) {
            $this->render_view(['hotels/chotel/providers/hotel_manage_nav', 'hotels/chotel/providers/hotel_manage_comset'], $data);
        } else {
            $this->addperiod();
        }
    }

    public function comedit($providerid = 0, $periodid)
    {
        if ($providerid == 0) {
            show_error('Some data missing check you link , Or Please Report', 404, 'The page Not Found');
        }
        $data['provider'] = $this->provider_model->getProvider($providerid)[0];
        $data['providerid'] = $providerid;
        $data['periodid'] = $periodid;
        $data['providedhotel'] = $this->Hotel_model->get_that_hotel($data['provider']->Hotel_ID);
        $data['hperiods'] = $this->Hperiods_model->getperiods($providerid);
        $data['tperiod'] = $this->Hperiods_model->gettperiod($periodid);
        $data['hperiodtypes'] = $this->Hperiods_model->periodtypes();
        $data['tyrooms'] = $this->Hperiods_model->get_hotel_roomsgroupedtype($data['provider']->Hotel_ID);
        $data['roomTypeCount'] = $this->Hperiods_model->getRoomTypeCount($periodid);
        $data['PeriodRmPriceOverType'] = $this->Hperiods_model->getPeriodRmPriceOverType($periodid);
        $data['tymeals'] = $this->Hperiods_model->get_hotel_mealsgroupedtype($data['provider']->Hotel_ID);
        $data['roomSuppTypes'] = $this->Hperiods_model->get_RoomSupptypes($providerid);
        $data['roomSuppPrices'] = $this->Hperiods_model->get_roomSuppPrices($periodid);
        $data['PdaySupp'] = $this->Hperiods_model->get_PdaySupp($periodid);
        $data['get_ava'] = function ($rtype) {
            return $this->Hperiods_model->get_ava($rtype)->Available_Count;
        };
        $data['title'] = "{$data['providedhotel']->Hotel_Name} Hotel Management";
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span>');
        $this->form_validation->set_rules('periodname', 'Period Name', 'required');
        $this->form_validation->set_rules('enddate', 'Period End Date', "callback_datesdiff_check");
        $this->form_validation->set_rules('startdate', 'Period End Date', "callback_dateconflict_check");
        if ($this->form_validation->run() == false) {
            $this->render_view(['hotels/chotel/providers/hotel_manage_nav', 'hotels/chotel/providers/hotel_manage_comedit'], $data);
        } else {
            $this->editperiod();
        }
    }

    public function aj_dates_conflicts($providerid, $currentsdate, $currentedate)
    {
        $periodset = $this->Hperiods_model->getperiods($providerid);
        $conflicts = dateconflict($currentsdate, $currentedate, $periodset);
        $res = [];
        if (is_array($conflicts) && count($conflicts) > 1) {
            $res['status'] = true;
            $res['data'] = $conflicts;
        } else {
            $res['status'] = true;
            $res['data'][0]['conflict'] = "<h4>Please Check Your Dates Conflicts</h4>";
        }
        $this->toolset->jsonfy($res);
    }

    public function dateconflict_check($currentsdate)
    {
        $currentedate = $this->input->post('enddate');
        $providerid = $this->input->post('providerid');
        $periodset = $this->Hperiods_model->getperiods($providerid);
        $conflicts = dateconflict($currentsdate, $currentedate, $periodset);
        if (count((array) $conflicts) > 1) {
            $this->form_validation->set_message('dateconflict_check', '<span style="color:red">The Dates You entered conflicts with other dates </span>');
            return false;
        } elseif (count((array) $conflicts) <= 0) {
            return true;
        }
    }

    public function datesdiff_check($enddate)
    {
        $startdate = $this->input->post('startdate');
        if (datediff($startdate, $enddate) < 1) {
            $this->form_validation->set_message('datesdiff_check', '<span style="color:red">The End Date Of The Period Must Be Newer Than The SartDate</span>');
            return false;
        } else {
            return true;
        }
    }

    public function room_supplements($providerid = 0)
    {
        if ($providerid == 0) {
            show_error('Some data missing check you link , Or Please Report', 404, 'The page Not Found');
        }
        $data['provider'] = $this->provider_model->getProvider($providerid)[0];
        $data['providedhotel'] = $this->Hotel_model->get_that_hotel($data['provider']->Hotel_ID);
        $data['hperiods'] = $this->Hperiods_model->getperiods($providerid);
        $data['hperiodtypes'] = $this->Hperiods_model->periodtypes();
        $data['tyrooms'] = $this->Hperiods_model->get_hotel_roomsgroupedtype($data['provider']->Hotel_ID);
        $data['tymeals'] = $this->Hperiods_model->get_hotel_mealsgroupedtype($data['provider']->Hotel_ID);
        $data['roomSuppTypes'] = $this->Hperiods_model->get_RoomSupptypes($data['provider']->Provider_ID);
        $data['title'] = "{$data['providedhotel']->Hotel_Name} Hotel Management";
        $this->render_view(['hotels/chotel/providers/hotel_manage_nav', 'hotels/chotel/providers/hotel_manage_rsupp'], $data);
    }

    public function addroom_supp()
    {
        $providerid = $this->input->post('provider_id');
        if ($providerid == 0) {
            show_error('Some data missing check you link , Or Please Report', 404, 'The page Not Found');
        }
        if ($this->Hperiods_model->set_RoomSupptype()) {
            $this->session->set_flashdata('Supplement_mgs', "<h4 style='text-align:center; color:green'>New Supplement added successfully</h4>");
            redirect("chotel/hotel_manage/room_supplements/{$providerid}");
        } else {
            $this->session->set_flashdata('Supplement_mgs', "<h4 style='text-align:center; color:red'>Failed to add room supplement</h4>");
            redirect("chotel/hotel_manage/room_supplements/{$providerid}");
        }
    }

    public function editroom_supp()
    {
        $providerid = $this->input->post('provider_id');
        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        if ($this->Hperiods_model->edit_RoomSupptype()) {
            $this->session->set_flashdata('Supplement_mgs', "<h4 style='text-align:center; color:blue'>New Supplement edited successfully</h4>");
            redirect("chotel/hotel_manage/room_supplements/{$providerid}");
        } else {
            $this->session->set_flashdata('Supplement_mgs', "<h4 style='text-align:center; color:red'>Failed to edit room supplement</h4>");
            redirect("chotel/hotel_manage/room_supplements/{$providerid}");
        }
    }

    public function delroom_supptype($providerid = 0, $rsupptypeid)
    {
        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        if ($this->Hperiods_model->del_RoomSupptype($rsupptypeid)) {
            $this->session->set_flashdata('Supplement_mgs', "<h4 style='text-align:center; color:red'>New Supplement Deleted successfully</h4>");
            redirect("chotel/hotel_manage/room_supplements/{$providerid}");
        } else {
            $this->session->set_flashdata('Supplement_mgs', "<h4 style='text-align:center; color:red'>Failed to delete room supplement</h4>");
            redirect("chotel/hotel_manage/room_supplements/{$providerid}");
        }
    }

    public function addperiod()
    {
        $providerid = $this->input->post('providerid');
        $period_done = false;
        $period_pricemtype = false;
        $period_countrtype = false;
        $period_Rsupp = false;
        $period_Rsuppdays = false;
        if ($this->Hperiods_model->add_period()) {
            $period_id = $this->session->userdata('perio_id');
            $period_done = true;
        } else {
            $period_done = false;
            show_error('Error While Adding A New Period');
        }

        $rtypecounted = [];
        $mpricetypeds = array();
        $mpricetyped[1] = array('Period_ID' => $period_id, 'Mtype_ID' => 1, 'SGL' => '', 'DBL' => '', 'TRPL' => '', 'Quad' => '', 'Queen' => '', 'KNG' => '', 'TWN' => '', 'HtwN' => '', 'DBLTWN' => '', 'STD' => '', 'Suite' => '');
        $mpricetyped[2] = array('Period_ID' => $period_id, 'Mtype_ID' => 2, 'SGL' =>  '', 'DBL' => '', 'TRPL' => '', 'Quad' => '', 'Queen' => '', 'KNG' => '', 'TWN' => '', 'HtwN' => '', 'DBLTWN' => '', 'STD' => '', 'Suite' => '');
        $mpricetyped[3] = array('Period_ID' => $period_id, 'Mtype_ID' => 3, 'SGL' => '', 'DBL' => '', 'TRPL' => '', 'Quad' => '', 'Queen' => '', 'KNG' => '', 'TWN' => '', 'HtwN' => '', 'DBLTWN' => '', 'STD' => '', 'Suite' => '');
        $mpricetyped[4] = array('Period_ID' => $period_id, 'Mtype_ID' => 4, 'SGL' => '', 'DBL' => '', 'TRPL' => '', 'Quad' => '', 'Queen' => '', 'KNG' => '', 'TWN' => '', 'HtwN' => '', 'DBLTWN' => '', 'STD' => '', 'Suite' => '');
        $mpricetyped[5] = array('Period_ID' => $period_id, 'Mtype_ID' => 5, 'SGL' =>  '', 'DBL' => '', 'TRPL' => '', 'Quad' => '', 'Queen' => '', 'KNG' => '', 'TWN' => '', 'HtwN' => '', 'DBLTWN' => '', 'STD' => '', 'Suite' => '');
        $Rsupp = array();
        $prsuppdays = array('Period_ID' => $period_id);
        $postdata = $this->input->post();
        foreach (array_keys($postdata) as $inputfield) {
            $fieldcount = preg_match("/count/i", $inputfield);
            $fieldprice = preg_match("/typeprice/i", $inputfield);
            $R_supprice = preg_match("/rsprice/i", $inputfield);
            $R_supprice_mop = preg_match("/spricemop/i", $inputfield);
            $R_supprice_type = preg_match("/spricetype/i", $inputfield);
            $R_suppRt = preg_match("/rsst/i", $inputfield);
            $R_suppday = preg_match("/prds_/i", $inputfield);
            $R_suppdaymathop = preg_match("/dprsmathop_/i", $inputfield);
            $R_suppdaypricetype = preg_match("/dprstype_/i", $inputfield);
            if ($fieldcount) {
                $fill = ['providerID' => $providerid, 'StartDate' => $this->input->post('startdate'), 'EndDate' => $this->input->post('enddate')];
                $fill['Available_Count'] = $this->input->post($inputfield);
                $fill['R_Type_ID'] = explode("_", $inputfield)[0];
                $rtypecounted[] = $fill;
            } elseif ($fieldprice) {
                $mpricetyped[explode("_", $inputfield)[2]][explode("_", $inputfield)[0]] = $this->input->post($inputfield);
            } elseif ($R_supprice) {
                $Rsupp[explode("_", $inputfield)[1]] = array(
                    'Period_ID' => $period_id,
                    'R_SuppType_ID' => '',
                    'R_Supp_Price' => '',
                    'SupplPrice_type' => '',
                    'SupplPrice_mop' => '',
                    'SGL' => '',
                    'DBL' => '',
                    'TRPL' => '',
                    'Quad' => '',
                    'Queen' => '',
                    'KNG' => '',
                    'TWN' => '',
                    'HtwN' => '',
                    'DBLTWN' => '',
                    'STD' => '',
                    'Suite' => ''
                );
                $Rsupp[explode("_", $inputfield)[1]]['R_SuppType_ID'] = explode("_", $inputfield)[1];
                $Rsupp[explode("_", $inputfield)[1]]['R_Supp_Price'] = $this->input->post($inputfield);
            } elseif ($R_supprice_mop) { //checking the price type at first
                $Rsupp[explode("_", $inputfield)[1]]['SupplPrice_mop'] = $this->input->post($inputfield);
            } elseif ($R_supprice_type) {
                $Rsupp[explode("_", $inputfield)[1]]['SupplPrice_type'] = $this->input->post($inputfield);
            } elseif ($R_suppRt) {
                $Rsupp[explode("_", $inputfield)[2]][explode("_", $inputfield)[0]] = $this->input->post($inputfield);
            } elseif ($R_suppday == 1) {
                $prsuppdays[explode("_", $inputfield)[1]] = $this->input->post($inputfield);
            } elseif ($R_suppdaymathop) {
                $prsuppdays[explode("_", $inputfield)[1] . 'Price_Mop'] = $this->input->post($inputfield);
            } elseif ($R_suppdaypricetype) {
                $prsuppdays[explode("_", $inputfield)[1] . 'Price_Type'] = $this->input->post($inputfield);
            }
        }

        if ($period_done) {
            array_push($mpricetypeds, $mpricetyped[1], $mpricetyped[2], $mpricetyped[3], $mpricetyped[4], $mpricetyped[5]);
            if ($this->Hperiods_model->addp_mealpricetype($mpricetypeds)) {
                $period_pricemtype = true;
            } else {
                show_error('Error while setting period meals over rooms type add period');
                $period_pricemtype = false;
            }
        }

        if ($period_pricemtype) {
            if ($this->Hperiods_model->addp_roomcounttype($rtypecounted)) {
                $period_countrtype = true;
            } else {
                $period_countrtype = false;
                show_error('Error while adding Room types Count');
            }
        }

        if ($period_countrtype) {
            if ($this->input->post('nosupp') == '0') {
                $period_Rsupp = true;
            } else {
                if ($this->Hperiods_model->addp_roomsupp($Rsupp)) {
                    $period_Rsupp = true;
                } else {
                    $period_Rsupp = false;
                    show_error('Error while adding Room Supplement type000--' . $this->input->post('nosupp'));
                }
            }
        }

        if ($period_Rsupp) {
            if ($this->Hperiods_model->addp_roomsuppdays($prsuppdays)) {
                $period_Rsuppdays = true;
                $this->session->set_flashdata('Periodmsg', '<h4 class="uk-text-center"><span class="uk-alert uk-alert-success">The New Period Addedd Successfully</span> </h4>');
                redirect("chotel/hotel_manage/comset/{$providerid}");
            } else {
                $period_Rsuppdays = false;
                $this->session->set_flashdata('Periodmsg', '<h4 class="uk-text-center"><span class="uk-alert uk-alert-danger">Error while adding Period</span> </h4>');
                redirect("chotel/hotel_manage/comset/{$providerid}");
            }
        }
    }

    public function editperiod()
    {
        $providerid = $this->input->post('providerid');
        $period_id = $this->input->post('periodid');
        $period_done = false;
        $period_pricemtype = false;
        $period_countrtype = false;
        $period_Rsupp = false;
        $period_Rsuppdays = false;
        if ($this->Hperiods_model->edit_period($period_id)) {
            $period_done = true;
        } else {
            $period_done = false;
            show_error('Error While editing The Period');
        }

        $rtypecounted = array('Period_ID' => $period_id);
        $mpricetypeds = array();
        $mpricetyped[1] = array('Period_ID' => $period_id, 'Mtype_ID' => 1, 'SGL' => '', 'DBL' => '', 'TRPL' => '', 'Quad' => '', 'Queen' => '', 'KNG' => '', 'TWN' => '', 'HtwN' => '', 'DBLTWN' => '', 'STD' => '', 'Suite' => '');
        $mpricetyped[2] = array('Period_ID' => $period_id, 'Mtype_ID' => 2, 'SGL' =>  '', 'DBL' => '', 'TRPL' => '', 'Quad' => '', 'Queen' => '', 'KNG' => '', 'TWN' => '', 'HtwN' => '', 'DBLTWN' => '', 'STD' => '', 'Suite' => '');
        $mpricetyped[3] = array('Period_ID' => $period_id, 'Mtype_ID' => 3, 'SGL' => '', 'DBL' => '', 'TRPL' => '', 'Quad' => '', 'Queen' => '', 'KNG' => '', 'TWN' => '', 'HtwN' => '', 'DBLTWN' => '', 'STD' => '', 'Suite' => '');
        $mpricetyped[4] = array('Period_ID' => $period_id, 'Mtype_ID' => 4, 'SGL' => '', 'DBL' => '', 'TRPL' => '', 'Quad' => '', 'Queen' => '', 'KNG' => '', 'TWN' => '', 'HtwN' => '', 'DBLTWN' => '', 'STD' => '', 'Suite' => '');
        $mpricetyped[5] = array('Period_ID' => $period_id, 'Mtype_ID' => 5, 'SGL' =>  '', 'DBL' => '', 'TRPL' => '', 'Quad' => '', 'Queen' => '', 'KNG' => '', 'TWN' => '', 'HtwN' => '', 'DBLTWN' => '', 'STD' => '', 'Suite' => '');
        $Rsupp = array();
        $prsuppdays = array('Period_ID' => $period_id);
        $postdata = $this->input->post();
        foreach (array_keys($postdata) as $inputfield) {
            $fieldcount = preg_match("/count/i", $inputfield);
            $fieldprice = preg_match("/typeprice/i", $inputfield);
            $R_supprice = preg_match("/rsprice/i", $inputfield);
            $R_supprice_mop = preg_match("/spricemop/i", $inputfield);
            $R_supprice_type = preg_match("/spricetype/i", $inputfield);
            $R_suppRt = preg_match("/rsst/i", $inputfield);
            $R_suppday = preg_match("/prds_/i", $inputfield);
            $R_suppdaymathop = preg_match("/dprsmathop_/i", $inputfield);
            $R_suppdaypricetype = preg_match("/dprstype_/i", $inputfield);
            if ($fieldcount == 1) {
                $rtypecounted[explode("_", $inputfield)[0]] = $this->input->post($inputfield);
            } elseif ($fieldprice == 1) {
                $mpricetyped[explode("_", $inputfield)[2]][explode("_", $inputfield)[0]] = $this->input->post($inputfield);
            } elseif ($R_supprice == 1) {
                $Rsupp[explode("_", $inputfield)[1]] = array(
                    'Period_ID' => $period_id,
                    'R_SuppType_ID' => '',
                    'R_Supp_Price' => '',
                    'SupplPrice_type' => '',
                    'SupplPrice_mop' => '',
                    'SGL' => '',
                    'DBL' => '',
                    'TRPL' => '',
                    'Quad' => '',
                    'Queen' => '',
                    'KNG' => '',
                    'TWN' => '',
                    'HtwN' => '',
                    'DBLTWN' => '',
                    'STD' => '',
                    'Suite' => ''
                );
                $Rsupp[explode("_", $inputfield)[1]]['R_SuppType_ID'] = explode("_", $inputfield)[1];
                $Rsupp[explode("_", $inputfield)[1]]['R_Supp_Price'] = $this->input->post($inputfield);
            } elseif ($R_supprice_type == 1) {
                $Rsupp[explode("_", $inputfield)[1]]['SupplPrice_type'] = $this->input->post($inputfield);
            } elseif ($R_supprice_mop == 1) { //checking the price type at first
                $Rsupp[explode("_", $inputfield)[1]]['SupplPrice_mop'] = $this->input->post($inputfield);
            } elseif ($R_suppRt == 1) {
                $Rsupp[explode("_", $inputfield)[2]][explode("_", $inputfield)[0]] = $this->input->post($inputfield);
            } elseif ($R_suppday == 1) {
                $prsuppdays[explode("_", $inputfield)[1]] = $this->input->post($inputfield);
            } elseif ($R_suppdaymathop == 1) {
                $prsuppdays[explode("_", $inputfield)[1] . 'Price_Mop'] = $this->input->post($inputfield);
            } elseif ($R_suppdaypricetype == 1) {
                $prsuppdays[explode("_", $inputfield)[1] . 'Price_Type'] = $this->input->post($inputfield);
            }
        }
        if ($period_done) {
            array_push($mpricetypeds, $mpricetyped[1], $mpricetyped[2], $mpricetyped[3], $mpricetyped[4], $mpricetyped[5]);
            if ($this->Hperiods_model->editp_mealpricetype($mpricetypeds)) {
                $period_pricemtype = true;
            } else {
                show_error('Error while setting period meals over rooms type');
                $period_pricemtype = false;
            }
        }
        if ($period_pricemtype) {
            if ($this->Hperiods_model->editp_roomcounttype($rtypecounted)) {
                $period_countrtype = true;
            } else {
                $period_countrtype = false;
                show_error('Error while adding Room types Count');
            }
        }
        if ($period_countrtype) {
            if ($this->input->post('nosupp')) {
                $period_Rsupp = true;
            } else {
                if ($this->Hperiods_model->editp_roomsupp($Rsupp)) {
                    $period_Rsupp = true;
                } else {
                    $period_Rsupp = false;
                    show_error('Error while adding Room Supplement type');
                }
            }
        }
        if ($period_Rsupp) {
            if ($this->Hperiods_model->editp_roomsuppdays($prsuppdays)) {
                $period_Rsuppdays = true;
                $this->session->set_flashdata('Periodmsg', '<h4 class="text-center"><span class="text-success text-center">The Period edited Successfully</span> </h4>');
                redirect("chotel/hotel_manage/comedit/{$providerid}/{$period_id}");
            } else {
                $period_Rsuppdays = false;
                show_error('Error while adding Room supplements days');
            }
        }
    }

    public function delete_period($providerid = 0, $period_id)
    {

        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        if ($this->Hperiods_model->del_period($period_id)) {
            $this->session->set_flashdata('period_mgs', "Period Deleted successfully");
            redirect("chotel/hotel_manage/comset/{$providerid}");
        } else {
            $this->session->set_flashdata('period_mgs', "Failed to delete period");
            redirect("chotel/hotel_manage/comset/{$providerid}");
        }
    }

    public function room_availability($providerid = 0, $month = null)
    {
        $this->load->library('avcal');
        $this->avcal->_init($providerid, $month ?? null);
        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        $data['provider'] = $this->provider_model->getProvider($providerid)[0];
        $data['providerid'] = $providerid;
        $data['providedhotel'] = $this->Hotel_model->get_that_hotel($data['provider']->Hotel_ID);
        $data['hperiods'] = $this->Hperiods_model->getperiods($providerid);
        $data['hperiodtypes'] = $this->Hperiods_model->periodtypes();
        $data['tyrooms'] = $this->Hperiods_model->get_hotel_roomsgroupedtype($data['provider']->Hotel_ID);
        $data['tymeals'] = $this->Hperiods_model->get_hotel_mealsgroupedtype($data['provider']->Hotel_ID);
        $data['roomSuppTypes'] = $this->Hperiods_model->get_RoomSupptypes($data['provider']->Provider_ID);
        $data['earliestDate'] = $this->Hperiods_model->roomAvearliestDate($providerid)->StartDate;
        $data['pmonthsava'] = $this->Hperiods_model->ProomAvMonths($providerid);
        $data['monthsava'] = $this->Hperiods_model->roomAvMonths($providerid);
        $data['datesava'] = $this->Hperiods_model->roomAvdates($providerid);
        $data['pdatesava'] = $this->Hperiods_model->ProomAvdates($providerid);
        $data['title'] = "{$data['providedhotel']->Hotel_Name} Hotel Management";
        $this->form_validation->set_rules('startdate', 'Start Date', 'required');
        $this->form_validation->set_rules('enddate', 'End Date', 'required');
        $this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        if ($this->form_validation->run() == false) {
            $this->render_view(['hotels/chotel/providers/hotel_manage_nav', 'hotels/chotel/providers/hotel_manage_ravailability'], $data);
        } else {
            $set_Av = $this->Hperiods_model->set_RoomAvailability($providerid, $data['tyrooms']);
            if ($set_Av) {
                $this->session->set_flashdata('roomAvailMsg', "Availaibility settings Added Successfully");
                redirect("chotel/hotel_manage/room_availability/{$providerid}");
            } else {
                $this->session->set_flashdata('roomAvailMsg', "Failed to Add Availaibility settings");
                redirect("chotel/hotel_manage/room_availability/{$providerid}");
            }
        }
    }

    public function rv2($providerid = 0, $month = null)
    {
        $this->load->library('avcal');
        $this->load->library('avcal');
        $this->avcal->_init($providerid, $month ?? null);

        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        print_r($this->avcal->reservations);
        $data['provider'] = $this->provider_model->getProvider($providerid)[0];
        $data['providedhotel'] = $this->Hotel_model->get_that_hotel($data['provider']->Hotel_ID);
        $data['hperiods'] = $this->Hperiods_model->getperiods($providerid);
        $data['hperiodtypes'] = $this->Hperiods_model->periodtypes();
        $data['tyrooms'] = $this->Hperiods_model->get_hotel_roomsgroupedtype($data['provider']->Hotel_ID);
        $data['tymeals'] = $this->Hperiods_model->get_hotel_mealsgroupedtype($data['provider']->Hotel_ID);
        $data['roomSuppTypes'] = $this->Hperiods_model->get_RoomSupptypes($data['provider']->Provider_ID);
        $data['earliestDate'] = $this->Hperiods_model->roomAvearliestDate($providerid)->StartDate;
        $data['pmonthsava'] = $this->Hperiods_model->ProomAvMonths($providerid);
        $data['monthsava'] = $this->Hperiods_model->roomAvMonths($providerid);
        $data['datesava'] = $this->Hperiods_model->roomAvdates($providerid);
        $data['pdatesava'] = $this->Hperiods_model->ProomAvdates($providerid);
        $data['title'] = "{$data['providedhotel']->Hotel_Name} Hotel Management";
        $this->form_validation->set_rules('startdate', 'Start Date', 'required');
        $this->form_validation->set_rules('enddate', 'End Date', 'required');
        $this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        if ($this->form_validation->run() == false) {
            $this->render_view(['hotel_control/providers/hotel_manage_nav', 'hotel_control/providers/rv2'], $data);
        } else {
            $set_Av = $this->Hperiods_model->set_RoomAvailability($providerid, $data['tyrooms']);
            if ($set_Av) {
                $this->session->set_flashdata('roomAvailMsg', "Availaibility settings Added Successfully");
                redirect("hotel_control/hotel_manage/room_availability/{$providerid}");
            } else {
                $this->session->set_flashdata('roomAvailMsg', "Failed to Add Availaibility settings");
                redirect("hotel_control/hotel_manage/room_availability/{$providerid}");
            }
        }
    }

    public function payment_config($providerid = 0)
    {

        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        $data['provider'] = $this->provider_model->getProvider($providerid)[0];
        $data['providerid'] = $providerid;
        $data['providedhotel'] = $this->Hotel_model->get_that_hotel($data['provider']->Hotel_ID);
        $data['earliestDate'] = $this->Hperiods_model->payAvearliestDate($providerid)->StartDate;
        $data['paymonthsava'] = $this->Hperiods_model->payAvMonths($providerid);
        $data['paydatesava'] = $this->Hperiods_model->payAvdates($providerid);
        $data['Paymethods'] = $this->Hperiods_model->get_payment_method($providerid);
        $data['title'] = "{$data['providedhotel']->Hotel_Name} Hotel Management";
        $this->form_validation->set_rules('startdate', 'Start Date', 'required');
        $this->form_validation->set_rules('enddate', 'End Date', 'required');
        $this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/chotel/providers/hotel_manage_payment', $data);
        } else {
            if ($this->Hperiods_model->set_payAvailability($providerid)) {
                $this->session->set_flashdata('payAvailMsg', "Payment Availaibility settings Added Successfully");
                redirect("chotel/hotel_manage/payment_config/{$providerid}");
            } else {
                $this->session->set_flashdata('payAvailMsg', "Failed to Add payment Availaibility settings");
                redirect("chotel/hotel_manage/payment_config/{$providerid}");
            }
        }
    }

    public function add_paymentmethod($providerid = 0)
    {
        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        $this->load->model('Hperiods_model');
        if ($this->Hperiods_model->add_payment_method($providerid)) {
            $this->session->set_flashdata('roomAvailMsg', "<h3>Payment Method Added Successfully</h3>");
            redirect("chotel/hotel_manage/payment_config/{$providerid}");
        } else {
            $this->session->set_flashdata('roomAvailMsg', "<h3>Failed to Add Payment Method</h3>");
            redirect("chotel/hotel_manage/payment_config/{$providerid}");
        }
    }

    public function del_paymentmethod($providerid = 0, $paymethodid)
    {
        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        $this->load->model('Hperiods_model');
        if ($this->Hperiods_model->del_payment_method($paymethodid)) {
            $this->session->set_flashdata('roomAvailMsg', "<h3>Payment Method Deleted Successfully</h3>");
            redirect("chotel/hotel_manage/payment_config/{$providerid}");
        } else {
            $this->session->set_flashdata('roomAvailMsg', "<h3>Failed to Delete Payment Method</h3>");
            redirect("chotel/hotel_manage/payment_config/{$providerid}");
        }
    }

    public function r_update_av()
    {
        $providerid = $this->input->post('providerid');
        if ($this->Hperiods_model->roomAvdates_update()) {
            $this->session->set_flashdata('roomAvaildatesMsg', "<h4>Availaibility dates updated Successfully</h4>");
            redirect("chotel/hotel_manage/room_availability/{$providerid}");
        } else {
            $this->session->set_flashdata('roomAvaildatesMsg', "<h4>Failed to Update Availaibility dates</h4>");
            redirect("chotel/hotel_manage/room_availability/{$providerid}");
        }
    }

    public function r_delete_av($providerid, $ravaid)
    {
        if ($this->Hperiods_model->roomAvdates_delete($ravaid)) {
            $this->session->set_flashdata('roomAvaildatesMsg', "<h4>Availaibility dates Deleted Successfully</h4>");
            redirect("chotel/hotel_manage/room_availability/{$providerid}");
        } else {
            $this->session->set_flashdata('roomAvaildatesMsg', "<h4>Failed to Delete Availaibility dates</h4>");
            redirect("chotel/hotel_manage/room_availability/{$providerid}");
        }
    }

    public function pay_update_av()
    {
        $providerid = $this->input->post('providerid');
        if ($this->Hperiods_model->payAvdates_update()) {
            $this->session->set_flashdata('payAvaildatesMsg', "<h4>Availaibility dates updated Successfully</h4>");
            redirect("chotel/hotel_manage/payment_config/{$providerid}");
        } else {
            $this->session->set_flashdata('payAvaildatesMsg', "<h4>Failed to Update Availaibility dates</h4>");
            redirect("chotel/hotel_manage/payment_config/{$providerid}");
        }
    }

    public function pay_delete_av($providerid, $payavaid)
    {
        if ($this->Hperiods_model->payAvdates_delete($payavaid)) {
            $this->session->set_flashdata('payAvaildatesMsg', "<h4>Pay Availaibility dates Deleted Successfully</h4>");
            redirect("chotel/hotel_manage/payment_config/{$providerid}");
        } else {
            $this->session->set_flashdata('payAvaildatesMsg', "<h4>Failed to Delete Pay Availaibility dates</h4>");
            redirect("chotel/hotel_manage/payment_config/{$providerid}");
        }
    }

    public function discount_code($providerid = 0)
    {
        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        $data['provider'] = $this->provider_model->getProvider($providerid)[0];
        $data['providerid'] = $providerid;
        $data['providedhotel'] = $this->Hotel_model->get_that_hotel($data['provider']->Hotel_ID);
        $data['discodes'] = $this->Hperiods_model->get_discountcodes($providerid);
        $data['title'] = "{$data['providedhotel']->Hotel_Name} Hotel Management";
        $this->form_validation->set_rules('discode', 'Discount Code', 'required');
        $this->form_validation->set_rules('startdate', 'Start Date', 'required');
        $this->form_validation->set_rules('enddate', 'End Date', 'required');
        $this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        if ($this->form_validation->run() == false) {
            $this->render_view(['hotels/chotel/providers/hotel_manage_nav','hotels/chotel/providers/hotel_manage_discount_code'], $data);
        } else {
            if ($this->Hperiods_model->add_discountcode($providerid)) {
                $this->session->set_flashdata('DiscountcodeMsg', "<h2 style='color:lightgreen'>Dicount Code Added Successfully</h2>");
                redirect("chotel/hotel_manage/discount_code/{$providerid}");
            } else {
                $this->session->set_flashdata('DiscountcodeMsg', "<h2 style='color:red'>Failed to Add Discount Code</h2>");
                redirect("chotel/hotel_manage/discount_code/{$providerid}");
            }
        }
    }

    public function discount_code_edit($providerid = 0, $discodeid = 0)
    {
        if ($discodeid == 0 && $discodeid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        $data['provider'] = $this->provider_model->getProvider($providerid)[0];
        $data['providerid'] = $providerid;
        $data['code'] = $discodeid;
        $data['providedhotel'] = $this->Hotel_model->get_that_hotel($data['provider']->Hotel_ID);
        $data['discode'] = $this->Hperiods_model->discountcode($discodeid);
        $data['title'] = "{$data['providedhotel']->Hotel_Name} Hotel Management";
        $this->form_validation->set_rules('discode', 'Discount Code', 'required');
        $this->form_validation->set_rules('startdate', 'Start Date', 'required');
        $this->form_validation->set_rules('enddate', 'End Date', 'required');
        $this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        if ($this->form_validation->run() == false) {
            $this->render_view(['hotels/chotel/providers/hotel_manage_nav','hotels/chotel/providers/hotel_manage_discount_code_edit'], $data);
        } else {
            if ($this->Hperiods_model->edit_discountcode($discodeid)) {
                $this->session->set_flashdata('DiscountcodeMsg', "<h2 style='color:lightgreen'>Dicount Code edited Successfully</h2>");
                redirect("chotel/hotel_manage/discount_code/{$providerid}");
            } else {
                $this->session->set_flashdata('DiscountcodeMsg', "<h2 style='color:red'>Failed to edit Discount Code</h2>");
                redirect("chotel/hotel_manage/discount_code/{$providerid}");
            }
        }
    }

    public function discount_code_cal($providerid = 0)
    {
        if ($providerid == 0) {
            show_error("Some data missing check you link , Or Please Report", 404, "The page Not Found");
        }
        $data['provider'] = $this->provider_model->getProvider($providerid)[0];
        $data['providedhotel'] = $this->Hotel_model->get_that_hotel($data['provider']->Hotel_ID);
        $data['discountmonthsava'] = $this->Hperiods_model->discountAvMonths($providerid);
        $data['discountdatesava'] = $this->Hperiods_model->discountsAvdates($providerid);
        $data['Paymethods'] = $this->Hperiods_model->get_payment_method($providerid);
        $data['title'] = "{$data['providedhotel']->Hotel_Name} Hotel Management";
        $this->form_validation->set_rules('startdate', 'Start Date', 'required');
        $this->form_validation->set_rules('enddate', 'End Date', 'required');
        $this->form_validation->set_error_delimiters('<div class="uk-alert uk-alert-danger">', '</div>');
        if ($this->form_validation->run() == false) {
            $this->render_view(['hotels/chotel/providers/hotel_manage_nav','hotels/chotel/providers/hotel_manage_discount_code_cal'], $data);
        } else {
            if ($this->Hperiods_model->add_discountcode($providerid)) {
                $this->session->set_flashdata('DiscountcodeMsg', "Dicount Code Added Successfully");
                redirect("chotel/hotel_manage/payment_config/{$providerid}");
            } else {
                $this->session->set_flashdata('DiscountcodeMsg', "Failed to Add Discount Code");
                redirect("chotel/hotel_manage/payment_config/{$providerid}");
            }
        }
    }
}


