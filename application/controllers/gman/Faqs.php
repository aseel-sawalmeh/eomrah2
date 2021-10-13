<?php defined('BASEPATH') or exit('No direct script access allowed');
class Faqs extends Gman_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pages_model', 'pm');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Faqs";
        $data['faqs'] = $this->pm->get_faqs();
        $this->render_view('hotels/gman/faqs_list', $data);
    }

    public function add()
    {
        $this->load->model('translate/translate_model', 'tm');
        $data['title'] = "Add Faqs";
        $data['cat'] = $this->pm->faq_cat();
        $this->form_validation->set_rules('question', 'Question', 'required');
        $this->form_validation->set_rules('answer', 'Answer', 'required');
        $this->form_validation->set_rules('faq_cat', 'Category', 'required');
        $this->form_validation->set_rules('lang_to', 'language', 'required');
        
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/faqs_add', $data);
        } else {
            $insert_id = $this->pm->insert_faqs();
            if ($insert_id) {
                $to = $this->input->post('lang_to');
                $ans_source = $this->input->post('answer');
                $qs_source = $this->input->post('question');
                //$sid, $source, $trg, $trgdata,  $type
                $qs = $this->tm->faq_translate($insert_id, $qs_source, $to, trans($qs_source, 'en', $to), 'qsfaq');
                if ($qs) {
                    $ans = $this->tm->faq_translate($insert_id, $ans_source, $to, trans($ans_source, 'en', $to), 'ansfaq');
                    if ($ans) {
                         $this->session->set_flashdata('faq_added', 'Faq Added SuccessFully');
                        redirect('gman/faqs');
                       
                    }else{
                        show_error("Problem While Adding the faqs");
                    }
                } else {
                    show_error('Problem While Adding FAQS');
                }
            } else {
                show_error('Problem While Adding FAQS');
            }
        }
    }

    public function edit($id)
    {
        $data['title'] = "Edit Faqs";
        $data['faq'] = $this->pm->get_faq($id);
        $data['cat'] = $this->pm->faq_cat();
        $this->form_validation->set_rules('question_edit', 'Question', 'required');
        $this->form_validation->set_rules('answer_edit', 'Answer', 'required');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/faqs_edit', $data);
        } else {
            $update = $this->pm->edit_faq($id);
            if ($update) {
                $this->session->set_flashdata('faq_added', 'Faq Update SuccessFully');
                redirect('gman/faqs');
            } else {
                show_error('Problem While Adding FAQS');
            }
        }
    }

    public function delete($id){
        $delete = $this->pm->faq_delete($id);
        if($delete){
            $this->session->set_flashdata('faq_deleted', 'Faq Deleted SuccessFully');
            redirect('gman/faqs');
        }else{
            show_error('Problem Deleting FAQS');
        }
    }


    public function translate($id)
    {
        $this->load->model('translate/translate_model', 'tm');
        $this->form_validation->set_rules('lang_to', 'language', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('faq_translated', 'Form error');
            redirect("gman/faqs/edit/$id");
        } else {
            $to = $this->input->post('lang_to');
            $ans_source = $this->input->post('ans_edit');
            $qs_source = $this->input->post('qs_edit');
            //$sid, $source, $trg, $trgdata,  $type
            $qs = $this->tm->faq_translate($id, $qs_source, $to, trans($qs_source, 'en', $to), 'qsfaq');
            if ($qs) {
                $ans = $this->tm->faq_translate($id, $ans_source, $to, trans($ans_source, 'en', $to), 'ansfaq');
                if ($ans) {
                    $this->session->set_flashdata('faq_translated', 'Faq Update SuccessFully');
                    redirect("gman/faqs/edit/$id");
                }
            } else {
                show_error('Problem While Adding FAQS');
            }
        }
    }

    public function edit_qs_trans($id){
        $this->load->model('translate/translate_model', 'tm');
        $this->form_validation->set_rules('qstrans_edit', 'Question', 'required');
        if($this->form_validation->run() == false){
            $this->session->set_flashdata('qs_translate', 'Question Is Required');
            redirect("gman/faqs/edit/$id");
        }else{
            $qs_trans = $this->input->post('qstrans_edit');
            $edit_question = $this->tm->translation_update($id,$qs_trans,'qsfaq');
            if($edit_question){
                $this->session->set_flashdata('qs_translate', 'Question Edited Successfully');
                redirect("gman/faqs/edit/$id");
            }else{
                $this->session->set_flashdata('qs_translate', 'Problem Editing Question');
                redirect("gman/faqs/edit/$id");
            }
        }
    }

    public function edit_ans_trans($id){
        $this->load->model('translate/translate_model', 'tm');
        $this->form_validation->set_rules('anstrans_edit', 'Question', 'required');
        if($this->form_validation->run() == false){
            $this->session->set_flashdata('ans_translate', 'Question Is Required');
            redirect("gman/faqs/edit/$id");
        }else{
            $ans_trans = $this->input->post('anstrans_edit');
            $edit_answer = $this->tm->translation_update($id,$ans_trans,'ansfaq');
            if($edit_answer){
                $this->session->set_flashdata('ans_translate', 'Question Edited Successfully');
                redirect("gman/faqs/edit/$id");
            }else{
                $this->session->set_flashdata('ans_translate', 'Problem Editing Question');
                redirect("gman/faqs/edit/$id");
            }
        }
    }

    
}
