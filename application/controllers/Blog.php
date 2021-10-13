<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Blog extends Front_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Blog_model', 'bm');
    }

    public function index()
    {
        $data['title'] = lang('blog');
        $data['blogs'] = $this->bm->get_blog();
        $data['f_blogs'] = $this->bm->featured_blog();
        $this->render_view(['hotels/inc/search_area','hotels/blog'],$data);
    }

    public function detail($slug)
    {
      
        $data['title'] = lang('blogdetails');
        $data['blogs'] = $this->bm->get_blog();

        $data['blog_detail'] = $this->bm->get_blog_detail($slug);
        if($data['blog_detail']){
            $this->render_view(['hotels/inc/search_area','hotels/blog_details'],$data);
         
        }else{
           $this->render_view(['errors/handle/err_404'], $data);
        }
       

       
           
        
        
    }

}