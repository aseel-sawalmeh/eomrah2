<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Faq extends Front_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pages_model', 'pm'); 
    }

    public function index()
    {
        $data['title'] = lang('faqs');
        $data['faqs'] = $this->pm->get_faqs();
        $data['category'] = $this->pm->faq_cat();
        $this->render_view(['hotels/inc/banner', 'hotels/faqs'], $data);
    }

    public function category($name)
    {
        $data['title'] = 'Faqs - '.$name;
        $data['faqs'] = $this->pm->faq($name);
        $data['category'] = $this->pm->faq_cat();
        $this->render_view(['hotels/inc/banner', 'hotels/faq_list'], $data);

    }
}