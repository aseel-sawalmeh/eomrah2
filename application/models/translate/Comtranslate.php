<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Comtranslate extends CI_Model {
    protected $tdb;
    public function __construct(){
        parent::__construct();
        $this->tdb = $this->load->database('translatedb', TRUE);
    }

    public function get_cols()
    {
        return $c = $this->tdb->list_fields('com_trans');
    }
    public function get_coms()
    {
        $com_trans = $this->tdb->get('com_trans');
        if ($com_trans) {
            return $com_trans->result();
        }else{
            return FALSE;
        }
    }
    public function that_com($id)
    {
        $this->tdb->where('Com_ID', $id);
        $com_trans = $this->tdb->get('com_trans');
        if ($com_trans) {
            return $com_trans->row();
        }else{
            return FALSE;
        }
    }
    public function com_update(int $id = null, $field)
    {
        $txt = $this->input->post($field);
        $this->tdb->where('Com_ID', $id);
        $data[$field] = $txt;
        $this->tdb->set($data);
        if ($this->tdb->update('com_trans')) {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function com_update_all(int $id = null, $data)
    {
        $this->tdb->where('Com_ID', $id);
        $this->tdb->set($data);
        if ($this->tdb->update('com_trans')) {
            return TRUE;
        }else{
            return FALSE;
        }
    }
       //geting the row for component translations
       public function lng($key, $lng){
        $this->tdb->select($lng);
        $this->tdb->select('en');
        $this->tdb->where('Com_Key', $key);
        $txt = $this->tdb->get('com_trans');
        if($txt->num_rows() > 0){
            $txt2 = $txt->row();
            if(!NULL == $txt2->$lng){
                return $txt2->$lng;
            }else{
                return $txt2->en;
            }
        }else{
            return FALSE;
        }
    }
    

}

/* End of file Comtranslate.php */
