<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

class Users extends Gman_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gman_Users_model');
        $this->load->model('Count','cm');
        $this->load->model('Reservation_model', 'rsm');
        $this->load->helper("url");
        $this->load->library('form_validation');

       
    }
    public function list()
    {
        $data['title'] = "User List Management";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'gman/users/list';
        $config['total_rows'] = $this->Gman_Users_model->users_count();
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
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
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $data['gman_users'] = $this->Gman_Users_model->fetch_users($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/gman/users/list', $data);
    }
  
    public function add()
    {
        $this->form_validation->set_rules('field', 'field', 'rfield');
        $data['title'] = "Add New USER";
        $this->render_view('hotels/gman/users/add_user',$data);
    }

    public function user_add_go()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('FullName','User Full Name', 'required');
        $this->form_validation->set_rules('LoginName','User Name', 'required');
        $this->form_validation->set_rules('Password','Password', 'required');
        $this->form_validation->set_rules('C_Pass','Password Confirm', 'required|matches[Password]');
        $this->form_validation->set_rules('Email','User Email', 'required');
        $this->form_validation->set_rules('PhoneNum','User Phone Name', 'required');
        if($this->form_validation->run(0) == false){
            $data['title'] = "Add User";
            $this->render_view('hotels/gman/users/add_user', $data);
        }else{
            $added = $this->Gman_Users_model->add_user();
            if($added){
                $this->session->set_flashdata('user_add_msg', "The User Details Added Successfully");
                redirect('gman/users/list/');
            }else{
                $this->session->set_flashdata('user_add_msg', "Failed Adding New User Kindly Report");
                redirect('gman/users/list/');
            }
        }
       
    }
   
    public function edit($user_id)
    {
        if (!$this->Gman_Users_model->checkUserExist($user_id)) {
            $data['title'] = "User Error";
            $data['error_item'] = "User";
            $this->render_view('hotels/gman/error',$data);
        } else {
            /*No Rules No Decision At Least One Field Should Be Marked As Required*/
            $this->form_validation->set_rules('FullName', "User Full Name", 'required');
            $this->form_validation->set_rules('LoginName', "User Name",'required');
            $this->form_validation->set_rules('Email', "User Email",'required');
            $this->form_validation->set_rules('PhoneNum', "User Phone Number",'required');
            $data['user'] = $this->Gman_Users_model->get_that_user($user_id);

            if ($this->form_validation->run() == false) {

                $data['title'] = "Edit/{$data['user']->gman_FullName}/details";
                $this->render_view('hotels/gman/users/user_edit', $data);
            } else {
                $userupdated = $this->Gman_Users_model->edit_user($user_id);
                if ($userupdated) {
                    $this->session->set_flashdata('user_edit_msg', "The User Details Updated Successfully");
                    redirect('gman/users/edit/'.$user_id);
                } else {
                    $this->session->set_flashdata('user_edit_msg', "<h4 style='text-align:center; color:red'> Sorry , user details update Failed </h4>");
                    $data['title'] = "Edit {$data['user']->gman_FullName}/details";
                    redirect('gman/users/edit/'.$user_id);
                }
            }
        }
    }

    public function profile()
    {
        /* Do It Later!! */
        if (!empty($_SESSION['gman_fullName'])) {
            $data['User_Fname'] = $_SESSION['gman_fullName'];
        }
        $data['title'] = "User Profile";
        $this->render_view('hotels/gman/users/user_profile', $data);
    }

    public function b2b_users(){
       
        $data['title'] = 'Active B2b Users';
        $data['user'] = $this->Gman_Users_model->fetch_b2b();
        $this->render_view('hotels/gman/users/b2b_users', $data);
    }

    public function activate_b2b($id){
        $activate = $this->Gman_Users_model->activate_b2b_user($id);
        if($activate){
            $res =  true;
            if($res){
                redirect('gman/users/b2b_users'); 
            }
        }else{
            return false;
        }
    }

    public function b2b_user_details($id){
        // $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        // show_error(print_r($uriSegments));
        $data['title'] = "Edit B2b User";
        $this->load->library('pagination');
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['base_url'] = site_url() . 'gman/users/b2b_user_details/'.$id;
        $config['total_rows'] = $this->Gman_Users_model->b2b_invoicecount($id);
        $config['per_page'] = 5;  
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] ='</li>';
        $config['next_link'] = '<span class="page-link">Next</span>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<span class="page-link">Prev</span>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['first_link'] = '<span class="page-link">First</span>';
        $config['last_link']  = '<span class="page-link">Last</span>';
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $page = ($this->input->get('page')) ? ( ( $this->input->get('page') - 1 ) * $config["per_page"] ) : 0;
        $data['list'] = $this->Gman_Users_model->get_b2binvoicelist($config['per_page'], $page,$id);
        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links();
        $data['details'] = $this->Gman_Users_model->b2b_user_details($id);
        $data['paidres'] = $this->cm->paidb2bres($id);
        $data['unpaidres'] = $this->cm->unpaidb2bres($id);
        $data['confirmres'] = $this->cm->confirmedb2bres($id);
        $data['sales'] = $this->cm->b2bsales($id);
      

        $this->render_view('hotels/gman/users/b2b_users_details', $data);


    }

    public function b2b_add_deposit($id){
       $amount = $this->input->post('amount');
        $deposited = $this->Gman_Users_model->add_deposit($id,$amount);

        if($deposited){
            redirect('gman/users/b2b_users');
        }else{
            show_error('problem while adding the deposit');
        }


    }
    public function b2c_users(){
        
        $data['title'] = 'Active B2c Users';
        $data['b2c_user'] = $this->Gman_Users_model->b2c_user_list();
        $this->render_view('hotels/gman/users/b2c_users', $data);

    }
 
}
