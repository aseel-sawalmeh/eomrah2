<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gman_login_model extends CI_Model
{

    public function __construct(){
        parent::__construct();
    }

    public function validate()
    {
        $h_login_username = html_escape($this->input->post('login_username'));
        $h_login_password = sha1(html_escape($this->input->post('login_password')));
        $this->db->where('gman_Login', $h_login_username);
        $this->db->where('gman_PassWord', $h_login_password);
        $query = $this->db->get('gman_admins');
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            $data = array(
                'hotels/gman_id' => $row->gman_ID,
                'hotels/gman_fullName' => $row->gman_fullName,
                'hotels/gman_Email' => $row->gman_Email,
                'hotels/gman_Username' => $row->gman_Login,
                'gvalidated' => true,
                'Admin_User_ID' => $row->gman_ID,
                'H_User_FullName' => $row->gman_FullName,
                'H_User_Email' => $row->gman_Email,
                'H_UserName' => $row->gman_Login,
                'hvalidated' => true,
                'Suser' => true
                );
            $this->session->set_userdata($data);
            return true;
        }else{


       
$this->session->set_userdata('authError', 'Username or Password Invalid !!');
            return false;
        }
    }

    public function checkUserExist($username)
    {
        $this->db->where('gman_Login', $username);
        $query = $this->db->get('gman_admins');
        if($query->num_rows() == 1){
            return true;
        }
    }
}
?>
