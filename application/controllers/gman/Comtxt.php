<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Comtxt extends Gman_Controller
{

    public function index()
    {
        $this->load->model('translate/comtranslate');
        $data['coms'] = $this->comtranslate->get_coms();
        $data['title'] = comtrans('syscomtitle');
        $this->render_view('hotels/gman/comtxt/list', $data);
    }
    public function edit(int $id = null)
    {
        $this->load->library('form_validation');
        $this->load->helper('array');
        $this->load->model('translate/comtranslate');
        $this->form_validation->set_rules('s_txt', 'Text to be traslated', 'required');
        $data['com'] = $this->comtranslate->that_com($id);
        $data['title'] = comtrans('syscomedit');
        if ($this->form_validation->run() == FALSE) {
            $this->render_view('hotels/gman/comtxt/edit', $data);
        } else {
            $data_add = array();
            $to_langs = langs_codes();
            $from_lang = $this->input->post('from_lang');
            $s_text = $this->input->post('s_txt');
            $data_add[$from_lang] = $s_text;
            foreach ($to_langs as $key => $value) {
                $data_add[$key] = trans($s_text, $from_lang, $key);
            }
            if ($this->comtranslate->com_update_all($id, $data_add)) {
                $this->session->set_flashdata('statusMsg', comtrans('editsuccess'));
                redirect("gman/comtxt/edit/{$id}");
            } else {
                show_error('Falied');
            }
        }
    }
    public function edit_com(int $id = null, $col_lang)
    {
        $this->load->model('translate/comtranslate');
        if ($this->comtranslate->com_update($id, $col_lang)) {
            $this->session->set_flashdata('statusMsg', comtrans('editsuccess'));
            redirect("gman/comtxt/edit/{$id}");
        } else {
            $this->session->set_flashdata('statusMsg', comtrans('editfailed'));
            redirect("gman/comtxt/edit/{$id}");
        }
    }
}
