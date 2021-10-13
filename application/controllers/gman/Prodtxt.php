<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

class Prodtxt extends Gman_Controller
{

    public function index()
    {
        $data['title'] = "MultiLanguage Text Manupilations";
        $this->render_view('hotels/gman/products/product_txt', $data);
    }

    public function add($txt_type, $prod_id)
    {
        $this->load->library('form_validation');
        $data['product_id'] = $prod_id;
        $data['title'] = "MultiLanguage Text Manupilations";
        $this->load->model('translate/Translate_model');
        $data['langs'] = $this->Translate_model->get_langs();
        $data['multilang_titles'] = $this->Translate_model->product_titles($prod_id);
        $data['txt_type'] = $txt_type;
        $this->form_validation->set_rules('s_txt', 'Text to be traslated', 'required');
        if (!isset($txt_type) || empty($txt_type)) {
            show_error('Wrong page no text type determined');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->render_view('hotels/gman/products/prod_addtxt', $data);
        } else {
            $data_add = array();
            $product_id = $this->input->post('product_id');
            $txt_type = $this->input->post('txt_type');
            $to_langs = $this->input->post('tolangs');
            $from_lang = $this->input->post('from_lang');
            $s_text = $this->input->post('s_txt');
            if ($this->input->post('assign_to')) {
                $data_add['assign_to'] = $this->input->post('assign_to');
            }
            $data_add['S_ID'] = $product_id;
            $data_add['S_TYPE'] = $txt_type;
            $data_add['R_Order'] = 1;
            $data_add[$from_lang] = $s_text;
            foreach ($to_langs as $key => $value) {
                $data_add[$key] = trans($s_text, $from_lang, $value);
            }
            if ($this->Translate_model->count_exist($product_id, $txt_type)) {
                $data_add['R_Order'] += $this->Translate_model->count_exist($product_id, $txt_type);
            }
            if ($txt_type == 'prodtitle') {
                $type_added = "Title";
            }elseif ($txt_type == 'proddesc') {
                $type_added = "Description";
            }
            if ($this->Translate_model->add_title($data_add)) {
                $this->session->set_flashdata('statusMsg', "multilanguage product {$type_added} added successfully");
                redirect("gman/products/edit/$product_id");
            } else {
                show_error('Falied');
            }
        }
    }


    public function edit($prod_id, $text_id, $txt_type)
    {
        $this->load->library('form_validation');
        $this->load->helper('array');
        $data['product_id'] = $prod_id;
        $data['title'] = "MultiLanguage Text edit";
        $this->load->model('translate/Translate_model');
        $data['langs'] = $this->Translate_model->get_langs();
        $data['txts'] = $this->Translate_model->get_trans($text_id);
        $data['txt_type'] = $txt_type;
        $this->form_validation->set_rules('s_txt', 'Text to be traslated', 'required');
        if (!isset($txt_type) || empty($txt_type)) {
            show_error('Wrong page no text type determined');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->render_view('hotels/gman/products/prod_edittxt', $data);
        } else {
            $data_add = array();
            $product_id = $this->input->post('product_id');
            $txt_type = $this->input->post('txt_type');
            $to_langs = langs_codes();
            $from_lang = $this->input->post('from_lang');
            $s_text = $this->input->post('s_txt');
            $data_add['S_ID'] = $product_id;
            $data_add['S_TYPE'] = $txt_type;
            $data_add['R_Order'] = 1;
            $data_add[$from_lang] = $s_text;
            foreach ($to_langs as $key => $value) {
                $data_add[$key] = trans($s_text, $from_lang, $key);
            }
            if ($this->Translate_model->count_exist($product_id, $txt_type)) {
                $data_add['R_Order'] += $this->Translate_model->count_exist($product_id, $txt_type);
            }
            if ($txt_type == 'prodtitle') {
                $type_added = "Title";
            } elseif ($txt_type == 'proddesc') {
                $type_added = "Description";
            }
            if ($this->Translate_model->edit_title($data_add, $text_id)) {
                $this->session->set_flashdata('statusMsg', " product {$type_added} edited successfully");
                redirect("gman/prodtxt/edit/$product_id/$text_id/$txt_type");
            } else {
                show_error('Falied');
            }
        }
    }

    public function edit_single_txt($prod_id, $text_id, $txt_type, $col)
    {
        $this->load->model('translate/Translate_model');
        $txt_data = $this->input->post($col);
        $data_add[$col] = $txt_data;
        if ($this->Translate_model->edit_that($data_add, $text_id)) {
            $this->session->set_flashdata('statusMsg', " product {$txt_type} edited successfully");
            redirect("gman/prodtxt/edit/$prod_id/$text_id/$txt_type");
        } else {
            show_error('Falied');
        }
    }

    public function assign_desc_title()
    {
        $this->load->model('translate/Translate_model');
        $prod_id = $this->input->post('prod_id');
        $desc_id = $this->input->post('assigned');
        $title_id = $this->input->post(assign_for);
        $data_add['assign_to'] = $title_id;
        if ($this->Translate_model->assign_desc_title($data_add, $desc_id)) {
            $this->session->set_flashdata('statusMsg', " product multilanguage edited successfully");
            redirect("gman/products/edit/$prod_id");
        } else {
            show_error('Falied');
        }
    }

    public function txt_order($prod_id, $id)
    {
        $this->load->model('translate/Translate_model');
        $order = $this->input->post('order');
        if ($this->Translate_model->prodtxt_order($id, $order)) {
            $this->session->set_flashdata('statusMsg', " product multilanguage sorted successfully");
            redirect("gman/products/edit/$prod_id");
        } else {
            show_error('Falied');
        }
    }

    public function delete($product_id, $text_id, $txt_type)
    {
        $this->load->model('translate/Translate_model');
        if ($this->Translate_model->delete($text_id)) {
            $this->session->set_flashdata('statusMsg', "multilanguage product {$txt_type} deleted successfully");
            redirect("gman/products/edit/$product_id");
        } else {
            show_error('Falied');
        }
    }
}
