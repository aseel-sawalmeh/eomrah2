<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

/*
 * This Library to add some tools customized for The work
 * Made to extend the core as Version one additional toolset
 * PHP Version 7.3
 * Auther Gebriel Alkhayal
 * toolset Version 1.0
 */

class Myimgtoolset
{
    private $IM;
    private $uploaded = [];
    private $folder = 'hotels/';
    private $fieldname;
    public $uperrors = [];

    public function __construct($params)
    {
        // Assign by reference with "&" so we don't create a copy
        $this->IM = &get_instance();
        $this->IM->load->library(['upload', 'image_lib']);
        $this->folder = $params['folder'];
        $this->fieldname = $params['fieldname'];
    }

    public function ups()
    {
        return $this->uploaded;
    }

    public function fileup()
    {
        if (isset($_FILES[$this->fieldname])) {
            $back_error = [];
            $errors = array(
                '0' => "UPLOAD_ERR_OK There is no error, the file uploaded with success",
                '1' => "UPLOAD_ERR_INI_SIZE  The uploaded file exceeds the upload_max_filesize",
                '2' => "UPLOAD_ERR_FORM_SIZE  The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
                '3' => "UPLOAD_ERR_PARTIAL  The uploaded file was only partially uploaded",
                '4' => "UPLOAD_ERR_NO_FILE  No file was uploaded",
                '6' => "UPLOAD_ERR_NO_TMP_DIR  Missing a temporary folder",
                '7' => "UPLOAD_ERR_CANT_WRITE  Failed to write file to disk",
                '8' => "UPLOAD_ERR_EXTENSION  A PHP extension stopped the file upload",
            );
            $fls = sizeof($_FILES[$this->fieldname]['tmp_name']);
            //show_error(print_r($_FILES[$this->fieldname]['name']));
            $files = $_FILES[$this->fieldname];
            for ($i = 0; $i < $fls; $i++) {
                if ($_FILES[$this->fieldname]['error'][$i] != 0) {
                    $back_error['b4'][] = $errors["{$_FILES[$this->fieldname]['error'][$i]}"];
                }
            }
            //generate time based name for files uploads
            $date = new DateTime();
            $date->setTimestamp(now());
            $time_based_name = $date->format('YmdHis');
            // next we pass the upload path for the images
            $config['upload_path'] = FCPATH . 'assets/images/' . $this->folder;
            // also, we make sure we allow only certain type of images
            $config['allowed_types'] = 'jpeg|jpg|png|gif|bmp|JPEG|JPG|PNG|GIF|BMP';
            $config['file_name'] = FILES_SITE_NAME . $time_based_name;
            $config['max_size'] = 5512;
            $this->IM->upload->initialize($config);
            for ($i = 0; $i < $fls; $i++) {
                $_FILES['uploadedimage']['name'] = $files['name'][$i];
                $_FILES['uploadedimage']['type'] = $files['type'][$i];
                $_FILES['uploadedimage']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['uploadedimage']['error'] = $files['error'][$i];
                $_FILES['uploadedimage']['size'] = $files['size'][$i];
                $config['file_name'] = FILES_SITE_NAME . $time_based_name . $i;
                //now we initialize the upload library
                if ($this->IM->upload->do_upload('uploadedimage')) {
                    $this->uploaded[$i] = $this->IM->upload->data();
                    $this->mkthumb($this->uploaded[$i]);
                    //print_r($i);
                    //return true;
                } else {
                    $back_error['af'] = $this->IM->upload->display_errors();
                }
            }
            if (count($back_error) > 0) {
                $this->uperrors = $back_error;
                return $back_error;
            } else {
                return true;
            }

        }else{
            return false;
        }
    }

    public function mkthumb($pic)
    {
        $config['image_library'] = 'gd2';
        $config['create_thumb'] = true;
        $config['maintain_ratio'] = true;
        $config['width'] = 800;
        $config['height'] = 600;
        //if the image file uploaded create a thumbnail
        $config['source_image'] = "./assets/images/$this->folder" . $pic['file_name'];
        $this->IM->image_lib->initialize($config);
        if ($this->IM->image_lib->resize()) {
            $this->IM->image_lib->clear();
        } else {
            $this->IM->image_lib->display_errors('<p>', '</p>');
        }
        $this->IM->image_lib->clear();
        $this->IM->image_lib->initialize($config);
        $this->IM->image_lib->resize();
    }

    public function img_upload($up_img)
    {
        $upload = new $this->IM->upload;
        $image_lib = new $this->IM->image_lib;
        //generate time based name for files uploads
        $date = new DateTime();
        $date->setTimestamp(now());
        $time_based_name = $date->format('YmdHis');
        // file upload settings for product images
        $config['upload_path'] = "./assets/images/$this->folder";
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = FILES_SITE_NAME . $time_based_name;
        $data['time_file_name'] = FILES_SITE_NAME . $time_based_name;
        $upload->initialize($config);
        //start with uploading the image
        if (!$upload->do_upload($up_img)) {
            $error = "<h3> hotel image upload error </h3>" . $upload->display_errors('<p>', '</p>');
            show_error($error);
        } else {
            $product_photo = $upload->data('file_name');
            //if the image file uploaded create a thumbnail
            $config['image_library'] = 'gd2';
            $config['source_image'] = "./assets/images/$this->folder" . $product_photo;
            $config['create_thumb'] = true;
            $config['maintain_ratio'] = true;
            //$config['width'] = 400;
            //$config['height'] = 150;
            $image_lib->initialize($config);
            //$image_lib->resize();
            return $product_photo;
        }
    }

}
