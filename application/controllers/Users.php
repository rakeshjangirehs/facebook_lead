<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('user');
		
		
		/*
		$h = fopen("rakesh.txt","a+");
        fwrite($h,"Request URI : " . $_SERVER['REQUEST_URI'] . PHP_EOL);
        fwrite($h,"Request Method : " . $_SERVER['REQUEST_METHOD'] . PHP_EOL);
        fwrite($h,"Remote Address : " . $_SERVER['REMOTE_ADDR'] . PHP_EOL);
        fwrite($h,json_encode($_SERVER,JSON_PRETTY_PRINT) . PHP_EOL);
        fwrite($h,json_encode($_REQUEST,JSON_PRETTY_PRINT) . PHP_EOL);
        fwrite($h,"============================================" . PHP_EOL);
        fclose($h);
        */
	}

	public function index()
	{
		// Check if user is logged in
        if($this->facebook->is_authenticated()){

			// Get user facebook profile details
			$fbUser = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,gender,picture');
			
			// Preparing data for database insertion
			$userData['oauth_provider'] = 'facebook';
			$userData['oauth_uid']    = !empty($fbUser['id'])?$fbUser['id']:'';;
			$userData['first_name']    = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
			$userData['last_name']    = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
			$userData['email']        = !empty($fbUser['email'])?$fbUser['email']:'';
			$userData['gender']        = !empty($fbUser['gender'])?$fbUser['gender']:'';
			$userData['picture']    = !empty($fbUser['picture']['data']['url'])?$fbUser['picture']['data']['url']:'';
			$userData['link']        = !empty($fbUser['link'])?$fbUser['link']:'';			
			$userData['fb_access_token']        = $this->session->fb_access_token;
			
			// Insert or update user data
			$userID = $this->user->checkUser($userData);
			
			// Check user data insert or update status
			if(!empty($userID)){

				$userData['logOutUrl']        = $this->facebook->logout_url();
				$userData['logInUrl']        = $this->facebook->login_url();

				$this->session->set_userdata('userData', $userData);
				$this->session->set_userdata('user_id', $userData['oauth_uid']);

				redirect('home');
			}

		} else {

			// Get login URL
            $data['logInUrl'] =  $this->facebook->login_url();
			$this->load->view('users/login',$data);
		}
    }
    
    public function logout() {
        // Remove local Facebook session
        $this->facebook->destroy_session();
        // Remove user data from session
        $this->session->unset_userdata('userData');
        // Redirect to login page
        redirect('users');
    }
}