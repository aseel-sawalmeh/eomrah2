<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Prodblocks extends Gman_Controller {

	public function index()
	{
        $data['title'] = "Positions of products";
        $this->load->view('hotels/gman/inc/header', $data);
        $this->load->view('hotels/gman/products/blocks');
        $this->load->view('hotels/gman/inc/footer');
    }

	public function list()
	{
        $this->load->model('Blocks_model');
        $this->load->model('Blocks_model');
        $data['blocks'] = $this->Blocks_model->get_blocks();
        $data['Products positions'] = "Positions of products";
        $data['title'] = "block products";
        $this->render_view('hotels/gman/products/block_list' , $data);
    }

	public function block_items($block_id)
	{
        $this->load->model('blocks_model');
        $data['bitems'] = $this->blocks_model->block_items($block_id);
        $data['products'] = $this->blocks_model->get_products();
        $data['block_id'] = $block_id;
        $data['Products positions'] = "Positions of products";
        $data['title'] = "Positions of products";
        $this->render_view('hotels/gman/products/bitems', $data);
    }

	public function add_item()
	{
        $this->load->model('blocks_model');
        $block_id = $this->input->post('blockid');
        $prodid = $this->input->post('prodid');
        if($this->blocks_model->add_item($block_id, $prodid)){
            redirect("gman/prodblocks/block_items/{$block_id}");
        }else{
            $this->session->set_flashdata('maxlimit', "The block maximum limit reached");
            redirect("gman/prodblocks/block_items/{$block_id}");
        }
    }

	public function max_item()
	{
        $this->load->model('blocks_model');
        $block_id = $this->input->post('blockid');
        $max = $this->input->post('maxitems');
        if($this->blocks_model->set_max($block_id, $max)){
            redirect("gman/prodblocks/list");
        }else{
            show_error('setting max Failed');
        }
    }
	public function orderitem()
	{
        $this->load->model('blocks_model');
        $blockid = $this->input->post('blockid');
        $prodid = $this->input->post('itemid');
        $order = $this->input->post('order');
        if($this->blocks_model->set_order($prodid, $order)){
            redirect("gman/prodblocks/block_items/{$blockid}");
        }else{
            show_error('setting max Failed');
        }
    }

	public function del_item($block_id, $item_id)
	{
        $this->load->model('blocks_model');
        if($this->blocks_model->del_item($item_id)){
            redirect("gman/prodblocks/block_items/{$block_id}");
        }else{
            show_error('Deleting items Failed');
        }
    }
}