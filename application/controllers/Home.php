<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Front_Controller
{

    public function __construct()
    {
        //need to clean this VI
        parent::__construct();
        $this->load->model('Reservation_model', 'rsm');
        $this->load->model('Product_Categories_model');
        $this->load->model('fendmodels/fendproducts_model', 'fm');
        $this->load->model('fendmodels/fphoto_model');
        $this->load->model('translate/Translate_model');
        $this->load->model('Multimedia_model');
        $this->load->model('Hotel_model', 'ht');
        $this->load->model('Products_model');
        $this->load->model('fendmodels/Txtblock_model', 'tb');
        $this->load->model('User_model', 'um');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('Users_Mails_model');
        $this->load->library('toolset');
        $this->load->model('Hotel_model', 'hm');
        $this->load->model('Blog_model', 'bm');
        $this->load->model('Pages_model', 'pm');
        $data['hmactive'] = true;
    }

    public function index()
    {
        $data['homeblogs'] = $this->bm->home_blogs();
        $data['featured_hotels'] = $this->fm->featured_hotels();
        $data['title'] = comtrans('home');
        $this->render_view(['hotels/inc/search_area', 'hotels/home'], $data);
    }

    public function pdetail($pid)
    {
        $this->load->library('magician');
        $this->load->library('table');
        $data['product'] = $this->fendproducts_model->fetch_data($pid);
        //show_error(print_r($data['product']));
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
                'table_open' => '<!-- Start PriceTable --><div class="table-responsive"> <table id="pricetable" class="table text-center">',
                'table_close' => '</table></div><!-- End PriceTable -->',
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
        $this->render_view('hotels/hotel_details', $data);

    }

    public function chlang($lang)
    {
        //edit url
        redirect(base_url($lang));

        // $langcode = $this->input->post('tolang');
        // $langs = $this->config->config['languages']; 
        // if (in_array($langcode, $langs)) {
        //     set_cookie('language', $langcode, 86400);
        //     $res['status'] = true;
        //     $this->toolset->jsonfy($res);
        // }
    }

    public function prefs()
    {
        $prefs = array('lang' => userlang(), 'currency' => usercur(), 'base_url' => site_url());
        $this->toolset->jsonfy($prefs);
    }

    public function ulogged()
    {
        if ($this->session->user_data != null && $this->session->user_data['loggedIn'] == true) {
            $this->toolset->jsonfy(['status' => true]);
        }else{
            $this->toolset->jsonfy(['status' => false]);

        }
    }

    public function chcur()
    {
        $currcode = $this->input->post('curr');
        if (array_key_exists($currcode, currencies())) {
            set_cookie('currency', $currcode, 86400);
            $res['status'] = true;
            $this->toolset->jsonfy($res);
        }

    }

    public function hotel_registration()
    {
        $data['title'] = "Hotel Registration";
        $this->render_view('hotels/b_register', $data);
    }

    public function emailconfirm($hashedkey)
    {
        if ($this->Users_Mails_model->confirmemail($hashedkey)) {
            $data['title'] = 'Your Mail Confirmed';
            $this->load->view("hotels/inc/header", $data);
            $this->load->view("hotels/b_mailconfirmed");
        }else{
            show_404();
        }
    }

    public function uemailconfirm($hashedkey)
    {
        //needs cleanup and timeout for the validation sent time
        $data['title'] = "Email Confirmed";
        if ($this->Users_Mails_model->confirmuemail($hashedkey)) {
           $this->load->view("hotels/inc/header", $data);
           $this->load->view("hotels/uemail_confirmed");
        }
    }

    public function resendconfirm()
    {
        $this->form_validation->set_rules('vmail', 'User Email', 'callback_useremail_check');
        $email = $this->input->post('vmail');
        if ($this->form_validation->run() == false) {
            $data['title'] = "email Reactivation";
            $this->render_view('email/reactivate_email', $data);
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
    public function jdiscodecheck()
    {
        $this->load->model('Hperiods_model');
        $err = [];
        $matches = false;
        $pvid = intval($this->input->get('pvid'));
        if (!$pvid) {
            $err['error'] = 'pvid is missing - ';
        }
        if (!$this->input->get('dt1')) {
            $err['error'] .= ' dt1 is missing - ';
        }
        if (!$this->input->get('dt2')) {
            $err['error'] .= ' dt2 is missing - ';
        }
        if (empty($err)) {
            $discountset = $this->Hperiods_model->get_discountcodes_typed($pvid, 'b2c');
            $match = discountAvailability($this->input->get('dt1'), $this->input->get('dt2'), $discountset);
            if ($match && count($match) > 0) {
                $matches = ["available" => true];
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
        if (!$this->input->get('pvid')) {
            $err['error'] = 'pvid is missing - ';
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

            $discountset = $this->Hperiods_model->get_discountcodes_typed((int) $this->input->get('pvid'), 'b2c');
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
        }
        $res = $matches ? $matches : $err;
        $this->toolset->jsonfy($res);
    }

    public function do_reserve()
    {
        $u_res = $this->session->userdata('res_mail');
        $u_res['pmethod'] = $this->input->post('pmethod');
        $u_res['guest_name'] = $this->input->post('guest_name');
        $u_res['guest_email'] = $this->input->post('guest_email');
        $data['u_res'] = $u_res;
        $puser = $this->um->get_p_user($this->session->userdata('User_data')['userID']);
        $this->rsm->prId($this->input->post())->pinit();
        $res = $this->rsm->get_finished();
        if ($res) {
            $this->session->set_flashdata('sendresmail', true);
            redirect("user/invoice/" . $u_res['ResRef']);
        } else {
            echo " Reservation error ";
        }
    }

    public function langtest()
    {
        //$idiom = $this->session->get_userdata('eomrah_language');
        //var_dump($idiom);
        $this->lang->load('form_validation', userlang());
        // $oops = $this->lang->line('test1');
        echo (lang('test1', 'form_item1', ['id' => 'hishamvue', 'class' => 'hishamclass']));
        echo "<br>";
        echo lang('required');
    }

    public function mailtest()
    {
        $receiver = "4ljbvqjqx@moakt.co";
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
            echo "mail sent";
        } else {
            print_r($this->email->print_debugger(array('headers')));
            echo "Error sending the mail";
        }
    }

    public function about()
    {
        $data['title'] = lang('aboutus'); 
        $data['about'] = $this->pm->get_about();
        $this->render_view(['hotels/inc/banner', 'hotels/about'], $data);
    }

    public function terms()
    {
        $data['title'] = lang('terms');
        $data['terms'] = $this->pm->get_terms();
        $this->render_view(['hotels/inc/banner', 'hotels/terms'], $data);
    }

    public function sendsms()
    {
        $res = sendwhats();
        print_r($res);
    }

    public function test_email(){
        $data['title'] = "Email Invoices";
        $this->load->view("email/usermarkedmail");
    }

}
