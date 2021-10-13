<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

class Pcategory extends Gman_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("url");
        $this->load->helper("form");
        $this->load->model('Product_Categories_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('translate/Translate_model');

    }
    public function index()
    {
        $data['title'] = "Products Categories Management, STATS";
        $data['pcats_count'] = $this->Product_Categories_model->pcats_count();
        $this->render_view('hotels/gman/products/pcats', $data);
    }

    public function list()
    {
        $data['title'] = "Product Category List Management";
        $config['base_url'] = base_url() . 'gman/Pcategory/list';
        $config['total_rows'] = $this->Product_Categories_model->pcats_count();
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
        $data['pcats'] = $this->Product_Categories_model->fetch_pcats($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/gman/products/pcat_list', $data);
    }

    public function add()
    {
        $this->form_validation->set_rules('field', 'field', 'rfield');
        $data['title'] = "Add New Product Category";
        $data['pcats'] = $this->Product_Categories_model->get_pcategories();
        $this->render_view('hotels/gman/products/pcat_add', $data);
    }

    public function pcat_add_go()
    {
       
        $pcat_parent = html_escape($this->input->post('pcat_parent'));
        $pcat_name = html_escape($this->input->post('pcat_name'));
        $pcat_desc = html_escape($this->input->post('pcat_desc'));
        $pcat_order = html_escape($this->input->post('pcat_order'));
        $pcat_added = $this->Product_Categories_model->add_Category($pcat_parent, $pcat_name, $pcat_desc, $pcat_order);
        if ($pcat_added == TRUE) {
            $this->session->set_flashdata('pcat_add_mgs', "Category <span style='color:red'> {$pcat_name} </span> Has Been Added Successfully");
            redirect('gman/pcategory/list');
        }
    }
   
    public function edit($pcat_id)
    {
        $data['multilang_titles'] = $this->Translate_model->cat_titles($pcat_id);
        $data['multilang_desc'] = $this->Translate_model->cat_desc($pcat_id);
        if (!$this->Product_Categories_model->checkPcatExist($pcat_id)) {
            $data['title'] = "Category Error";
            $data['error_item'] = "Category";
            $this->render_view('hotels/gman/error', $data);
        } else {
            $data['pcats'] = $this->Product_Categories_model->get_pcategories();
            $data['pcat'] = $this->Product_Categories_model->get_that_Pcat($pcat_id);
            $this->form_validation->set_rules('pcat_name', 'Category Name', 'required');
            $data['title'] = "Edit {$data['pcat']->P_Category_Name} details";

            if ($this->form_validation->run() == FALSE) {
                $this->render_view('hotels/gman/products/pcat_edit', $data);
            } else {
                if ($this->Product_Categories_model->edit_category($pcat_id)) {
                    redirect('gman/pcategory/list');
                }
            }
        }
    }
}
