<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

class Hotel extends Hotel_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("url");
        $this->load->model('Hotel_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('table');
        $this->load->model('HotelSysUsers_model');
    }


    public function list()
    {
        $data['title'] = "Hotel List Management";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'chotel/hotel/list';
        $config['total_rows'] = $this->Hotel_model->hotels_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 4;
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
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['active_hotels'] = $this->Hotel_model->fetch_active_hotels($config['per_page'], $page);
        $data['inactive_hotels'] = $this->Hotel_model->fetch_inactive_hotels($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/chotel/hotels/list', $data);
        
    }

    public function pending_requests()
    {
        $data['title'] = "Hotel requests Management";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'chotel/hotel/pending_requests';
        $config['total_rows'] = $this->Hotel_model->requests_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 4;
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
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['hotel_resquests'] = $this->Hotel_model->fetch_inactive_hotelreq($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/chotel/hotels/requests', $data);
    }
       

    /*public function pending_list()
    {
        $data['title'] = "Hotel List Management";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'chotel/hotel/pending_list';
        $config['total_rows'] = $this->Hotel_model->providers_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] = '<ul class="uk-pagination uk-margin-medium-top">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] = '<li><span>&hellip;</span>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li><span>&hellip;</span>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li><a href="#"><i class="uk-icon-angle-double-left"></i></a>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li><a href="#"><i class="uk-icon-angle-double-right"></i></a>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="uk-active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['inactive_providers'] = $this->Hotel_model->fetch_inactive_provider($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/chotel/hotels/pending_list', $data);
    }*/

    public function add()
    {
        $this->form_validation->set_rules('field', 'named field', 'rules');
        $data['title'] = "Add New User";
        $this->render_view('chotel/hotels/add', $data);
       
    }

    public function user_add_go()
    {
        $username = html_escape($this->input->post('LoginName'));
        $password = html_escape(sha1($this->input->post('PassWord')));
        $full_name = html_escape($this->input->post('FullName'));
        $email = html_escape($this->input->post('Email'));
        $phone = html_escape($this->input->post('PhoneNum'));

        $user_added = $this->Hotel_model->add_user($username, $password, $full_name, $email, $phone);
        if ($user_added == true) {
            $this->session->set_flashdata('user_add_mgs', "User <span style='color:red'> {$full_name} </span> Has Been Added Successfully");
            redirect('chotel/hotels/list');
        }
    }

    public function edit($hotel_id = '')
    {
        if (empty($hotel_id)) {
            show_error("The Page is not found please check your address again or report eomrah.h", 503, "Not Found");
        }

        $this->load->model('Hotel_model');
        if (!$this->Hotel_model->checkhotelExist($hotel_id)) {
            show_error("Hotel Not found {$hotel_id}");
        } else {

            $this->load->helper('form', 'url');
            $this->load->library('form_validation');
            $data['hotel'] = $this->Hotel_model->get_that_hotel($hotel_id);
            $this->form_validation->set_rules('field', 'named', 'rule');
            $data['title'] = "Edit {$data['hotel']->Hotel_Name} details";
            $this->render_view('chotel/hotels/hotel_edit',$data);
        }
    }

    public function addroom($hotel_id = '', $provid = null)
    {
        if (empty($hotel_id)) {
            show_error("The Page is not found please check your address again or report eomrah", 503, "Not Found");
        }
        if (!$this->Hotel_model->checkhotelExist($hotel_id)) {
            show_error("Hotel Not found {$hotel_id}");
        } else {
            $this->load->library('form_validation');
            $data['hotel'] = $this->Hotel_model->get_that_hotel($hotel_id);
            $data['provid'] = $provid;
            $data['hotelRooms'] = $this->Hotel_model->getHotelRooms($hotel_id);
            $data['hotel_meals'] = $this->Hotel_model->get_hotel_meals($hotel_id);
            $data['data_table'] = "No Data";
            $data['occattr_datatable'] = "No Data";
            if($this->list_hrooms($hotel_id)) {
                $data['data_table'] = $this->list_hrooms($hotel_id);
            }
            $data['roomTypes'] = $this->Hotel_model->getRoomTypes();
            $data['Meals'] = $this->Hotel_model->getMeals();
            $data['h_age'] = $this->Hotel_model->get_hotel_age($hotel_id);
            $this->form_validation->set_rules('maxocc', 'max Occupant', 'required');
            $data['title'] = "Add Room In {$data['hotel']->Hotel_Name} Hotel";
            if ($this->form_validation->run() == false) {
                

                $this->load->view('hotels/chotel/inc/header', $data);
                $this->load->view('hotels/chotel/hotels/addroom');
            } else {
                $this->load->view('hotels/chotel/inc/header', $data);
                $this->load->view('hotels/chotel/hotels/addroom');
            }
        }
    }

    public function list_hrooms($hid)
    {
        $hotelRooms = $this->Hotel_model->getHotelRooms($hid);
        $this->table->set_heading('Room Type', 'max_Occ', 'Action');
        if($hotelRooms) {
            foreach ($hotelRooms as $room) {
                $this->table->add_row(
                    array(
                        $room->R_Type_Name." <span id='{$room->Room_ID}MxMessage'></span>",
                        "<input id='{$room->Room_ID}_maxocc' class='form-control' type='text' value='$room->Max_Occ' />",
                        form_button(['class'=> "btn btn-success", 'content'=> "edit", 'onclick'=>"maxocc_update($room->Room_ID)"]),
                        "<button data-target='#modal-default' type='button' class='btn btn-default' data-toggle='modal'onclick='showgm({$room->Room_ID}, {$room->Max_Occ})' >
                       Attributes
                      </button>",
                        (roomoccmaxed($room->Room_ID))?"<input type='hidden' id='{$room->Room_ID}RoomFilled' value='1' />" : "<input type='hidden' id='{$room->Room_ID}RoomFilled' value='0' />"
                    )
                );
            }
             return $this->table->generate();
        }else{
            return false;
        }
    }
    

    public function ins_room()
    {
        $res = [];
        $hotel_id = $this->input->post('hotelid');
        $Rtype = $this->input->post('rtype');
        $MxOcc = $this->input->post('mxocc');
        $room_exists = $this->Hotel_model->RoomExists($hotel_id, $Rtype);
        if($room_exists) {
            $res = ['result'=>false, 'datatable'=>'<h4 class="text-center">The Room Already Exists In Your List Kindly Refresh</h4>'];
        }else{
            $room_added = $this->Hotel_model->addRoom($hotel_id, $Rtype, $MxOcc);
            if($room_added) {
                if($this->list_hrooms($hotel_id)) {
                    $res = ['result'=>true, 'datatable'=>$this->list_hrooms($hotel_id)];
                }
            }else {
                $res = ['result'=>false, 'datatable'=>'<h4 class="text-center">Error While Adding The Room</h4>'];
            }
        }
        $this->toolset->jsonfy($res);
    }

    public function room_maxocc_update()
    {
        $room_id = $this->input->post('rid');
        $MxOcc = $this->input->post('mx');
        $res = [];
        if($this->Hotel_model->update_room_max($room_id, $MxOcc)) {
                $res = ['result'=>true];
        }else {
            $res = ['result'=>false , 'error'=>'failed'];
        }
        $this->toolset->jsonfy($res);
    }

    public function add_occ_attr()
    {
        $roomId = $this->input->post('rid');
        $attrType = $this->input->post('attrtype');
        $maxAdult = $this->input->post('mxadult');
        $res = [];
        if($this->Hotel_model->ins_occ_attr($roomId, $attrType, $maxAdult)) {
            $res['status'] = true;
        } else {
            $res['status'] = false;
            $res['error'] = "Error while adding occupant to the room Numer {$roomId}";
        }
        $this->toolset->jsonfy($res);
    }

    public function del_occ_attr()
    {
        $roomId = $this->input->post('rid');
        $attrType = $this->input->post('attrtype');
        $res = [];
        if($this->Hotel_model->del_occ_attr($roomId, $attrType)) {
            $res['status'] = true;
        } else {
            $res['status'] = false;
            $res['error'] = "Error while deleting occupant from the room Numer {$roomId}";
        }
        $this->toolset->jsonfy($res);
    }

    public function add_hotel_meal()
    {
        $hotel_id = $this->input->post('hid');
        $meal_id = $this->input->post('mid');
        if($this->Hotel_model->add_hotel_meal($hotel_id, $meal_id)) {
            $res['status'] = true;
        } else {
            $res['status'] = false;
            $res['error'] = "<h4 class='text-center'>Meal Already Exits In Your List</h4>";
        }
        $this->toolset->jsonfy($res);
    }

    public function get_hotel_meal()
    {
        $hotel_id = $this->input->post('hid');
        $res = [];
        if($this->Hotel_model->get_hotel_meals($hotel_id)) {
            $hotel_meals = $this->Hotel_model->get_hotel_meals($hotel_id);
            for($i = 0; $i < count($hotel_meals);  $i++){
                $count = $i+1;
                $res['status'] = true;
                $res['data'][]  = "<tr><td>Meal {$count} : {$hotel_meals[$i]->Meal_Name}</td><td> <button class='btn btn-danger' type='button' onclick='delHotelMeal({$hotel_meals[$i]->HM_ID})'>Delete Meal</button> </td></tr>";
            }
        }else{
            $res['status'] = false;
            $res['data'][] = 'No Meals Inserted Yet';
        }
        $this->toolset->jsonfy($res);
    }

    public function del_hotel_meal()
    {
        $hmid = $this->input->post('mid');
        $res = [];
        if($this->Hotel_model->del_hotel_meal($hmid)) {
            $res['status'] = true;
            $res['data'][] = 'Meal Deleted Successfully';
        }else{
            $res['status'] = false;
            $res['data'][] = 'delete meal error';
        }
        $this->toolset->jsonfy($res);
    }

    public function getroomattr($roomId)
    {

        if($this->Hotel_model->getroomattr($roomId)) {
            $roomattrs = $this->Hotel_model->getroomattr($roomId)[0];
            $counted = 1;
            $res = [];
            if(roomoccmaxed($roomId)) {
                $res['roomfiled'] = true;
            }else{
                $res['roomfiled'] =  false;
            }

            for($i = 1; $i <= $roomattrs->Adlult_Count; $i++){
                $res['data'][] = "Occupant {$counted} : -> Adult   <a class='btn btn-danger mx-auto p-0' onclick='delRoomAttr(1)' >Delete</a>  <br> <br>";
                $counted++;
            }
            for($i = 1; $i <= $roomattrs->Child_Count; $i++){
                $res['data'][] =  "Occupant {$counted} : -> Child  <a class='btn btn-danger mx-auto p-0' onclick='delRoomAttr(2)' >Delete</a>  <br> <br>";
                $counted++;
            }
            for($i = 1; $i <= $roomattrs->Infant_Count; $i++){
                $res['data'][] =  "Occupant {$counted} : -> Infant  <a class='btn btn-danger mx-auto p-0' onclick='delRoomAttr(3)' >Delete</a> <br> <br>";
                $counted++;
            }
        } else {
            $res['data'][] = "No Data For Room Attributes";
            if(roomoccmaxed($roomId)) {
                $res['roomfiled'] = true;
            }else{
                $res['roomfiled'] = false;
            }
        }
        $this->toolset->jsonfy($res);
    }

    public function set_occ_age()
    {
        $res = [];
        $typemessage = '';
        $hotel_id = $this->input->post('hid');
        $agetype = $this->input->post('agetype');
        $age_from = $this->input->post('agefrom');
        $age_to = $this->input->post('ageto');
        switch ($agetype){
        case "a_age":
            $typemessage = "Adults";
            break;
        case "c_age":
            $typemessage = "Childs";
            break;
        case "i_age":
            $typemessage = "Infants";
            break;
        default:
            "Not Defined";
        }
        if ($this->Hotel_model->set_age($hotel_id, $agetype, $age_from, $age_to)) {
            $res['status'] = true;
            $res['data'][] = "{$typemessage} Ages successfully added ";
        } else {
            $res['status'] = false;
            $res['data'][] = " Error adding {$typemessage} Ages to hotel ";
        }
        $this->toolset->jsonfy($res);
    }

    public function update_occ_age()
    {
        $res = [];
        $typemessage ='';
        $hotel_id = $this->input->post('hid');
        $agetype = $this->input->post('agetype');
        $age_from = $this->input->post('agefrom');
        $age_to = $this->input->post('ageto');
        switch ($agetype){
        case "a_age":
            $typemessage = "Adults";
            break;
        case "c_age":
            $typemessage = "Childs";
            break;
        case "i_age":
            $typemessage = "Infants";
            break;
        default:
            "Not Defined";
        }
        if ($this->Hotel_model->update_age($hotel_id, $agetype, $age_from, $age_to)) {
            $res['status'] = true;
            $res['data'][] = "{$typemessage} Age Successfully Updated ";
        } else {
            $res['status'] = false;
            $res['data'][] = " Error updating {$typemessage} ages to hotel ";
        }
        $this->toolset->jsonfy($res);
    }

    public function rooms()
    {
        $data['rooms'] = $this->Hotel_model->getRooms();
        $this->table->set_heading('max_Occ', 'Action');
        foreach ($data['rooms'] as $room) {
            $this->table->add_row(
                array(
                    $room->Max_Occ,
                    anchor("url/$room->Room_ID", "edit", 'title="Hotel Details"')
                )
            );
        }
        $data['data_table'] = $this->table->generate();
        $data['title'] = " hotel Rooms list ";
        $this->render_view('hotels/chotel/hotels/rooms', $data);
    }

    public function hotel_request_update($huid, $id,$hid)
    {
        $provid = $this->Hotel_model->update_hotel_request($huid, $id, $hid);
        if($provid) {
            $this->session->set_flashdata('hotelinform_mgs', "<span style='color:green'>Provider Has Been Activated Successfully</span>");
            redirect("chotel/provider/activate/$provid");
            
        }
    }


    public function hotel_request_delete($rid, $hid)
    {

        if($this->Hotel_model->delete_hotel_request($rid, $hid)) {
            $this->session->set_flashdata('hotelinform_mgs', "<span style='color:red'>Has Been Deleted Successfully</span>");
            if(!$this->session->userdata('Suser')) {
                redirect("chotel/hotel/pending_requests");
            }else{
                redirect("gman/providers/requested_hotel");
            }
           
        }else{
            show_error("Not Deleted");
        }
    }
    
}