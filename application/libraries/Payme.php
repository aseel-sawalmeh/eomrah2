<?php defined('BASEPATH') or exit('No Direct Access Is Allowed');
/*
* This Library Will Be Used To Intigrate The Payment Mehods.
* Made to extend the core as Version one additional toolset
* PHP Version 7.3
* Author Hisham Muhammad Nasib Abbasi
* Payment Version P-1.0
*/

class Payme{

    private $p_method;
    private $transtype;
    private $ret_auth;
    private $ret_can;
    private $ret_decl;
    private $paydata;

    public function init_payment($mthd, $trans, $paydatain, $transcode, $mailHash){
        $this->p_method = $mthd;
        $this->transtype = $trans;
        $this->paydata = $paydatain;        
        $this->ret_auth = site_url("payment/success/$transcode/{$this->paydata->reservation_ref}/$mailHash");
        $this->ret_can = site_url("payment/cancel/{$this->paydata->reservation_ref}/$mailHash");
        $this->ret_decl = site_url("payment/decline/{$this->paydata->reservation_ref}/$mailHash");
    }
    
    public function make_payment()
    {
        $params = array(
            'ivp_method'  => $this->p_method,
            'ivp_trantype' => $this->transtype,
            'ivp_store'   => '22649',
            'ivp_authkey' => '3f6B@csrSS#Cdcvk',
            'ivp_cart'    => $this->paydata->reservation_ref,  
            'ivp_test'    => '1',
            'ivp_amount'  => $this->paydata->NetPrice,
            'ivp_currency'=> usercur(),
            'ivp_desc'    => $this->paydata->TotalRoomCount.' rooms Invoice No. '. $this->paydata->reservation_ref,
            'return_auth' => $this->ret_auth,
            'return_can'  => $this->ret_can,
            'return_decl' => $this->ret_decl,
            'bill_title' => explode(' ', $this->paydata->Public_UserFullName)[0],
            'bill_fname' => explode(' ', $this->paydata->Public_UserFullName)[1],
            'bill_sname' => explode(' ', $this->paydata->Public_UserFullName)[2],
            'bill_addr1' => !empty($this->paydata->address)? explode('-', $this->paydata->address)[0]??'':'',
            'bill_addr2' => !empty($this->paydata->address) ? explode('-', $this->paydata->address)[1]??'':'',
            'bill_city' => $this->paydata->city, //country name in the query
            'bill_country' => $this->paydata->country, //country 2char code
            'bill_email' => $this->paydata->Public_UserEmail,
            'ivp_cn'=> 4444424444444440,
            // 'ivp_firstRef'=> 4444424444444440,
            'ivp_exm'=> 11,
            'ivp_exy'=>2021,
            'ivp_cv'=>123
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/order.json");//old
        // curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/remote.html"); // needs their activation and some edits
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        $results = curl_exec($ch);
        curl_close($ch);
        $results = json_decode($results,true);
        if(isset($results['error'])){
            //logs needed for backend customer care as permenent data
            log_message('error', $results['error']['message']);
            // echo $results['error']['message'];
            return false;

        }
        $ref= trim($results['order']['ref']);
        $url= trim($results['order']['url']);
        // print_r($ref);
        // print_r($url);
        print_r($results);
        if (empty($ref) || empty($url)) {
        # Failed to create order
        }else{
            set_cookie('res_order_ref', $ref, 3600);
            redirect($url);
        }
    }
}