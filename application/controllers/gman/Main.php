<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');

class Main extends Gman_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Count', 'cn');
        $this->load->model('Hperiods_model', 'hpm');
        $this->load->library('pagination');
        $this->load->model('Pages_model', 'pm');
        $this->load->model('Reservation_model', 'rsm');
    }

    public function index()
    {
        $data['title'] = "Admin Panel";
        $h_count = $this->cn->get_h_count();
        $h_incount = $this->cn->getin_h_count();
        $providing_request = $this->cn->getpr_count();
        $exproviding_request = $this->cn->exist_providing();
        $provider_count = $this->cn->getpro_count();
        $active_users = $this->cn->ausers_count();
        $inactive_users = $this->cn->inausers_count();
        $inactive_products = $this->cn->inacpro_count();
        $active_products = $this->cn->acpro_count();
        $data['h_counts'] = $h_count;
        $data['hin_counts'] = $h_incount;
        $data['providers_count'] = $provider_count;
        $data['providing_requests'] =  $providing_request;
        $data['active_user'] =  $active_users;
        $data['inactive_user'] =  $inactive_users;
        $data['ac_pro'] =  $active_products;
        $data['in_pro'] =  $inactive_products;
        $data['ex_re'] = $exproviding_request;
        $this->render_view('hotels/gman/main', $data);
    }

    /*B2C INVOICES STARTS HERE */

    public function b2c_reports()
    {
        $config['base_url'] = site_url() . 'gman/main/b2c_reports';
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<span class="page-link">Next</span>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<span class="page-link">Prev</span>';
        $config['first_link'] = '<li class="page-item"><span class="page-link">First</span></li>';
        $config['last_link']  = '<li class="page-item"><span class="page-link">Last</span></li>';
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $data['title'] = "Reports";
        $data['reports'] = [];
        $paidres = [];
        $unpaidres = [];
        $totalpaidres = 0;
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $resva = $this->hpm->get_reports();
        if ($resva) {
            for ($i = 0; $i < count($resva); $i++) {
                $data['reports'][] = $resva[$i];
                if ($resva[$i]->Paid == 1) {
                    $paidres[] = $resva[$i];
                    $totalpaidres += $resva[$i]->NetPrice;
                } else {
                    $unpaidres[] = $resva[$i];
                }
            }
        }
        $config['total_rows'] = count($data['reports']);
        $this->pagination->initialize($config);
        $data['reports'] = array_slice($data['reports'], $page, $config['per_page']);
        $data['totalpaidres'] = $totalpaidres;
        $data['paidrescount'] = count($paidres);
        $data['unpaidrescount'] = count($unpaidres);
        $data['paginationlinks'] = $this->pagination->create_links();
        $provider_count = $this->cn->getpro_count();
        $data['providers_count'] = $provider_count;
        $this->render_view('hotels/gman/reports', $data);
    }


    public function search_b2c()
    {
        $res = [];
        $res['status'] = false;
        $term = html_escape($this->input->get('term'));
        $page = $this->input->get('page') ?? 0;
        $str = '';
        $resv = $this->hpm->search_reports($term);
        $res['data'] = [];
        if ($resv) {
            for ($i = 0; $i < count($resv); $i++) {
                $res['status'] = true;
                if ($resv[$i]->b2b == 0) {
                    $str = "<tr><td>" . '<a target="_blank" href="' . site_url('gman/main/invoice_details/' . $resv[$i]->reservation_ref) . '">' . $resv[$i]->reservation_ref . "</a></td>";
                }else{
                    $str = "<tr><td>" . '<a target="_blank" href="' . site_url('gman/main/b2b_details/' . $resv[$i]->reservation_ref) . '">' . $resv[$i]->reservation_ref . "</a></td>";
                }
                $str .= "<td>" . $resv[$i]->Public_UserFullName . "</td>";
                $str .= "<td>" . $resv[$i]->Public_UserPhone . "</td>";
                $str .= "<td>" . $resv[$i]->ResDate . "</td>";
                $str .= "<td>" . $resv[$i]->CheckInDate . "</td>";
                $str .= "<td>" . $resv[$i]->CheckOutDate . "</td>";
                $str .= "<td>" . $resv[$i]->TotalRoomCount . "</td>";
                $str .= "<td>" . $resv[$i]->NetPrice . "</td>";
                if ($resv[$i]->Payment_method == 1) {
                    $str .= "<td class='bg-warning'>Pay At hotel</td>";
                } else {
                    $str .= "<td class='bg-info'>Online</td>";
                }
                if ($resv[$i]->Paid == 1) {
                    $str .= "<td class='bg-success'>Paid</td>";
                } else {
                    $str .= "<td class='bg-danger'>Not Paid</td>";
                }
                if ($resv[$i]->confirm == 0) {
                    $str .= "<td class='bg-danger'>Not Confirmed</td></tr>";
                } else {
                    $str .= "<td class='bg-success'>Confirmed</td></tr>";
                }
                $res['data'][] = $str;
            }
        }
        $res1 = [];
        $pager = $page * 5;
        $res1['data'] = array_slice($res['data'], $pager, 5);
        $res1['totalpages'] = (count($res['data']) > 5) ? round(count($res['data']) / 5) : 0;
        $res1['status'] = $res['status'];
        $this->toolset->jsonfy($res1);
    }

    public function invoice_details($invrid)
    {
        $reservationData = $this->rsm->get_invoice($invrid);
        if ($reservationData) {
            $data['h_name'] = $this->rsm->get_r_h($invrid);
            $data['idetails'] = $this->rsm->get_invoice_details($reservationData->resrefid);
        }
        $data['title'] = 'Reservation Invoice No. ' . $invrid;
        $data['idata'] = $reservationData;
        $this->render_view('hotels/gman/b2c_invoice', $data);
    }

    /*B2C INVOICES ENDS HERE */

    /*B2B INVOICES STARTS HERE */

    public function b2b_reports()
    {
        $s_text = $this->input->get('search');
        
        $config['base_url'] = site_url() . 'gman/main/b2b_reports';
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['full_tag_open'] =  '<ul class="pagination">';
        $config['full_tag_close'] = "</ul>";
        $config['next_tag_open'] =  '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<span class="page-link">Next</span>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<span class="page-link">Prev</span>';
        $config['first_link'] = '<li class="page-item"><span class="page-link">First</span></li>';
        $config['last_link']  = '<li class="page-item"><span class="page-link">Last</span></li>';
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item disabled"><span class="page-link">';
        $conig['cur_tag_close'] = '</span></li>';
        $data['title'] = "Reports";
        $data['reports'] = [];
        $paidres = [];
        $unpaidres = [];
        $totalpaidres = 0;
        if($s_text !== null){
            $page = 0;
        }else{
            $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        }
        if($s_text !== null){
            $resva = $this->hpm->search_b2b_list($s_text);
        }else{
            $resva = $this->hpm->get_b2b_list();
        }
        if ($resva) {
            for ($i = 0; $i < count($resva); $i++) {
                $data['reports'][] = $resva[$i];
                if ($resva[$i]->Paid == 1) {
                    $paidres[] = $resva[$i];
                    $totalpaidres += $resva[$i]->NetPrice;
                } else {
                    $unpaidres[] = $resva[$i];
                }
            }
        }
        $config['total_rows'] = count($data['reports']);
        $this->pagination->initialize($config);
        $data['reports'] = array_slice($data['reports'], $page, $config['per_page']);
        $data['totalpaidres'] = $totalpaidres;
        $data['paidrescount'] = count($paidres);
        $data['unpaidrescount'] = count($unpaidres);
        $data['paginationlinks'] = $this->pagination->create_links();
     
        $this->render_view('hotels/gman/b2b_reports', $data);
    }


   

    public function b2b_details($refid)
    {
       
        $data['title'] = 'Reservation Invoice No. ' . $refid;

        $reservationData = $this->rsm->get_b2binvoice($refid);
        if ($reservationData) {
            $data['h_name'] = $this->rsm->get_r_h($refid);
            $data['idetails'] = $this->rsm->get_invoice_details($reservationData->resrefid);
        }
        $data['idata'] = $reservationData;
        $this->render_view('hotels/gman/b2b_invoice', $data);
    }

    /*B2B INVOICES ENDS HERE */

    public function about()
    {
        $data['title'] = "About Us";
        $data['about'] = $this->pm->get_about();
        $this->form_validation->set_rules('about_content', 'Content', 'required');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/about', $data);
        } else {
            $insert = $this->pm->about();
            if ($insert) {
                redirect('gman');
            } else {
                show_error('Problem Adding the content');
            }
        }
    }

    public function translate_about()
    {
        $this->load->model('translate/translate_model', 'tm');
        $this->form_validation->set_rules('about_lang_to', 'language', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('about_translated', 'Form error');
            redirect("gman/main/about");
        } else {
            $to = $this->input->post('about_lang_to');
            $source = $this->input->post('about_edit');
            $id = $this->input->post('about_id');
            //$sid, $source, $trg, $trgdata,  $type
            $translate = $this->tm->about_translate($id, $source, $to, trans($source, 'en', $to), 'about_text');
            if ($translate) {
                $this->session->set_flashdata('about_translated', 'About Update SuccessFully');
                redirect("gman/main/about");
            } else {
                show_error('Problem While Translating About');
            }
        }
    }


    public function update_about($id){
        $this->load->model('translate/translate_model', 'tm');
        $this->form_validation->set_rules('about_update', 'Language', 'required');
        if ($this->form_validation->run() == false) {
            redirect('gman/main/about');
        } else {
            $text = $this->input->post('about_update');
            $update_about = $this->tm->translation_update($id, $text,'about_text');
            if ($update_about) {
                $this->session->set_flashdata('about_updated', "About Us updated successfully");
                redirect('gman/main/about');
            } else {
            }
        }
    }


    public function terms()
    {
        $data['title'] = "Terms";
        $data['terms'] = $this->pm->get_terms();
        $this->form_validation->set_rules('term_content', 'Content', 'required');
        if ($this->form_validation->run() == false) {
            $this->render_view('hotels/gman/terms', $data);
        } else {
            $insert = $this->pm->terms();
            if ($insert) {
                redirect('gman');
            } else {
                show_error('Problem Adding the content');
            }
        }
    }

    public function translate_terms()
    {
        $this->load->model('translate/translate_model', 'tm');
        $this->form_validation->set_rules('terms_lang_to', 'Language', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('Terms_translated', 'Error Happend While Translating Terms');
            redirect('gman/main/terms');
        } else {
            $source = $this->input->post('edit_terms');
            $id = $this->input->post('term_id');
            $to = $this->input->post('terms_lang_to');
            $translate_terms = $this->tm->terms_translate($id, $source, $to, trans($source, 'en', $to), 'terms_text');
            if ($translate_terms) {
                $this->session->set_flashdata('Terms_translated', 'Translated Successfully');
                redirect('gman/main/terms');
            } else {
                show_error('Some Error Happend While Translatig');
            }
        }
    }

   public function update_terms($id){
       $this->load->model('translate/translate_model', 'tm');
       $this->form_validation->set_rules('terms_update', 'Language', 'required');
       if ($this->form_validation->run() == false) {
           redirect('gman/main/terms');
       } else {
           $text = $this->input->post('terms_update');
           $update_terms = $this->tm->translation_update($id, $text, 'terms_text');
           if ($update_terms) {
               $this->session->set_flashdata('terms_updated', "Terms And Conditions Updated Successfully");
               redirect('gman/main/terms');
           } else {
           }
       }
   }
}