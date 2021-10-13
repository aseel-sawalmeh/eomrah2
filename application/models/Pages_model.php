<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');
class Pages_model  extends CI_Model
{

    /*About Page Edit and Add Starts */
    public function get_about()
    {
        $this->db->where('name', 'aboutus');
        $query = $this->db->get('pages');
        if ($query->num_rows() > 0) {
           return $query->row();
        } else {
            return false;
        }
    }


    public function about()
    {
        $content =$this->input->post('about_content');
        $data = array(
            'content' => $content
        );
        $this->db->where('name', 'aboutus');
        $query = $this->db->update('pages', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    /*About Page Edit and Add Ends */


    /**Terms Page Edit and Add Starts */
    public function get_terms()
    {
        $this->db->where('name', 'terms');
        $query = $this->db->get('pages');
        if ($query->num_rows() > 0) {
         return $query->row();
        } else {
            return false;
        }
    }

    public function terms()
    {
        $content = $this->input->post('term_content');
        $data = array(

            'content' => $content
        );
        $this->db->where('name', 'terms');
        $query = $this->db->update('pages', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    /*End Terms Edit and Add*/

    //Faqs List Queries//
    public function get_faqs()
    {
        $this->db->join('faq_cat', 'faqs.category = faq_cat.cat_id', 'left');
        $query = $this->db->get('faqs');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

   
    //Faqs List Ends Here//


    //Faqs Add Queries//

    public function insert_faqs()
    {
        $question = $this->input->post('question');
        $answer = $this->input->post('answer');
        $category = $this->input->post('faq_cat');

        $data = array(
            'question' => $question,
            'answer' => $answer,
            'q_slug' => slugify($question),
            'category' => $category
        );

        $this->db->insert('faqs', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
       
    }

    public function faq_cat()
    {
        $query = $this->db->get('faq_cat');
        if ($query) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    //Faqs Add Ends Here//


    //Faqs Edit Queries//

    public function get_faq($id)
    {
        $this->db->join('faq_cat', 'faqs.category = faq_cat.cat_id', 'left');
        $this->db->where('faqs.id', $id);
        $query =  $this->db->get('faqs');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function edit_faq($id){
        $question = $this->input->post('question_edit');
        $answer = $this->input->post('answer_edit');
        $category = $this->input->post('faq_cat');
        $data = array(
            'question' => $question,
            'answer' => $answer,
            'category' => $category
        );
        $this->db->where('id', $id);
        $query = $this->db->update('faqs', $data);
        if($query){
            return true;
        }else{
            return false;
        }
    }
    //Faqs Edit Ends Here //

    //Get Faqs According To Category In The Front End//
    public function faq($name)
    {
        $this->db->join('faq_cat', 'faqs.category = faq_cat.cat_id', 'left');
        $this->db->where('faq_cat.cat_name', $name);
        $query = $this->db->get('faqs');
        if($query->num_rows() > 0)
        {
            return $query->result();
        }else{
            return false;
        }
    }

    public function faq_delete($id){
        $this->db->where('id', $id);
       $query =  $this->db->delete('faqs');
       if($query){
           return true;
       }else{
           return false;
       }

    }
    
    
    
}
