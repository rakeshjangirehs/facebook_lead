<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    function __construct() {
        parent::__construct();
        
        //Load user model
		$this->load->model('user');
		$this->token = $this->config->item('hub_verify_token');
		$this->app_id =  $this->config->item('facebook_app_id');
		$this->appAccessToken = $this->facebook->object()->getApp()->getAccessToken();
    }

    public function index(){		
        $this->load->view('users/index',$this->data);
	}

	public function my_pages()
	{
		$user_access_toke = $this->session->fb_access_token;		
        $response = $this->facebook->request('get',"me/accounts", (string)$user_access_toke);
		
		// echo "<pre>";print_r($response);die;
		foreach($response['data'] as $k=>$page) {
			
			$subscribed_apps = $this->facebook->request('get',"{$page['id']}/subscribed_apps",$page['access_token']);
			// $response['data'][$k]['subscription'] = $response;

			// echo "<pre>";print_r($subscribed_apps);echo "</pre>";

			if(!isset($subscribed_apps['error']) && isset($subscribed_apps['data'])) {

				$response['data'][$k]['subscribed_fields'] = [];

				
				foreach ($subscribed_apps['data'] as $app) {
					if($app && $this->config->item('facebook_app_id') == $app['id'] && isset($app['subscribed_fields'])) {
						
						if(is_array($app['subscribed_fields'])) {
							$response['data'][$k]['subscribed_fields'] = $app['subscribed_fields'];
						} elseif(is_string($app['subscribed_fields'])) {
							$response['data'][$k]['subscribed_fields'] = [$app['subscribed_fields']];
						}
					}
				}
				
			} else {
				
			}
		}

		// echo "<pre>";print_r($response['data']);die;
		
		if(isset($response['error'])) {
			$this->data['error'] = $response;
			$this->data['has_error'] = true;
		} else {

			$this->data['pages'] = $response['data'];
			$this->data['has_error'] = false;
		}
		
		$this->load->view('users/pages',$this->data);
	}

	public function subscribe_page($page_id) {
	
		// /* Webhook */
		// https://developers.facebook.com/docs/graph-api/reference/page/subscribed_apps
		// https://developers.facebook.com/docs/pages/realtime/
	
		// /* Api Explorer */
		// https://developers.facebook.com/tools/explorer/?method=GET&path=101484314611820%2Fsubscribed_apps&version=v5.0

		// /* API Reference */
		// https://developers.facebook.com/docs/graph-api/reference/user/


		//Get subscribed_apps page_id/subscribed_apps 
		// Subscribe for page ["subscribed_fields": "leadgen"] on page_id/subscribed_apps

		$subscribed_fields = "";
		$this->data['page_access_toekn'] = '';
		
		if($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$fields = ($this->input->post('fields')) ? array_map(function($val){return trim($val);},explode(",",$this->input->post('fields'))) : null;
			$page_access_token = $this->input->post('page_access_token');
			
			if($page_access_token && $fields) {
				
				// Add Subscription
				$params = ["subscribed_fields" => implode(",",$fields)];
				
				$resp = $this->facebook->request('post',"{$page_id}/subscribed_apps",$params, $page_access_token);
				
				if(!isset($resp['error'])) {
					$this->session->set_flashdata('success','Successfully Subscribed');										
				} else {
					$this->session->set_flashdata('error',$resp['message']);
				}
				redirect("home/subscribe_page/{$page_id}");
			}
		} else {

			// Get Page Access Token
			$page = $this->facebook->request('get',"{$page_id}?fields=id,name,access_token");

			if(!isset($page['error']) && isset($page['access_token'])) {
			
				$page_access_token = $page['access_token'];
				$this->data['page_access_toekn'] = $page_access_token;
	
				$subscribed_apps = $this->facebook->request('get',"{$page_id}/subscribed_apps",$page_access_token);
	
				if(!isset($subscribed_apps['error']) && isset($subscribed_apps['data'])) {
	
					foreach ($subscribed_apps['data'] as $app) {
						if($app && $this->config->item('facebook_app_id') == $app['id'] && isset($app['subscribed_fields'])) {
							
							if(is_array($app['subscribed_fields'])) {
								$subscribed_fields = implode(",",$app['subscribed_fields']);
							} elseif(is_string($app['subscribed_fields'])) {
								$subscribed_fields = [$app['subscribed_fields']];
							}
						}
					}
					
				}
			}
		}

		$this->data['page_id'] = $page_id;
		$this->data['subscribed_fiedls'] = $subscribed_fields;
		$this->load->view("users/add_subscription",$this->data);
	}

	public function remove_permission($page_id)
	{
		// Get Page Access Token
		$page = $this->facebook->request('get',"{$page_id}?fields=id,name,access_token");

		if(!isset($page['error']) && isset($page['access_token'])) {
		
			$page_access_token = $page['access_token'];

			$resp = $this->facebook->request('delete',"{$page_id}/subscribed_apps",[], $page_access_token);

			if(!isset($resp['error'])) {
				$this->session->set_flashdata('success',"Subscription Removed");
			} else {
				$this->session->set_flashdata('error',$resp['message']);
			}

		} else {
			$this->session->set_flashdata('error',$page['message']);
		}

		redirect("home/subscribe_page/{$page_id}");
	}

	public function wehooks()
	{
		// https://developers.facebook.com/docs/graph-api/reference/v5.0/app/subscriptions		


		$this->data['editing'] = [
			'object'	=>	'',
			'callback'	=>	'',
			'fields'	=>	'',				
		];

		$this->data['subscriptions'] = [];

		// Get Subscriptions
		
		$subscriptions = $this->facebook->request('get',"/app/subscriptions",$this->appAccessToken);
		if(!isset($subscriptions['error'])) {
			$this->data['subscriptions'] = $subscriptions['data'];
		} else {
			$this->session->set_flashdata('error',$subscriptions['message']);
		}
		

		if($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$object = $this->input->post('object');
			$callback_url = $this->input->post('callback');			
			$fields = ($this->input->post('fields')) ? array_map(function($val){return trim($val);},explode(",",$this->input->post('fields'))) : null;

			// Add Subscription
			$params = [
				"object"    =>  $object,
				"callback_url"  =>  $callback_url,
				"fields"    =>  $fields,
				"verify_token"  =>  $this->token,
			];

			$response = $this->facebook->request('post',"/app/subscriptions",$params,$this->appAccessToken);

			if(!isset($response['error'])) {
				$this->session->set_flashdata('success',"Subscribed");
			} else {
				$this->session->set_flashdata('error',$response['message']);
			}

			redirect("home/wehooks");

		}
		

		$this->load->view("users/webhooks",$this->data);
	}

	public function wehooks_edit()
	{
		$this->data['editing'] = [
			'object'	=>	$this->input->post('object'),
			'callback'	=>	$this->input->post('callback_url'),
			'fields'	=>	$this->input->post('fields'),
		];

		// Get Subscriptions
		
		$subscriptions = $this->facebook->request('get',"/app/subscriptions",$this->appAccessToken);
		if(!isset($subscriptions['error'])) {
			$this->data['subscriptions'] = $subscriptions['data'];
		} else {
			$this->session->set_flashdata('error',$subscriptions['message']);
		}

		$this->load->view("users/webhooks",$this->data);
	}

	public function remove_subscription($object, $field=NULL)
	{
		if ($object) {

			// Delete webhook. (You can delete specific fields from your subscription by including a fields param.)

			$params = [
				"object"    =>  $object,
			];

			if ($field) {
				$params['fields'] = $field;
			}

			$response = $this->facebook->request('delete',"/app/subscriptions",$params,$this->appAccessToken);

			if(!isset($response['error'])) {
				$this->session->set_flashdata('success',"Unsubscribed");
			} else {
				$this->session->set_flashdata('error',$response['message']);
			}			
		} else {
			$this->session->set_flashdata('error',"Object name missing.");
		}

		redirect("home/wehooks");
	}
}