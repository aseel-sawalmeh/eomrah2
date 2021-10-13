<?php

class User_model extends CI_Model
{
    public function register($en_password)
    {
        $data = array(
            'Public_UserName' => $this->input->post('name'),
            'Public_UserPassword' => $en_password,
            'Public_UserFullName' => $this->input->post('fullname'),
            'Public_UserEmail' => $this->input->post('email'),
            'Public_UserPhone' => $this->input->post('phone'),
            'address' => $this->input->post('address'),
            'country' => $this->input->post('country'),
            'city' => $this->input->post('city')

        );
        return $this->db->insert('public_users', $data) ? TRUE : FALSE;
    }


    public function reg_user($data)
    {
        $ins = $this->db->insert('public_users', $data) ? TRUE : FALSE;
        if ($ins) {
            return $this->db->insert_id();
        } else {
            // return $this->db->error();
            return false;
        }
    }

    public function login($email, $password)
    {
        $this->db->where('Public_UserEmail', $email);
        $this->db->or_where('Public_UserName', $email);
        $this->db->where('Public_UserPassword', $password);
        $this->db->where('state', 1);
        $result = $this->db->get('public_users');
        if ($result && $result->num_rows() > 0) {
            $user_result = $result->row();
            $user_data = array(
                'userID' => $user_result->Public_User_ID,
                'userFname' => $user_result->Public_UserFullName,
                'userEmail' => $user_result->Public_UserEmail,
                'userphone' => $user_result->Public_UserPhone,
                'loggedIn' => true
            );

            $this->session->set_userdata('user_data', $user_data);
            return true;
        }
    }


    public function recoverlogin($email)
    {
        $this->db->where('Public_UserEmail', $email);
        $result = $this->db->get('public_users');
        if ($result && $result->num_rows() > 0) {
            $user_result = $result->row();
            $user_data = array(
                'userID' => $user_result->Public_User_ID ?? '',
                'userFname' => $user_result->Public_UserFullName,
                'userEmail' => $user_result->Public_UserEmail,
                'userphone' => $user_result->Public_UserPhone,
                'loggedIn' => true
            );

            $this->session->set_userdata('user_data', $user_data);
            return true;
        }
        show_error($this->db->error());
        exit();
        // return false;
    }

    public function check_name_exists($name)
    {
        $this->db->where("Public_UserName", $name);
        $query = $this->db->get('public_users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_email_exists($email)
    {
        $this->db->where("Public_UserEmail", $email);
        $this->db->where("state", 1);
        $query = $this->db->get('public_users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_p_user($id)
    {
        $this->db->where('Public_User_ID', $id);
        $e_res = $this->db->get('public_users');
        if ($e_res) {
            return $e_res->row();
        }
    }
    public function user_res_head($p_uid)
    {
        $this->db->where('P_Userid', $p_uid);

        $results = $this->db->get('p_reservation');
        if ($results) {
            return $results->num_rows();
        } else {
            return False;
        }
    }

    public function resheads($p_uid, $offset, $limit)
    {
        $this->db->where('P_Userid', $p_uid);
        $this->db->limit($offset, $limit);
        $results = $this->db->get('p_reservation');
        if ($results) {
            return $results->result();
        } else {
            return False;
        }
    }
    public function u_ress($res_uid)
    {
        $this->db->join('meals', 'presdetails.MealType = meals.Meal_ID', 'left');
        $this->db->join('room_type', 'presdetails.RoomType = room_type.R_Type_ID', 'left');
        $this->db->where('ResId', $res_uid);

        $results = $this->db->get('presdetails');
        if ($results) {
            return $results->result();
        } else {
            return False;
        }
    }
    public function endusercheck($eu_name)
    {
        $this->db->where('Public_UserName', $eu_name);
        $query = $this->db->get('public_users');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function endemailcheck($eu_mail, $returnName = false)
    {
        $this->db->where('Public_UserEmail', $eu_mail);
        $query = $this->db->get('public_users');
        if ($query->num_rows() > 0) {
            if ($returnName) {
                return $query->row()->Public_UserFullName;
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function phonecheck($phone)
    {
        $this->db->where('Public_UserPhone', $phone);
        $query = $this->db->get('public_users');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_user()
    {
        $id = $this->session->userdata('user_data')['userID'];
        $this->db->where('Public_User_ID', $id);
        $query = $this->db->get('public_users');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function check_pass($Pass)
    {
        $id = $this->session->userdata('user_data')['userID'];
        $this->db->where('Public_UserPassword', md5($Pass));
        $this->db->where('Public_User_ID', $id);
        $query = $this->db->get('public_users');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function pass_update($newpass)
    {
        $id = $this->session->userdata('user_data')['userID'];
        $data = array(
            'Public_UserPassword' => $newpass
        );
        $this->db->where('Public_User_ID', $id);
        $query = $this->db->update('public_users', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function profileUpdate()
    {
        $data = array(
            'Public_UserPassword' => md5($this->input->post('password')),
            'Public_UserName' => $this->input->post('username'),
            'address' => $this->input->post('address'),
            'country' => $this->input->post('country'),
            'city' => $this->input->post('city'),
        );
        $this->db->where('Public_User_ID', $this->input->post('uid'));
        $update = $this->db->update('public_users', $data);

        if ($update) {
            $user_data = array(
                'userID' => $this->input->post('uid'),
                'userFname' => $this->input->post('fullname'),
                'userEmail' => $this->input->post('email'),
                'userphone' => $this->input->post('phone'),
                'loggedIn' => true
            );

            $this->session->set_userdata('user_data', $user_data);
            return true;
        } else {
            return false;
        }
    }
}
