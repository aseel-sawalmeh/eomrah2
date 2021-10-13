<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{
    public function register($hashed_password)
    {
        $data = array(
            'C_FullName' => $this->input->post('first_name') . " " . $this->input->post('last_name'),
            'C_UserPassword' => $hashed_password,
            'Company_Name' => $this->input->post('company_name'),
            'Country' => $this->input->post('country'),
            'C_MobileNumber' => $this->input->post("country_code") . "-" .  $this->input->post('mobile_number'),
            'C_PhoneNumber' => $this->input->post('office_number'),
            'C_Email' => $this->input->post('company_email'),
            'IBAN' => $this->input->post('iban'),
            'C_Vat' => $this->input->post('vat_number'),
            'C_Reg' => $this->input->post('reg_number'),
            'C_License' => $this->input->post('hajj_license'),
            'state' => 0
        );
        return $this->db->insert('b2b_users', $data) ? TRUE : FALSE;
    }

    public function checkemail($email)
    {
        $this->db->where('C_Email', $email);
        $query = $this->db->get("b2b_users");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_name($name)
    {
        $this->db->where('Company_Name', $name);
        $query = $this->db->get('b2b_users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_iban($iban)
    {
        $this->db->where('IBAN', $iban);
        $query = $this->db->get('b2b_users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_registration($reg_no)
    {
        $this->db->where('C_Reg', $reg_no);
        $query = $this->db->get('b2b_users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function validate()
    {
        $c_email = html_escape($this->input->post('company_email'));
        $c_password = md5(html_escape($this->input->post('company_password')));
        $this->db->where('C_Email',  $c_email);
        $this->db->where('C_UserPassword', $c_password);
        $this->db->where('state', 1);
        $query = $this->db->get('b2b_users');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $data = array(
                'C_ID' => $row->C_ID,
                'C_FullName' => $row->C_FullName,
                'Country' => $row->Country,
                'C_MobileNumber' => $row->C_MobileNumber,
                'C_PhoneNumber' => $row->C_PhoneNumber,
                'C_Email' => $row->C_Email,
                'IBAN' => $row->IBAN,
                'C_Vat' => $row->C_Vat,
                'C_Reg' => $row->C_Reg,
                'C_License' => $row->C_License,
                'Company_Name' => $row->Company_Name,
                'b2bvalidated' => true,
            );
            $this->session->set_userdata($data);
            return true;
        } else {
            return false;
        }
    }

    public function check_login($email)
    {
        $this->db->where("C_Email", $email);
        $query = $this->db->get("b2b_users");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_pass($pass)
    {
        $pass = md5($this->input->post("company_password"));
        $this->db->where("C_UserPassword", $pass);
        $query = $this->db->get("b2b_users");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_profile($id)
    {
        $this->db->where('C_ID', $id);
        $query = $this->db->get('b2b_users');

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function update_profile($id)
    {
        $data = array(
            'C_PhoneNumber' => $this->input->post('c_phone'),
            'C_MobileNumber' => $this->input->post('c_mobile'),
            'C_FullName' => $this->input->post('c_fullname'),
            'C_Email' => $this->input->post('c_email'),

        );
        $this->db->where('C_ID', $id);
        $query =  $this->db->update('b2b_users', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function update_password()
    {
        $id = $this->session->userdata("C_ID");
        $data = array(
            'C_UserPassword' => md5($this->input->post('confirm_pass'))
        );
        $this->db->where('C_ID', $id);
        $query = $this->db->update("b2b_users", $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function match_pass()
    {
        $pass = md5($this->input->post('oldpass'));
        $this->db->where('C_UserPassword', $pass);
        $query = $this->db->get('b2b_users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
