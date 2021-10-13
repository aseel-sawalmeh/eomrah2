<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Outer extends Front_Controller
{
    private $today;
    private $tomorrow;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reservation_model', 'rsm');
        $this->load->model('Product_Categories_model');
        $this->load->model('fendmodels/fendproducts_model');
        $this->load->model('fendmodels/fphoto_model');
        $this->load->model('translate/Translate_model');
        $this->load->model('Multimedia_model');
        $this->load->model('Hotel_model', 'ht');
        $this->load->model('Products_model');
        $this->load->model('Auto_model');
        $this->load->model('Products_model');
        $this->today = date("Y-m-d");
        $this->tomorrow = date("Y-m-d", strtotime("+1 day"));

    }

    public function index()
    {
        initlang();
        $this->load->library('toolset');
        $data['list_hotels'] = $this->Products_model->get_active_allproducts();
        $data['title'] = comtrans('home');

        $container['today'] = $this->today;
        $container['tomorrow'] = $this->tomorrow;

        $container['homeTop'] = $this->fendproducts_model->top_products();

        $this->toolset->jsonfy($container);
    }

    public function search()
    {
        initlang();
        $this->load->library('toolset');
        $this->load->library('magician');
        if (!empty($this->input->get('book-date'))) {
            if ($this->input->get('book-date') && $this->input->get('adults') && !empty($this->input->get('book-date') && !empty($this->input->get('find'))) ) {
                $book_date = explode('-', $this->input->get('book-date'))?explode('-', $this->input->get('book-date')):[1,1];
                $data['startdate'] = $book_date[0];
                $data['enddate'] = $book_date[1];
                $data['adultscount'] = $this->input->get('adults');
            }

        } elseif ($this->input->get('dfrom') && $this->input->get('dto') && $this->input->get('adults') && !empty($this->input->get('find')) ) {
            $data['startdate'] = $this->input->get('dfrom');
            $data['enddate'] = $this->input->get('dto');
            $data['adultscount'] = $this->input->get('adults');
        } 
        $data['hotels'] = $this->Auto_model->fetch_data();
        $hotelids = $data['hotels'];
        $this->magician->setPuserId($this->session->userdata('User_data')['userID']);
        $this->magician->do_magic($hotelids, $data['startdate'], $data['enddate'], $data['adultscount']);
        $data['rec_results'] = $this->magician->sorted_res;
        $this->toolset->jsonfy($this->magician->sorted_res);
    }

    public function bestdeals($pid)
    {
        $data['product'] = $this->fendproducts_model->fetch_data($pid);
        $data['hotel'] = $this->ht->get_that_hotel($data['product']->P_Item_ID);
        $data['similarproducts'] = $this->fendproducts_model->top_products();
        $data['productscript'] = true;
        $data['imgs'] = $this->fphoto_model->photos($pid);
        $data['product_multimedia'] = $this->Multimedia_model->get_product_multimedia($pid);
        $data['title'] = tolang($pid, 'prodtitle') ? tolang($pid, 'prodtitle') : $data['product']->P_Name;
        $data['multilang_titles'] = $this->Translate_model->product_titles($pid);
        $this->render_view('top_hotels',$data);
        
    }

    public function pdetail($pid)
    {
        $this->load->library('magician');
        $this->load->library('table');
        $data['product'] = $this->fendproducts_model->fetch_data($pid);
        $data['similarproducts'] = $this->fendproducts_model->top_products();
        $data['productscript'] = true;
        $data['imgs'] = $this->fphoto_model->photos($pid);
        $data['product_multimedia'] = $this->Multimedia_model->get_product_multimedia($pid);
        $data['title'] = tolang($pid, 'prodtitle') ? tolang($pid, 'prodtitle') : $data['product']->P_Name;
        $data['hotel'] = $this->ht->get_that_hotel($data['product']->P_Item_ID);
        $data['multilang_titles'] = $this->Translate_model->product_titles($pid);
        if ($this->input->get('h')) {
            $hotelid[0] = array('Hotel_ID' => $this->input->get('h'));
            $data['hotel_details'] = $this->ht->get_that_hotel($hotelid[0]['Hotel_ID']);
            $startdate = $this->input->get('dt1');
            $enddate = $this->input->get('dt2');
            $adultscount = $this->input->get('adults');
            $data['guests'] = $adultscount;
            $this->magician->setPuserId($this->session->userdata('User_data')['userID']);
            $this->magician->do_magic($hotelid, $startdate, $enddate, $adultscount);
            $data['magicdata'] = $this->magician->rec_result;
            $data['hprops'] = $this->magician->hotel_props;
            $data['head'] = $this->magician->ResHeads;
            $template = array(
                'table_open' => '<!-- Start PriceTable --><table id="pricetable" class="table table-responsive table-condensed text-center">',
                'table_close' => '</table><!-- End PriceTable -->',
            );

            $this->table->set_template($template);
            $this->table->set_heading(['Room', 'Price', 'Meals', 'Rooms']);
            foreach ($this->magician->hotel_props['roomtypes'] as $t) {
                $row = [];
                $row[0] = $t . '<br>';
                $typed = 0;
                foreach ($this->magician->hotel_props[$t] as $res) {
                    if ($res['av_count'] > 0) {
                        $rtm = $res['room_type'] . '_' . $res['snmeal_type'];

                        for ($i = 0; $i < $res['Adlult_Count']; $i++) {
                            $row[0] .= '<i class="fa fa-user"></i>';
                        }

                        $row['prices'] = "<span id='{$rtm}'>" . $res['price_per_night'] . '</span><br>';
                        $row['chs'] = $res['meal_type'] . '<br>';
                        $row['chs'] .= '<input type="hidden" id="hidden_result_count_' . $rtm . '"' . ' name="' . $rtm . '_count" value="" />';
                        $row['chs'] .= '<input type="hidden" id="hidden_result_price_' . $rtm . '"';
                        $row['chs'] .= ' name="' . $rtm . '_price" value="" />';
                        $row['chs'] .= '<input type="hidden" id="' . $res['room_type'] . 'maxres" value="' . mxrs($res['av_count']) . '"/>';
                        $row['sr'] = '<select id="' . $rtm . '_count" ' . 'onchange="price_calc(this.value, \'' . $rtm . '\')">';
                        $row['sr'] .= '<option value="0" >0</option>';
                        for ($i = 1; $i <= mxrs($res['av_count']); $i++) {
                            $row['sr'] .= '<option value="' . $i . '">' . $i . ' [' . $i * $res['price_per_night'] . ']</option>';
                        }
                        $row['sr'] .= '</select>';
                        if ($typed > 0) {$row[0] = '';}
                        $this->table->add_row($row);
                        $typed = 1;
                    }
                }
            }
            $data['datatable'] = $this->table->generate();
        } else {
            $data['magicdata'] = false;
        }
        $data['pscritp'] = "pscritp";
        $data['langs'] = langs();
        $this->render_view('fendprod/pdetails', $data);
    }

    public function chlang()
    {
        $langcode = $this->input->post('tolng');
        set_cookie('language', $langcode, 86400);
        $res['status'] = true;
        $this->toolset->jsonfy($res);
    }

    public function hotel_registration()
    {
        $data['title'] = "Hotel Registration";
        $this->render_view('h_register', $data);
    }

    public function emailconfirm($hashedkey)
    {
        $this->load->model('Users_Mails_model');
        if ($this->Users_Mails_model->confirmemail($hashedkey)) {
            $this->session->set_flashdata('h_email', '<p class="alert alert-success">Email Confirmed!After you will be notified after we authenticate and active your hotel then you can add your rooms please be patience</p>');
            $this->load->model('Product_Categories_model');
            redirect('/', 'refresh');
        }
    }

    public function uemailconfirm($hashedkey)
    {
        $this->load->model('Users_Mails_model');
        if ($this->Users_Mails_model->confirmuemail($hashedkey)) {
            $this->session->set_flashdata('u_email', '<p class="alert alert-success">Congrats now you are activated</p>');
            $this->load->model('Product_Categories_model');
            redirect('/', 'refresh');
        }
    }

    public function resendconfirm()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('Users_Mails_model');
        $this->form_validation->set_rules('vmail', 'User Email', 'callback_useremail_check');
        $email = $this->input->post('vmail');
        if ($this->form_validation->run() == false) {
            $data['title'] = "email Reactivation";
            $this->load->view('inc/header', $data);
            $this->load->view('email/reactivate_email');
            $this->load->view('inc/footer');
        } else {
            $email = $this->input->post('vmail');
            if ($this->Users_Mails_model->sendemail($email)) {
                $this->session->set_flashdata('rs_email', '<p class="alert alert-success">You have been sent an email kindly verify it</p>');
            } else {
                $this->session->set_flashdata('ers_email', '<p class="alert alert-danger">An Error Happend Kindly Contact Us</p>');
            }
            redirect('/', 'refresh');
        }
    }

    public function useremail_check($mail)
    {
        if (!$this->Users_Mails_model->emailexist($mail)) {
            $this->form_validation->set_message('useremail_check', 'The {field} is not Found');
            return false;
        }
    }

    public function terms()
    {
        $this->load->model('fendmodels/Txtblock_model', 'tb');
        $data['terms'] = $this->tb->terms();
        $data['title'] = "Terms and Conditions";
        $this->render_view('fendprod/terms', $data);
    }

    public function jdiscodecheck()
    {
        $this->load->model('Hperiods_model');
        $err = [];
        $matches = false;
        $perid = intval($this->input->get('checkprid'));
        if (!$perid) {
            $err['error'] = 'checkprid is missing - ';
        }
        if (!$this->input->get('dt1')) {
            $err['error'] .= ' dt1 is missing - ';
        }
        if (!$this->input->get('dt2')) {
            $err['error'] .= ' dt2 is missing - ';
        }
        if (empty($err)) {
            $period = $this->Hperiods_model->gettperiod($perid);
            if ($period) {
                $discountset = $this->Hperiods_model->get_discountcodes_typed($period->providerID, 'b2c');
                $match = discountAvailability($this->input->get('dt1'), $this->input->get('dt2'), $discountset);
                //show_error(var_dump($match));
                if ($match && count($match) > 0) {
                    $matches = ["available" => true];
                } else {
                    $matches = ["available" => false];
                }
            } else {
                $matches = ["available" => false];
            }
        }
        $res = $matches ? $matches : $err;
        $this->toolset->jsonfy($res);
    }

    public function jdiscodecheckav()
    {
        $this->load->model('Hperiods_model');
        $err = [];
        $matches = false;
        if (!$this->input->get('prid')) {
            $err['error'] = 'prid is missing - ';
        }
        if (!$this->input->get('dt1')) {
            $err['error'] .= ' dt1 is missing - ';
        }
        if (!$this->input->get('dt2')) {
            $err['error'] .= ' dt2 is missing - ';
        }
        if (!$this->input->get('tprice')) {
            $err['error'] .= ' the total price amount is missing - ';
        }
        if (!$this->input->get('discount')) {
            $err['error'] = '';
            $err['error'] ? $err['error'] = ' Please Enter The Code ' : $err['error'] .= ' Please Enter The Code ';
        }
        if (empty($err)) {
            $period = $this->Hperiods_model->gettperiod($this->input->get('prid'));
            if ($period) {
                $discountset = $this->Hperiods_model->get_discountcodes_typed($period->providerID, 'b2c');
                $match = discountAvailability($this->input->get('dt1'), $this->input->get('dt2'), $discountset);
                if (count($match) > 0) {
                    if ($this->input->get('discount') == $match[0]['discount_codes']) {
                        $dsp = $match[0]['discount_amount'];
                        $tp = $this->input->get('tprice');
                        $matches["codestatus"] = true;
                        $matches["discount"] = $match[0]['discount_id'];
                        $matches['discount_amount'] = changeMargin(intval($dsp), '%', '-', intval($this->input->get('tprice')));
                        $matches["discounted"] = intval($this->input->get('tprice')) + $matches['discount_amount'];
                    } else {
                        $matches = ["codestatus" => false];
                    }
                } else {
                    $matches[0] = ["available_dates" => 'Not Available'];
                }
            } else {
                $matches[0] = ["available_dates" => 'Not Available'];
            }
        }
        $res = $matches ? $matches : $err;
        $this->toolset->jsonfy($res);
    }

    public function do_reserve()
    {
        $this->load->model('Public_Users_model', 'p_m');
        $u_res = $this->session->userdata('res_mail');
        $u_res['pmethod'] = $this->input->post('pmethod');
        $u_res['guest_name'] = $this->input->post('guest_name');
        $u_res['guest_email'] = $this->input->post('guest_email');
        $data['u_res'] = $u_res;
        $puser = $this->p_m->get_p_user($this->session->userdata('User_data')['userID']);
        $this->rsm->prId($this->input->post())->pinit();
        $res = $this->rsm->get_finished();
        if ($res) {
            $this->session->set_flashdata('sendresmail', true);
            redirect("user/invoice/" . $u_res['ResRef']);
        } else {
            echo " Reservation error ";
        }
    }
}
