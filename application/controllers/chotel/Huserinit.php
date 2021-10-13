<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

class Huserinit extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Geo_model', 'geo');
        $this->load->model('Hotelcontrol_model', 'hc');
        $this->load->helper('file');

    }

    public function render_view($views, $data)
    {
        $this->load->view('hotels/chotel/inc/header', $data);
        if (is_array($views)) {
            foreach ($views as $view) {
                $this->load->view($view);
            }
        } else {
            $this->load->view("$views");
        }
        $this->load->view('hotels/chotel/inc/footer');
    }

    public function index()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('arhotelname', 'Hotel Arabic Name', 'required');
        $this->form_validation->set_rules('enhotelname', 'Hotel English Name', 'required');
        $this->form_validation->set_rules('hotelphone', 'Hotel Phone', 'required');
        $this->form_validation->set_rules('hotelemail', 'Hotel Email', 'required');
        $this->form_validation->set_rules('hoteladdress', 'Hotel Address', 'required');
        $this->form_validation->set_rules('hoteldescription', 'Hotel Description', 'required');

        $data['title'] = "Adding Your Property";
        if ($this->form_validation->run() == false) {
            $this->load->view('hotels/chotel/huserinit/header', $data);
            $this->load->view('hotels/chotel/huserinit/huserinit', $data);
            $this->load->view('hotels/chotel/huserinit/footer', $data);
        } else {
            $hotel_inserted = $this->hc->hotelinsert();
            if ($hotel_inserted) {
                redirect('chotel/huserinit/hotelmedia/'.$hotel_inserted);
            } else {
                echo "Error Not Inserted";
            }
        }
    }

    public function hotelmedia($hotelid)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('token', "tokken", "required");
        $data['hotelid'] = $hotelid;
        $data['title'] = "Adding Your Property Photos";
        if ($this->form_validation->run() == false) {
            $this->load->view('hotels/chotel/huserinit/header', $data);
            $this->load->view('hotels/chotel/huserinit/hinitmedia', $data);
            $this->load->view('hotels/chotel/huserinit/footer', $data);
        } else {
            print_r($this->input->post());
        }
    }

    public function hotelsetting($hotelid)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('token', "tokken", "required");
        $this->form_validation->set_rules('agreement', "Agree", "required");
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $data['hotelid'] = $hotelid;
        $hotel = $this->hc->gethotel($hotelid);
        $data['title'] = "Adding Your Property settings";
        if ($this->form_validation->run() == false) {
            $this->load->view('hotels/chotel/huserinit/header', $data);
            $this->load->view('hotels/chotel/huserinit/hinitsetting', $data);
            $this->load->view('hotels/chotel/huserinit/footer', $data);
        } else {
            //provider_request
            $prvreq = $this->hc->provider_request();
            if($prvreq){
                //$this->toolset->sendemail($this->session->userdata('H_User_Email'), "You hotel $hotel->Hotel_Name Request", 'hotelrequest', ['hotelname'=>$hotel->Hotel_Name]);
                $this->session->set_flashdata('prv_requested', "Your request has been submitted");
                redirect('chotel/main');
            }
        }
    }

    public function get_photos()
    {
        $hotel_id = $this->input->get('hid');
        $hotel_photos = $this->hc->get_photos($hotel_id);
        if ($hotel_photos) {
            $this->toolset->jsonfy(['result' => true, 'photos' => $hotel_photos]);
        } else {
            $this->toolset->jsonfy(['status' => false]);
        }
    }
    
    public function del_photo()
    {
        $photo_id = $this->input->get('pid');
        $photo_name = $this->input->get('name');
        if ($photo_id && $photo_name) {
            $photo_deleted = $this->hc->del_photo($photo_id, $photo_name);
            if ($photo_deleted) {
                $this->toolset->jsonfy(['result' => true]);
            } else {
                $this->toolset->jsonfy(['result' => false]);
            }
        } else {
            $this->toolset->jsonfy(['result' => false, 'error' => "arguments Missing"]);
        }
    }

    public function setmainphoto()
    {
        $hotel_id = $this->input->get('hid');
        $photo_name = $this->input->get('photo');
        if ($hotel_id && $photo_name) {
            $photo_set = $this->hc->setdef_photo($hotel_id, $photo_name);
            if ($photo_set) {
                $this->toolset->jsonfy(['result' => true]);
            } else {
                $this->toolset->jsonfy(['result' => false]);
            }
        } else {
            $this->toolset->jsonfy(['result' => false, 'error' => "arguments Missing"]);
        }
    }

    public function getmainphoto()
    {
        $hotel_id = $this->input->get('hid');
        if ($hotel_id) {
            $photo_get = $this->hc->getdef_photo($hotel_id);
            if ($photo_get) {
                $this->toolset->jsonfy(['result' => true, 'mainphoto'=> $photo_get]);
            } else {
                $this->toolset->jsonfy(['result' => false]);
            }
        } else {
            $this->toolset->jsonfy(['result' => false, 'error' => "arguments Missing"]);
        }
    }

    public function testpost()
    {
        //var_dump($_POST);
        var_dump($this->input->post());
        var_dump($_FILES);
    }

    public function hotelimgs()
    {
        $this->load->library('Myimgtoolset', ['folder' => 'hotels', 'fieldname' => 'hotelphotos']);
        $res = [];
        if ($this->input->post()) {
            $hotel_id = $this->input->post('hotelid');
            $oldcount = $this->hc->hotel_photo_count($hotel_id);
            $up_data = [];
            $upload = $this->myimgtoolset->fileup();
            if ($upload) {
                $uploaded = $this->myimgtoolset->ups();
                if (!$uploaded) {
                    var_dump($this->myimgtoolset->uperrors);
                    $res['status'] = false;
                    $res['error'] = 'Errors Happened While Uploading Process';
                } else {
                    $count = count($uploaded);
                    for ($i = 0; $i < $count; $i++) {
                        //Hotel_ID    Photo_Name    Photo_Order
                        $up_data_push = array(
                            'Hotel_ID' => $hotel_id,
                            'Photo_Name' => $uploaded[$i]['file_name'],
                            'Photo_Order' => ($i + 1 + $oldcount),
                        );
                        $up_data[] = $up_data_push;
                    }
                    $upload_done = $this->hc->add_hotel_photos($up_data);
                    if ($upload_done) {
                        $res['status'] = true;
                    } else {
                        $res['status'] = false;
                        $res['error'] = 'Sorry Some Errors Happened While Uploading The hotel Images';
                    }
                }
            } else {
                $res['status'] = false;
                $res['error'] = 'No Files Selected';
            }
        }
        $this->toolset->jsonfy($res);
    }

    public function toarabic()
    {
        $txt = $this->input->get('name');
        //$txt = trans($txt, detectlang($txt), 'ar');
        // echo detectlang($txt);
        if (detectlang($txt) == 'ar') {
            $this->toolset->jsonfy(['isarabic' => true]);
        } else {
            $this->toolset->jsonfy(['isarabic' => false]);
        }

    }

    public function toenglish()
    {
        $txt = $this->input->get('name');
        $txt = trans($txt, detectlang($txt), 'en');
        $this->toolset->jsonfy(['english_name' => $txt]);
    }

    public function countries()
    {
        $countries = $this->geo->get_countries();
        if ($countries) {
            $this->toolset->jsonfyo($countries);
        } else {
            $this->toolset->jsonfyo(['error' => "No countries"]);
        }
    }
}
