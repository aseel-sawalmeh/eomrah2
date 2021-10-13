<?php defined('BASEPATH') or exit('No Direct Access Is Alloewd');

class Front_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if($this->session->userdata('b2bvalidated')){
            redirect('b2b');
        }
      
       
    }

    public function render_view($views, $data)
    {
        $this->load->view('hotels/inc/header', $data);
        if (is_array($views)) {
            foreach ($views as $view) {
                $this->load->view("$view");
            }
        } else {
            $this->load->view("$views");
        }
        $this->load->view('hotels/inc/footer');

    }
}
