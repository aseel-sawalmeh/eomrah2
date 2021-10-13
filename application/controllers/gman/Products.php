<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

class Products extends Gman_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'date'));
        $this->load->model('Products_model');
        $this->load->model('Product_Categories_model');
        $this->load->model('Hotel_model');
        $this->load->model('Product_Photo_model');
        $this->load->model('Multimedia_model');
        $this->load->model('translate/Translate_model');
        $this->load->library('form_validation');
        $this->load->library('Myimgtoolset', ['folder'=>'products', 'fieldname'=>'mainphoto']);
    }

    public function index() {
echo "list";
    }
    public function active()
    {

        $data['title'] = "Product Category List Management";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'gman/products/active';
        $config['total_rows'] = $this->Products_model->active_products_count();
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
        $data['active_products'] = $this->Products_model->get_active_products($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/gman/products/active_products_list', $data);
    }

    public function pending()
    {
        $data['title'] = "Product Category List Management";
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'gman/products/pending';
        $config['total_rows'] = $this->Products_model->inactive_products_count();
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
        $data['active_products'] = $this->Products_model->get_inactive_products($config['per_page'], $page);
        $this->pagination->initialize($config);
        $data['pagination_links'] =  $this->pagination->create_links();
        $this->render_view('hotels/gman/products/active_products_list', $data);
    }

    public function add()
    {
       
        $this->form_validation->set_rules('product_name', lang('blogname'), 'required');
        $this->form_validation->set_rules('product_desc', lang('blogdesc'), 'required');
        $this->form_validation->set_rules('source_lang', lang('language'), 'required');
        $data['title'] = "Add New Product";
        $data['product_categories'] = $this->Product_Categories_model->get_pcategories();
        $data['hotels'] = $this->Hotel_model->get_hotels();
        $data['hotelinfo'] = $this->session->userdata('hotelinfo');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/products/add_product', $data);
        } else {
            if ($_FILES['mainphoto']['size'] !== 0) {
                $product_photo = $this->myimgtoolset->img_upload('mainphoto');
            } else {
                $product_photo = FALSE;
            }
            $insert_id = $this->Products_model->add_product($product_photo);
            if ($insert_id) {
                $p_name = $this->input->post('product_name');
                $p_desc = $this->input->post('product_desc');
                $source = $this->input->post('source_lang');
                $to = '';
                if($source == 'en'){
                    $to = 'ar';
                }else if($source == 'ar'){
                    $to = 'en';
                }
                
              $title_trans = $this->Translate_model->prod_translate($insert_id, $p_name, $to, trans($p_name, $source, $to), 'prodtitle');
              if($title_trans){
                $this->Translate_model->prod_translate($insert_id, $p_desc, $to, trans($p_desc, $source, $to), 'proddesc');
            }
               
                $this->session->set_flashdata('product_add_mgs', "<h1 class='uk-text-center'>Product <span style='color:red'> {$p_name} </span> Has Been Added Successfully </h1>");
                redirect('gman/products/pending');
            } else {
                show_error('Falied to insert the product from controller');
            }
        }
    }

    public function edit($product_id)
    {

        if (!$this->Products_model->checkproductExist($product_id)) {
            $data['title'] = "Product Error";
            $data['error_item'] = "Product";
            $this->render_view('hotels/gman/error', $data);
        } else {
            $data['pcats'] = $this->Product_Categories_model->get_pcategories();
            $data['hotels'] = $this->Hotel_model->get_hotels();
            $data['product'] = $this->Products_model->get_that_Product($product_id);
            $data['selectproducts'] = $this->Products_model->selects_product();
            $data['subproducts'] = $this->Products_model->get_subproducts($product_id);
            $data['multimedia_cats'] = $this->Multimedia_model->get_multimedia_cats();
            $data['multilang_titles'] = $this->Translate_model->product_titles($product_id);
            $data['multilang_desc'] = $this->Translate_model->product_desc($product_id);
            $data['productid'] = $product_id;
            $data['product_multimedia'] = $this->Multimedia_model->get_product_multimedia($product_id);
            $data['prod_cat'] = $data['product']->P_Category_ID;
            if ($this->Product_Photo_model->get_that_product_photos($product_id)) {
                $data['product_photos'] = $this->Product_Photo_model->get_that_product_photos($product_id);
            } else {
                $data['product_photos'] = FALSE;
            }
            $this->form_validation->set_rules('product_name', lang('blogname'), 'required');
            $this->form_validation->set_rules('product_desc', lang('blogdesc'), 'required');
            $this->form_validation->set_rules('source_lang', lang('language'), 'required');
            $data['title'] = "Edit {$data['product']->P_Name} details";
            if ($this->form_validation->run() == FALSE) {
                $this->render_view('hotels/gman/products/product_edit', $data);
            } else {
                if ($_FILES['mainphoto']['size'] !== 0) {
                    $product_photo = $this->myimgtoolset->img_upload('mainphoto');
                } else {
                    $product_photo = FALSE;
                }
            $edited = $this->Products_model->edit_product($product_id, $product_photo);
                if ($edited) {
                    $p_name = $this->input->post("product_name");
                    $p_desc = $this->input->post("product_desc");
                    $source = $this->input->post('source_lang');
                    $to = '';
                    if($source == 'en'){
                        $to = 'ar';
                    }else if($source == 'ar'){
                        $to = 'en';
                    }
                    
                  $title_trans = $this->Translate_model->prod_translate($product_id, $p_name, $to, trans($p_name, $source, $to), 'prodtitle');
                  if($title_trans){
                    $this->Translate_model->prod_translate($product_id, $p_desc, $to, trans($p_desc, $source, $to), 'proddesc');
                }
                    
                    $this->session->set_flashdata('statusMsg', 'The Product Details Updated Successfully');
                    redirect("gman/products/edit/{$product_id}");
                } else {
                    show_error("erro while updating the product");
                }
            }
        }
    }
    public function set_product_multimedia()
    {
      
        $product_id = $this->input->post('product_id');
        if ($this->Multimedia_model->set_product_mediadata($product_id)) {
            $this->session->set_flashdata('statusMsg', 'Mutlimedia data saved');
            redirect("gman/products/edit/{$product_id}");
        } else {
            show_error("An Error Happened while add multimedia data");
        }
    }

    public function sub_product_add()
    {
        $this->load->model('Products_model');
        $product_id = $this->input->post('mainproduct_id');
        $subproduct_id = $this->input->post('subproduct_id');
        if ($product_id !== $subproduct_id) {
            if ($this->Products_model->sub_product_add($product_id, $subproduct_id)) {
                $this->session->set_flashdata('statusMsg', 'sub product added to list');
                redirect("gman/products/edit/{$product_id}");
            } else {
                $this->session->set_flashdata('statusMsg', '<div style=color:red >you have to select a category to add</div>');

                $this->session->set_flashdata('statusMsg', 'You Have to select a product');

                redirect("gman/products/edit/{$product_id}");
            }
        } else {
            show_error("The Product Can not be A sub of it self (-_-)");
        }
    }
    public function sub_prod_order($product_id, $subproduct_id)
    {
        $this->load->model('Products_model');
        $order = $this->input->post('subproductorder');
        if ($this->Products_model->sub_prod_order($subproduct_id, $order)) {
            $this->session->set_flashdata('statusMsg', 'sub product ordered successfully list');
            redirect("gman/products/edit/{$product_id}");
        } else {
            show_error("An Error Happened while ordering sub product in list");
        }
    }

    public function sub_prod_delete($product_id, $subproduct_id)
    {
        $this->load->model('Products_model');
        if ($this->Products_model->sub_product_delete($subproduct_id)) {
            $this->session->set_flashdata('statusMsg', 'sub product deleted from list');
            redirect("gman/products/edit/{$product_id}");
        } else {
            show_error("An Error Happened while deleting sub product from list");
        }
    }

    public function delete_product_photo()
    {
        $toback = $this->input->post('toback');
        $product_id = $this->input->post('product_id');
        $photo_id = $this->input->post('photo_id');
        if ($this->Product_Photo_model->product_photo_delete($photo_id)) {
            $this->session->set_flashdata('statusMsg', 'photo deleted successfully');
            if ($toback && $toback != null) {
                redirect($toback);
            } else {
                redirect("gman/products/edit/{$product_id}");
            }
        } else {
            show_error("An Error Happened while delete photo data");
        }
    }

    public function order_product_photo()
    {
        $toback = $this->input->post('toback');
        $product_id = $this->input->post('product_id');
        $photo_id = $this->input->post('photo_id');
        $photo_order = $this->input->post('photo_order');
        if ($this->Product_Photo_model->product_photo_order($photo_id, $photo_order)) {
            $this->session->set_flashdata('statusMsg', 'photo ordered successfully');
            if ($toback && $toback != null) {
                redirect($toback);
            } else {
                redirect("gman/products/edit/{$product_id}");
            }
        } else {
            show_error("An Error Happened while update photo order");
        }
    }

    public function profile()
    {
        if (!empty($_SESSION['hotels/gman_fullName'])) {
            $data['User_Fname'] = $_SESSION['hotels/gman_fullName'];
        }
        $data['title'] = "User Profile";
        $this->render_view('gman/users/user_profile', $data);
    }

    public function cat_products($cat_id)
    {
        $this->load->model('Products_model');
        if (isset($cat_id) && !empty($cat_id)) {
            if ($this->Products_model->get_that_cat_products($cat_id)) {
                $cat_products = $this->Products_model->get_that_cat_products($cat_id);
                foreach ($cat_products as $product) {
                    echo "<option value='{$product->P_ID}'>{$product->P_Name}</option>";
                }
            } else {
                echo NULL;
            }
        }
    }
    public function subcatsajax($parent_id)
    {
        $this->load->model('Product_Categories_model');
        $this->load->model('Product_Categories_model');
    }
    private $_uploaded;
    public function p_mphoto_upload()
    {
        $this->load->helper('form');
        $data['title'] = 'Multiple file upload';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('uploadedimages[]', 'Upload image', 'callback_fileupload_check');

        if ($this->input->post()) {

            if ($this->form_validation->run()) {
                echo '<pre>';
                print_r($this->_uploaded);
                echo '</pre>';
                exit;
            }
        }
        $this->render_view('hotels/products/product_edit', $data);
    }

    public function fileupload_check()
    {
        $number_of_files = sizeof($_FILES['uploadedimages']['tmp_name']);

        $files = $_FILES['uploadedimages'];

        for ($i = 0; $i < $number_of_files; $i++) {
            if ($_FILES['uploadedimages']['error'][$i] != 0) {
                $this->form_validation->set_message('fileupload_check', 'Couldn\'t upload the file(s)');
                return FALSE;
            }
        }

        $this->load->library('upload');
        $config['upload_path'] = FCPATH . 'upload/';
        $config['allowed_types'] = 'gif|jpg|png';

        for ($i = 0; $i < $number_of_files; $i++) {
            $_FILES['uploadedimage']['name'] = $files['name'][$i];
            $_FILES['uploadedimage']['type'] = $files['type'][$i];
            $_FILES['uploadedimage']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['uploadedimage']['error'] = $files['error'][$i];
            $_FILES['uploadedimage']['size'] = $files['size'][$i];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('uploadedimage')) {
                $this->_uploaded[$i] = $this->upload->data();
            } else {
                $this->form_validation->set_message('fileupload_check', $this->upload->display_errors());
                return FALSE;
            }
        }
        return TRUE;
    }
}