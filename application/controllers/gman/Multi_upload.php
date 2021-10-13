<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Multi_upload extends Gman_Controller
{
 
    private $_uploaded;
 
    public function index()
    {
        $data['title'] = 'Multiple file upload';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('uploadedimages[]', 'Upload image', 'callback_fileupload_check');
        if($this->input->post()) {
            if($this->form_validation->run()) {
                $this->load->model('Product_Photo_model');
                $product_id = $this->input->post('product_id');
                $oldcount = $this->Product_Photo_model->product_photo_count($product_id);
                $up_data = array();
                $count = count($this->_uploaded);
                for($i = 0; $i < $count; $i++){
                    $up_data_push = array('Product_ID' => $product_id,
                               'Photo_Name'=>$this->_uploaded[$i]['file_name'],
                               'Photo_Order' => ($i+1+$oldcount) 
                              );
                    array_push($up_data, $up_data_push);
                }

                if($this->Product_Photo_model->add_product_photos($up_data)) {
                    $this->session->set_flashdata('statusMsg', 'The Product Images Updated Successfully');
                    redirect("gman/products/edit/{$product_id}");
                }else{
                    echo "So Sorry";
                }
            }
        }
        show_error('You HAve not selected any images', 'Upload error happened');
    }
 
    public function fileupload_check()
    {
       
        $number_of_files = sizeof($_FILES['uploadedimages']['tmp_name']);
        $files = $_FILES['uploadedimages'];
        for($i=0;$i<$number_of_files;$i++)
        {
            if($_FILES['uploadedimages']['error'][$i] != 0) {
               
                $this->form_validation->set_message('fileupload_check', 'Couldn\'t upload the file(s)');
                return false;
            }
        }
       
        $this->load->library('upload');
        
        $date = new DateTime();
        $date->setTimestamp(now());
        $time_based_name = $date->format('YmdHis');
       
        $config['upload_path'] = FCPATH . './assets/images/products/';
       
        $config['allowed_types'] = 'jpeg|jpg|png|gif|bmp|JPEG|JPG|PNG|GIF|BMP';
        $config['file_name'] = FILES_SITE_NAME.$time_based_name;
        for ($i = 0; $i < $number_of_files; $i++)
        {
            $_FILES['uploadedimage']['name'] = $files['name'][$i];
            $_FILES['uploadedimage']['type'] = $files['type'][$i];
            $_FILES['uploadedimage']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['uploadedimage']['error'] = $files['error'][$i];
            $_FILES['uploadedimage']['size'] = $files['size'][$i];
            $config['file_name'] = FILES_SITE_NAME.$time_based_name.$i;
            
            $this->upload->initialize($config);
            if ($this->upload->do_upload('uploadedimage')) {
                $this->_uploaded[$i] = $this->upload->data();
                $this->mkthumb($this->_uploaded[$i]);
            }
            else
            {
                $this->form_validation->set_message('fileupload_check', $this->upload->display_errors());
                return false;
            }
        }
        return true;
    }
  
    public function mkthumb($pic)
    {
        $config['image_library'] = 'gd2';
        $config['create_thumb'] = true;
        $config['maintain_ratio'] = true;
        $config['width'] = 120;
        $config['height'] = 90;
        $config['source_image'] = './assets/images/products/'.$pic['file_name'];
        $this->load->library('image_lib', $config);
        if($this->image_lib->resize()) {
            $this->image_lib->clear();
        }else{
            $this->image_lib->display_errors('<p>', '</p>');
        }
            $this->load->library('image_lib', $config);
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
    }
}