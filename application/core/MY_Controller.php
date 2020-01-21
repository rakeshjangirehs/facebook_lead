<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    function __construct() {

        parent::__construct();
        
        // echo "<a href='".$this->facebook->logout_url()."'>dd</a>";
        // $this->facebook->destroy_session();
        // $this->session->unset_userdata('userData');
        // $this->session->unset_userdata('fb_expire');
        // $this->session->unset_userdata('FBRLH_state');die;

        
        $current_url = $this -> router -> fetch_class() . "/" . $this -> router -> fetch_method();

        // $fp = fopen('rr.txt', 'a');
        // fwrite($fp, $current_url. PHP_EOL);
        // fclose($fp);

        if($current_url!="/facebook_lead/index.php/users" && !$this->facebook->is_authenticated()) {
			redirect('users');
        }
        
        $this->data['is_in'] = ($this->session->userdata('fb_access_token')) ? true : false;
        $this->data = array_merge($this->data,$this->session->userData);
    }
}
