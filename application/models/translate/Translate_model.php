<?php defined('BASEPATH') or exit('No direct script access allowed');

class translate_model extends CI_Model
{
    protected $tdb;
    public $login_error = TRUE;
    public function __construct()
    {
        parent::__construct();
        $this->tdb = $this->load->database('translatedb', TRUE);
    }

    public function get_cols()
    {
        return $c = $this->tdb->list_fields('Text_trans');
    }

    public function add_title($data)
    {
        $this->tdb->set($data);
        if ($this->tdb->insert('Text_trans')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function add_hotel($data)
    {
        $this->tdb->set($data);
        if ($this->tdb->insert('Text_trans')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function add_hotel_replace($data)
    {
        $query = $this->db->insert_string('Text_trans', $data);
        $query = str_replace('INSERT INTO', 'INSERT OR REPLACE INTO', $query);
        // echo $query;die();
        if ($this->tdb->query($query)) {
            echo " <br/> Affected rows ".$this->db->affected_rows()."<br/>";
            return TRUE;
        } else {
            echo " <br/> Affected rows false " . $this->db->affected_rows() . "<br/>";
            return FALSE;
        }
    }

    public function edit_title($data, $txt_id)
    {
        $this->tdb->set($data);
        $this->tdb->where('Text_ID', $txt_id);
        if ($this->tdb->update('Text_trans')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function assign_desc_title($data, $txt_id)
    {
        $this->tdb->set($data);
        $this->tdb->where('Text_ID', $txt_id);
        if ($this->tdb->update('Text_trans')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function edit_that($data, $txt_id)
    {
        $this->tdb->set($data);
        $this->tdb->where('Text_ID', $txt_id);
        if ($this->tdb->update('Text_trans')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_langs()
    {
        $this->tdb->order_by('order', 'ASC');
        $c = $this->tdb->get('translate_codes');
        return $c->result();
    }
    public function get_langs_arr()
    {
        return $this->db->select('code')->order_by('order', 'ASC')->get('translate_codes')->result();
    }

    public function get_trans($id)
    {
        $this->tdb->where('Text_ID', $id);
        $txt = $this->tdb->get('Text_trans');
        if ($txt->num_rows() > 0) {
            foreach ($txt->result_array() as $txtrow) {
                return $txtrow;
            }
        } else {
            return FALSE;
        }
    }

    public function get_trans_by_source_hotel($sid, $s_type)
    {
        $this->tdb->where('S_ID', $sid);
        $this->tdb->where('S_Type', $s_type);
        $txt = $this->tdb->get('Text_trans');
        if ($txt->num_rows() > 0) {
            foreach ($txt->result_array() as $txtrow) {
                return $txtrow;
            }
        } else {
            return FALSE;
        }
    }


    public function cat_titles($cat_id)
    {
        $this->tdb->where('S_ID', $cat_id);
        $this->tdb->where('S_TYPE', 'pcattitle');
        $this->tdb->order_by('R_Order', 'ASC');
        $c = $this->tdb->get('Text_trans');
        if ($c->num_rows() > 0) {
            return $c->result();
        } else {
            return FALSE;
        }
    }

    public function faq_translate($sid, $source, $trg, $trgdata,  $type)
    {
        $fdata = ['S_ID' => $sid, 'S_TYPE' => $type, 'en' => $source, "$trg" => $trgdata];
        $this->tdb->where('S_ID', $sid);
        $this->tdb->where('S_TYPE', $type);
        $exist = $this->tdb->get('Text_trans');
        if ($exist->num_rows() > 0) {
            $id = $exist->row()->Text_ID;
            $this->tdb->reset_query();
            $this->tdb->set($fdata);
            $this->tdb->where('Text_ID', $id);
            $ins = $this->tdb->update('Text_trans');
            if ($ins) {
                return true;
            } else {
                show_error('translation error');
            }
        } else{
            $this->tdb->set($fdata);
            $ins = $this->tdb->insert('Text_trans');
            if ($ins) {
                return true;
            } else {
                show_error('translation error');
            }
        }
    }

    public function about_translate($sid, $source, $trg, $trgdata,  $type)
    {
        $about_data = array(
            'S_ID'=> $sid,
            'S_TYPE'=> $type,
            'en' => $source,
            "$trg" => $trgdata,
        );
        $this->tdb->where('S_ID', $sid);
        $this->tdb->where('S_TYPE', $type);
        $query = $this->tdb->get('Text_trans');
        if($query->num_rows() > 0)
        {
            $text_id = $query->row()->Text_ID;
            $this->tdb->reset_query();
            $this->tdb->set($about_data);
            $this->tdb->where('Text_ID', $text_id);
            $update = $this->tdb->update('Text_trans');
            if($update){
                return true;
            }else{
                return false;
            } 
        }else{
            $this->tdb->set($about_data);
            $insert = $this->tdb->insert('Text_trans');
            if($insert){
                return true;
            }else{
                return false;
            } 
        }
    }

    public function terms_translate($sid, $source, $trg, $trgdata,  $type)
    {
        $terms_data = array(
            'S_ID'=> $sid,
            'S_TYPE'=> $type,
            'en' => $source,
            "$trg" => $trgdata,
        );
        $this->tdb->where('S_ID', $sid);
        $this->tdb->where('S_TYPE', $type);
        $query = $this->tdb->get('Text_trans');
        if($query->num_rows() > 0)
        {
            $text_id = $query->row()->Text_ID;
            $this->tdb->reset_query();
            $this->tdb->set($terms_data);
            $this->tdb->where('Text_ID', $text_id);
            $update = $this->tdb->update('Text_trans');
            if($update){
                return true;
            }else{
                return false;
            } 
        }else{
            $this->tdb->set($terms_data);
            $insert = $this->tdb->insert('Text_trans');
            if($insert){
                return true;
            }else{
                return false;
            } 
        }
    }

    public function cat_desc($cat_id)
    {
        $this->tdb->where('S_ID', $cat_id);
        $this->tdb->where('S_TYPE', 'pcatdesc');
        $this->tdb->order_by('R_Order', 'ASC');
        $c = $this->tdb->get('Text_trans');
        if ($c->num_rows() > 0) {
            return $c->result();
        } else {
            return FALSE;
        }
    }


    public function product_titles($product_id)
    {
        $this->tdb->where('S_ID', $product_id);
        $this->tdb->where('S_TYPE', 'prodtitle');
        $this->tdb->order_by('R_Order', 'ASC');
        $c = $this->tdb->get('Text_trans');
        if ($c->num_rows() > 0) {
            return $c->result();
        } else {
            return FALSE;
        }
    }

    public function product_desc($product_id)
    {
        $this->tdb->where('S_ID', $product_id);
        $this->tdb->where('S_TYPE', 'proddesc');
        $this->tdb->order_by('R_Order', 'ASC');
        $c = $this->tdb->get('Text_trans');
        if ($c->num_rows() > 0) {
            return $c->result();
        } else {
            return FALSE;
        }
    } 
    

    public function prodtitle_desc($product_id, $assign_to)
    {
        $this->tdb->where('S_ID', $product_id);
        $this->tdb->where('S_TYPE', 'proddesc');
        $this->tdb->where('assign_to', $assign_to);
        $this->tdb->order_by('R_Order', 'ASC');
        $c = $this->tdb->get('Text_trans');
        if ($c->num_rows() > 0) {
            return $c->result();
        } else {
            return FALSE;
        }
    }

    public function prodtxt_order($txtid, $order)
    {
        $this->tdb->where('S_ID', $product_id);
        $this->tdb->set('R_Order', $order);
        if ($this->tdb->update()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function delete($text_id)
    {
        $this->tdb->where('Text_ID', $text_id);
        if ($this->tdb->delete('Text_trans')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    //count items exist before adding new to make the order
    public function count_exist($sid, $type)
    {
        $this->tdb->where('S_ID', $sid);
        $this->tdb->where('S_TYPE', $type);
        $c = $this->tdb->get('Text_trans');
        if ($this->tdb->get('Text_trans')) {
            return $c->num_rows();
        } else {
            return FALSE;
        }
    }

    public function translation_update($id, $text, $type){
        $lang_column =  userlang();
        $data = [
            "$lang_column"=> $text,
        ];
        $this->tdb->where('S_ID', $id);
        $this->tdb->where('S_TYPE', $type);
        $this->tdb->set($data);
        $insert = $this->tdb->update('Text_trans');
        if($insert){
            return true;
        }else{
           return false;
        }
    }


    public function prod_translate($sid, $source, $trg, $trgdata,  $type)
    {
        $fdata = ['S_ID' => $sid, 'S_TYPE' => $type, 'en' => $source, "$trg" => $trgdata];
        $this->tdb->where('S_ID', $sid);
        $this->tdb->where('S_TYPE', $type);
        $exist = $this->tdb->get('Text_trans');
        if ($exist->num_rows() > 0) {
            $id = $exist->row()->Text_ID;
            $this->tdb->reset_query();
            $this->tdb->set($fdata);
            $this->tdb->where('Text_ID', $id);
            $ins = $this->tdb->update('Text_trans');
            if ($ins) {
                // show_error($this->tdb->last_query());
                // die;
                return true;
            } else {
                show_error($this->tdb->last_query());
                // show_error('translation errorrrrrrrrrrr');
            }
        } else{
            $this->tdb->set($fdata);
            $ins = $this->tdb->insert('Text_trans');
            if ($ins) {
                return true;
            } else {
                show_error('translation error');
            }
        }
    }

   

    
}
