<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');
/**
 * Travel Hotel User Class
 * Author :: Gebriel Alkhayal
 * Project :: Eomrah
 */

class Husers extends Gman_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HotelSysUsers_model');
        $this->load->model('Products_model', 'prod');
        $this->load->model('Product_Photo_model', 'prodphoto');
        $this->load->library('form_validation');
        $this->load->library('form_validation', 'products');
        $this->load->model('translate/Translate_model');
        $this->load->model('hotel_model');
        $this->load->model('products_model');
        $this->load->model('provider_model');
        $this->load->model('geo_model');
    }

    public function active()
    {
        $data['title'] = "Hotel Users List Management";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'gman/hotel_system/husers/active';
        $config['total_rows'] = $this->HotelSysUsers_model->active_users_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<span class="page-link">Next</span>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<span class="page-link">Prev</span>';
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['first_link'] = '<li class="page-item"><span class="page-link">First</span></li>';
        $config['last_link']  = '<li class="page-item"><span class="page-link">Last</span></li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $data['next_hotel_users'] = $this->HotelSysUsers_model->get_active_hotel_users($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/gman/hotel_system/users/active_users', $data);
    }

    public function inactive()
    {
        $data['title'] = "Hotel Users List Management";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'gman/hotel_system/husers/inactive';
        $config['total_rows'] = $this->HotelSysUsers_model->inactive_users_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<span class="page-link">Next</span>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<span class="page-link">Prev</span>';
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['first_link'] = '<li class="page-item"><span class="page-link">First</span></li>';
        $config['last_link']  = '<li class="page-item"><span class="page-link">Last</span></li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $data['next_hotel_users'] = $this->HotelSysUsers_model->get_inactive_hotel_users($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/gman/hotel_system/users/inactive_users', $data);
    }

    public function add()
    {
        $this->form_validation->set_rules('field', 'named', 'rule');
        $data['title'] = "Add New USER";
        $this->render_view('hotels/gman/hotel_system/husers/add_user', $data);
    }

    public function user_add_go()
    {
        $username = html_escape($this->input->post('LoginName'));
        $password = html_escape(sha1($this->input->post('PassWord')));
        $full_name = html_escape($this->input->post('FullName'));
        $email = html_escape($this->input->post('Email'));
        $phone = html_escape($this->input->post('PhoneNum'));
        $user_added = $this->Next_Users_model->add_user($username, $password, $full_name, $email, $phone);
        if ($user_added == true) {
            $this->session->set_flashdata('user_add_mgs', "User <span style='color:red'> {$full_name} </span> Has Been Added Successfully");
            redirect('gman/users/list');
        }
    }

    public function edit($user_id)
    {
        if (!$this->HotelSysUsers_model->checkUserExist($user_id)) {
            $data['title'] = "User Error";
            $data['error_item'] = "User";
            $this->render_view('hotels/gman/error', $data);
        } else {
            $data['huser'] = $this->HotelSysUsers_model->get_that_h_user($user_id);
            $this->form_validation->set_rules('field', 'named', 'rules');
            $data['title'] = "Edit {$data['huser']->H_User_FullName} details";
            $this->render_view('hotels/gman/hotel_system/users/user_edit', $data);
        }
    }

    public function activate_step1($h_user_id)
    {
        if ($this->hotel_model->get_hotel_by_user($h_user_id)) {
            $data['user_hotel'] = $this->hotel_model->get_hotel_by_user($h_user_id);
            $data['hotel_id'] = $data['user_hotel']->Hotel_ID;
        } else {
            show_error("ThIs user isnot linked with a hotel, please report to Eomrah", 503, "Technical Error");
        }

        $data['title'] = "MultiLanguage Hotel Name";
        $data['langs'] = $this->Translate_model->get_langs();
        $langsarr = $this->Translate_model->get_langs_arr();
        $this->form_validation->set_rules('s_txt', 'Text to be traslated', 'required');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/hotel_system/hotels/addtxt', $data);
        } else {
            $data_add = [];
            $hotel_id = $this->input->post('hotel_id');
            $txt_type = "hotelname";
            $to_langs = $this->input->post('tolangs');
            $from_lang = $this->input->post('from_lang');
            $s_text = $this->input->post('s_txt');
            $data_add['S_ID'] = $hotel_id;
            $data_add['S_TYPE'] = $txt_type;
            $data_add['R_Order'] = 1;
            $data_add[$from_lang] = $s_text;
            if ($this->Translate_model->count_exist($hotel_id, $txt_type)) {
                redirect("gman/hotel_system/husers/activate_step2/{$h_user_id}");
            }
            if ($this->input->post('transall') == '1') {
                foreach ($langsarr as $lang) {
                    $data_add[$lang->code] = $s_text;
                }
            } else {
                foreach ($to_langs as $key => $value) {
                    $data_add[$key] = trans($s_text, $from_lang, $value);
                }
            }
            if ($this->Translate_model->add_title($data_add)) {
                $this->session->set_flashdata('statusMsg', "Hotel Name Translated Successfully please Complete The Following");
                redirect("gman/hotel_system/husers/activate_step2/{$h_user_id}");
            } else {
                show_error('Failed to Translate Hotel Name Please report');
            }
        }
    }


    public function activate_step2($h_user_id)
    {
        $data['user_id'] = $h_user_id;
        $data['user_hotel'] = $this->hotel_model->get_hotel_by_user($h_user_id);
        $data['title'] = "MultiLanguage Hotel Name";
        $data['langs'] = $this->Translate_model->get_langs();
        $data['hotel_id'] = $data['user_hotel']->Hotel_ID;
        $this->form_validation->set_rules('s_txt', 'Text to be traslated', 'required');
        $data['txt_type'] = "hotelname";
        $data['txts'] = $this->Translate_model->get_trans_by_source_hotel($data['hotel_id'], $data['txt_type']);

        $this->form_validation->set_rules('s_txt', 'Text to be traslated', 'required');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/hotel_system/hotels/edittxt', $data);
        } else {
            $data_add = [];
            $txt_id = $this->input->post('txt_id');
            $hotel_id = $this->input->post('hotel_id');
            $txt_type = "hotelname";
            $to_langs = langs_codes();
            $from_lang = $this->input->post('from_lang');
            $s_text = $this->input->post('s_txt');
            $data_add['S_ID'] = $hotel_id;
            $data_add['S_TYPE'] = $txt_type;
            $data_add['R_Order'] = 1;
            $data_add[$from_lang] = $s_text;
            foreach ($to_langs as $key => $value) {
                $data_add[$key] = trans($s_text, $from_lang, $key);
            }
            if ($this->Translate_model->edit_title($data_add, $txt_id)) {
                $this->session->set_flashdata('statusMsg', " product {$type_added} edited successfully");
                redirect("gman/hotel_system/husers/activate_step1d/$h_user_id");
            } else {
                show_error('Falied');
            }
        }
    }

    public function activate_step1d($h_user_id)
    {
        if ($this->hotel_model->get_hotel_by_user($h_user_id)) {
            $data['user_hotel'] = $this->hotel_model->get_hotel_by_user($h_user_id);
            $data['hotel_id'] = $data['user_hotel']->Hotel_ID;
        } else {
            show_error("ThIs user isnot linked with a hotel, please report to Eomrah", 503, "Technical Error");
        }

        $data['title'] = "MultiLanguage Hotel Description";
        $data['langs'] = $this->Translate_model->get_langs();
        $langsarr = $this->Translate_model->get_langs_arr();
        $this->form_validation->set_rules('s_txt', 'Text to be traslated', 'required');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/hotel_system/hotels/addtxtdesc', $data);
        } else {
            $hotel_id = $this->input->post('hotel_id');
            $txt_type = "hoteldesc";
            $to_langs = $this->input->post('tolangs');
            $from_lang = $this->input->post('from_lang');
            $s_text = $this->input->post('s_txt');
            $data_add['S_ID'] = $hotel_id;
            $data_add['S_TYPE'] = $txt_type;
            $data_add['R_Order'] = 1;
            $data_add[$from_lang] = $s_text;
            if ($this->Translate_model->count_exist($hotel_id, $txt_type)) {
                redirect("gman/hotel_system/husers/activate_step2d/{$h_user_id}");
            }
            if ($this->input->post('transall') == '1') {
                foreach ($langsarr as $lang) {
                    $data_add[$lang->code] = $s_text;
                }
            } else {
                foreach ($to_langs as $key => $value) {
                    $data_add[$key] = trans($s_text, $from_lang, $value);
                }
            }
            if ($this->Translate_model->add_title($data_add)) {
                $this->session->set_flashdata('statusMsg', "Hotel description Translated Successfully please Complete The Following");
                redirect("gman/hotel_system/husers/activate_step2d/{$h_user_id}");
            } else {
                show_error('Failed to Translate Hotel Description Please report');
            }
        }
    }

    public function activate_step2d($h_user_id)
    {
        $data['user_id'] = $h_user_id;
        $data['user_hotel'] = $this->hotel_model->get_hotel_by_user($h_user_id);
        $data['title'] = "MultiLanguage Hotel description";
        $data['langs'] = $this->Translate_model->get_langs();
        $data['hotel_id'] = $data['user_hotel']->Hotel_ID;
        $this->form_validation->set_rules('s_txt', 'Text to be traslated', 'required');
        $data['txt_type'] = "hoteldesc";
        $data['txts'] = $this->Translate_model->get_trans_by_source_hotel($data['hotel_id'], $data['txt_type']);

        $this->form_validation->set_rules('s_txt', 'Text to be traslated', 'required');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/hotel_system/hotels/edittxtdesc', $data);
        } else {
            $data_add = [];
            $txt_id = $this->input->post('txt_id');
            $hotel_id = $this->input->post('hotel_id');
            $txt_type = "hoteldesc";
            $to_langs = langs_codes();
            $from_lang = $this->input->post('from_lang');
            $s_text = $this->input->post('s_txt');
            $data_add['S_ID'] = $hotel_id;
            $data_add['S_TYPE'] = $txt_type;
            $data_add['R_Order'] = 1;
            $data_add[$from_lang] = $s_text;
            if ($this->Translate_model->count_exist($hotel_id, $txt_type)) {
                redirect("gman/hotel_system/husers/activate_step3/$h_user_id");
            }
            foreach ($to_langs as $key => $value) {
                $data_add[$key] = trans($s_text, $from_lang, $key);
            }
            if ($this->Translate_model->edit_title($data_add, $txt_id)) {
                $this->session->set_flashdata('statusMsg', " product {$type_added} edited successfully");
                redirect("gman/hotel_system/husers/activate_step3/$h_user_id");
            } else {
                show_error('Falied');
            }
        }
    }

    public function activate_step3($h_user_id)
    {
        $data['title'] = "Hotel Details setting";
        $data['user_id'] = $h_user_id;
        $data['user_hotel'] = $this->hotel_model->get_hotel_by_user($h_user_id);
        $data['product'] = $this->prod->get_product_by_hotel($data['user_hotel']->Hotel_ID);
        $data['product_photos'] = $this->prodphoto->get_that_product_photos($data['product']->P_ID);
        $data['hotel_id'] = $data['user_hotel']->Hotel_ID;
        $this->render_view('hotels/gman/hotel_system/hotels/hotelproductconf', $data);
    }

    public function prodImgs()
    {
        $res = [];
        if ($this->input->post()) {
            $product_id = $this->input->post('product_id');
            $oldcount = $this->prodphoto->product_photo_count($product_id);
            $up_data = [];
            $this->myimgtoolset->fileup();
            $uploaded = $this->myimgtoolset->ups();
            if (!$uploaded) {
                show_error(var_dump($uploaded));
            } else {
                $count = count($uploaded);
                for ($i = 0; $i < $count; $i++) {
                    $up_data_push = array(
                        'Product_ID' => $product_id,
                        'Photo_Name' => $uploaded[$i]['file_name'],
                        'Photo_Order' => ($i + 1 + $oldcount)
                    );
                    $up_data[] = $up_data_push;
                }
            }
            $upload_done = $this->prodphoto->add_product_photos($up_data);
            if ($upload_done) {
                $res['status'] = true;
            } else {
                $res['status'] = false;
                $res['error'] = 'Sorry Some Errors Happened While Uploading The Product Images';
            }
        }
        $this->toolset->jsonfy($res);
    }

    public function activate($h_user_id)
    {
        $this->form_validation->set_rules('country', 'country', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('governorate', 'City', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('city', 'Region', 'required|is_natural_no_zero');
        $this->form_validation->set_message('is_natural_no_zero', 'Please Select a {field}');
        $this->form_validation->set_rules('discount', 'Dicount Value', 'required');
        $this->form_validation->set_rules('markup', 'MarkUp Value', 'required');
        $data['title'] = "Hotel User Activation";
        $data['h_user'] = $this->HotelSysUsers_model->get_that_h_user($h_user_id);
        $data['user_hotel'] = $this->hotel_model->get_hotel_by_user($h_user_id);
        $data['user_hotel_product'] = $this->products_model->get_product_by_hotel($data['user_hotel']->Hotel_ID);
        $data['admin_user_id'] =  $this->session->userdata('hotels/gman_id');
        $data['countries'] =  $this->geo_model->get_countries();
        $data['governorates'] =  $this->geo_model->get_governorates_country($data['user_hotel']->Hotel_Country_ID);
        $data['regions'] =  $this->geo_model->get_regions_governo($data['user_hotel']->Hotel_Governorate_ID);
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/hotel_system/users/activate', $data);
        } else {
            $providerid = $this->HotelSysUsers_model->activate_user($h_user_id);
            if ($providerid) {
                $this->session->set_flashdata('user_add_mgs', "The Hotel User Activated SuccessFully");
                redirect('gman/hotel_system/husers/active');
            } else {
                $this->session->set_flashdata('user_add_mgs', "An Error Happened While Activating The User Please Send report to Eomrah Team");
                redirect('gman/hotel_system/husers/inactive');
            }
        }
    }

    public function aj_country_cities($country_id)
    {

        if ($this->geo_model->get_country_governorates($country_id)) {
            foreach ($this->geo_model->get_country_governorates($country_id) as $governorate) {
                echo "<option value='$governorate->Governorate_ID'>$governorate->Governorate_Name</option>";
            }
        } else {
            echo "<option> No Result </option>";
        }
    }

    public function aj_country_regions($governorate_id)
    {

        if ($this->geo_model->get_governorate_cities($governorate_id)) {
            foreach ($this->geo_model->get_governorate_cities($governorate_id) as $city) {
                echo "<option value='$city->City_ID'>$city->City_Name</option>";
            }
        } else {
            echo "<option> No Result </option>";
        }
    }

    public function profile()
    {
        if (!empty($_SESSION['hotels/gman_fullName'])) {
            $data['User_Fname'] = $_SESSION['hotels/gman_fullName'];
        }
        $data['title'] = "User Profile";
        $this->render_view('hotels/gman/users/user_profile', $data);
    }

    //NEW HOTEL ACTIVATE METHOD//

    public function hotel_user_details($id)
    {
        $data['title'] = "Hotel User Details";
        $data['details'] = $this->HotelSysUsers_model->hotel_user_details($id);
        $this->render_view('hotels/gman/hotel_system/users/hotel_user_details', $data);
    }

    public function activate_hotel_user($id)
    {
        $data['title'] = "Hotel User Details";
        $data['details'] = $this->HotelSysUsers_model->hotel_user_details($id);

        $this->form_validation->set_rules('husername', 'User Name', 'required');
        $this->form_validation->set_rules('hfullname', 'Full Name', 'required');
        $this->form_validation->set_rules('hemail', 'Email', 'required');
        $this->form_validation->set_rules('hphone', 'Phone', 'required');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/hotel_system/users/hotel_user_details', $data);
        } else {
            $update = $this->HotelSysUsers_model->update_huser($id);
            if($update){
                redirect('gman/hotel_system/husers/active');
            }else {
                show_error('Oops something went wrong');
            }
        }
    }
}
