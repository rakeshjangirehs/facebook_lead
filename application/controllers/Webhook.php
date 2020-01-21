<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller {
    function __construct() {
        parent::__construct();

        $this->token = $this->config->item('hub_verify_token');
        $this->request_method = $this->input->server('REQUEST_METHOD');

        // $url = $this->input->server('REQUEST_URI');        
        // $this->log("Method $request_method");
        // $this->log("URL $url");
        // $this->log($_REQUEST,"json");        



    }

    // https://9626dd85.ngrok.io/facebook_lead/index.php/webhook/verify_token
    // yamin_pipadwala2001@yahoo.com
    // 9998449378
    
    public function verify_token() {

        if($this->request_method == 'GET') {
        
            $challenge = $_REQUEST['hub_challenge'];
            $verify_token = $_REQUEST['hub_verify_token'];

            if ($verify_token === $this->token) {
                
                $this->log("Subscription Verified.");

                echo $challenge;
            } else {
                $this->log("Subscription not Verified.");
            }

        } elseif($this->request_method == 'POST') {

            $payload = file_get_contents('php://input');

            $this->log("Webhook Payload :");
            $this->log($payload,"json_response");

            if($payload) {

                $payload = json_decode($payload,true);

                if($payload['object']=='page') {

                    if(isset($payload['entry']) && is_array($payload['entry'])) {
                        $entries = $payload['entry'];
                        foreach($entries as $entry) {
                            if(isset($entry['changes']) && is_array($entry['changes'])) {
                                $changes = $entry['changes'];
                                foreach($changes as $change) {
                                    if(isset($change['value']) && is_array($change['value'])) {
                                        $value = $change['value'];
                                        $ad_id = $value['ad_id'];
                                        $form_id = $value['form_id'];
                                        $page_id = $value['page_id'];
                                        $adgroup_id = $value['adgroup_id'];
                                        $leadgen_id = $value['leadgen_id'];
                                        

                                        if($page_access_token = $this->get_page_access_token("104539430893057")) {                                            

                                            $this->log("Page Access Token :");
                                            $this->log($page_access_token);

                                            if( $lead_details = $this->get_leadgen_data(603179507106474,$page_access_token) ) {
                                                
                                                $this->log("Raw Lead Details :");                                                
                                                $this->log($lead_details,"json");

                                                $lead_data = $this->format_lead_data($lead_details);

                                                $this->log("Formated Lead Data:");
                                                $this->log($lead_data,"json");
                                            }
                                        }                                        
                                    }
                                }				
                            }
                        }		
                    }
                }

            } else {
                $this->log("No Webhook Payload Found.");
            }
        }
    }


    public function get_page_access_token($page_id)
    {        
        $user = $this->db->get("users")->row_array();
        $accessToken = $user['fb_access_token'];
        $response = $this->facebook->request('get','/'.$page_id.'?fields=access_token', (string)$accessToken);

        if(!isset($response['error']) && isset($response['access_token'])) {
            return $response['access_token'];
        } else {
            $this->log("Error while fetching page access token. See your logs or enable if not enabled.");
            return false;
        }
    }

    public function get_leadgen_data($leadgen_id,$page_access_token)
    {
        $response = $this->facebook->request('get',$leadgen_id, $page_access_token);            

        if(!isset($response['error'])) {            
            return $response;
        } else {
            $this->log("Error while fetching lead data. See your logs or enable if not enabled.");
            return false;
        }
    }

    public function format_lead_data($payload)
    {
        if(isset($payload['field_data']) && is_array($payload['field_data'])) {
	
            $field_data = $payload['field_data'];
        
            foreach($field_data as $field) {
                if(is_array($field) && isset($field['name']) && isset($field['values'])) {
        
                    $field_name = $field['name'];
                    
                    if(is_array($field['values'])) {
                        $response[$field_name] = implode(",",$field['values']);
                    } elseif(is_string($field['values'])) {
                        $response[$field_name] = $field['values'];
                    }
                }
            }
        
            return $response;

        } else {
            return null;
        }
    }
    
    public function log($data, $type="string")
    {
        // file_get_contents('php://input')


        $fp = fopen('webhook_log.txt', 'a');

        switch ($type) {
            case 'json_response':
                fwrite($fp, json_encode(json_decode($data), JSON_PRETTY_PRINT). PHP_EOL);
            break;
            case 'json':
                fwrite($fp, json_encode($data, JSON_PRETTY_PRINT). PHP_EOL);
                break;
            default:
                fwrite($fp, $data. PHP_EOL);
                break;
        }

        fclose($fp);
    }    
}