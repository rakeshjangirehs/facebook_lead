<?php

require_once FCPATH . '/vendor/autoload.php'; // change path as needed

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// echo FCPATH;die;
		$fb = new \Facebook\Facebook([
			'app_id' => '605982930242451',
			'app_secret' => '816c3d8ea340962e4e93de2e93d4cae4',
			'default_graph_version' => 'v2.10',
			'default_access_token' => '', // optional
		]);

		try {
			// Get the \Facebook\GraphNodes\GraphUser object for the current user.
			// If you provided a 'default_access_token', the '{access-token}' is optional.
			$response = $fb->get('/me', 'EAAInI2VMC5MBAEaP7xgOz0ZAe0lzZBtocUgPpRpofM6jSVZAdGoZCDOUmqVqiAV1ZBnV20bZCdP0Cwo6FWLW9DlcOcHYIREA1s5P58Osdr8teGCiRCXVBM5Kb50UgjWY6al4z091aCLi4mZANRJRQrDsLSlbgaA6FX3pMIZAAa9tjf0eE7sYYjbEHVqb1fmfLX2KG1lu8KRZBZAPvDPGn6cCb8');
		  } catch(\Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		  } catch(\Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		  }
		  
		$me = $response->getGraphUser();
		echo 'Logged in as ' . $me->getName();
		echo "<pre>"; print_r($me);die;
		// $this->load->view('welcome_message');
	}
}
