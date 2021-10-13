<?php defined('BASEPATH') or exit('No direct script access allowed');

class Fendtranslate_model extends CI_Model
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

    public function get_langs()
    {
        $this->tdb->order_by('order', 'ASC');
        $c = $this->tdb->get('translate_codes');
        return $c->result();
    }

    public function lng($id, $type, $lng)
    {
        $this->tdb->select($lng);
        $this->tdb->where('S_ID', $id);
        $this->tdb->where('S_TYPE', $type);
        $this->tdb->order_by('R_Order', 'ASC');
        $txt = $this->tdb->get('Text_trans');
        if ($txt->num_rows() > 0) {
            $txt2 = $txt->first_row();
            return $txt2->$lng;
        } else {
            return FALSE;
        }
    }
    //geting the row for main titles and desc
    public function lng_all($id, $type, $lng)
    {
        $this->tdb->select($lng);
        $this->tdb->where('S_ID', $id);
        $this->tdb->where('S_TYPE', $type);
        $this->tdb->order_by('R_Order', 'ASC');
        $txt = $this->tdb->get('Text_trans');
        if ($txt->num_rows() > 0) {
            $txt2 = $txt->result();
            return $txt2->$lng;
        } else {
            return FALSE;
        }
    }  
     //geting the row for main titles and desc
    public function hotel2en($txt)
    {
        $this->tdb->select('en');
        $this->tdb->where('S_TYPE', 'hotelname');
        $this->tdb->like('ar', $txt);
        $txt = $this->tdb->get('Text_trans');
        if ($txt->num_rows() > 0) {
            return $txt->result();
        } else {
            return FALSE;
        }
    }


    public function cat_titles($cat_id)
    {
        $this->tdb->where('S_ID', $cat_id);
        $this->tdb->where('S_TYPE', 'pcattitle');
        $this->tdb->order_by('order', 'ASC');
        $c = $this->tdb->get('Text_trans');
        if ($c->num_rows() > 0) {
            return $c->result();
        } else {
            return FALSE;
        }
    }

    public function cat_desc($cat_id)
    {
        $this->tdb->where('S_ID', $cat_id);
        $this->tdb->where('S_TYPE', 'pcatdesc');
        $this->tdb->order_by('order', 'ASC');
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
        $this->tdb->order_by('order', 'ASC');
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
        $this->tdb->order_by('order', 'ASC');
        $c = $this->tdb->get('Text_trans');
        if ($c->num_rows() > 0) {
            return $c->result();
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
}
